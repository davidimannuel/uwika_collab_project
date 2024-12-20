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
INSERT INTO users 
    (id, name, email, password, created_at, updated_at, is_admin, status) 
VALUES
    (1, 'admin 1', 'admin1@admin.com','s3cr3t', '2024-12-17 16:45:13.000', '2024-12-17 16:45:13.000', true, 'active'),
    (2, 'user1', 'user1@gmail.com','s3cr3t', '2024-12-17 16:48:35.000', '2024-12-17 16:49:44.000', false, 'active'),
    (3, 'user2', 'user2@gmail.com','s3cr3t', '2024-12-17 16:48:56.000', '2024-12-17 16:48:56.000', false, 'inactive'),
    (4, 'user3', 'user3@gmail.com','s3cr3t', '2024-12-17 16:49:14.000', '2024-12-17 16:49:14.000', false, 'inactive');

INSERT INTO categories 
    (id, user_id, name, created_at, updated_at) 
VALUES
    (1, 2, 'Kebutuhan Pokok', '2024-12-17 16:54:47.000', '2024-12-17 16:54:47.000'),
    (2, 2, 'Pendidikan', '2024-12-17 16:55:00.000', '2024-12-17 16:55:00.000'),
    (3, 2, 'Hobi', '2024-12-17 16:57:03.000', '2024-12-17 16:57:03.000'),
    (4, 2, 'Pemasukan Bulanan', '2024-12-17 16:57:49.000', '2024-12-17 16:57:49.000'),
    (5, 2, 'Pengeluaran Bulanan', '2024-12-17 17:12:35.000', '2024-12-17 17:12:35.000'),
    (6, 2, 'pensiun dini', '2024-12-17 17:24:16.000', '2024-12-17 17:24:16.000');

INSERT INTO accounts 
    (id, user_id, name, balance, created_at, updated_at) 
VALUES
    (2, 2, 'Bank BCA', 22000000.000, '2024-12-17 17:09:35.000', '2024-12-17 17:19:14.000'),
    (3, 2, 'Uang Cash', 1200000.000, '2024-12-17 17:09:44.000', '2024-12-17 17:23:36.000'),
    (1, 2, 'Bank Mandiri', 3300000.000, '2024-12-17 17:09:29.000', '2024-12-17 17:26:25.000'),
    (4, 2, 'Tabungan', 3000000.000, '2024-12-17 17:40:08.000', '2024-12-17 17:40:46.000');

INSERT INTO transactions 
    (id, account_id, remark, type, amount, transaction_at, created_at, updated_at, is_debt) 
