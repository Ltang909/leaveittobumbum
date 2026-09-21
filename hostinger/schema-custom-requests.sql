-- Custom tool requests for the Operator 36-hour guarantee.
-- Apply once on the production database before deploying the requests feature.
CREATE TABLE IF NOT EXISTS custom_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  details TEXT NOT NULL,
  status ENUM('open','delivered','overdue_credited') NOT NULL DEFAULT 'open',
  requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deadline_at DATETIME NOT NULL,
  delivered_at DATETIME NULL,
  credit_owed TINYINT(1) NOT NULL DEFAULT 0,
  stripe_credit_id VARCHAR(80) NULL,
  INDEX idx_user (user_id),
  INDEX idx_status_deadline (status, deadline_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
