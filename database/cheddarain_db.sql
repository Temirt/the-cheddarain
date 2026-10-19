-- ====================================================================
-- The Cheddarain — Premium Restaurant Management System
-- MySQL Schema & Initial Production Demo Dataset
-- Compatible with MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+
-- Ready for phpMyAdmin / cPanel / Plesk Import
-- ====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial Administrator & Kitchen Staff Credentials:
-- admin@thecheddarain.com / Cheddarain2026!
-- kitchen@thecheddarain.com / Kitchen2026!
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'The Cheddarain Manager', 'admin@thecheddarain.com', '15552433327', 'admin', '$2y$12$D24pQ8J9T.vK4B/44f5PgeC3k6O3H/e8uR8N3vH3gG.5M3E2sX1dG', NOW(), NOW()),
(2, 'Kitchen Staff Lead', 'kitchen@thecheddarain.com', '15552433328', 'staff', '$2y$12$R5kQ8J9T.vK4B/44f5PgeC3k6O3H/e8uR8N3vH3gG.5M3E2sX1dG', NOW(), NOW());

-- --------------------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`, `is_active`) VALUES
(1, 'Burgers', 'burgers', 'Smashed prime Angus beef, artisan brioche, and our signature melted cheddar cascades.', '🍔', 1, 1),
(2, 'Fries', 'fries', 'Crisp Idaho russets, double-fried to golden perfection and seasoned with chef blends.', '🍟', 2, 1),
(3, 'Signature Sauces', 'signature-sauces', 'House-crafted dipping sauces, liquid gold melted cheddar, and gourmet aiolis.', '🧀', 3, 1),
(4, 'Combos and Meals', 'combos-and-meals', 'Signature burger, loaded side, artisan dipping sauce, and an ice-cold beverage.', '🥤', 4, 1);

-- --------------------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `prep_time_minutes` int(11) NOT NULL DEFAULT 15,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `image_url`, `badge`, `calories`, `prep_time_minutes`, `is_available`, `is_featured`, `sort_order`) VALUES
(1, 1, 'The Ultimate Cheddar Lava', 'the-ultimate-cheddar-lava', 'Double smashed Angus beef patties, molten cheddar waterfall, caramelized balsamic onions, and house-cured pickles on toasted artisanal brioche.', 14.99, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80', 'Signature', 890, 14, 1, 1, 1),
(2, 1, 'The Smokey Truffle Melt', 'the-smokey-truffle-melt', 'Double prime beef, aged smoked Wisconsin cheddar, garlic butter sautéed cremini mushrooms, and black truffle crema on a glazed brioche bun.', 16.49, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80', 'Chef Special', 940, 15, 1, 1, 2),
(3, 1, 'The Classic Double Smash', 'the-classic-double-smash', 'Twin crispy-edged smashed beef patties, double American cheddar slices, butterhead lettuce, vine-ripened tomato, and our secret Cheddarain sauce.', 12.99, 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80', 'Best Seller', 760, 12, 1, 1, 3),
(4, 1, 'The Fiery Jalapeño Crunch', 'the-fiery-jalapeno-crunch', 'Molten pepper jack and sharp cheddar infusion, crispy fried jalapeño wheels, smoked habanero honey glaze, and pickled red onions.', 14.49, 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80', 'Spicy', 840, 14, 1, 0, 4),
(5, 1, 'The Bacon Mountain Royale', 'the-bacon-mountain-royale', 'Triple thick-cut applewood smoked bacon, double sharp cheddar drip, crispy haystack onions, and Kentucky bourbon BBQ glaze.', 15.99, 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?auto=format&fit=crop&w=800&q=80', 'Popular', 1020, 16, 1, 1, 5),
(6, 2, 'Loaded Cheddar Bacon Fries', 'loaded-cheddar-bacon-fries', 'Hand-cut crispy Idaho russets drowned in our signature warm melted cheddar sauce, topped with crunchy smoked bacon bits and fresh scallions.', 7.99, 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=800&q=80', 'Must Try', 610, 10, 1, 1, 6),
(7, 2, 'Crispy Cajun Curly Fries', 'crispy-cajun-curly-fries', 'Spiral-cut potatoes tossed in aromatic New Orleans Cajun seasoning, served piping hot and crunchy.', 5.49, 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?auto=format&fit=crop&w=800&q=80', NULL, 450, 8, 1, 0, 7),
(8, 2, 'Truffle Parmesan Fries', 'truffle-parmesan-fries', 'Golden crisp-tossed fries drizzled with white truffle oil, shaved aged Italian parmesan, and freshly cracked black pepper.', 7.49, 'https://images.unsplash.com/photo-1585109649139-366815a0d713?auto=format&fit=crop&w=800&q=80', 'Gourmet', 520, 10, 1, 1, 8),
(9, 3, 'Liquid Gold Cheddar Dip Pot', 'liquid-gold-cheddar-dip-pot', 'Our world-famous velvety smooth melted cheddar sauce served warm in an artisan dipping crock.', 2.49, 'https://images.unsplash.com/photo-1452195100486-9cc805987862?auto=format&fit=crop&w=800&q=80', 'Iconic', 190, 2, 1, 1, 9),
(10, 3, 'Smoky Chipotle Aioli', 'smoky-chipotle-aioli', 'Charred Mexican chipotle peppers folded into creamy garlic mayonnaise with a kiss of fresh lime.', 1.75, 'https://images.unsplash.com/photo-1472476443507-c7a5948772fc?auto=format&fit=crop&w=800&q=80', NULL, 140, 2, 1, 0, 10),
(11, 3, 'Truffle Garlic Crema', 'truffle-garlic-crema', 'Black summer truffle essence whipped with roasted garlic crema and chives.', 2.25, 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?auto=format&fit=crop&w=800&q=80', 'Premium', 160, 2, 1, 0, 11),
(12, 4, 'The King Cheddar Feast', 'the-king-cheddar-feast', 'The Ultimate Cheddar Lava Burger + Loaded Cheddar Bacon Fries + Liquid Gold Dip Pot + Choice of Ice-Cold Fountain Drink.', 22.99, 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?auto=format&fit=crop&w=800&q=80', 'Best Value', 1480, 16, 1, 1, 12),
(13, 4, 'Smokey Truffle Gourmet Meal', 'smokey-truffle-gourmet-meal', 'The Smokey Truffle Melt Burger + Truffle Parmesan Fries + Truffle Garlic Crema + Craft Cane Sugar Soda.', 24.49, 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80', 'Luxury Combo', 1420, 18, 1, 1, 13);

-- --------------------------------------------------------------------
-- Table structure for `product_options`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `product_options`;
CREATE TABLE `product_options` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'radio',
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_options_product_id_foreign` (`product_id`),
  CONSTRAINT `product_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Table structure for `product_option_values`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `product_option_values`;
CREATE TABLE `product_option_values` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_option_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_option_values_option_id_foreign` (`product_option_id`),
  CONSTRAINT `product_option_values_option_id_foreign` FOREIGN KEY (`product_option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Table structure for `orders`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_reference` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `fulfillment_type` enum('pickup','delivery') NOT NULL DEFAULT 'delivery',
  `delivery_address` varchar(255) DEFAULT NULL,
  `delivery_apartment` varchar(100) DEFAULT NULL,
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL DEFAULT 'cash_on_delivery',
  `payment_status` varchar(50) NOT NULL DEFAULT 'pending',
  `estimated_prep_minutes` int(11) DEFAULT NULL,
  `estimated_delivery_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_reference_unique` (`order_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `order_reference`, `customer_name`, `customer_email`, `customer_phone`, `fulfillment_type`, `delivery_address`, `delivery_apartment`, `delivery_city`, `subtotal`, `delivery_fee`, `tax`, `total`, `status`, `payment_method`, `payment_status`, `estimated_prep_minutes`, `created_at`, `updated_at`) VALUES
(1, 'CHED-782901', 'Michael Sterling', 'michael.sterling@example.com', '5551234567', 'delivery', '742 Evergreen Terrace', 'Apt 4B', 'Springfield', 25.47, 3.99, 2.16, 31.62, 'preparing', 'cash_on_delivery', 'pending', 12, NOW() - INTERVAL 10 MINUTE, NOW() - INTERVAL 2 MINUTE),
(2, 'CHED-782902', 'Sarah Connor', 'sarah.c@example.com', '5551234567', 'pickup', NULL, NULL, NULL, 22.99, 0.00, 1.95, 24.94, 'ready_for_pickup', 'payment_on_pickup', 'pending', 0, NOW() - INTERVAL 25 MINUTE, NOW() - INTERVAL 3 MINUTE),
(3, 'CHED-782904', 'David Miller', 'david.m@example.com', '5551234567', 'delivery', '1204 Pine Valley Road', NULL, 'Springfield', 16.49, 3.99, 1.40, 21.88, 'delivered', 'cash_on_delivery', 'paid', NULL, NOW() - INTERVAL 60 MINUTE, NOW() - INTERVAL 15 MINUTE);

-- --------------------------------------------------------------------
-- Table structure for `order_items`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `selected_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `subtotal`, `selected_options`) VALUES
(1, 1, 1, 'The Ultimate Cheddar Lava', 14.99, 1, 14.99, '[{"option":"Bun Style","choice":"Artisanal Brioche Bun","price":0}]'),
(2, 1, 6, 'Loaded Cheddar Bacon Fries', 7.99, 1, 7.99, '[]'),
(3, 1, 9, 'Liquid Gold Cheddar Dip Pot', 2.49, 1, 2.49, '[]'),
(4, 2, 12, 'The King Cheddar Feast Combo', 22.99, 1, 22.99, '[]'),
(5, 3, 2, 'The Smokey Truffle Melt', 16.49, 1, 16.49, '[]');

-- --------------------------------------------------------------------
-- Table structure for `order_status_histories`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `order_status_histories`;
CREATE TABLE `order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_histories_order_id_foreign` (`order_id`),
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_status_histories` (`id`, `order_id`, `from_status`, `to_status`, `note`, `user_id`, `created_at`) VALUES
(1, 1, NULL, 'pending', 'Order received from website checkout.', NULL, NOW() - INTERVAL 10 MINUTE),
(2, 1, 'pending', 'confirmed', 'Order verified and accepted by kitchen dispatch.', 1, NOW() - INTERVAL 8 MINUTE),
(3, 1, 'confirmed', 'preparing', 'Chef started grilling Angus patties and melting cheddar. Est. 12 mins.', 1, NOW() - INTERVAL 5 MINUTE),
(4, 2, NULL, 'pending', 'Order received for counter pickup.', NULL, NOW() - INTERVAL 25 MINUTE),
(5, 2, 'pending', 'confirmed', 'Confirmed by front counter staff.', 1, NOW() - INTERVAL 22 MINUTE),
(6, 2, 'confirmed', 'preparing', 'Packaging and frying loaded fries.', 1, NOW() - INTERVAL 15 MINUTE),
(7, 2, 'preparing', 'ready_for_pickup', 'Bagged in heat-insulated carrier at counter. Ready for pickup!', 1, NOW() - INTERVAL 3 MINUTE),
(8, 3, 'ready_for_pickup', 'delivered', 'Handed over directly to customer. Payment collected.', 1, NOW() - INTERVAL 15 MINUTE);

-- --------------------------------------------------------------------
-- Table structure for `restaurant_settings`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `restaurant_settings`;
CREATE TABLE `restaurant_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `restaurant_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `restaurant_settings` (`key`, `value`, `group`, `description`) VALUES
('restaurant_name', 'The Cheddarain', 'general', 'Restaurant brand name'),
('restaurant_tagline', 'Dripping in Cheddar, Crafted to Perfection', 'general', 'Brand tagline'),
('whatsapp_number', '15552433327', 'contact', 'Direct WhatsApp customer support number'),
('restaurant_phone', '+1 (555) 243-3327', 'contact', 'Public phone number'),
('restaurant_email', 'orders@thecheddarain.com', 'contact', 'Customer service email'),
('restaurant_address', '108 Artisan Burger Way, Melt District', 'contact', 'Physical store address'),
('opening_hours', 'Monday - Sunday: 11:00 AM - 11:00 PM', 'timing', 'Store hours'),
('delivery_fee', '3.99', 'ordering', 'Standard delivery fee ($)'),
('tax_rate_percent', '8.5', 'ordering', 'Estimated tax rate (%)'),
('currency_symbol', '$', 'ordering', 'Currency symbol'),
('currency_code', 'USD', 'ordering', 'ISO Currency Code'),
('enable_pickup', '1', 'ordering', 'Enable customer pickup option'),
('enable_delivery', '1', 'ordering', 'Enable home delivery option'),
('enable_cash_on_delivery', '1', 'ordering', 'Accept Cash on Delivery'),
('enable_payment_on_pickup', '1', 'ordering', 'Accept Payment at Pickup Counter');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
