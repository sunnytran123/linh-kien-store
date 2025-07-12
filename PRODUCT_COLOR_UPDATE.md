# Cập nhật thêm trường Màu sắc cho Form Sản phẩm

## Tổng quan
Đã thêm trường quản lý màu sắc vào cả form thêm sản phẩm và form chỉnh sửa sản phẩm để đồng nhất với nhau.

## Các thay đổi đã thực hiện

### 1. Form thêm sản phẩm (form-add-san-pham.php)

#### Backend:
- ✅ Thêm truy vấn lấy danh sách màu sắc từ bảng `mausac`
- ✅ Thêm xử lý lưu màu sắc vào bảng `sanpham_mausac` khi thêm sản phẩm

#### Frontend:
- ✅ Thêm section "Quản lý Màu sắc" với bảng checkbox
- ✅ Hiển thị tên màu và ô màu tượng trưng
- ✅ Thêm validation JavaScript để kiểm tra ít nhất 1 màu được chọn

### 2. Form chỉnh sửa sản phẩm (form-edit-san-pham.php)

#### Backend:
- ✅ Thêm truy vấn lấy màu sắc hiện tại của sản phẩm
- ✅ Thêm truy vấn lấy danh sách tất cả màu sắc có sẵn
- ✅ Thêm xử lý cập nhật màu sắc (xóa cũ, thêm mới)

#### Frontend:
- ✅ Thêm section "Quản lý Màu sắc" với bảng checkbox
- ✅ Hiển thị màu sắc hiện tại đã được chọn
- ✅ Hiển thị tên màu và ô màu tượng trưng
- ✅ Thêm validation JavaScript để kiểm tra ít nhất 1 màu được chọn

## Cấu trúc dữ liệu

### Bảng `mausac`:
- `mausacid`: ID màu sắc
- `tenmau`: Tên màu (VD: Trắng, Đen, Đỏ...)
- `mamau`: Mã màu hex (VD: #FFFFFF, #000000...)

### Bảng `sanpham_mausac`:
- `sanphamid`: ID sản phẩm
- `mausacid`: ID màu sắc

## Màu sắc có sẵn trong hệ thống:
1. Trắng (#FFFFFF)
2. Đen (#000000)
3. Đỏ (#FF0000)
4. Xanh dương (#0000FF)
5. Vàng (#FFFF00)
6. Xanh lá (#008000)
7. Hồng (#FFC1CC)
8. Xám (#808080)
9. Nâu (#8B4513)
10. Be (#F5F5DC)

## Tính năng mới

### Giao diện:
- Bảng checkbox để chọn nhiều màu sắc
- Hiển thị tên màu rõ ràng
- Ô màu tượng trưng (30x30px) với viền và bo góc
- Validation đảm bảo ít nhất 1 màu được chọn

### Backend:
- Lưu trữ quan hệ nhiều-nhiều giữa sản phẩm và màu sắc
- Xử lý cập nhật màu sắc khi chỉnh sửa sản phẩm
- Sử dụng prepared statements để bảo mật

## Cách sử dụng

### Thêm sản phẩm:
1. Điền thông tin cơ bản
2. Nhập số lượng cho các size
3. **Chọn ít nhất một màu sắc** từ danh sách
4. Upload ảnh và mô tả
5. Lưu sản phẩm

### Chỉnh sửa sản phẩm:
1. Thông tin màu sắc hiện tại sẽ được hiển thị
2. Có thể thêm/bớt màu sắc bằng cách check/uncheck
3. **Phải chọn ít nhất một màu sắc**
4. Lưu thay đổi

## Validation
- Bắt buộc chọn ít nhất 1 màu sắc
- Bắt buộc nhập ít nhất 1 size với số lượng > 0
- Hiển thị thông báo lỗi nếu không đáp ứng yêu cầu 