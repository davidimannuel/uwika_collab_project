-- untuk basis data
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `is_admin` BOOLEAN DEFAULT FALSE,
    `status` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `accounts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `balance` DECIMAL(20, 3) DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `transactions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `remark` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL, -- debit / credit
    `amount` DECIMAL(20, 3) DEFAULT 0,
    `transaction_at` TIMESTAMP NOT NULL,
    `is_debt` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
);

CREATE TABLE `transaction_categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
);

CREATE TABLE `debts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `status` VARCHAR(255) DEFAULT 'unpaid',
    `paid_amount` DECIMAL(20, 3) DEFAULT 0,
    `due_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
);

CREATE TABLE `debt_repayments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `debt_id` BIGINT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`debt_id`) REFERENCES `debts` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
);

CREATE TABLE `budgets` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `transaction_type` VARCHAR(255) NOT NULL, -- debit / credit
    `collected_amount` DECIMAL(20, 3) DEFAULT 0,
    `threshold_amount` DECIMAL(20, 3) NOT NULL,
    `start_at` TIMESTAMP NOT NULL,
    `end_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
);

CREATE TABLE `budget_transactions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `budget_id` BIGINT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
);

-- seeder
-- Insert users
INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `status`, `created_at`, `updated_at`)
VALUES
(1, 'John Doe', 'john@example.com', 'hashed_password_1', TRUE, 'active', NOW(), NOW()),
(2, 'Jane Smith', 'jane@example.com', 'hashed_password_2', FALSE, 'inactive', NOW(), NOW()),
(3, 'Alice Johnson', 'alice@example.com', 'hashed_password_3', FALSE, 'active', NOW(), NOW()),
(4, 'Bob Brown', 'bob@example.com', 'hashed_password_4', FALSE, 'active', NOW(), NOW()),
(5, 'Charlie White', 'charlie@example.com', 'hashed_password_5', TRUE, 'active', NOW(), NOW());

-- Insert categories
INSERT INTO `categories` (`id`, `user_id`, `name`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Groceries', NOW(), NOW()),
(2, 1, 'Utilities', NOW(), NOW()),
(3, 2, 'Entertainment', NOW(), NOW()),
(4, 3, 'Travel', NOW(), NOW()),
(5, 4, 'Health', NOW(), NOW()),
(6, 5, 'Education', NOW(), NOW());

-- Insert accounts
INSERT INTO `accounts` (`id`, `user_id`, `name`, `balance`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Checking Account', 1000.000, NOW(), NOW()),
(2, 1, 'Savings Account', 5000.000, NOW(), NOW()),
(3, 2, 'Travel Fund', 300.000, NOW(), NOW()),
(4, 3, 'Emergency Fund', 800.000, NOW(), NOW()),
(5, 4, 'Health Savings', 200.000, NOW(), NOW()),
(6, 5, 'Investment Account', 10000.000, NOW(), NOW());

-- Insert transactions
INSERT INTO `transactions` (`id`, `account_id`, `remark`, `type`, `amount`, `transaction_at`, `is_debt`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Grocery shopping', 'credit', 50.000, NOW(), FALSE, NOW(), NOW()),
(2, 2, 'Salary deposit', 'debit', 2000.000, NOW(), FALSE, NOW(), NOW()),
(3, 3, 'Flight tickets', 'credit', 150.000, NOW(), FALSE, NOW(), NOW()),
(4, 4, 'Medical bill', 'credit', 100.000, NOW(), TRUE, NOW(), NOW()),
(5, 5, 'Book purchase', 'credit', 30.000, NOW(), FALSE, NOW(), NOW()),
(6, 1, 'Online subscription', 'credit', 15.000, NOW(), FALSE, NOW(), NOW()),
(7, 2, 'Interest credit', 'debit', 50.000, NOW(), FALSE, NOW(), NOW()),
(8, 3, 'Travel insurance', 'credit', 20.000, NOW(), FALSE, NOW(), NOW()),
(9, 4, 'Hospital visit', 'credit', 200.000, NOW(), TRUE, NOW(), NOW()),
(10, 5, 'Stock dividend', 'debit', 500.000, NOW(), FALSE, NOW(), NOW());

-- Insert transaction categories
INSERT INTO `transaction_categories` (`id`, `transaction_id`, `category_id`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, NOW(), NOW()),
(2, 2, 2, NOW(), NOW()),
(3, 3, 3, NOW(), NOW()),
(4, 4, 4, NOW(), NOW()),
(5, 5, 5, NOW(), NOW()),
(6, 6, 6, NOW(), NOW()),
(7, 7, 1, NOW(), NOW()),
(8, 8, 2, NOW(), NOW()),
(9, 9, 3, NOW(), NOW()),
(10, 10, 4, NOW(), NOW());

-- Insert debts
INSERT INTO `debts` (`id`, `transaction_id`, `status`, `paid_amount`, `due_at`, `created_at`, `updated_at`)
VALUES
(1, 4, 'unpaid', 0.000, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW(), NOW()),
(2, 9, 'partial_paid', 50.000, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW(), NOW());

-- Insert debt repayments
INSERT INTO `debt_repayments` (`id`, `debt_id`, `transaction_id`, `created_at`, `updated_at`)
VALUES
(1, 1, 6, NOW(), NOW()),
(2, 2, 8, NOW(), NOW());

-- Insert budgets
INSERT INTO `budgets` (`id`, `category_id`, `name`, `transaction_type`, `collected_amount`, `threshold_amount`, `start_at`, `end_at`, `created_at`, `updated_at`)
VALUES
(1, 1, 'Monthly Groceries', 'credit', 500.000, 1000.000, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), NOW(), NOW()),
(2, 2, 'Utility Bills', 'credit', 300.000, 600.000, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), NOW(), NOW());

-- Insert budget transactions
INSERT INTO `budget_transactions` (`id`, `budget_id`, `transaction_id`, `created_at`, `updated_at`)
VALUES
(1, 1, 1, NOW(), NOW()),
(2, 2, 2, NOW(), NOW());
