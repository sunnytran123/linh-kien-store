-- Script cập nhật database từng bước để tránh lỗi "MySQL server has gone away"
-- Chạy từng câu lệnh một cách riêng biệt

-- Bước 1: Cập nhật bảng conversations
ALTER TABLE `conversations` MODIFY COLUMN `user_id` varchar(255) DEFAULT NULL;

-- Bước 2: Cập nhật bảng messages  
ALTER TABLE `messages` MODIFY COLUMN `user_id` varchar(255) NOT NULL;

-- Bước 3: Cập nhật dữ liệu conversations (chạy riêng)
UPDATE `conversations` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0 OR `user_id` IS NULL;

-- Bước 4: Cập nhật dữ liệu messages (chạy riêng)
UPDATE `messages` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0;

-- Bước 5: Thêm index (chạy riêng từng câu)
CREATE INDEX idx_conversations_user_id ON conversations(user_id);

-- Bước 6: Thêm index (chạy riêng từng câu)
CREATE INDEX idx_messages_user_id ON messages(user_id);

-- Bước 7: Thêm index (chạy riêng từng câu)
CREATE INDEX idx_messages_conversation_id ON messages(conversation_id); 