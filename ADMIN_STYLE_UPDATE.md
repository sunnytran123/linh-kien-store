# Cập Nhật Phong Cách Trang Admin - Màu Chính Xác Từ Trang Home

## Tổng Quan
Đã cập nhật phong cách của tất cả các trang admin với màu sắc chính xác từ trang home, sử dụng màu chủ đạo `#8BC34A` (xanh lá) và các màu phụ tương ứng, với thiết kế đơn giản và sạch sẽ.

## Những Thay Đổi Đã Thực Hiện

### 1. Tạo File CSS Tùy Chỉnh
- **File**: `css/admin-custom.css`
- **Mục đích**: Định nghĩa lại màu sắc và phong cách cho trang admin với màu chính xác từ trang home

### 2. Bảng Màu - Chính Xác Từ Trang Home
- **Màu chủ đạo**: `#8BC34A` (xanh lá từ trang home)
- **Màu đậm**: `#689F38` (xanh lá đậm)
- **Màu nhạt**: `#A5D6A7` (xanh lá nhạt)
- **Màu rất nhạt**: `#E8F5E8` (xanh lá rất nhạt)
- **Màu nền phụ**: `#f5f5f5` (từ trang home)
- **Màu accent**: `#8BC34A` (giống trang home)
- **Màu text chính**: `#333` (từ trang home)
- **Màu text phụ**: `#666` (từ trang home)
- **Màu viền**: `#eee` (từ trang home)
- **Màu nền chính**: `#FFFFFF` (trắng)
- **Màu nền nhạt**: `#f5f5f5` (từ trang home)

### 3. Các Thành Phần Được Cập Nhật

#### Header và Navigation
- Header: Background xanh lá đơn giản
- Sidebar: Background trắng với viền xám nhạt
- User section: Background xanh lá đơn giản
- Menu items: Background xanh lá đơn giản khi hover/active

#### Buttons
- Primary buttons: Background xanh lá đơn giản
- Success buttons: Background xanh lá success
- Info buttons: Background xanh dương info
- Warning buttons: Background cam
- Danger buttons: Background đỏ
- Tất cả buttons đều có border radius và transition đơn giản

#### Widget Cards
- Background trắng với viền xám nhạt
- Icon backgrounds: Màu đơn giản tương ứng với loại widget
- Hover effects: Shadow nhẹ đơn giản

#### Tables
- Background trắng với viền xám nhạt
- Header: Background xanh lá đơn giản với text trắng
- Hover rows: Background xanh lá rất nhạt
- Border radius và shadow nhẹ

#### Forms
- Input fields: Viền xám nhạt với focus state xanh lá
- Focus states: Box shadow xanh lá nhạt
- Border radius cho tất cả form elements

#### Pagination
- Active page: Background xanh lá đơn giản
- Hover states: Background xanh lá rất nhạt
- Border radius và spacing cải thiện

#### Modals và Alerts
- Modal headers: Background xanh lá đơn giản
- Alert styling: Background xanh lá rất nhạt
- Border radius và shadow nhẹ

### 4. Hiệu Ứng Đơn Giản
- **Solid colors**: Tất cả các thành phần đều sử dụng màu đơn giản
- **Smooth transitions**: 0.3s ease cho tất cả hover effects
- **Simple shadows**: Shadow nhẹ cho hover states
- **Border radius**: Bo góc nhất quán (6px, 8px)
- **Fade-in animation**: Hiệu ứng xuất hiện mượt mà cho các tile cards

### 5. Cải Thiện Visual Hierarchy
- **Typography**: Font weight và spacing cải thiện
- **Spacing**: Margin và padding nhất quán
- **Color contrast**: Tương phản tốt giữa text và background
- **Visual feedback**: Hover states rõ ràng cho tất cả interactive elements

### 6. Responsive Design
- Tất cả thay đổi đều responsive
- Mobile-friendly với breakpoints phù hợp
- Touch-friendly với kích thước buttons và spacing

## Cách Sử Dụng

### Để Thêm CSS Tùy Chỉnh Vào Trang Mới
```html
<!-- Main CSS-->
<link rel="stylesheet" type="text/css" href="css/main.css">
<!-- Custom Admin CSS -->
<link rel="stylesheet" type="text/css" href="css/admin-custom.css">
```

### Để Tùy Chỉnh Màu Sắc
Chỉnh sửa các biến CSS trong file `css/admin-custom.css`:
```css
:root {
  --admin-primary: #8BC34A; /* Màu chính từ trang home */
  --admin-primary-dark: #689F38; /* Màu đậm hơn */
  --admin-primary-light: #A5D6A7; /* Màu nhạt hơn */
  --admin-primary-very-light: #E8F5E8; /* Màu rất nhạt */
  --admin-secondary: #f5f5f5; /* Màu nền phụ từ trang home */
  --admin-accent: #8BC34A; /* Màu accent giống trang home */
}
```

## Lưu Ý
- Tất cả các thay đổi đều sử dụng `!important` để override CSS gốc
- File CSS tùy chỉnh được load sau file CSS chính để đảm bảo ưu tiên
- Các thay đổi không ảnh hưởng đến chức năng, chỉ thay đổi giao diện
- Sử dụng CSS variables để dễ dàng tùy chỉnh màu sắc
- Màu sắc chính xác từ trang home để tạo trải nghiệm nhất quán
- Thiết kế đơn giản và sạch sẽ, không có gradient hay hiệu ứng phức tạp

## Kết Quả
- **Giao diện thống nhất**: Sử dụng màu chính xác từ trang home
- **Đơn giản và sạch sẽ**: Không có gradient hay hiệu ứng phức tạp
- **Trải nghiệm người dùng tốt hơn**: Hover effects và transitions mượt mà
- **Dễ bảo trì**: Sử dụng CSS variables và cấu trúc rõ ràng
- **Responsive**: Hoạt động tốt trên mọi thiết bị
- **Professional look**: Giao diện chuyên nghiệp và đơn giản
- **Brand consistency**: Thống nhất hoàn toàn với phong cách trang home

## Các Trang Được Cập Nhật
- Tất cả trang quản lý (báo cáo, sản phẩm, khách hàng, đơn hàng, nhân viên, khuyến mãi)
- Tất cả trang form (thêm/sửa sản phẩm, thêm nhân viên, sửa đơn hàng) 