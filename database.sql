CREATE TABLE
    `Submissions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `sender_name` VARCHAR(100) NOT NULL,
        `contact_detail` VARCHAR(150) DEFAULT NULL,
        `submission_type` VARCHAR(50) NOT NULL,
        `message_content` TEXT NOT NULL,
        `status` ENUM ('pending', 'reviewed', 'on_air', 'archived') DEFAULT 'pending',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    IF NOT EXISTS `mailing_list` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL UNIQUE,
        `subscribed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `status` ENUM ('subscribed', 'unsubscribed') DEFAULT 'subscribed'
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;