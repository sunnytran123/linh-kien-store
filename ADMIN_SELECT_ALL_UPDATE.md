# Cập nhật chức năng Select All và Xóa tất cả cho Admin Panel

## Tổng quan
Đã cập nhật chức năng "Select All" và "Xóa tất cả" cho tất cả các trang quản lý trong admin panel.

## Các trang đã được cập nhật

### 1. Quản lý đơn hàng (table-data-oder.php)
- ✅ Đã có checkbox với tên đúng
- ✅ Đã cập nhật nút "Xóa tất cả" để gọi hàm `deleteAllOrders()`
- ✅ Đã thêm JavaScript xử lý xóa nhiều đơn hàng
- ✅ Đã tạo file `delete_orders.php` để xử lý backend

### 2. Quản lý khách hàng (table-data-customers.php)
- ✅ Đã có checkbox với tên đúng
- ✅ Đã cập nhật nút "Xóa tất cả" để gọi hàm `deleteAllCustomers()`
- ✅ Đã thêm JavaScript xử lý xóa nhiều khách hàng
- ✅ Đã tạo file `delete_customers.php` để xử lý backend

### 3. Quản lý sản phẩm (table-data-product.php)
- ✅ Đã cập nhật checkbox với tên đúng
- ✅ Đã cập nhật nút "Xóa tất cả" để gọi hàm `deleteAllProducts()`
- ✅ Đã thêm JavaScript xử lý xóa nhiều sản phẩm
- ✅ Đã tạo file `delete_products.php` để xử lý backend

### 4. Quản lý khuyến mãi (table-data-khuyenmai.php)
- ✅ Đã cập nhật checkbox với tên đúng
- ✅ Đã thêm nút "Xóa tất cả" và gọi hàm `deleteAllPromotions()`
- ✅ Đã thêm JavaScript xử lý xóa nhiều khuyến mãi
- ✅ Đã tạo file `delete_promotions.php` để xử lý backend

### 5. Quản lý nhân viên (table-data-table.php)
- ✅ Đã có chức năng Select All và Xóa tất cả hoạt động

## Tính năng chung

### Select All
- Checkbox "Select All" sẽ chọn/bỏ chọn tất cả các checkbox trong bảng
- Khi chọn tất cả checkbox riêng lẻ, checkbox "Select All" sẽ tự động được chọn
- Khi bỏ chọn bất kỳ checkbox nào, checkbox "Select All" sẽ tự động bỏ chọn

### Xóa tất cả
- Chỉ xóa những mục đã được chọn
- Hiển thị cảnh báo nếu không có mục nào được chọn
- Hiển thị xác nhận với số lượng mục sẽ bị xóa
- Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
- Xóa các liên kết liên quan trước khi xóa mục chính

## Files mới được tạo
1. `delete_orders.php` - Xử lý xóa nhiều đơn hàng
2. `delete_customers.php` - Xử lý xóa nhiều khách hàng
3. `delete_products.php` - Xử lý xóa nhiều sản phẩm
4. `delete_promotions.php` - Xử lý xóa nhiều khuyến mãi

## Bảo mật
- Tất cả các file xử lý đều kiểm tra session và quyền truy cập
- Sử dụng prepared statements để tránh SQL injection
- Sử dụng transaction để đảm bảo tính nhất quán dữ liệu
- Xóa các liên kết liên quan trước khi xóa mục chính

## Cách sử dụng
1. Chọn các mục cần xóa bằng checkbox
2. Hoặc sử dụng checkbox "Select All" để chọn tất cả
3. Nhấn nút "Xóa tất cả"
4. Xác nhận hành động trong hộp thoại
5. Hệ thống sẽ xóa các mục đã chọn và reload trang 