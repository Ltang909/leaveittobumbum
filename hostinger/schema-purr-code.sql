-- Purr Code scan tracking.
-- Run once in phpMyAdmin on the bumbum database (staging shares the production DB).
-- Safe to re-run: CREATE TABLE IF NOT EXISTS, no changes to existing tables.
CREATE TABLE IF NOT EXISTS purrcode_links (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(16) NOT NULL UNIQUE COMMENT 'Short code in the QR, e.g. abc123xy',
  target_url TEXT NOT NULL COMMENT 'Where the scan redirects to',
  owner_kind ENUM('user','guest') NOT NULL,
  owner_id VARCHAR(64) NOT NULL COMMENT 'users.id or guest_id',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_owner (owner_kind, owner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS purrcode_scans (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  link_id BIGINT UNSIGNED NOT NULL,
  scanned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip_hash CHAR(64) NOT NULL COMMENT 'Daily-rotating hash, never a raw IP',
  ua VARCHAR(200) NULL COMMENT 'Truncated user agent',
  KEY idx_link (link_id),
  CONSTRAINT purrcode_scans_link FOREIGN KEY (link_id) REFERENCES purrcode_links(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
