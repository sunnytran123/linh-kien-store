-- Script để tăng timeout và buffer size cho MySQL
-- Chạy các câu lệnh này trước khi chạy script cập nhật database

-- Tăng max_allowed_packet (mặc định là 1MB, tăng lên 16MB)
SET GLOBAL max_allowed_packet = 16777216;

-- Tăng wait_timeout (mặc định là 28800 giây = 8 giờ)
SET GLOBAL wait_timeout = 600;

-- Tăng interactive_timeout
SET GLOBAL interactive_timeout = 600;

-- Kiểm tra các giá trị hiện tại
SHOW VARIABLES LIKE 'max_allowed_packet';
SHOW VARIABLES LIKE 'wait_timeout';
SHOW VARIABLES LIKE 'interactive_timeout'; 