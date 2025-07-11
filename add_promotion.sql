-- Thêm khuyến mãi cho sản phẩm mã 10 (Váy peplum công sở)
-- Khuyến mãi hôm nay (10/07/2025)
INSERT INTO `khuyenmai` (`khuyenmaiid`, `tenkhuyenmai`, `giatri`, `ngaybatdau`, `ngayketthuc`) VALUES
(1, 'Khuyến mãi đặc biệt - Váy peplum công sở', 15.00, '2025-07-10 00:00:00', '2025-07-10 23:59:59');

-- Khuyến mãi ngày mai (11/07/2025)
INSERT INTO `khuyenmai` (`khuyenmaiid`, `tenkhuyenmai`, `giatri`, `ngaybatdau`, `ngayketthuc`) VALUES
(2, 'Flash Sale - Váy peplum công sở', 20.00, '2025-07-11 00:00:00', '2025-07-11 23:59:59');

-- Liên kết khuyến mãi với sản phẩm mã 10
INSERT INTO `sanpham_khuyenmai` (`id`, `khuyenmai_id`, `sanpham_id`) VALUES
(1, 1, 10),
(2, 2, 10);

-- Cập nhật sản phẩm để có khuyến mãi
UPDATE `sanpham` SET `makhuyenmai` = 1 WHERE `sanphamid` = 10; 