VALUES
    (1, 1, 'gajian', 'debit', 10000000.000, '2024-12-01 17:09:50.000', '2024-12-17 17:10:08.000', '2024-12-17 17:10:08.000', false),
    (2, 1, 'transfer balance to Uang Cash', 'credit', 2500000.000, '2024-12-02 17:10:37.000', '2024-12-17 17:11:09.000', '2024-12-17 17:11:09.000', false),
    (3, 3, 'received balance from Bank Mandiri', 'debit', 2500000.000, '2024-12-02 17:10:37.000', '2024-12-17 17:11:09.000', '2024-12-17 17:11:09.000', false),
    (4, 2, 'gaji freelance', 'debit', 5000000.000, '2024-12-05 17:11:16.000', '2024-12-17 17:11:46.000', '2024-12-17 17:11:46.000', false),
    (5, 3, 'belanja bulanan bahan makanan', 'credit', 1000000.000, '2024-12-03 17:12:40.000', '2024-12-17 17:13:21.000', '2024-12-17 17:13:21.000', false),
    (6, 2, 'teman hutang', 'credit', 500000.000, '2024-12-05 17:15:36.000', '2024-12-17 17:16:02.000', '2024-12-17 17:16:02.000', true),
    (7, 2, 'cicilan teman hutang pertama', 'debit', 200000.000, '2024-12-09 17:16:05.000', '2024-12-17 17:16:27.000', '2024-12-17 17:16:27.000', false),
    (8, 2, 'cicilan teman hutang kedua', 'debit', 200000.000, '2024-12-11 17:16:28.000', '2024-12-17 17:16:40.000', '2024-12-17 17:16:40.000', false),
    (9, 2, 'cicilan teman hutang ketiga', 'debit', 100000.000, '2024-12-12 17:16:46.000', '2024-12-17 17:17:02.000', '2024-12-17 17:17:02.000', false),
    (10, 2, 'hutang bank untuk renovasi rumah', 'debit', 20000000.000, '2025-12-31 17:17:21.000', '2024-12-17 17:18:28.000', '2024-12-17 17:18:28.000', true),
    (11, 2, 'cicilan pertama', 'credit', 1000000.000, '2025-01-11 17:18:33.000', '2024-12-17 17:18:48.000', '2024-12-17 17:18:48.000', false),
    (12, 2, 'cicilan kedua', 'credit', 2000000.000, '2025-02-07 17:18:50.000', '2024-12-17 17:19:14.000', '2024-12-17 17:19:14.000', false),
    (13, 1, 'badminton', 'credit', 200000.000, '2024-12-07 17:20:07.000', '2024-12-17 17:20:26.000', '2024-12-17 17:20:26.000', false),
    (14, 1, 'badminton', 'credit', 200000.000, '2024-12-21 17:20:30.000', '2024-12-17 17:20:54.000', '2024-12-17 17:20:54.000', false),
    (15, 1, 'Bayar spp kuliah', 'credit', 800000.000, '2024-12-04 17:20:57.000', '2024-12-17 17:21:21.000', '2024-12-17 17:21:21.000', false),
    (16, 3, 'hutang beras di toko sebelah', 'credit', 300000.000, '2024-12-19 17:23:06.000', '2024-12-17 17:23:36.000', '2024-12-17 17:23:36.000', true),
    (17, 1, 'penyisihan gaji freelance untuk pensiun dini', 'credit', 3000000.000, '2024-12-17 17:25:23.000', '2024-12-17 17:26:25.000', '2024-12-17 17:26:25.000', false),
    (18, 4, 'penyisihan gaji freelance untuk pensiun dini dari mandiri', 'debit', 3000000.000, '2024-12-17 17:40:22.000', '2024-12-17 17:40:46.000', '2024-12-17 17:40:46.000', false);

INSERT INTO transaction_categories 
    (id, transaction_id, category_id, created_at, updated_at) 
VALUES
    (1, 1, 4, NULL, NULL),
    (2, 4, 4, NULL, NULL),
    (3, 5, 1, NULL, NULL),
    (4, 5, 5, NULL, NULL),
    (5, 13, 3, NULL, NULL),
    (6, 13, 5, NULL, NULL),
    (7, 14, 3, NULL, NULL),
    (8, 14, 5, NULL, NULL),
    (9, 15, 2, NULL, NULL),
    (10, 15, 5, NULL, NULL),
    (11, 16, 1, NULL, NULL),
    (12, 16, 5, NULL, NULL),
    (13, 17, 4, NULL, NULL),
    (14, 17, 6, NULL, NULL),
    (15, 18, 4, NULL, NULL),
    (16, 18, 6, NULL, NULL);

INSERT INTO debts 
    (id, transaction_id, status, paid_amount, due_at, created_at, updated_at) 
VALUES
    (1, 6, 'paid', 500000.000, '2024-12-12 00:15:00.000', '2024-12-17 17:16:02.000', '2024-12-17 17:17:02.000'),
    (2, 10, 'partial_paid', 3000000.000, '2025-12-31 00:17:00.000', '2024-12-17 17:18:28.000', '2024-12-17 17:19:14.000'),
    (3, 16, 'unpaid', 0.000, '2024-12-24 00:23:00.000', '2024-12-17 17:23:36.000', '2024-12-17 17:23:36.000');

INSERT INTO debt_repayments 
    (id, debt_id, transaction_id, created_at, updated_at) 
