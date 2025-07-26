# Hướng dẫn cập nhật database thủ công

## Nếu gặp lỗi "MySQL server has gone away"

### Cách 1: Sử dụng phpMyAdmin
1. Mở phpMyAdmin
2. Chọn database `shoplinhkien`
3. Chạy từng câu lệnh SQL một cách riêng biệt:

```sql
-- Câu 1: Cập nhật bảng conversations
ALTER TABLE `conversations` MODIFY COLUMN `user_id` varchar(255) DEFAULT NULL;
```

```sql
-- Câu 2: Cập nhật bảng messages
ALTER TABLE `messages` MODIFY COLUMN `user_id` varchar(255) NOT NULL;
```

```sql
-- Câu 3: Cập nhật dữ liệu conversations
UPDATE `conversations` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0 OR `user_id` IS NULL;
```

```sql
-- Câu 4: Cập nhật dữ liệu messages
UPDATE `messages` SET `user_id` = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE `user_id` = 0;
```

### Cách 2: Sử dụng MySQL Command Line
1. Mở Command Prompt/Terminal
2. Kết nối MySQL:
```bash
mysql -u root -p shoplinhkien
```

3. Chạy từng câu lệnh:
```sql
ALTER TABLE conversations MODIFY COLUMN user_id varchar(255) DEFAULT NULL;
ALTER TABLE messages MODIFY COLUMN user_id varchar(255) NOT NULL;
UPDATE conversations SET user_id = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE user_id = 0 OR user_id IS NULL;
UPDATE messages SET user_id = CONCAT('guest_', UNIX_TIMESTAMP(), '_', id) WHERE user_id = 0;
```

### Cách 3: Tăng timeout trước khi chạy
1. Chạy script `fix_mysql_timeout.sql` trước
2. Sau đó chạy script cập nhật database

### Cách 4: Kiểm tra và sửa lỗi
Nếu vẫn gặp lỗi, kiểm tra:
1. Kích thước database có quá lớn không
2. MySQL có đủ memory không
3. Có process nào đang lock bảng không

### Lệnh kiểm tra:
```sql
-- Kiểm tra kích thước database
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'shoplinhkien'
GROUP BY table_schema;

-- Kiểm tra process đang chạy
SHOW PROCESSLIST;

-- Kiểm tra trạng thái bảng
SHOW TABLE STATUS LIKE 'conversations';
SHOW TABLE STATUS LIKE 'messages';
``` 