<?php

class AdminController extends Controller {
    public function index(Request $request) {
        // Each report section is isolated: a single failing query must never
        // blank the whole dashboard (previously any exception => no report).
        $salesTrend = $this->safeReport('salesTrend', function () {
            return Product::getSalesTrend(14);
        }, []);
        $totalRevenue = $this->safeReport('totalRevenue', function () {
            return Order::getTotalRevenue();
        }, 0.0);
        $todayRevenue = $this->safeReport('todayRevenue', function () {
            return $this->revenueSince('today');
        }, 0.0);
        $weekRevenue = $this->safeReport('weekRevenue', function () {
            return $this->revenueSince('week');
        }, 0.0);
        $ordersToday = $this->safeReport('ordersToday', function () {
            return $this->countOrdersSince('today');
        }, 0);

        return $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'currentPage' => 'dashboard',
            'stats' => [
                'totalOrders' => $this->safeReport('totalOrders', function () {
                    return Order::getTotalOrderCount();
                }, 0),
                'pendingOrders' => $this->safeReport('pendingOrders', function () {
                    return Order::getPendingOrderCount();
                }, 0),
                'totalRevenue' => $totalRevenue,
                'todayRevenue' => $todayRevenue,
                'weekRevenue' => $weekRevenue,
                'ordersToday' => $ordersToday,
                'customers' => $this->safeReport('customers', function () {
                    return User::getTotalCustomers();
                }, 0),
                'menu' => $this->safeReport('menuStats', function () {
                    return Product::getMenuStats();
                }, ['total' => 0, 'available' => 0, 'unavailable' => 0, 'bestsellers' => 0, 'featured' => 0]),
            ],
            'salesTrend' => $salesTrend,
            'ordersByStatus' => $this->safeReport('ordersByStatus', function () {
                return Product::getOrdersByStatus();
            }, []),
            'revenueByCategory' => $this->safeReport('revenueByCategory', function () {
                return Product::getRevenueByCategory(6);
            }, []),
            'bestSellers' => $this->safeReport('bestSellers', function () {
                return Product::getBestSellers(8);
            }, []),
            'recentOrders' => $this->safeReport('recentOrders', function () {
                return Order::getRecentOrders(8);
            }, []),
            'urgentOrders' => $this->safeReport('urgentOrders', function () {
                return Order::getUrgentOrders(5);
            }, []),
            'unavailableItems' => $this->safeReport('unavailableItems', function () {
                return Product::getLowAvailability(6);
            }, []),
            'categories' => $this->safeReport('categories', function () {
                return Product::getCategories();
            }, []),
        ]);
    }

    /** Dedicated best-sellers management page (auto top + manual flags). */
    public function bestSellers(Request $request) {
        return $this->view('admin/best-sellers', [
            'title' => 'Best Sellers - CafeCat',
            'currentPage' => 'best-sellers',
            'topSellers' => $this->safeReport('topSellers', function () {
                return Product::getBestSellers(12);
            }, []),
            'flagged' => $this->safeReport('flaggedBestSellers', function () {
                return Product::getFlaggedBestSellers(24);
            }, []),
            'featured' => $this->safeReport('featuredItems', function () {
                return Product::getFeaturedItems(12);
            }, []),
            'menuStats' => $this->safeReport('menuStats', function () {
                return Product::getMenuStats();
            }, ['total' => 0, 'available' => 0, 'unavailable' => 0, 'bestsellers' => 0, 'featured' => 0]),
        ]);
    }

    /**
     * Run one report query safely. On failure the section degrades to its
     * fallback instead of taking down the entire report page.
     */
    private function safeReport($name, $fn, $fallback) {
        try {
            return $fn();
        } catch (Throwable $e) {
            error_log("Admin report section '{$name}' failed: " . $e->getMessage());
            try {
                Session::flash('report_warning_' . $name, true);
            } catch (Throwable $ignored) {
                // Session unavailable (e.g. CLI) — report still renders.
            }
            return $fallback;
        }
    }

    private function revenueSince($range) {
        $where = $range === 'today'
            ? "DATE(created_at) = CURDATE()"
            : "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = Model::query(
            "SELECT COALESCE(SUM(total_amount),0) AS t FROM orders WHERE $where AND status != 'cancelled'"
        );
        $row = $stmt->fetch();
        return (float)($row['t'] ?? 0);
    }

    private function countOrdersSince($range) {
        $where = $range === 'today'
            ? "DATE(created_at) = CURDATE()"
            : "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = Model::query("SELECT COUNT(*) AS t FROM orders WHERE $where");
        $row = $stmt->fetch();
        return (int)($row['t'] ?? 0);
    }
}