VALUES
    (1, 1, 7, '2024-12-17 17:16:27.000', '2024-12-17 17:16:27.000'),
    (2, 1, 8, '2024-12-17 17:16:40.000', '2024-12-17 17:16:40.000'),
    (3, 1, 9, '2024-12-17 17:17:02.000', '2024-12-17 17:17:02.000'),
    (4, 2, 11, '2024-12-17 17:18:48.000', '2024-12-17 17:18:48.000'),
    (5, 2, 12, '2024-12-17 17:19:14.000', '2024-12-17 17:19:14.000');

INSERT INTO budgets 
    (id, category_id, name, transaction_type, collected_amount, threshold_amount, start_at, end_at, created_at, updated_at) 
VALUES
    (1, 3, 'Pengeluaran Badminton Desember', 'credit', 400000.000, 500000.000, '2024-12-01 00:00:00.000', '2024-12-31 00:00:00.000', '2024-12-17 17:14:18.000', '2024-12-17 17:20:54.000'),
    (3, 1, 'budget kebutuhan pokok januari 2025', 'credit', 0.000, 2500000.000, '2025-01-01 00:00:00.000', '2025-01-31 00:00:00.000', '2024-12-17 17:27:18.000', '2024-12-17 17:27:18.000'),
    (2, 6, 'tabungan pensiun', 'debit', 3000000.000, 20000000000.000, '2024-12-01 00:00:00.000', '2030-12-17 00:00:00.000', '2024-12-17 17:25:10.000', '2024-12-17 17:40:46.000');

INSERT INTO budget_transactions 
    (id, budget_id, transaction_id, created_at, updated_at) 
VALUES
    (1, 1, 13, '2024-12-17 17:20:26.000', '2024-12-17 17:20:26.000'),
    (2, 1, 14, '2024-12-17 17:20:54.000', '2024-12-17 17:20:54.000'),
    (3, 2, 18, '2024-12-17 17:40:46.000', '2024-12-17 17:40:46.000');


-- query data
SELECT * FROM users;
SELECT * FROM categories ORDER BY created_at DESC;
SELECT id, remark, amount FROM transactions WHERE type = 'credit';
SELECT * FROM transactions WHERE amount > 100000;
SELECT * FROM budgets WHERE threshold_amount > 100000;

SELECT 
    users.name AS user_name,
    COUNT(accounts.id) AS total_accounts,
    SUM(accounts.balance) AS total_balance
FROM users
JOIN accounts ON users.id = accounts.user_id
GROUP BY users.id, users.name;

SELECT users.name AS user_name, categories.name AS category_name
FROM users
JOIN categories ON users.id = categories.user_id;

SELECT 
    users.name AS user_name,
    transactions.remark AS transaction_remark,
    transactions.amount AS transaction_amount,
    categories.name AS category_name
FROM transactions
JOIN transaction_categories ON transactions.id = transaction_categories.transaction_id
JOIN categories ON transaction_categories.category_id = categories.id
JOIN users ON categories.user_id = users.id;

SELECT 
    debts.status AS debt_status,
    debts.due_at AS due_date,
    debts.paid_amount AS paid_amount,
    accounts.name AS account_name,
    categories.name AS category_name,
    users.name AS user_name
FROM debts
JOIN transactions ON debts.transaction_id = transactions.id
JOIN accounts ON transactions.account_id = accounts.id
JOIN transaction_categories ON transactions.id = transaction_categories.transaction_id
JOIN categories ON transaction_categories.category_id = categories.id
JOIN users ON accounts.user_id = users.id;

SELECT 
    debts.id AS debt_id,
    debts.status AS debt_status,
    debt_repayments.created_at AS repayment_date,
    debt_repayments.updated_at AS repayment_update,
    transactions.remark AS transaction_remark,
    accounts.name AS account_name,
    users.name AS user_name
FROM debt_repayments
JOIN debts ON debt_repayments.debt_id = debts.id
JOIN transactions ON debt_repayments.transaction_id = transactions.id
JOIN accounts ON transactions.account_id = accounts.id
JOIN users ON accounts.user_id = users.id;
