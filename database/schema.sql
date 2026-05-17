-- ============================================================
-- AgriHotel Connect — Database Schema
-- Engine: InnoDB | Charset: utf8mb4_unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS `agrihotel`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `agrihotel`;

-- ── Users ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(180)     NOT NULL,
  `password`      VARCHAR(255)     NOT NULL,
  `business_name` VARCHAR(150)     NOT NULL,
  `role`          ENUM('hotel','farmer','admin') NOT NULL DEFAULT 'farmer',
  `phone`         VARCHAR(25)      DEFAULT NULL,
  `location`      VARCHAR(150)     DEFAULT NULL,
  `bio`           TEXT             DEFAULT NULL,
  `avatar`        VARCHAR(200)     DEFAULT NULL,
  `is_active`     TINYINT(1)       NOT NULL DEFAULT 1,
  `reset_token`   VARCHAR(128)     DEFAULT NULL,
  `reset_expires` DATETIME         DEFAULT NULL,
  `last_login`    DATETIME         DEFAULT NULL,
  `created_at`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME         DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`),
  KEY `idx_role` (`role`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Products ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `products` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `farmer_id`      INT UNSIGNED  NOT NULL,
  `name`           VARCHAR(150)  NOT NULL,
  `description`    TEXT          DEFAULT NULL,
  `category`       VARCHAR(50)   NOT NULL DEFAULT 'other',
  `price_per_unit` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` INT           NOT NULL DEFAULT 0,
  `unit`           VARCHAR(30)   NOT NULL DEFAULT 'kg',
  `min_order`      INT           NOT NULL DEFAULT 1,
  `image`          VARCHAR(200)  DEFAULT NULL,
  `is_active`      TINYINT(1)    NOT NULL DEFAULT 1,
  `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME      DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_farmer`   (`farmer_id`),
  KEY `idx_category` (`category`),
  KEY `idx_active`   (`is_active`),
  KEY `idx_price`    (`price_per_unit`),
  FULLTEXT KEY `ft_name_desc` (`name`, `description`),
  CONSTRAINT `fk_product_farmer` FOREIGN KEY (`farmer_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Orders ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orders` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `hotel_id`     INT UNSIGNED  NOT NULL,
  `farmer_id`    INT UNSIGNED  NOT NULL,
  `total_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `status`       ENUM('pending','confirmed','processing','shipped','completed','cancelled')
                               NOT NULL DEFAULT 'pending',
  `notes`        TEXT          DEFAULT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME      DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hotel`   (`hotel_id`),
  KEY `idx_farmer`  (`farmer_id`),
  KEY `idx_status`  (`status`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_order_hotel`  FOREIGN KEY (`hotel_id`)  REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Order Items ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `order_items` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `order_id`   INT UNSIGNED  NOT NULL,
  `product_id` INT UNSIGNED  NOT NULL,
  `quantity`   INT           NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `subtotal`   DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `idx_order`   (`order_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `fk_item_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Messages ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `messages` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id`    INT UNSIGNED NOT NULL,
  `recipient_id` INT UNSIGNED NOT NULL,
  `subject`      VARCHAR(200) NOT NULL DEFAULT 'No subject',
  `body`         TEXT         NOT NULL,
  `is_read`      TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_recipient` (`recipient_id`),
  KEY `idx_sender`    (`sender_id`),
  KEY `idx_read`      (`is_read`),
  CONSTRAINT `fk_msg_sender`    FOREIGN KEY (`sender_id`)    REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_msg_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Seed: Admin user ───────────────────────────────────────
-- Password: Admin@1234  (Argon2id hash — regenerate for production!)
INSERT IGNORE INTO `users`
  (`email`, `password`, `business_name`, `role`, `location`, `is_active`, `created_at`)
VALUES (
  'admin@agrihotel.co.ke',
  '$argon2id$v=19$m=65536,t=4,p=1$c29tZXNhbHRoZXJl$hashed_placeholder_change_me',
  'AgriHotel Admin',
  'admin',
  'Nairobi, Kenya',
  1,
  NOW()
);

-- ── Seed: Demo Hotel ───────────────────────────────────────
-- Password: Hotel@1234
INSERT IGNORE INTO `users`
  (`email`, `password`, `business_name`, `role`, `phone`, `location`, `bio`, `is_active`, `created_at`)
VALUES (
  'hotel@demo.co.ke',
  '$argon2id$v=19$m=65536,t=4,p=1$c29tZXNhbHRoZXJl$hashed_placeholder_change_me',
  'Serena Hotel Nairobi',
  'hotel',
  '+254 720 000001',
  'Nairobi, Kenya',
  'A premier 5-star hotel committed to sourcing fresh, local produce.',
  1,
  NOW()
);

-- ── Seed: Demo Farmer ──────────────────────────────────────
-- Password: Farmer@1234
INSERT IGNORE INTO `users`
  (`email`, `password`, `business_name`, `role`, `phone`, `location`, `bio`, `is_active`, `created_at`)
VALUES (
  'farmer@demo.co.ke',
  '$argon2id$v=19$m=65536,t=4,p=1$c29tZXNhbHRoZXJl$hashed_placeholder_change_me',
  'Kiambu Fresh Farms',
  'farmer',
  '+254 720 000002',
  'Kiambu, Kenya',
  'Family-run organic farm specialising in fresh vegetables and fruits.',
  1,
  NOW()
);
