<?php
/**
 * Order Status Tracker Component
 * Provides visual progress indicator for order status
 */

function renderOrderStatusTracker($currentStatus, $orderDetails = []) {
    $statusFlow = [
        'pending' => [
            'title' => 'Order Processing',
            'description' => 'Your order has been received and is being reviewed',
            'icon' => 'bi-cart-check',
            'color' => '#ffc107',
            'estimated_time' => '0-5 min'
        ],
        'confirmed' => [
            'title' => 'Order Confirmed',
            'description' => 'Your order has been confirmed and payment verified',
            'icon' => 'bi-check-circle',
            'color' => '#17a2b8',
            'estimated_time' => '5-10 min'
        ],
        'preparing' => [
            'title' => 'Preparing',
            'description' => 'Your order is being prepared by our staff',
            'icon' => 'bi-cup-hot',
            'color' => '#6f42c1',
            'estimated_time' => '10-20 min'
        ],
        'ready' => [
            'title' => 'Ready for Pickup',
            'description' => 'Your order is ready and waiting for pickup',
            'icon' => 'bi-bag-check',
            'color' => '#28a745',
            'estimated_time' => 'Ready now'
        ],
        'completed' => [
            'title' => 'Completed',
            'description' => 'Your order has been completed',
            'icon' => 'bi-check2-all',
            'color' => '#6c757d',
            'estimated_time' => 'Completed'
        ],
        'cancelled' => [
            'title' => 'Cancelled',
            'description' => 'Your order has been cancelled',
            'icon' => 'bi-x-circle',
            'color' => '#dc3545',
            'estimated_time' => 'Cancelled'
        ]
    ];

    $statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'completed'];
    $currentIndex = array_search($currentStatus, $statusOrder);
    
    // Handle cancelled status separately
    if ($currentStatus === 'cancelled') {
        renderCancelledStatus($statusFlow['cancelled'], $orderDetails);
        return;
    }
    
    ?>
    <div class="order-status-tracker">
        <div class="tracker-header mb-4">
            <h5 class="mb-1">Order Status Tracker</h5>
            <p class="text-muted small mb-0">Track your order progress in real-time</p>
        </div>
        
        <div class="tracker-progress">
            <!-- Progress Line -->
            <div class="progress-line-container">
                <div class="progress-line" style="width: <?= ($currentIndex / (count($statusOrder) - 1)) * 100 ?>%"></div>
            </div>
            
            <!-- Status Steps -->
            <div class="status-steps">
                <?php foreach ($statusOrder as $index => $status): ?>
                    <?php 
                    $isCompleted = $index < $currentIndex;
                    $isCurrent = $index === $currentIndex;
                    $isUpcoming = $index > $currentIndex;
                    $statusInfo = $statusFlow[$status];
                    ?>
                    
                    <div class="status-step <?= $isCompleted ? 'completed' : '' ?> <?= $isCurrent ? 'current' : '' ?> <?= $isUpcoming ? 'upcoming' : '' ?>">
                        <div class="status-indicator">
                            <div class="status-icon" style="background-color: <?= $isCompleted || $isCurrent ? $statusInfo['color'] : '#e9ecef' ?>;">
                                <?php if ($isCompleted): ?>
                                    <i class="<?= $statusInfo['icon'] ?> text-white"></i>
                                <?php elseif ($isCurrent): ?>
                                    <i class="<?= $statusInfo['icon'] ?> text-white"></i>
                                <?php else: ?>
                                    <i class="<?= $statusInfo['icon'] ?> text-muted"></i>
                                <?php endif; ?>
                            </div>
                            <?php if ($isCurrent): ?>
                                <div class="pulse-ring"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="status-content">
                            <h6 class="status-title <?= $isCurrent ? 'text-primary' : ($isCompleted ? 'text-success' : 'text-muted') ?>">
                                <?= $statusInfo['title'] ?>
                            </h6>
                            <p class="status-description small text-muted"><?= $statusInfo['description'] ?></p>
                            <div class="status-meta">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i><?= $statusInfo['estimated_time'] ?>
                                </small>
                                <?php 
                                // Show timestamp for completed steps
                                $timestampField = [
                                    'pending' => 'created_at',
                                    'confirmed' => 'preparation_started_at',
                                    'preparing' => 'preparation_started_at',
                                    'ready' => 'ready_at',
                                    'completed' => 'completed_at'
                                ];
                                
                                if ($isCompleted && isset($timestampField[$status]) && !empty($orderDetails[$timestampField[$status]])):
                                ?>
                                    <small class="text-muted ms-3">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date('M d, h:i A', strtotime($orderDetails[$timestampField[$status]])) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Current Status Details -->
        <?php if ($currentStatus !== 'completed'): ?>
            <div class="current-status-details mt-4 p-3 bg-light rounded">
                <div class="d-flex align-items-center">
                    <div class="status-icon-large me-3" style="background-color: <?= $statusFlow[$currentStatus]['color'] ?>;">
                        <i class="<?= $statusFlow[$currentStatus]['icon'] ?> text-white fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?= $statusFlow[$currentStatus]['title'] ?></h6>
                        <p class="mb-0 small text-muted"><?= $statusFlow[$currentStatus]['description'] ?></p>
                    </div>
                </div>
                
                <?php if ($currentStatus === 'ready'): ?>
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Your order is ready for pickup!</strong> Please visit the counter to collect your order.
                    </div>
                <?php endif; ?>
                
                <?php if ($currentStatus === 'preparing'): ?>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Your order is being prepared. Estimated time: <strong>10-15 minutes</strong>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <style>
    .order-status-tracker {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .progress-line-container {
        position: relative;
        height: 4px;
        background-color: #e9ecef;
        margin: 40px 0;
        border-radius: 2px;
    }
    
    .progress-line {
        height: 100%;
        background: linear-gradient(90deg, var(--maccafe-primary) 0%, #28a745 100%);
        border-radius: 2px;
        transition: width 0.5s ease;
    }
    
    .status-steps {
        position: relative;
        margin-top: -60px;
    }
    
    .status-step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 40px;
        position: relative;
    }
    
    .status-indicator {
        position: relative;
        margin-right: 20px;
        z-index: 2;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .status-icon-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .pulse-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        border: 3px solid var(--maccafe-primary);
        border-radius: 50%;
        animation: pulse 2s infinite;
        opacity: 0.3;
    }
    
    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0.3;
        }
        50% {
            transform: translate(-50%, -50%) scale(1.2);
            opacity: 0.1;
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0.3;
        }
    }
    
    .status-content {
        flex-grow: 1;
        padding-top: 5px;
    }
    
    .status-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .status-description {
        margin-bottom: 8px;
        line-height: 1.4;
    }
    
    .status-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .status-step.completed .status-icon {
        transform: scale(1.1);
    }
    
    .status-step.current .status-icon {
        transform: scale(1.2);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }
    
    .status-step.upcoming .status-icon {
        opacity: 0.6;
    }
    
    @media (max-width: 768px) {
        .status-step {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .status-indicator {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .status-meta {
            justify-content: center;
        }
    }
    </style>
    <?php
}

function renderCancelledStatus($cancelledInfo, $orderDetails) {
    ?>
    <div class="order-status-tracker">
        <div class="tracker-header mb-4">
            <h5 class="mb-1">Order Status</h5>
            <p class="text-muted small mb-0">Your order status</p>
        </div>
        
        <div class="cancelled-status text-center py-5">
            <div class="status-icon mx-auto mb-3" style="background-color: <?= $cancelledInfo['color'] ?>; width: 80px; height: 80px;">
                <i class="<?= $cancelledInfo['icon'] ?> text-white fs-1"></i>
            </div>
            <h5 class="text-danger mb-2"><?= $cancelledInfo['title'] ?></h5>
            <p class="text-muted mb-3"><?= $cancelledInfo['description'] ?></p>
            
            <?php if (!empty($orderDetails['cancelled_at'])): ?>
                <p class="small text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    Cancelled on: <?= date('F d, Y h:i A', strtotime($orderDetails['cancelled_at'])) ?>
                </p>
            <?php endif; ?>
            
            <div class="alert alert-warning d-inline-block">
                <i class="bi bi-info-circle me-2"></i>
                If you believe this was an error, please contact our customer support.
            </div>
        </div>
    </div>
    <?php
}

function renderCompactStatusIndicator($status, $showProgress = true) {
    $statusFlow = [
        'pending' => ['color' => '#ffc107', 'icon' => 'bi-cart-check', 'title' => 'Order Received'],
        'confirmed' => ['color' => '#17a2b8', 'icon' => 'bi-check-circle', 'title' => 'Confirmed'],
        'preparing' => ['color' => '#6f42c1', 'icon' => 'bi-cup-hot', 'title' => 'Preparing'],
        'ready' => ['color' => '#28a745', 'icon' => 'bi-bag-check', 'title' => 'Ready'],
        'completed' => ['color' => '#6c757d', 'icon' => 'bi-check2-all', 'title' => 'Completed'],
        'cancelled' => ['color' => '#dc3545', 'icon' => 'bi-x-circle', 'title' => 'Cancelled']
    ];
    
    $statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'completed'];
    $currentIndex = array_search($status, $statusOrder);
    $currentInfo = $statusFlow[$status];
    
    if ($status === 'cancelled') {
        ?>
        <div class="compact-status cancelled">
            <div class="status-icon" style="background-color: <?= $currentInfo['color'] ?>;">
                <i class="<?= $currentInfo['icon'] ?> text-white"></i>
            </div>
            <div class="status-text">
                <small class="text-muted">Status:</small>
                <strong class="text-danger"><?= $currentInfo['title'] ?></strong>
            </div>
        </div>
        <?php
        return;
    }
    
    ?>
    <div class="compact-status">
        <?php if ($showProgress): ?>
            <div class="mini-progress">
                <div class="progress-bar" style="width: <?= ($currentIndex / (count($statusOrder) - 1)) * 100 ?>%; background-color: <?= $currentInfo['color'] ?>;"></div>
            </div>
        <?php endif; ?>
        
        <div class="d-flex align-items-center">
            <div class="status-icon" style="background-color: <?= $currentInfo['color'] ?>;">
                <i class="<?= $currentInfo['icon'] ?> text-white"></i>
            </div>
            <div class="status-text flex-grow-1">
                <small class="text-muted">Status:</small>
                <strong style="color: <?= $currentInfo['color'] ?>;"><?= $currentInfo['title'] ?></strong>
            </div>
            <?php if ($status === 'ready'): ?>
                <span class="badge bg-success pulse-badge">Ready</span>
            <?php elseif ($status === 'preparing'): ?>
                <span class="badge bg-primary">Preparing</span>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
    .compact-status {
        margin-bottom: 15px;
    }
    
    .mini-progress {
        height: 3px;
        background-color: #e9ecef;
        border-radius: 2px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    
    .mini-progress .progress-bar {
        height: 100%;
        transition: width 0.3s ease;
    }
    
    .compact-status .status-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 12px;
    }
    
    .compact-status .status-text small {
        display: block;
        line-height: 1;
    }
    
    .compact-status .status-text strong {
        font-size: 13px;
    }
    
    .pulse-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .compact-status.cancelled {
        opacity: 0.7;
    }
    </style>
    <?php
}
?>
