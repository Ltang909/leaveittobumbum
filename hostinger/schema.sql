CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(254) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  stripe_customer_id VARCHAR(64) NULL UNIQUE,
  stripe_subscription_id VARCHAR(64) NULL UNIQUE,
  plan ENUM('free', 'helper', 'operator') NOT NULL DEFAULT 'free',
  subscription_status VARCHAR(32) NOT NULL DEFAULT 'none',
  period_start DATETIME NULL,
  period_end DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usage_periods (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  period_key CHAR(7) NOT NULL,
  used_actions INT UNSIGNED NOT NULL DEFAULT 0,
  included_actions INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user_period (user_id, period_key),
  CONSTRAINT usage_period_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE action_ledger (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  period_key CHAR(7) NOT NULL,
  tool_key VARCHAR(64) NOT NULL,
  idempotency_key VARCHAR(128) NOT NULL,
  action_count SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  status ENUM('completed', 'reversed') NOT NULL DEFAULT 'completed',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_action_attempt (user_id, idempotency_key),
  KEY user_period (user_id, period_key),
  CONSTRAINT action_ledger_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE webhook_events (
  stripe_event_id VARCHAR(64) PRIMARY KEY,
  event_type VARCHAR(96) NOT NULL,
  processed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(254) NOT NULL,
  ip_hash CHAR(64) NOT NULL,
  attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY recent_attempts (email, ip_hash, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Guest actions (no-signup tool use). Created lazily by ensureGuestTables()
-- on first guest request; documented here for reference. period_key is the
-- fixed string 'lifetime': guests get 15 lifetime actions, never a reset.
CREATE TABLE guests (
  id CHAR(36) NOT NULL PRIMARY KEY,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_seen_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  ip_hash CHAR(64) NOT NULL DEFAULT '',
  ua_hash CHAR(64) NOT NULL DEFAULT '',
  converted_user_id BIGINT UNSIGNED NULL DEFAULT NULL,
  KEY idx_guests_ip_created (ip_hash, created_at),
  KEY idx_guests_seen (converted_user_id, last_seen_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE guest_usage (
  guest_id CHAR(36) NOT NULL,
  period_key VARCHAR(16) NOT NULL,
  used_actions INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (guest_id, period_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE guest_ledger (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  guest_id CHAR(36) NOT NULL,
  period_key VARCHAR(16) NOT NULL,
  tool_key VARCHAR(64) NOT NULL,
  idempotency_key VARCHAR(128) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_guest_idem (guest_id, idempotency_key),
  KEY idx_guest_period (guest_id, period_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
