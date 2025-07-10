-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 07:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoplinhkien`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `chitietdonhangid` int(11) NOT NULL,
  `madonhang` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `sizeid` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `chitietgiohangid` int(11) NOT NULL,
  `magiohang` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `sizeid` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhgia`
--

CREATE TABLE `danhgia` (
  `danhgiaid` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `manguoidung` int(11) NOT NULL,
  `diem` int(11) NOT NULL CHECK (`diem` between 1 and 5),
  `noidung` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `danhmucid` int(11) NOT NULL,
  `tendanhmuc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`danhmucid`, `tendanhmuc`) VALUES
(1, 'Váy nữ'),
(2, 'Túi xách'),
(3, 'Áo nam');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `donhangid` int(11) NOT NULL,
  `manguoidung` int(11) NOT NULL,
  `ten_nguoi_dat` varchar(100) NOT NULL,
  `ngaydat` datetime DEFAULT current_timestamp(),
  `trangthai` varchar(50) NOT NULL,
  `tongtien` decimal(10,2) NOT NULL,
  `diachigiao` text NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `phuongthuctt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `giohangid` int(11) NOT NULL,
  `manguoidung` int(11) NOT NULL,
  `tongtien` decimal(10,2) DEFAULT 0.00,
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hinhanhsanpham`
--

CREATE TABLE `hinhanhsanpham` (
  `hinhanhid` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `duongdan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hinhanhsanpham`
--

INSERT INTO `hinhanhsanpham` (`hinhanhid`, `masanpham`, `duongdan`) VALUES
(24, 1, 'picture/vay_maxi_hoa_nhi_front.webp'),
(25, 1, 'picture/vay_maxi_hoa_nhi_side.webp'),
(26, 1, 'picture/vay_maxi_hoa_nhi_back.webp'),
(27, 1, 'picture/vay_maxi_hoa_nhi_detail.webp'),
(28, 2, 'picture/vay_suong_co_v_front.webp'),
(29, 2, 'picture/vay_suong_co_v_side.webp'),
(30, 2, 'picture/vay_suong_co_v_back.webp'),
(31, 2, 'picture/vay_suong_co_v_detail.webp'),
(32, 3, 'picture/vay_xoe_cong_chua_front.webp'),
(33, 3, 'picture/vay_xoe_cong_chua_side.webp'),
(34, 3, 'picture/vay_xoe_cong_chua_back.webp'),
(35, 3, 'picture/vay_xoe_cong_chua_detail.webp'),
(36, 4, 'picture/vay_body_om_sat_front.webp'),
(37, 4, 'picture/vay_body_om_sat_side.webp'),
(38, 4, 'picture/vay_body_om_sat_back.webp'),
(39, 4, 'picture/vay_body_om_sat_detail.webp'),
(40, 5, 'picture/vay_yem_denim_front.webp'),
(41, 5, 'picture/vay_yem_denim_side.webp'),
(42, 5, 'picture/vay_yem_denim_back.webp'),
(43, 5, 'picture/vay_yem_denim_detail.webp'),
(44, 6, 'picture/vay_ren_trang_front.webp'),
(45, 6, 'picture/vay_ren_trang_side.webp'),
(46, 6, 'picture/vay_ren_trang_back.webp'),
(47, 6, 'picture/vay_ren_trang_detail.webp'),
(48, 7, 'picture/vay_da_hoi_dai_front.webp'),
(49, 7, 'picture/vay_da_hoi_dai_side.webp'),
(50, 7, 'picture/vay_da_hoi_dai_back.webp'),
(51, 7, 'picture/vay_da_hoi_dai_detail.webp'),
(52, 8, 'picture/vay_midi_xep_ly_front.webp'),
(53, 8, 'picture/vay_midi_xep_ly_side.webp'),
(54, 8, 'picture/vay_midi_xep_ly_back.webp'),
(55, 8, 'picture/vay_midi_xep_ly_detail.webp'),
(56, 9, 'picture/vay_so_mi_dang_dai_side.webp'),
(57, 9, 'picture/vay_so_mi_dang_dai_back.webp'),
(58, 9, 'picture/vay_so_mi_dang_dai_detail.webp'),
(59, 10, 'picture/vay_peplum_cong_so_front.webp'),
(60, 10, 'picture/vay_peplum_cong_so_side.webp'),
(61, 10, 'picture/vay_peplum_cong_so_back.webp'),
(62, 10, 'picture/vay_peplum_cong_so_detail.webp'),
(63, 11, 'picture/vay_hai_day_lua_front.webp'),
(64, 11, 'picture/vay_hai_day_lua_side.webp'),
(65, 11, 'picture/vay_hai_day_lua_back.webp'),
(66, 11, 'picture/vay_hai_day_lua_detail.webp'),
(67, 12, 'picture/vay_cham_bi_retro_front.webp'),
(68, 12, 'picture/vay_cham_bi_retro_side.webp'),
(69, 12, 'picture/vay_cham_bi_retro_back.webp'),
(70, 12, 'picture/vay_cham_bi_retro_detail.webp'),
(71, 13, 'picture/vay_bo_sat_dinh_da_front.webp'),
(72, 13, 'picture/vay_bo_sat_dinh_da_side.webp'),
(73, 14, 'picture/vay_tre_vai_hoa_front.webp'),
(74, 14, 'picture/vay_tre_vai_hoa_side.webp'),
(75, 14, 'picture/vay_tre_vai_hoa_back.webp'),
(76, 14, 'picture/vay_tre_vai_hoa_detail.webp'),
(77, 15, 'picture/vay_xep_tang_bohemian_front.webp'),
(78, 15, 'picture/vay_xep_tang_bohemian_side.webp'),
(79, 15, 'picture/vay_xep_tang_bohemian_back.webp'),
(80, 15, 'picture/vay_xep_tang_bohemian_detail.webp'),
(81, 1, 'picture/vay_maxi_hoa_nhi_front.webp'),
(82, 1, 'picture/vay_maxi_hoa_nhi_side.webp'),
(83, 1, 'picture/vay_maxi_hoa_nhi_back.webp'),
(84, 1, 'picture/vay_maxi_hoa_nhi_detail.webp'),
(85, 2, 'picture/vay_suong_co_v_front.webp'),
(86, 2, 'picture/vay_suong_co_v_side.webp'),
(87, 2, 'picture/vay_suong_co_v_back.webp'),
(88, 2, 'picture/vay_suong_co_v_detail.webp'),
(89, 3, 'picture/vay_xoe_cong_chua_front.webp'),
(90, 3, 'picture/vay_xoe_cong_chua_side.webp'),
(91, 3, 'picture/vay_xoe_cong_chua_back.webp'),
(92, 3, 'picture/vay_xoe_cong_chua_detail.webp'),
(93, 4, 'picture/vay_body_om_sat_front.webp'),
(94, 4, 'picture/vay_body_om_sat_side.webp'),
(95, 4, 'picture/vay_body_om_sat_back.webp'),
(96, 4, 'picture/vay_body_om_sat_detail.webp'),
(97, 5, 'picture/vay_yem_denim_front.webp'),
(98, 5, 'picture/vay_yem_denim_side.webp'),
(99, 5, 'picture/vay_yem_denim_back.webp'),
(100, 5, 'picture/vay_yem_denim_detail.webp'),
(101, 6, 'picture/vay_ren_trang_front.webp'),
(102, 6, 'picture/vay_ren_trang_side.webp'),
(103, 6, 'picture/vay_ren_trang_back.webp'),
(104, 6, 'picture/vay_ren_trang_detail.webp'),
(105, 7, 'picture/vay_da_hoi_dai_front.webp'),
(106, 7, 'picture/vay_da_hoi_dai_side.webp'),
(107, 7, 'picture/vay_da_hoi_dai_back.webp'),
(108, 7, 'picture/vay_da_hoi_dai_detail.webp'),
(109, 8, 'picture/vay_midi_xep_ly_front.webp'),
(110, 8, 'picture/vay_midi_xep_ly_side.webp'),
(111, 8, 'picture/vay_midi_xep_ly_back.webp'),
(112, 8, 'picture/vay_midi_xep_ly_detail.webp'),
(113, 9, 'picture/vay_so_mi_dang_dai_side.webp'),
(114, 9, 'picture/vay_so_mi_dang_dai_back.webp'),
(115, 9, 'picture/vay_so_mi_dang_dai_detail.webp'),
(116, 10, 'picture/vay_peplum_cong_so_front.webp'),
(117, 10, 'picture/vay_peplum_cong_so_side.webp'),
(118, 10, 'picture/vay_peplum_cong_so_back.webp'),
(119, 10, 'picture/vay_peplum_cong_so_detail.webp'),
(120, 11, 'picture/vay_hai_day_lua_front.webp'),
(121, 11, 'picture/vay_hai_day_lua_side.webp'),
(122, 11, 'picture/vay_hai_day_lua_back.webp'),
(123, 11, 'picture/vay_hai_day_lua_detail.webp'),
(124, 12, 'picture/vay_cham_bi_retro_front.webp'),
(125, 12, 'picture/vay_cham_bi_retro_side.webp'),
(126, 12, 'picture/vay_cham_bi_retro_back.webp'),
(127, 12, 'picture/vay_cham_bi_retro_detail.webp'),
(128, 13, 'picture/vay_bo_sat_dinh_da_front.webp'),
(129, 13, 'picture/vay_bo_sat_dinh_da_side.webp'),
(130, 14, 'picture/vay_tre_vai_hoa_front.webp'),
(131, 14, 'picture/vay_tre_vai_hoa_side.webp'),
(132, 14, 'picture/vay_tre_vai_hoa_back.webp'),
(133, 14, 'picture/vay_tre_vai_hoa_detail.webp'),
(134, 15, 'picture/vay_xep_tang_bohemian_front.webp'),
(135, 15, 'picture/vay_xep_tang_bohemian_side.webp'),
(136, 15, 'picture/vay_xep_tang_bohemian_back.webp'),
(137, 15, 'picture/vay_xep_tang_bohemian_detail.webp'),
(138, 16, 'picture/tui_tote_canvas_front.webp'),
(139, 16, 'picture/tui_tote_canvas_side.webp'),
(140, 16, 'picture/tui_tote_canvas_back.webp'),
(141, 16, 'picture/tui_tote_canvas_detail.webp'),
(142, 17, 'picture/tui_xach_da_mini_front.webp'),
(143, 17, 'picture/tui_xach_da_mini_side.webp'),
(144, 17, 'picture/tui_xach_da_mini_back.webp'),
(145, 17, 'picture/tui_xach_da_mini_detail.webp'),
(146, 18, 'picture/tui_bucket_day_rut_front.webp'),
(147, 18, 'picture/tui_bucket_day_rut_side.webp'),
(148, 18, 'picture/tui_bucket_day_rut_back.webp'),
(149, 18, 'picture/tui_bucket_day_rut_detail.webp'),
(150, 19, 'picture/tui_deo_cheo_vai_front.webp'),
(151, 19, 'picture/tui_deo_cheo_vai_side.webp'),
(152, 19, 'picture/tui_deo_cheo_vai_back.webp'),
(153, 19, 'picture/tui_deo_cheo_vai_detail.webp'),
(154, 20, 'picture/tui_clutch_da_hoi_front.webp'),
(155, 20, 'picture/tui_clutch_da_hoi_side.webp'),
(156, 20, 'picture/tui_clutch_da_hoi_back.webp'),
(157, 20, 'picture/tui_clutch_da_hoi_detail.webp'),
(158, 21, 'picture/tui_shopper_lon_front.webp'),
(159, 21, 'picture/tui_shopper_lon_side.webp'),
(160, 21, 'picture/tui_shopper_lon_back.webp'),
(161, 21, 'picture/tui_shopper_lon_detail.webp'),
(162, 22, 'picture/tui_xach_cong_so_front.webp'),
(163, 22, 'picture/tui_xach_cong_so_side.webp'),
(164, 22, 'picture/tui_xach_cong_so_back.webp'),
(165, 22, 'picture/tui_xach_cong_so_detail.webp'),
(166, 23, 'picture/tui_deo_vai_boho_front.webp'),
(167, 23, 'picture/tui_deo_vai_boho_side.webp'),
(168, 23, 'picture/tui_deo_vai_boho_back.webp'),
(169, 23, 'picture/tui_deo_vai_boho_detail.webp'),
(170, 24, 'picture/tui_backpack_vai_front.webp'),
(171, 24, 'picture/tui_backpack_vai_side.webp'),
(172, 24, 'picture/tui_backpack_vai_back.webp'),
(173, 24, 'picture/tui_backpack_vai_detail.webp'),
(174, 25, 'picture/tui_xach_tay_cam_front.webp'),
(175, 25, 'picture/tui_xach_tay_cam_side.webp'),
(176, 25, 'picture/tui_xach_tay_cam_back.webp'),
(177, 25, 'picture/tui_xach_tay_cam_detail.webp'),
(178, 26, 'picture/tui_deo_cheo_da_front.webp'),
(179, 26, 'picture/tui_deo_cheo_da_side.webp'),
(180, 26, 'picture/tui_deo_cheo_da_back.webp'),
(181, 26, 'picture/tui_deo_cheo_da_detail.webp'),
(182, 27, 'picture/tui_tote_hoa_tiet_front.webp'),
(183, 27, 'picture/tui_tote_hoa_tiet_side.webp'),
(184, 27, 'picture/tui_tote_hoa_tiet_back.webp'),
(185, 27, 'picture/tui_tote_hoa_tiet_detail.webp'),
(186, 31, 'picture/ao_so_mi_trang_front.webp'),
(187, 31, 'picture/ao_so_mi_trang_side.webp'),
(188, 31, 'picture/ao_so_mi_trang_back.webp'),
(189, 31, 'picture/ao_so_mi_trang_detail.webp'),
(190, 32, 'picture/ao_thun_co_tron_front.webp'),
(191, 32, 'picture/ao_thun_co_tron_side.webp'),
(192, 32, 'picture/ao_thun_co_tron_back.webp'),
(193, 32, 'picture/ao_thun_co_tron_detail.webp'),
(194, 33, 'picture/ao_polo_basic_front.webp'),
(195, 33, 'picture/ao_polo_basic_side.webp'),
(196, 33, 'picture/ao_polo_basic_back.webp'),
(197, 33, 'picture/ao_polo_basic_detail.webp'),
(198, 34, 'picture/ao_so_mi_caro_front.webp'),
(199, 34, 'picture/ao_so_mi_caro_side.webp'),
(200, 34, 'picture/ao_so_mi_caro_back.webp'),
(201, 34, 'picture/ao_so_mi_caro_detail.webp'),
(202, 35, 'picture/ao_hoodie_tron_front.webp'),
(203, 35, 'picture/ao_hoodie_tron_side.webp'),
(204, 35, 'picture/ao_hoodie_tron_back.webp'),
(205, 35, 'picture/ao_hoodie_tron_detail.webp'),
(206, 45, 'picture/ao_bombo_front.webp'),
(207, 45, 'picture/ao_bombo_side.webp'),
(208, 45, 'picture/ao_bombo_back.webp'),
(209, 45, 'picture/ao_bombo_detail.webp');

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `khuyenmaiid` int(11) NOT NULL,
  `tenkhuyenmai` varchar(255) NOT NULL,
  `giatri` decimal(10,2) NOT NULL,
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mausac`
--

CREATE TABLE `mausac` (
  `mausacid` int(11) NOT NULL,
  `tenmau` varchar(50) NOT NULL,
  `mamau` varchar(7) DEFAULT NULL COMMENT 'Mã màu hex, ví dụ: #FFFFFF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mausac`
--

INSERT INTO `mausac` (`mausacid`, `tenmau`, `mamau`) VALUES
(1, 'Trắng', '#FFFFFF'),
(2, 'Đen', '#000000'),
(3, 'Đỏ', '#FF0000'),
(4, 'Xanh dương', '#0000FF'),
(5, 'Vàng', '#FFFF00'),
(6, 'Xanh lá', '#008000'),
(7, 'Hồng', '#FFC1CC'),
(8, 'Xám', '#808080'),
(9, 'Nâu', '#8B4513'),
(10, 'Be', '#F5F5DC');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ten_dang_nhap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `loai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `email`, `sdt`, `mat_khau`, `ngay_tao`, `loai`) VALUES
(1, 'admin', 'admin01@gmail.com', '09128382782', '123456', '2025-02-12 22:17:15', 1),
(2, 'nhanvien01', 'nhanvien01@gmail.com', '0923728263', '123456', '2025-02-12 22:17:15', 2),
(3, 'nhanvien02', 'nhanvien02@gmail.com', '09127384675', '123456', '2025-02-12 22:18:13', 2),
(4, 'khachhang01', 'khachhang01@gmail.com', '07238361283', '123456', '2025-02-12 22:18:13', 3),
(5, 'khachhang02', 'khachhang02@gmail.com', '0937872637', '123456', '2025-02-12 22:18:43', 3),
(6, 'SunnyTran', 'sunny@gmail.com', '093728356223', '123454', '2025-02-13 12:44:54', 3),
(7, 'SunnyTran1', 'sunny1@gmail.com', '0914090763', '12343', '2025-02-13 12:45:34', 3),
(10, 'SunnyTran4', 'sunny4@gmail.com', '09140909763', '123456', '2025-02-13 13:21:47', 3),
(11, 'Nguyễn Thị A', 'phuongthuy091203@gmail.com', '0912382917', '1', '2025-02-16 19:48:39', 3),
(12, 'Phuong Thuy', 'phuongthuy@gmail.com', '0978456467', '1234', '2025-02-16 23:27:19', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `sanphamid` int(11) NOT NULL,
  `tensanpham` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `madanhmuc` int(11) DEFAULT NULL,
  `makhuyenmai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`sanphamid`, `tensanpham`, `mota`, `gia`, `madanhmuc`, `makhuyenmai`) VALUES
(1, 'Váy maxi hoa nhí', 'Váy maxi dài, họa tiết hoa nhí, chất liệu voan nhẹ', 450000.00, 1, NULL),
(2, 'Váy suông cổ V', 'Váy suông dáng rộng, cổ V, chất liệu cotton', 320000.00, 1, NULL),
(3, 'Váy xòe công chúa', 'Váy xòe tầng, phong cách công chúa, chất liệu taffeta', 550000.00, 1, NULL),
(4, 'Váy body ôm sát', 'Váy body ôm sát cơ thể, chất liệu thun co giãn', 280000.00, 1, NULL),
(5, 'Váy yếm denim', 'Váy yếm dáng dài, chất liệu denim cao cấp', 400000.00, 1, NULL),
(6, 'Váy ren trắng', 'Váy ren trắng tinh khôi, dáng xòe nhẹ', 480000.00, 1, NULL),
(7, 'Váy dạ hội dài', 'Váy dạ hội dài, đính sequin lấp lánh', 750000.00, 1, NULL),
(8, 'Váy midi xếp ly', 'Váy midi dáng xếp ly, chất liệu lụa mềm', 420000.00, 1, NULL),
(9, 'Váy sơ mi dáng dài', 'Váy sơ mi dáng dài, chất liệu cotton thoáng mát', 350000.00, 1, NULL),
(10, 'Váy peplum công sở', 'Váy peplum dáng ôm, phù hợp công sở', 380000.00, 1, NULL),
(11, 'Váy hai dây lụa', 'Váy hai dây chất liệu lụa, dáng suông nhẹ', 300000.00, 1, NULL),
(12, 'Váy chấm bi retro', 'Váy chấm bi phong cách retro, dáng xòe', 460000.00, 1, NULL),
(13, 'Váy bó sát đính đá', 'Váy bó sát, đính đá lấp lánh, chất liệu thun', 500000.00, 1, NULL),
(14, 'Váy trễ vai hoa', 'Váy trễ vai, họa tiết hoa, chất liệu voan', 430000.00, 1, NULL),
(15, 'Váy xếp tầng bohemian', 'Váy xếp tầng phong cách bohemian, chất liệu cotton', 470000.00, 1, NULL),
(16, 'Túi tote canvas', 'Túi tote chất liệu canvas, in họa tiết đơn giản', 200000.00, 2, NULL),
(17, 'Túi xách da mini', 'Túi xách da mini, phong cách tối giản', 350000.00, 2, NULL),
(18, 'Túi bucket dây rút', 'Túi bucket dây rút, chất liệu da PU', 300000.00, 2, NULL),
(19, 'Túi đeo chéo vải', 'Túi đeo chéo chất liệu vải dù, nhẹ nhàng', 180000.00, 2, NULL),
(20, 'Túi clutch dạ hội', 'Túi clutch đính sequin, phù hợp dạ hội', 250000.00, 2, NULL),
(21, 'Túi shopper lớn', 'Túi shopper kích thước lớn, chất liệu canvas', 220000.00, 2, NULL),
(22, 'Túi xách công sở', 'Túi xách công sở, chất liệu da cao cấp', 400000.00, 2, NULL),
(23, 'Túi đeo vai boho', 'Túi đeo vai phong cách boho, đính tua rua', 280000.00, 2, NULL),
(24, 'Túi backpack vải', 'Túi backpack chất liệu vải, phong cách năng động', 320000.00, 2, NULL),
(25, 'Túi xách tay cầm', 'Túi xách tay cầm, chất liệu da PU', 360000.00, 2, NULL),
(26, 'Túi đeo chéo da', 'Túi đeo chéo chất liệu da, khóa kéo chắc chắn', 340000.00, 2, NULL),
(27, 'Túi tote họa tiết', 'Túi tote in họa tiết hoa, chất liệu vải', 190000.00, 2, NULL),
(31, 'Áo sơ mi trắng', 'Áo sơ mi trắng, chất liệu cotton, form slimfit', 250000.00, 3, NULL),
(32, 'Áo thun cổ tròn', 'Áo thun cổ tròn, chất liệu cotton mềm mại', 180000.00, 3, NULL),
(33, 'Áo polo basic', 'Áo polo cơ bản, chất liệu thun pique', 220000.00, 3, NULL),
(34, 'Áo sơ mi caro', 'Áo sơ mi caro, chất liệu cotton thoáng mát', 270000.00, 3, NULL),
(35, 'Áo hoodie trơn', 'Áo hoodie trơn, chất liệu nỉ bông ấm áp', 350000.00, 3, NULL),
(45, 'Áo khoác bomber', 'Áo khoác bomber, chất liệu dù, phong cách trẻ trung', 450000.00, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_khuyenmai`
--

CREATE TABLE `sanpham_khuyenmai` (
  `id` int(11) NOT NULL,
  `khuyenmai_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_mausac`
--

CREATE TABLE `sanpham_mausac` (
  `sanphamid` int(11) NOT NULL,
  `mausacid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham_mausac`
--

INSERT INTO `sanpham_mausac` (`sanphamid`, `mausacid`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 7),
(2, 1),
(2, 2),
(2, 8),
(3, 1),
(3, 7),
(3, 10),
(4, 2),
(4, 3),
(4, 4),
(5, 2),
(5, 9),
(6, 1),
(6, 7),
(6, 10),
(7, 2),
(7, 8),
(7, 9),
(8, 1),
(8, 3),
(8, 4),
(9, 1),
(9, 2),
(9, 8),
(10, 2),
(10, 4),
(10, 7),
(11, 1),
(11, 3),
(11, 6),
(12, 1),
(12, 7),
(12, 10),
(13, 2),
(13, 4),
(13, 9),
(14, 1),
(14, 6),
(14, 7),
(15, 1),
(15, 8),
(15, 10),
(16, 1),
(16, 2),
(16, 10),
(17, 2),
(17, 8),
(17, 9),
(18, 1),
(18, 3),
(18, 7),
(19, 2),
(19, 4),
(19, 9),
(20, 2),
(20, 7),
(20, 8),
(21, 1),
(21, 2),
(21, 10),
(22, 2),
(22, 8),
(22, 9),
(23, 1),
(23, 6),
(23, 7),
(24, 2),
(24, 8),
(24, 10),
(25, 3),
(25, 5),
(25, 7),
(26, 2),
(26, 4),
(26, 9),
(27, 1),
(27, 3),
(27, 7),
(31, 1),
(31, 2),
(31, 8),
(32, 1),
(32, 4),
(32, 6),
(33, 2),
(33, 8),
(33, 9),
(34, 1),
(34, 3),
(34, 7),
(35, 2),
(35, 8),
(35, 10),
(45, 2),
(45, 4),
(45, 9);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_size`
--

CREATE TABLE `sanpham_size` (
  `id` int(11) NOT NULL,
  `sanphamid` int(11) NOT NULL,
  `sizeid` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham_size`
--

INSERT INTO `sanpham_size` (`id`, `sanphamid`, `sizeid`, `soluong`) VALUES
(1, 1, 1, 50),
(2, 1, 2, 60),
(3, 1, 3, 40),
(4, 1, 4, 30),
(5, 2, 1, 45),
(6, 2, 2, 55),
(7, 2, 3, 35),
(8, 2, 4, 25),
(9, 3, 1, 40),
(10, 3, 2, 50),
(11, 3, 3, 30),
(12, 3, 4, 20),
(13, 4, 1, 60),
(14, 4, 2, 70),
(15, 4, 3, 50),
(16, 4, 4, 40),
(17, 5, 1, 50),
(18, 5, 2, 60),
(19, 5, 3, 40),
(20, 5, 4, 30),
(21, 6, 1, 45),
(22, 6, 2, 55),
(23, 6, 3, 35),
(24, 6, 4, 25),
(25, 7, 1, 40),
(26, 7, 2, 50),
(27, 7, 3, 30),
(28, 7, 4, 20),
(29, 8, 1, 55),
(30, 8, 2, 65),
(31, 8, 3, 45),
(32, 8, 4, 35),
(33, 9, 1, 50),
(34, 9, 2, 60),
(35, 9, 3, 40),
(36, 9, 4, 30),
(37, 10, 1, 45),
(38, 10, 2, 55),
(39, 10, 3, 35),
(40, 10, 4, 25),
(41, 11, 1, 60),
(42, 11, 2, 70),
(43, 11, 3, 50),
(44, 11, 4, 40),
(45, 12, 1, 50),
(46, 12, 2, 60),
(47, 12, 3, 40),
(48, 12, 4, 30),
(49, 13, 1, 45),
(50, 13, 2, 55),
(51, 13, 3, 35),
(52, 13, 4, 25),
(53, 14, 1, 40),
(54, 14, 2, 50),
(55, 14, 3, 30),
(56, 14, 4, 20),
(57, 15, 1, 55),
(58, 15, 2, 65),
(59, 15, 3, 45),
(60, 15, 4, 35),
(61, 31, 1, 50),
(62, 31, 2, 60),
(63, 31, 3, 40),
(64, 31, 4, 30),
(65, 32, 1, 45),
(66, 32, 2, 55),
(67, 32, 3, 35),
(68, 32, 4, 25),
(69, 33, 1, 40),
(70, 33, 2, 50),
(71, 33, 3, 30),
(72, 33, 4, 20),
(73, 34, 1, 60),
(74, 34, 2, 70),
(75, 34, 3, 50),
(76, 34, 4, 40),
(77, 35, 1, 50),
(78, 35, 2, 60),
(79, 35, 3, 40),
(80, 35, 4, 30),
(117, 45, 1, 55),
(118, 45, 2, 65),
(119, 45, 3, 45),
(120, 45, 4, 35);

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `sizeid` int(11) NOT NULL,
  `kichco` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`sizeid`, `kichco`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL');

-- --------------------------------------------------------

--
-- Table structure for table `yeuthich`
--

CREATE TABLE `yeuthich` (
  `yeuthichid` int(11) NOT NULL,
  `manguoidung` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `ngaythem` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`chitietdonhangid`),
  ADD KEY `madonhang` (`madonhang`),
  ADD KEY `masanpham` (`masanpham`),
  ADD KEY `sizeid` (`sizeid`);

--
-- Indexes for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`chitietgiohangid`),
  ADD KEY `magiohang` (`magiohang`),
  ADD KEY `masanpham` (`masanpham`),
  ADD KEY `sizeid` (`sizeid`);

--
-- Indexes for table `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`danhgiaid`),
  ADD KEY `masanpham` (`masanpham`),
  ADD KEY `manguoidung` (`manguoidung`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`danhmucid`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`donhangid`),
  ADD KEY `manguoidung` (`manguoidung`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`giohangid`),
  ADD UNIQUE KEY `manguoidung` (`manguoidung`);

--
-- Indexes for table `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD PRIMARY KEY (`hinhanhid`),
  ADD KEY `masanpham` (`masanpham`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`khuyenmaiid`);

--
-- Indexes for table `mausac`
--
ALTER TABLE `mausac`
  ADD PRIMARY KEY (`mausacid`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`sanphamid`),
  ADD KEY `madanhmuc` (`madanhmuc`),
  ADD KEY `makhuyenmai` (`makhuyenmai`);

--
-- Indexes for table `sanpham_khuyenmai`
--
ALTER TABLE `sanpham_khuyenmai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khuyenmai_id` (`khuyenmai_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Indexes for table `sanpham_mausac`
--
ALTER TABLE `sanpham_mausac`
  ADD PRIMARY KEY (`sanphamid`,`mausacid`),
  ADD KEY `mausacid` (`mausacid`);

--
-- Indexes for table `sanpham_size`
--
ALTER TABLE `sanpham_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanphamid` (`sanphamid`),
  ADD KEY `sizeid` (`sizeid`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`sizeid`);

--
-- Indexes for table `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD PRIMARY KEY (`yeuthichid`),
  ADD KEY `manguoidung` (`manguoidung`),
  ADD KEY `masanpham` (`masanpham`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `chitietdonhangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `chitietgiohangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `danhgiaid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `danhmucid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `donhangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `giohangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  MODIFY `hinhanhid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `khuyenmaiid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mausac`
--
ALTER TABLE `mausac`
  MODIFY `mausacid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `sanphamid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `sanpham_khuyenmai`
--
ALTER TABLE `sanpham_khuyenmai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sanpham_size`
--
ALTER TABLE `sanpham_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `sizeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `yeuthich`
--
ALTER TABLE `yeuthich`
  MODIFY `yeuthichid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`madonhang`) REFERENCES `donhang` (`donhangid`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdonhang_ibfk_3` FOREIGN KEY (`sizeid`) REFERENCES `size` (`sizeid`) ON DELETE CASCADE;

--
-- Constraints for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `chitietgiohang_ibfk_1` FOREIGN KEY (`magiohang`) REFERENCES `giohang` (`giohangid`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietgiohang_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietgiohang_ibfk_3` FOREIGN KEY (`sizeid`) REFERENCES `size` (`sizeid`) ON DELETE CASCADE;

--
-- Constraints for table `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`manguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`manguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`manguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD CONSTRAINT `hinhanhsanpham_ibfk_1` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE;

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`madanhmuc`) REFERENCES `danhmuc` (`danhmucid`) ON DELETE SET NULL,
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`makhuyenmai`) REFERENCES `khuyenmai` (`khuyenmaiid`) ON DELETE SET NULL;

--
-- Constraints for table `sanpham_khuyenmai`
--
ALTER TABLE `sanpham_khuyenmai`
  ADD CONSTRAINT `sanpham_khuyenmai_ibfk_1` FOREIGN KEY (`khuyenmai_id`) REFERENCES `khuyenmai` (`khuyenmaiid`),
  ADD CONSTRAINT `sanpham_khuyenmai_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`sanphamid`);

--
-- Constraints for table `sanpham_mausac`
--
ALTER TABLE `sanpham_mausac`
  ADD CONSTRAINT `sanpham_mausac_ibfk_1` FOREIGN KEY (`sanphamid`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE,
  ADD CONSTRAINT `sanpham_mausac_ibfk_2` FOREIGN KEY (`mausacid`) REFERENCES `mausac` (`mausacid`) ON DELETE CASCADE;

--
-- Constraints for table `sanpham_size`
--
ALTER TABLE `sanpham_size`
  ADD CONSTRAINT `sanpham_size_ibfk_1` FOREIGN KEY (`sanphamid`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE,
  ADD CONSTRAINT `sanpham_size_ibfk_2` FOREIGN KEY (`sizeid`) REFERENCES `size` (`sizeid`) ON DELETE CASCADE;

--
-- Constraints for table `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD CONSTRAINT `yeuthich_ibfk_1` FOREIGN KEY (`manguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeuthich_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
