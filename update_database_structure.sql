-- Script cập nhật cấu trúc database để hỗ trợ guest_id
-- Chạy script này để cập nhật cấu trúc bảng conversations và messages

-- Cập nhật bảng conversations
ALTER TABLE `conversations` MODIFY COLUMN `user_id` varchar(255) DEFAULT NULL;

-- Cập nhật bảng messages  
ALTER TABLE `messages` MODIFY COLUMN `user_id` varchar(255) NOT NULL;

-- Cập nhật dữ liệu hiện tại: chuyển user_id = 0 thành guest_id
UPDATE `conversations` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0 OR `user_id` IS NULL;

UPDATE `messages` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0;

-- Thêm index để tối ưu hiệu suất tìm kiếm
CREATE INDEX idx_conversations_user_id ON conversations(user_id);
CREATE INDEX idx_messages_user_id ON messages(user_id);
CREATE INDEX idx_messages_conversation_id ON messages(conversation_id); 