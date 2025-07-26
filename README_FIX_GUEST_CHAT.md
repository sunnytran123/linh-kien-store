# Hướng dẫn sửa lỗi Guest Chat

## Vấn đề
Khi user chưa đăng nhập (user_id = 0), hệ thống chat gặp các vấn đề:
1. Không lưu được tin nhắn vào database
2. Không thể lấy lịch sử chat
3. Chatbot vẫn hoạt động nhưng không lưu trữ được

**Vấn đề bổ sung**: Session key không đúng - code tìm `$_SESSION['user_id']` nhưng login.php lưu `$_SESSION['id']`

## Giải pháp đã thực hiện

### 1. Cập nhật cấu trúc database
Chạy script SQL để cập nhật cấu trúc bảng:

```sql
-- Chạy file: update_database_structure.sql
```

Script này sẽ:
- Thay đổi kiểu dữ liệu `user_id` từ `int(11)` thành `varchar(255)` trong cả 2 bảng `conversations` và `messages`
- Cập nhật dữ liệu hiện tại: chuyển `user_id = 0` thành `guest_id` duy nhất
- Thêm index để tối ưu hiệu suất

### 2. Cập nhật code PHP
- `popupchatbot.php`: Tạo `guest_id` tạm thời cho khách chưa đăng nhập, sửa session key từ `$_SESSION['user_id']` thành `$_SESSION['id']`
- `get_chat_history.php`: Xử lý cả user đã đăng nhập và guest, sửa session key
- `debug_chat.php`: Sửa session key

### 3. Cập nhật code Python
- `serverlinhkien.py`: Sửa các hàm để xử lý `user_id` string

## Cách hoạt động mới

### Cho khách chưa đăng nhập:
1. Tạo `guest_id` duy nhất: `guest_[session_id]_[timestamp]`
2. Lưu tin nhắn với `guest_id` này
3. Có thể lấy lại lịch sử chat trong cùng session

### Cho user đã đăng nhập:
1. Sử dụng `user_id` thật (chuyển thành string)
2. Lưu tin nhắn bình thường
3. Có thể lấy lại lịch sử chat

## Các file đã sửa:
1. `popupchatbot.php` - Tạo guest_id và xử lý JavaScript, sửa session key
2. `get_chat_history.php` - Xử lý lấy lịch sử chat, sửa session key
3. `debug_chat.php` - Sửa session key
4. `pythonProject/serverlinhkien.py` - Sửa các hàm database
5. `update_database_structure.sql` - Script cập nhật database
6. `shoplinhkien (8).sql` - Cập nhật cấu trúc bảng
7. `test_session.php` - File test session và đăng nhập

## Lưu ý:
- Sau khi chạy script SQL, dữ liệu cũ với `user_id = 0` sẽ được chuyển thành `guest_id`
- Guest chỉ có thể xem lịch sử chat trong cùng session
- User đã đăng nhập có thể xem lịch sử chat từ mọi thiết bị 