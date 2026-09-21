-- Team members migration for Leave It to Bum Bum.
-- Run once in phpMyAdmin on the bumbum database (staging shares the production DB).
-- Safe to re-run: CREATE TABLE IF NOT EXISTS, no changes to existing tables.
CREATE TABLE IF NOT EXISTS team_members (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  owner_user_id BIGINT UNSIGNED NOT NULL COMMENT 'Billing account that owns the seats',
  member_user_id BIGINT UNSIGNED NULL COMMENT 'Set when the invite is accepted; NULL again when removed',
  email VARCHAR(254) NOT NULL COMMENT 'Invitee email; updated to the actual account email on accept',
  invite_token CHAR(64) NOT NULL UNIQUE,
  status ENUM('invited','active','removed') NOT NULL DEFAULT 'invited',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  accepted_at DATETIME NULL,
  UNIQUE KEY uniq_owner_email (owner_user_id, email),
  UNIQUE KEY uniq_member (member_user_id),
  KEY idx_member (member_user_id),
  CONSTRAINT team_members_owner FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT team_members_member FOREIGN KEY (member_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If you already ran an earlier version of this script before the
-- uniq_member key existed, run this line too. A "Duplicate key name"
-- error is harmless: it means the key is already there.
ALTER TABLE team_members ADD UNIQUE KEY uniq_member (member_user_id);
