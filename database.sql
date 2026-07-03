CREATE TABLE `Submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sender_name` VARCHAR(100) NOT NULL,
    `contact_detail` VARCHAR(150) DEFAULT NULL,
    `submission_type` VARCHAR(50) NOT NULL,
    `message_content` TEXT NOT NULL,
    `status` ENUM('pending', 'reviewed', 'on_air', 'archived') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);