# Tóm tắt các thay đổi để sửa lỗi Guest Chat

## Vấn đề gốc
- Khi user chưa đăng nhập, `user_id = 0` gây ra lỗi:
  - Không lưu được tin nhắn vào database
  - Không thể lấy lịch sử chat
  - Chatbot hoạt động nhưng không lưu trữ

## Các file đã sửa

### 1. Database Structure
- **`shoplinhkien (8).sql`**: Thay đổi `user_id` từ `int(11)` thành `varchar(255)` trong bảng `conversations` và `messages`
- **`update_database_structure.sql`**: Script SQL để cập nhật database hiện tại

### 2. PHP Files
- **`popupchatbot.php`**:
  - Thêm logic tạo `guest_id` cho user chưa đăng nhập
  - Sửa JavaScript để xử lý cả `user_id` số và `guest_id` string
  - Bỏ điều kiện `if (userId > 0)` để cho phép guest chat

- **`get_chat_history.php`**:
  - Thêm logic xử lý `guest_id`
  - Sử dụng `bind_param("s", $user_id)` cho guest và `bind_param("i", $user_id)` cho user

### 3. Python Files
- **`pythonProject/serverlinhkien.py`**:
  - Sửa hàm `create_conversation()`: chuyển `user_id` thành string
  - Sửa hàm `save_message()`: chuyển `user_id` thành string
  - Sửa hàm `save_conversation()`: chuyển `user_id` thành string
  - Sửa route `/api/chat`: chuyển `user_id` thành string

### 4. Test Files
- **`test_guest_chat.php`**: File test để kiểm tra hệ thống
- **`README_FIX_GUEST_CHAT.md`**: Hướng dẫn chi tiết

## Cách hoạt động mới

### Guest (chưa đăng nhập):
1. Tạo `guest_id = "guest_" + session_id + "_" + timestamp`
2. Lưu tin nhắn với `guest_id` này
3. Có thể lấy lịch sử chat trong cùng session

### User (đã đăng nhập):
1. Sử dụng `user_id` thật (chuyển thành string)
2. Lưu tin nhắn bình thường
3. Có thể lấy lịch sử chat từ mọi thiết bị

## Các bước triển khai

1. **Chạy script SQL**:
   ```sql
   -- Chạy file update_database_structure.sql
   ```

2. **Khởi động lại server Python**:
   ```bash
   python serverlinhkien.py
   ```

3. **Test hệ thống**:
   - Truy cập `test_guest_chat.php` để kiểm tra
   - Test chatbot với user chưa đăng nhập
   - Test chatbot với user đã đăng nhập

## Lưu ý quan trọng

- **Backup database** trước khi chạy script SQL
- Dữ liệu cũ với `user_id = 0` sẽ được chuyển thành `guest_id`
- Guest chỉ có thể xem lịch sử chat trong cùng session
- User đã đăng nhập có thể xem lịch sử chat từ mọi thiết bị
- Tất cả `user_id` trong database sẽ được lưu dưới dạng string 