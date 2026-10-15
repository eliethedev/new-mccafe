-- 002: Payment methods policy hardening
-- Run once: mysql -u root -p cafecat_db < database/migrations/002_payment_methods_policy_hardening.sql
-- If a statement fails with "Duplicate" (already applied), it is safe to ignore it.

-- 1) Canonical `maya` method alongside legacy `paymaya`; widen payment_status lifecycle.
ALTER TABLE orders
    MODIFY COLUMN payment_method ENUM('cash','card','gcash','paymaya','maya','bank_transfer') DEFAULT 'cash';

ALTER TABLE orders
    MODIFY COLUMN payment_status ENUM('pending','paid','unpaid','rejected','refunded') DEFAULT 'pending';

-- 2) payment_proofs: accept maya + enforce one-time reference numbers.
ALTER TABLE payment_proofs
    MODIFY COLUMN payment_method ENUM('gcash','paymaya','maya','bank_transfer') NOT NULL;

-- Dedupe guard: only add unique index when no duplicates exist.
-- If this fails, remove duplicate reference_number rows first, then re-run.
ALTER TABLE payment_proofs ADD UNIQUE INDEX uniq_reference_number (reference_number);

-- 3) Verification SLA / lookup indexes.
CREATE INDEX idx_proofs_status_created ON payment_proofs (status, created_at);
CREATE INDEX idx_orders_pay_status ON orders (payment_status, status);

-- 4) Refunds: accept maya channel.
ALTER TABLE refunds
    MODIFY COLUMN refund_method ENUM('gcash','maya','paymaya','cash','bank_transfer','store_credit') NOT NULL;
