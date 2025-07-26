-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 05:41 AM
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

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`chitietdonhangid`, `madonhang`, `masanpham`, `sizeid`, `soluong`, `gia`) VALUES
(38, 38, 33, 1, 1, 220000.00),
(39, 38, 32, 2, 2, 180000.00),
(40, 39, 35, 2, 1, 350000.00),
(43, 42, 11, 2, 3, 270000.00),
(44, 42, 32, 2, 1, 180000.00),
(49, 43, 1, 2, 1, 450000.00),
(50, 43, 16, 1, 2, 200000.00),
(53, 44, 1, 2, 1, 450000.00),
(54, 44, 16, 1, 2, 200000.00),
(55, 45, 5, 1, 2, 400000.00),
(56, 45, 1, 2, 1, 450000.00),
(57, 45, 3, 1, 1, 550000.00),
(58, 46, 7, 2, 1, 750000.00),
(59, 46, 17, 1, 1, 350000.00),
(60, 46, 4, 1, 1, 280000.00),
(61, 47, 6, 1, 1, 480000.00),
(62, 47, 9, 2, 1, 350000.00),
(66, 49, 12, 1, 2, 460000.00),
(67, 49, 8, 1, 1, 420000.00),
(68, 49, 2, 2, 1, 320000.00),
(69, 50, 14, 1, 2, 430000.00),
(70, 50, 2, 2, 1, 320000.00),
(71, 50, 3, 3, 1, 550000.00),
(72, 51, 10, 2, 1, 380000.00),
(73, 51, 5, 1, 1, 400000.00),
(74, 52, 15, 2, 1, 470000.00),
(75, 52, 17, 1, 1, 350000.00),
(76, 52, 3, 2, 1, 550000.00),
(77, 53, 1, 2, 1, 450000.00),
(78, 53, 5, 1, 1, 400000.00),
(79, 53, 9, 2, 1, 350000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `chitietgiohangid` int(11) NOT NULL,
  `magiohang` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `sizeid` int(11) NOT NULL,
  `mausacid` int(11) DEFAULT NULL,
  `soluong` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`chitietgiohangid`, `magiohang`, `masanpham`, `sizeid`, `mausacid`, `soluong`, `gia`) VALUES
(37, 4, 11, 3, 1, 1, 0.00),
(40, 4, 34, 1, 7, 1, 0.00),
(41, 4, 35, 1, 2, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_message` text DEFAULT NULL,
  `bot_response` text DEFAULT NULL,
  `admin_message` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `status` enum('waiting_for_admin','admin_responded') DEFAULT 'waiting_for_admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`donhangid`, `manguoidung`, `ten_nguoi_dat`, `ngaydat`, `trangthai`, `tongtien`, `diachigiao`, `sdt`, `phuongthuctt`) VALUES
(38, 6, 'Minh Hào', '2025-07-10 23:09:19', 'Hoàn thành', 580000.00, '124 Nguyễn Khiêm , vĩnh long', '0916372843', 'COD'),
(39, 7, 'Minh Hào', '2025-07-11 15:50:11', 'Hoàn thành', 380000.00, '124 Nguyễn Khiêm , vĩnh long', '0916372843', 'COD'),
(42, 7, 'Trần Phương Thùy', '2025-07-11 22:53:47', 'Đã hủy', 990000.00, '164 , ấp mỹ tân, Xã Tạ An Khương, Huyện Đầm Dơi, Tỉnh Cà Mau', '0914090763', 'COD'),
(43, 4, 'Nguyễn Thị A', '2025-01-15 10:00:00', 'Đang chờ xử lý', 1500000.00, '123 Nguyễn Trãi, Hà Nội', '0912382917', 'Chuyển khoản'),
(44, 4, 'Nguyễn Thị A', '2025-01-15 10:00:00', 'Đang chờ xử lý', 1500000.00, '123 Nguyễn Trãi, Hà Nội', '0912382917', 'Chuyển khoản'),
(45, 5, 'Phương Thuy', '2025-02-02 11:00:00', 'Đang xử lý', 1800000.00, '456 Phạm Văn Đồng, Hà Nội', '0978456467', 'Thanh toán khi nhận hàng'),
(46, 6, 'SunnyTran', '2025-03-15 12:30:00', 'Đang giao', 1900000.00, '789 Trần Hưng Đạo, Hà Nội', '093728356223', 'Thanh toán khi nhận hàng'),
(47, 7, 'SunnyTran1', '2025-04-10 14:00:00', 'Đã hoàn thành', 1700000.00, '20 Lý Thái Tổ, Hà Nội', '0914090763', 'Chuyển khoản'),
(49, 10, 'Trần Quốc Khánh', '2025-06-20 16:45:00', 'Đang giao', 1250000.00, '10 Hồ Tùng Mậu, Hà Nội', '0935221323', 'Chuyển khoản'),
(50, 11, 'Nguyễn Thị B', '2025-07-01 17:00:00', 'Đang xử lý', 1350000.00, '45 Lê Lợi, Hà Nội', '0913845362', 'Thanh toán khi nhận hàng'),
(51, 12, 'Phạm Thị X', '2025-07-05 18:30:00', 'Đang giao', 800000.00, '30 Láng Hạ, Hà Nội', '0917324568', 'Chuyển khoản'),
(52, 10, 'Trần Quốc Khánh', '2025-06-20 16:45:00', 'Đang giao', 1250000.00, '10 Hồ Tùng Mậu, Hà Nội', '0935221323', 'Chuyển khoản'),
(53, 11, 'Nguyễn Thị B', '2025-07-01 17:00:00', 'Đang xử lý', 1350000.00, '45 Lê Lợi, Hà Nội', '0913845362', 'Thanh toán khi nhận hàng'),
(54, 12, 'Phạm Thị X', '2025-07-05 18:30:00', 'Đang giao', 800000.00, '30 Láng Hạ, Hà Nội', '0917324568', 'Chuyển khoản'),
(55, 12, 'Lê Văn H', '2025-07-07 19:00:00', 'Đã hoàn thành', 1900000.00, '60 Nguyễn Chí Thanh, Hà Nội', '0913847584', 'Chuyển khoản');

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

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`giohangid`, `manguoidung`, `tongtien`, `ngaycapnhat`) VALUES
(4, 7, 0.00, '2025-07-09 23:25:30'),
(5, 6, 0.00, '2025-07-10 12:42:00');

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
(24, 1, 'vay_maxi_hoa_nhi_front.webp'),
(25, 1, 'vay_maxi_hoa_nhi_side.webp'),
(26, 1, 'vay_maxi_hoa_nhi_back.webp'),
(27, 1, 'vay_maxi_hoa_nhi_detail.webp'),
(28, 2, 'vay_suong_co_v_front.webp'),
(29, 2, 'vay_suong_co_v_side.webp'),
(30, 2, 'vay_suong_co_v_back.webp'),
(31, 2, 'vay_suong_co_v_detail.webp'),
(32, 3, 'vay_xoe_cong_chua_front.webp'),
(33, 3, 'vay_xoe_cong_chua_side.webp'),
(34, 3, 'vay_xoe_cong_chua_back.webp'),
(35, 3, 'vay_xoe_cong_chua_detail.webp'),
(36, 4, 'vay_body_om_sat_front.webp'),
(37, 4, 'vay_body_om_sat_side.webp'),
(38, 4, 'vay_body_om_sat_back.webp'),
(39, 4, 'vay_body_om_sat_detail.webp'),
(40, 5, 'vay_yem_denim_front.webp'),
(41, 5, 'vay_yem_denim_side.webp'),
(42, 5, 'vay_yem_denim_back.webp'),
(43, 5, 'vay_yem_denim_detail.webp'),
(44, 6, 'vay_ren_trang_front.webp'),
(45, 6, 'vay_ren_trang_side.webp'),
(46, 6, 'vay_ren_trang_back.webp'),
(47, 6, 'vay_ren_trang_detail.webp'),
(48, 7, 'vay_da_hoi_dai_front.webp'),
(49, 7, 'vay_da_hoi_dai_side.webp'),
(50, 7, 'vay_da_hoi_dai_back.webp'),
(51, 7, 'vay_da_hoi_dai_detail.webp'),
(52, 8, 'vay_midi_xep_ly_front.webp'),
(53, 8, 'vay_midi_xep_ly_side.webp'),
(54, 8, 'vay_midi_xep_ly_back.webp'),
(55, 8, 'vay_midi_xep_ly_detail.webp'),
(56, 9, 'vay_so_mi_dang_dai_side.webp'),
(57, 9, 'vay_so_mi_dang_dai_back.webp'),
(58, 9, 'vay_so_mi_dang_dai_detail.webp'),
(59, 10, 'vay_peplum_cong_so_front.webp'),
(60, 10, 'vay_peplum_cong_so_side.webp'),
(61, 10, 'vay_peplum_cong_so_back.webp'),
(62, 10, 'vay_peplum_cong_so_detail.webp'),
(63, 11, 'vay_hai_day_lua_front.webp'),
(64, 11, 'vay_hai_day_lua_side.webp'),
(65, 11, 'vay_hai_day_lua_back.webp'),
(66, 11, 'vay_hai_day_lua_detail.webp'),
(67, 12, 'vay_cham_bi_retro_front.webp'),
(68, 12, 'vay_cham_bi_retro_side.webp'),
(69, 12, 'vay_cham_bi_retro_back.webp'),
(70, 12, 'vay_cham_bi_retro_detail.webp'),
(71, 13, 'vay_bo_sat_dinh_da_front.webp'),
(72, 13, 'vay_bo_sat_dinh_da_side.webp'),
(73, 14, 'vay_tre_vai_hoa_front.webp'),
(74, 14, 'vay_tre_vai_hoa_side.webp'),
(75, 14, 'vay_tre_vai_hoa_back.webp'),
(76, 14, 'vay_tre_vai_hoa_detail.webp'),
(77, 15, 'vay_xep_tang_bohemian_front.webp'),
(78, 15, 'vay_xep_tang_bohemian_side.webp'),
(79, 15, 'vay_xep_tang_bohemian_back.webp'),
(80, 15, 'vay_xep_tang_bohemian_detail.webp'),
(81, 1, 'vay_maxi_hoa_nhi_front.webp'),
(82, 1, 'vay_maxi_hoa_nhi_side.webp'),
(83, 1, 'vay_maxi_hoa_nhi_back.webp'),
(84, 1, 'vay_maxi_hoa_nhi_detail.webp'),
(85, 2, 'vay_suong_co_v_front.webp'),
(86, 2, 'vay_suong_co_v_side.webp'),
(87, 2, 'vay_suong_co_v_back.webp'),
(88, 2, 'vay_suong_co_v_detail.webp'),
(89, 3, 'vay_xoe_cong_chua_front.webp'),
(90, 3, 'vay_xoe_cong_chua_side.webp'),
(91, 3, 'vay_xoe_cong_chua_back.webp'),
(92, 3, 'vay_xoe_cong_chua_detail.webp'),
(93, 4, 'vay_body_om_sat_front.webp'),
(94, 4, 'vay_body_om_sat_side.webp'),
(95, 4, 'vay_body_om_sat_back.webp'),
(96, 4, 'vay_body_om_sat_detail.webp'),
(97, 5, 'vay_yem_denim_front.webp'),
(98, 5, 'vay_yem_denim_side.webp'),
(99, 5, 'vay_yem_denim_back.webp'),
(100, 5, 'vay_yem_denim_detail.webp'),
(101, 6, 'vay_ren_trang_front.webp'),
(102, 6, 'vay_ren_trang_side.webp'),
(103, 6, 'vay_ren_trang_back.webp'),
(104, 6, 'vay_ren_trang_detail.webp'),
(105, 7, 'vay_da_hoi_dai_front.webp'),
(106, 7, 'vay_da_hoi_dai_side.webp'),
(107, 7, 'vay_da_hoi_dai_back.webp'),
(108, 7, 'vay_da_hoi_dai_detail.webp'),
(109, 8, 'vay_midi_xep_ly_front.webp'),
(110, 8, 'vay_midi_xep_ly_side.webp'),
(111, 8, 'vay_midi_xep_ly_back.webp'),
(112, 8, 'vay_midi_xep_ly_detail.webp'),
(113, 9, 'vay_so_mi_dang_dai_side.webp'),
(114, 9, 'vay_so_mi_dang_dai_back.webp'),
(115, 9, 'vay_so_mi_dang_dai_detail.webp'),
(116, 10, 'vay_peplum_cong_so_front.webp'),
(117, 10, 'vay_peplum_cong_so_side.webp'),
(118, 10, 'vay_peplum_cong_so_back.webp'),
(119, 10, 'vay_peplum_cong_so_detail.webp'),
(120, 11, 'vay_hai_day_lua_front.webp'),
(121, 11, 'vay_hai_day_lua_side.webp'),
(122, 11, 'vay_hai_day_lua_back.webp'),
(123, 11, 'vay_hai_day_lua_detail.webp'),
(124, 12, 'vay_cham_bi_retro_front.webp'),
(125, 12, 'vay_cham_bi_retro_side.webp'),
(126, 12, 'vay_cham_bi_retro_back.webp'),
(127, 12, 'vay_cham_bi_retro_detail.webp'),
(128, 13, 'vay_bo_sat_dinh_da_front.webp'),
(129, 13, 'vay_bo_sat_dinh_da_side.webp'),
(130, 14, 'vay_tre_vai_hoa_front.webp'),
(131, 14, 'vay_tre_vai_hoa_side.webp'),
(132, 14, 'vay_tre_vai_hoa_back.webp'),
(133, 14, 'vay_tre_vai_hoa_detail.webp'),
(134, 15, 'vay_xep_tang_bohemian_front.webp'),
(135, 15, 'vay_xep_tang_bohemian_side.webp'),
(136, 15, 'vay_xep_tang_bohemian_back.webp'),
(137, 15, 'vay_xep_tang_bohemian_detail.webp'),
(138, 16, 'tui_tote_canvas_front.webp'),
(139, 16, 'tui_tote_canvas_side.webp'),
(140, 16, 'tui_tote_canvas_back.webp'),
(141, 16, 'tui_tote_canvas_detail.webp'),
(142, 17, 'tui_xach_da_mini_front.webp'),
(143, 17, 'tui_xach_da_mini_side.webp'),
(144, 17, 'tui_xach_da_mini_back.webp'),
(145, 17, 'tui_xach_da_mini_detail.webp'),
(146, 18, 'tui_bucket_day_rut_front.webp'),
(147, 18, 'tui_bucket_day_rut_side.webp'),
(148, 18, 'tui_bucket_day_rut_back.webp'),
(149, 18, 'tui_bucket_day_rut_detail.webp'),
(150, 19, 'tui_deo_cheo_vai_front.webp'),
(151, 19, 'tui_deo_cheo_vai_side.webp'),
(152, 19, 'tui_deo_cheo_vai_back.webp'),
(153, 19, 'tui_deo_cheo_vai_detail.webp'),
(154, 20, 'tui_clutch_da_hoi_front.webp'),
(155, 20, 'tui_clutch_da_hoi_side.webp'),
(156, 20, 'tui_clutch_da_hoi_back.webp'),
(157, 20, 'tui_clutch_da_hoi_detail.webp'),
(158, 21, 'tui_shopper_lon_front.webp'),
(159, 21, 'tui_shopper_lon_side.webp'),
(160, 21, 'tui_shopper_lon_back.webp'),
(161, 21, 'tui_shopper_lon_detail.webp'),
(162, 22, 'tui_xach_cong_so_front.webp'),
(163, 22, 'tui_xach_cong_so_side.webp'),
(164, 22, 'tui_xach_cong_so_back.webp'),
(165, 22, 'tui_xach_cong_so_detail.webp'),
(166, 23, 'tui_deo_vai_boho_front.webp'),
(167, 23, 'tui_deo_vai_boho_side.webp'),
(168, 23, 'tui_deo_vai_boho_back.webp'),
(169, 23, 'tui_deo_vai_boho_detail.webp'),
(170, 24, 'tui_backpack_vai_front.webp'),
(171, 24, 'tui_backpack_vai_side.webp'),
(172, 24, 'tui_backpack_vai_back.webp'),
(173, 24, 'tui_backpack_vai_detail.webp'),
(174, 25, 'tui_xach_tay_cam_front.webp'),
(175, 25, 'tui_xach_tay_cam_side.webp'),
(176, 25, 'tui_xach_tay_cam_back.webp'),
(177, 25, 'tui_xach_tay_cam_detail.webp'),
(178, 26, 'tui_deo_cheo_da_front.webp'),
(179, 26, 'tui_deo_cheo_da_side.webp'),
(180, 26, 'tui_deo_cheo_da_back.webp'),
(181, 26, 'tui_deo_cheo_da_detail.webp'),
(182, 27, 'tui_tote_hoa_tiet_front.webp'),
(183, 27, 'tui_tote_hoa_tiet_side.webp'),
(184, 27, 'tui_tote_hoa_tiet_back.webp'),
(185, 27, 'tui_tote_hoa_tiet_detail.webp'),
(186, 31, 'ao_so_mi_trang_front.webp'),
(187, 31, 'ao_so_mi_trang_side.webp'),
(188, 31, 'ao_so_mi_trang_back.webp'),
(189, 31, 'ao_so_mi_trang_detail.webp'),
(190, 32, 'ao_thun_co_tron_front.webp'),
(191, 32, 'ao_thun_co_tron_side.webp'),
(192, 32, 'ao_thun_co_tron_back.webp'),
(193, 32, 'ao_thun_co_tron_detail.webp'),
(194, 33, 'ao_polo_basic_front.webp'),
(195, 33, 'ao_polo_basic_side.webp'),
(196, 33, 'ao_polo_basic_back.webp'),
(197, 33, 'ao_polo_basic_detail.webp'),
(198, 34, 'ao_so_mi_caro_front.webp'),
(199, 34, 'ao_so_mi_caro_side.webp'),
(200, 34, 'ao_so_mi_caro_back.webp'),
(201, 34, 'ao_so_mi_caro_detail.webp'),
(202, 35, 'ao_hoodie_tron_front.webp'),
(203, 35, 'ao_hoodie_tron_side.webp'),
(204, 35, 'ao_hoodie_tron_back.webp'),
(205, 35, 'ao_hoodie_tron_detail.webp'),
(206, 45, 'ao_bombo_front.webp'),
(207, 45, 'ao_bombo_side.webp'),
(208, 45, 'ao_bombo_back.webp'),
(209, 45, 'ao_bombo_detail.webp');

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `khuyenmaiid` int(11) NOT NULL,
  `tenkhuyenmai` varchar(255) NOT NULL,
  `giatri` decimal(10,3) NOT NULL,
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`khuyenmaiid`, `tenkhuyenmai`, `giatri`, `ngaybatdau`, `ngayketthuc`) VALUES
(2, 'Flash Saleee', 30000.000, '2025-07-11 00:00:00', '2025-11-11 23:59:00'),
(8, 'aaaab', 125000.000, '2025-07-22 11:07:00', '2025-07-26 11:07:00'),
(18, 'tao lạy ', 23000.000, '2025-07-22 14:36:00', '2025-08-08 14:36:00');

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
(4, 'khachhang01', 'khachhang01@gmail.com', '07238361283', '123456', '2025-02-12 22:18:13', 3),
(5, 'khachhang02', 'khachhang02@gmail.com', '0937872637', '123456', '2025-02-12 22:18:43', 3),
(6, 'SunnyTran', 'sunny@gmail.com', '093728356223', '123454', '2025-02-13 12:44:54', 3),
(7, 'SunnyTran1', 'sunny1@gmail.com', '0914090763', '12343', '2025-02-13 12:45:34', 3),
(10, 'SunnyTran4', 'sunny4@gmail.com', '09140909763', '123456', '2025-02-13 13:21:47', 3),
(11, 'Nguyễn Thị A', 'phuongthuy091203@gmail.com', '0912382917', '1', '2025-02-16 19:48:39', 3),
(12, 'Phuong Thuy', 'phuongthuy@gmail.com', '0978456467', '1234', '2025-02-16 23:27:19', 3),
(13, 'Nguyễn Kim Ngọc', 'kn@gmail.com', '0932879617', '$2y$10$1L8V4ow99iYM/UXlxqOUhOkVNZj9uOP/pn8IyMa8g2qTEnVgcztv6', '2025-07-12 11:50:42', 2);

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
  `makhuyenmai` int(11) DEFAULT NULL,
  `chatlieu` varchar(255) DEFAULT NULL,
  `thuonghieu` varchar(255) DEFAULT NULL,
  `baohanh` varchar(255) DEFAULT NULL,
  `gianhap` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`sanphamid`, `tensanpham`, `mota`, `gia`, `madanhmuc`, `makhuyenmai`, `chatlieu`, `thuonghieu`, `baohanh`, `gianhap`) VALUES
(1, 'Váy maxi hoa nhí', 'Chiếc váy maxi hoa nhí này có thiết kế dài, thoải mái, với họa tiết hoa nhí nữ tính, mang đến vẻ đẹp dịu dàng cho người mặc. Chất liệu voan nhẹ, mềm mại giúp làn da của bạn luôn thoáng mát, thoải mái trong suốt cả ngày dài. Phù hợp cho những buổi dạo phố vào mùa hè, các chuyến đi du lịch, hoặc tham dự những buổi tiệc nhẹ nhàng. Vẻ ngoài tự nhiên và thanh thoát của chiếc váy sẽ giúp bạn trở thành tâm điểm của mọi ánh nhìn, đặc biệt là khi kết hợp với những phụ kiện đơn giản nhưng tinh tế.\nPhối cùng:\n\nGiày: Một đôi sandal đế xuồng hoặc giày bệt màu nude sẽ giúp làm nổi bật dáng váy mà không làm mất đi sự nhẹ nhàng của nó.\n\nTúi xách: Túi tote họa tiết hoa hoặc túi xách mini da màu sáng sẽ rất hợp để làm nổi bật phong cách nhẹ nhàng nhưng vẫn đầy quyến rũ.\n\nPhụ kiện: Một chiếc mũ rộng vành và một cặp kính râm sẽ là lựa chọn tuyệt vời cho một buổi dạo phố hoặc đi biển.\n', 450000.00, 1, NULL, 'Voan', 'Zara', '6 tháng', 350000.00),
(2, 'Váy suông cổ V', 'Váy suông cổ V là lựa chọn lý tưởng cho những ai yêu thích phong cách thoải mái, thanh lịch nhưng vẫn có phần quyến rũ. Chất liệu cotton mềm mại và thoáng mát giúp bạn dễ dàng vận động, trong khi cổ V tôn lên vẻ đẹp của cổ và xương quai xanh. Với thiết kế suông, váy này không chỉ tạo cảm giác thoải mái mà còn mang đến vẻ ngoài trẻ trung, dễ thương, phù hợp cho các buổi đi chơi, dạo phố, hoặc thậm chí là những buổi gặp gỡ bạn bè trong những dịp không chính thức.\nPhối cùng:\n\nGiày: Một đôi giày thể thao trắng hoặc giày búp bê sẽ tạo nên phong cách năng động, thoải mái.\n\nTúi xách: Túi xách da mini hoặc túi xách tote đơn giản sẽ mang đến vẻ đẹp thanh lịch mà vẫn rất dễ dàng kết hợp với váy suông.\n\nPhụ kiện: Một chiếc vòng cổ dài mảnh hoặc một chiếc đồng hồ tinh tế sẽ giúp bạn nổi bật mà không quá phô trương.', 320000.00, 1, NULL, 'Cotton', 'Zara', '6 tháng', 250000.00),
(3, 'Váy xòe công chúa', 'Váy xòe tầng với phong cách công chúa chắc chắn sẽ khiến bạn cảm thấy như một nàng tiểu thư trong những dịp đặc biệt. Chất liệu taffeta giúp tạo độ phồng hoàn hảo, làm nổi bật những đường cong cơ thể một cách tự nhiên. Sự kết hợp giữa sự sang trọng và nữ tính khiến váy trở thành sự lựa chọn lý tưởng cho các bữa tiệc, sự kiện trang trọng, hoặc những dịp lễ hội cần đến sự lộng lẫy và nổi bật.\nPhối cùng:\n\nGiày: Giày cao gót mũi nhọn hoặc giày ballet sẽ tạo nên vẻ duyên dáng và nữ tính cho chiếc váy.\n\nTúi xách: Túi clutch lấp lánh hoặc túi đính sequin sẽ làm bạn nổi bật trong mọi bữa tiệc.\n\nPhụ kiện: Một chiếc dây chuyền lấp lánh và băng đô hoa hoặc chiếc kẹp tóc dễ thương sẽ tạo điểm nhấn hoàn hảo cho vẻ ngoài của bạn.', 550000.00, 1, NULL, 'Taffeta', 'Zara', '6 tháng', 400000.00),
(4, 'Váy body ôm sát', 'Váy body ôm sát được làm từ chất liệu thun co giãn, mang lại sự thoải mái và ôm vừa vặn cơ thể, tôn lên những đường cong quyến rũ. Với thiết kế ôm sát, váy này không chỉ giúp bạn trở nên nổi bật mà còn mang đến sự tự tin cho người mặc. Đây là lựa chọn hoàn hảo cho những buổi hẹn hò lãng mạn, các buổi tiệc tối hoặc những sự kiện yêu cầu sự trang nhã và quyến rũ.\nPhối cùng:\n\nGiày: Một đôi giày cao gót với thiết kế đơn giản nhưng thanh thoát, hoặc giày boot cổ thấp cho vẻ ngoài thời trang và quyến rũ.\n\nTúi xách: Túi xách cầm tay nhỏ gọn hoặc clutch sẽ giúp bạn giữ được sự sang trọng trong mọi bữa tiệc.\n\nPhụ kiện: Bạn có thể đeo một chiếc vòng tay mảnh hoặc một chiếc bông tai dài để tăng phần nổi bật.', 280000.00, 1, NULL, 'Cotton', 'Zara', '6 tháng', 210000.00),
(5, 'Váy yếm denim', 'Váy yếm denim là sự kết hợp hoàn hảo giữa phong cách trẻ trung và sự tiện dụng. Chất liệu denim cao cấp không chỉ tạo cảm giác chắc chắn mà còn mang đến vẻ ngoài cá tính và năng động. Với thiết kế dáng dài, váy này dễ dàng kết hợp với nhiều kiểu áo và phụ kiện khác nhau, từ áo thun đơn giản đến các mẫu áo sơ mi thanh lịch. Phù hợp cho những chuyến dã ngoại, đi chơi cùng bạn bè, hoặc thậm chí là một buổi đi làm casual.\nPhối cùng:\n\nGiày: Một đôi giày sneaker trắng hoặc giày đế bệt sẽ làm tăng thêm sự năng động và thoải mái cho trang phục.\n\nTúi xách: Túi xách tote canvas hoặc túi đeo chéo boho sẽ hoàn thiện phong cách thời trang tự do, phóng khoáng.\n\nPhụ kiện: Một chiếc kính râm to và vòng tay nhiều lớp sẽ tạo thêm sự trẻ trung và nổi bật.\n', 400000.00, 1, NULL, 'Denim', 'Zara', '6 tháng', 320000.00),
(6, 'Váy ren trắng', 'Váy ren trắng tinh khôi mang đến vẻ ngoài thanh thoát và nữ tính cho người mặc. Chất liệu ren mềm mại kết hợp với dáng xòe nhẹ tạo sự bồng bềnh, mang lại cảm giác dịu dàng và sang trọng. Đây là sự lựa chọn lý tưởng cho những dịp như tiệc cưới, sự kiện trang trọng, hay những buổi tối lãng mạn bên bạn bè và người thân.\nPhối cùng:\n\nGiày: Giày cao gót màu nude hoặc giày bệt sẽ giúp giữ được vẻ thanh thoát cho bộ trang phục.\n\nTúi xách: Túi clutch hoặc túi xách tay nhỏ gọn màu trắng hoặc pastel sẽ giúp bạn trông thanh lịch hơn.\n\nPhụ kiện: Một chiếc vòng cổ thanh mảnh hoặc một chiếc vòng tay sẽ làm cho trang phục của bạn thêm phần hoàn hảo.', 480000.00, 1, NULL, 'Denim', 'H&M', '6 tháng', 380000.00),
(7, 'Váy dạ hội dài', 'Váy dạ hội dài được thiết kế với các chi tiết đính sequin lấp lánh, khiến bạn tỏa sáng trong mọi sự kiện. Với chiều dài thanh thoát và chất liệu cao cấp, váy này mang đến vẻ sang trọng, đẳng cấp, và là lựa chọn hoàn hảo cho các buổi tiệc tối, lễ trao giải, hay các sự kiện cần sự trang trọng. Được thiết kế để nổi bật và cuốn hút, váy này chắc chắn sẽ khiến bạn là tâm điểm của mọi ánh nhìn.\nPhối cùng:\n\nGiày: Giày cao gót lấp lánh hoặc giày satin sẽ là lựa chọn lý tưởng cho một buổi dạ tiệc.\n\nTúi xách: Túi clutch lấp lánh hoặc túi xách da đính đá để tạo sự sang trọng.\n\nPhụ kiện: Bạn có thể chọn một chiếc vòng cổ sang trọng hoặc một chiếc băng đô lấp lánh để làm điểm nhấn cho vẻ ngoài của mình.\n', 750000.00, 1, NULL, 'Voan', 'H&M', '1 năm', 650000.00),
(8, 'Váy midi xếp ly', 'Váy midi xếp ly mang đến vẻ ngoài thanh lịch và nhẹ nhàng nhờ vào chất liệu lụa mềm mại. Dáng váy midi dễ dàng kết hợp với giày cao gót hay sandal để tạo nên một bộ trang phục hoàn hảo cho các buổi tiệc hoặc đi làm. Được thiết kế để tôn lên vẻ đẹp thanh thoát của người mặc, váy này là lựa chọn lý tưởng cho những dịp cần sự duyên dáng nhưng không quá cầu kỳ.\nPhối cùng:\n\nGiày: Một đôi sandal cao gót hoặc giày mũi nhọn để tăng thêm vẻ thanh thoát và nữ tính cho trang phục.\n\nTúi xách: Túi xách tay nhỏ hoặc túi xách da với thiết kế đơn giản nhưng sang trọng sẽ giúp làm nổi bật chiếc váy.\n\nPhụ kiện: Một chiếc dây chuyền mảnh hoặc một chiếc vòng tay sẽ mang đến sự thanh thoát cho trang phục của bạn.\n\n', 420000.00, 1, NULL, 'Lụa', 'H&M', '1 năm', 300000.00),
(9, 'Váy sơ mi dáng dài', 'Váy sơ mi dáng dài với chất liệu cotton thoáng mát mang lại cảm giác thoải mái tuyệt đối cho người mặc. Thiết kế dáng dài giúp bạn trông cao ráo và thanh thoát hơn, trong khi sự đơn giản của chiếc váy cũng tạo nên vẻ đẹp tinh tế và thanh lịch. Đây là lựa chọn tuyệt vời cho những buổi dạo phố, đi làm hoặc các sự kiện không quá trang trọng.\nPhối cùng:\n\nGiày: Giày sneaker trắng hoặc giày sandal sẽ rất hợp với chiếc váy sơ mi dáng dài, mang đến phong cách trẻ trung, năng động.\n\nTúi xách: Túi tote canvas hoặc túi xách đeo vai màu sáng sẽ là lựa chọn phù hợp cho chiếc váy sơ mi.\n\nPhụ kiện: Một chiếc đồng hồ hoặc vòng tay bạc đơn giản sẽ giúp bạn thêm phần tinh tế.\n\n', 350000.00, 1, NULL, 'Cotton', 'H&M', '1 năm', 280000.00),
(10, 'Váy peplum công sở', 'Váy peplum với dáng ôm sát cơ thể, tạo đường cong quyến rũ nhưng vẫn giữ được sự thanh lịch cần có trong môi trường công sở. Thiết kế này giúp bạn trông sang trọng và chuyên nghiệp, nhưng vẫn rất thoải mái khi làm việc. Váy này là lựa chọn lý tưởng cho các buổi họp, phỏng vấn, hoặc các dịp cần đến sự trang nhã và nghiêm túc.\nPhối cùng:\n\nGiày: Giày cao gót đen hoặc giày mũi nhọn sẽ tạo nên sự chuyên nghiệp và thanh lịch cho bạn trong môi trường công sở.\n\nTúi xách: Túi xách công sở làm từ da cao cấp hoặc túi xách tay cầm sẽ hoàn thiện vẻ ngoài trang nhã của bạn.\n\nPhụ kiện: Một chiếc thắt lưng mảnh sẽ giúp tôn lên vòng eo, tạo thêm sự thanh thoát cho bộ trang phục.\n\n', 380000.00, 1, NULL, 'Voan', 'H&M', '1 năm', 300000.00),
(11, 'Váy hai dây lụa', 'Váy hai dây làm từ chất liệu lụa nhẹ nhàng, mang đến sự thoải mái và mát mẻ cho người mặc trong những ngày hè oi ả. Dáng váy suông không bó sát giúp bạn di chuyển dễ dàng, tạo sự thanh thoát, nữ tính. Đây là lựa chọn lý tưởng cho những buổi tiệc ngoài trời, dạo phố, hay thậm chí là một buổi tối thư giãn tại nhà.\nPhối cùng:\n\nGiày: Một đôi sandal cao gót hoặc giày bệt sẽ phù hợp để bạn có thể thoải mái di chuyển mà vẫn giữ được vẻ nữ tính.\n\nTúi xách: Túi xách mini cầm tay sẽ là lựa chọn hoàn hảo cho vẻ ngoài thanh lịch.\n\nPhụ kiện: Một chiếc bông tai kim cương hoặc một chiếc vòng cổ dài mảnh sẽ giúp bạn nổi bật trong mọi buổi tiệc.\n', 300000.00, 1, NULL, 'Lụa', 'H&M', '2 năm', 240000.00),
(12, 'Váy chấm bi retro', 'Váy chấm bi phong cách retro mang lại sự tươi mới và dễ thương, phù hợp cho những ai yêu thích phong cách cổ điển nhưng vẫn muốn giữ được nét trẻ trung. Dáng xòe của váy tạo sự bồng bềnh và thoải mái khi di chuyển, trong khi chất liệu vải mềm mại giúp bạn cảm thấy thoải mái suốt cả ngày. Váy này rất thích hợp cho các dịp đi chơi, sự kiện mang phong cách vintage, hoặc những bữa tiệc nhẹ nhàng.\nPhối cùng:\n\nGiày: Một đôi giày ballet hoặc giày cao gót mũi nhọn sẽ rất phù hợp với phong cách retro của chiếc váy.\n\nTúi xách: Túi xách mini hoặc túi xách màu đỏ tươi sẽ là lựa chọn nổi bật, thêm phần thu hút cho bộ trang phục.\n\nPhụ kiện: Một chiếc mũ rộng vành hoặc chiếc kính râm vintage sẽ tạo thêm điểm nhấn cho vẻ ngoài cổ điển của bạn.\n', 460000.00, 1, NULL, 'Retro', 'Mango', '2 năm', 390000.00),
(13, 'Váy bó sát đính đá', 'Váy bó sát cơ thể làm từ chất liệu thun co giãn giúp tôn lên mọi đường cong cơ thể một cách hoàn hảo. Những viên đá lấp lánh được đính tinh tế trên váy tạo điểm nhấn độc đáo và sang trọng. Đây là lựa chọn lý tưởng cho các buổi tiệc dạ hội, sự kiện trang trọng hoặc những dịp cần đến sự quyến rũ và nổi bật.\nPhối cùng:\n\nGiày: Giày cao gót lấp lánh hoặc giày satin sẽ tôn lên vẻ đẹp sang trọng của bạn.\n\nTúi xách: Túi clutch đính sequin hoặc túi xách da đính đá sẽ là lựa chọn hoàn hảo để kết hợp với váy.\n\nPhụ kiện: Một chiếc vòng cổ sang trọng hoặc bông tai kim cương sẽ tạo thêm sự lộng lẫy, giúp bạn nổi bật trong mọi bữa tiệc.\n', 500000.00, 1, NULL, 'Cotton', 'Mango', '2 năm', 430000.00),
(14, 'Váy trễ vai hoa', 'Váy trễ vai với họa tiết hoa tươi tắn mang đến vẻ ngoài dịu dàng, nữ tính nhưng cũng rất quyến rũ. Chất liệu voan nhẹ giúp bạn thoải mái di chuyển và cảm thấy dễ chịu suốt cả ngày. Đây là chiếc váy lý tưởng cho các buổi hẹn hò, tiệc tối hoặc những dịp cần đến sự nhẹ nhàng và trang nhã.\nPhối cùng:\n\nGiày: Giày cao gót hoặc sandal đế vuông sẽ giúp bạn duyên dáng và dễ di chuyển trong những bữa tiệc hoặc sự kiện ngoài trời.\n\nTúi xách: Túi clutch nhỏ nhắn hoặc túi xách mini cầm tay với màu sắc nhẹ nhàng sẽ tạo thêm vẻ nữ tính cho bộ trang phục.\n\nPhụ kiện: Một chiếc vòng cổ tinh tế hoặc bông tai ngọc trai sẽ giúp bạn thêm phần thanh thoát và quyến rũ.\n', 430000.00, 1, NULL, 'Cotton', 'Mango', '2 năm', 340000.00),
(15, 'Váy xếp tầng bohemian', 'Váy xếp tầng phong cách bohemian này mang đến vẻ ngoài phóng khoáng, tự do và đầy sáng tạo. Chất liệu cotton mềm mại, thoáng mát giúp bạn cảm thấy thoải mái và dễ chịu, lý tưởng cho những chuyến du lịch hoặc các buổi picnic ngoài trời. Dáng váy xếp tầng giúp tạo hiệu ứng bồng bềnh và nữ tính, kết hợp với phong cách bohemian sẽ khiến bạn trở nên nổi bật trong mọi tình huống. Đây là lựa chọn hoàn hảo cho những ai yêu thích sự tự do trong phong cách thời trang của mình.\nPhối cùng:\n\nGiày: Giày sandal xỏ ngón hoặc giày bệt sẽ giúp bạn có vẻ ngoài tự nhiên, thoải mái.\n\nTúi xách: Túi xách đeo chéo phong cách boho, hoặc túi xách có chi tiết tua rua sẽ là lựa chọn hoàn hảo để kết hợp với váy bohemian.\n\nPhụ kiện: Một chiếc vòng tay nhiều lớp và băng đô tóc sẽ tạo thêm sự phóng khoáng và năng động cho bộ trang phục.\n\n', 470000.00, 1, NULL, 'Voan', 'Mango', '6 tháng', 360000.00),
(16, 'Túi tote canvas', 'Túi tote làm từ chất liệu canvas chắc chắn và bền bỉ, mang lại vẻ ngoài trẻ trung và năng động. Với thiết kế rộng rãi, túi này có thể đựng nhiều đồ vật, từ sách vở đến các món đồ cần thiết cho một ngày dài. Họa tiết in đơn giản trên túi làm tăng thêm vẻ đẹp tinh tế mà không quá cầu kỳ. Đây là một sản phẩm lý tưởng cho những chuyến đi chơi, mua sắm, hoặc đi làm, khi bạn cần một chiếc túi dễ sử dụng và đầy phong cách.\nPhối cùng:\n\nTrang phục: Túi tote canvas rất dễ phối hợp với các trang phục đơn giản như áo thun, váy maxi hoặc quần jean.\n\nGiày: Giày thể thao hoặc sandal bệt sẽ là lựa chọn tuyệt vời để tạo sự thoải mái khi di chuyển.\n\nPhụ kiện: Một chiếc mũ rộng vành hoặc kính râm sẽ là điểm nhấn phong cách cho trang phục của bạn khi kết hợp với túi tote.\n', 200000.00, 2, NULL, 'Canvas', 'Gucci', '1 năm', 130000.00),
(17, 'Túi xách da mini', 'Túi xách da mini này mang lại sự sang trọng và thanh lịch, với chất liệu da cao cấp giúp tạo nên một vẻ ngoài tinh tế. Thiết kế nhỏ gọn nhưng vẫn đủ rộng để chứa các vật dụng cần thiết như điện thoại, ví tiền và mỹ phẩm. Phong cách tối giản của túi rất dễ kết hợp với nhiều bộ trang phục khác nhau, từ váy đầm cho đến quần jean và áo sơ mi thanh lịch. Đây là chiếc túi lý tưởng cho các buổi tiệc, hẹn hò, hoặc dạo phố.\nPhối cùng:\n\nTrang phục: Túi xách da mini dễ dàng kết hợp với các trang phục như váy ngắn, áo sơ mi hoặc đầm suông.\n\nGiày: Một đôi giày cao gót hoặc giày ballet sẽ giúp bạn tạo thêm sự thanh lịch cho bộ trang phục.\n\nPhụ kiện: Một chiếc đồng hồ vàng hoặc bông tai ngọc trai sẽ làm tăng thêm sự sang trọng cho trang phục của bạn khi kết hợp với túi xách mini này.\n\n', 350000.00, 2, NULL, 'Canvas', 'Gucci', '1 năm', 230000.00),
(18, 'Túi bucket dây rút', 'Túi bucket dây rút này mang đến sự tiện dụng và phong cách cá tính. Chất liệu da PU bền bỉ kết hợp với thiết kế dây rút giúp bạn dễ dàng đóng mở túi. Kiểu dáng bucket với hình dáng tròn sẽ tạo sự thoải mái khi mang theo các đồ vật như điện thoại, ví, hoặc đồ dùng cá nhân. Túi này rất thích hợp cho các buổi đi chơi, shopping hay những dịp tụ tập bạn bè trong những ngày năng động.\nPhối cùng:\n\nTrang phục: Túi bucket dây rút kết hợp hoàn hảo với áo sơ mi đơn giản, váy maxi hoặc set đồ thể thao.\n\nGiày: Giày thể thao hoặc sandal đế bệt sẽ mang đến sự năng động, thoải mái cho bạn.\n\nPhụ kiện: Một chiếc vòng tay bản rộng hoặc kính râm thời trang sẽ là điểm nhấn nổi bật cho bộ trang phục khi đi dạo phố hoặc đi du lịch.\n\n', 300000.00, 2, NULL, 'Da PU', 'Gucci', '1 năm', 180000.00),
(19, 'Túi đeo chéo vải', 'Túi đeo chéo làm từ vải dù nhẹ nhàng và thoáng mát, rất phù hợp cho những ai yêu thích sự tiện dụng trong cuộc sống hàng ngày. Thiết kế gọn gàng và dây đeo có thể điều chỉnh giúp bạn thoải mái khi di chuyển. Túi đeo chéo này rất thích hợp cho các chuyến đi ngắn ngày, đi du lịch hoặc các buổi đi chơi với bạn bè, nơi bạn chỉ cần mang theo các vật dụng cơ bản.\nPhối cùng:\n\nTrang phục: Túi đeo chéo vải sẽ hoàn hảo với các bộ trang phục trẻ trung, năng động như áo thun, quần short hoặc váy ngắn.\n\nGiày: Một đôi giày thể thao hoặc sandal sẽ tạo thêm sự thoải mái và hợp thời trang cho bạn.\n\nPhụ kiện: Kính mát to và một chiếc vòng tay dễ thương sẽ là lựa chọn lý tưởng khi kết hợp với túi đeo chéo vải này.\n', 180000.00, 2, NULL, 'Da PU', 'Gucci', '1 năm', 140000.00),
(20, 'Túi clutch dạ hội', 'Túi clutch dạ hội đính sequin lấp lánh, thiết kế sang trọng và tinh tế, sẽ là điểm nhấn hoàn hảo cho các buổi tiệc dạ hội hoặc sự kiện trang trọng. Túi nhỏ gọn này có thể chứa những vật dụng cần thiết như điện thoại, ví và mỹ phẩm, giúp bạn dễ dàng di chuyển mà không làm mất đi vẻ thanh lịch.\nPhối cùng:\n\nTrang phục: Túi clutch dạ hội rất hợp với những bộ váy dạ hội dài, váy cocktail hoặc váy đính đá.\n\nGiày: Giày cao gót satin hoặc giày đính đá sẽ tôn lên vẻ đẹp sang trọng của bạn.\n\nPhụ kiện: Một chiếc dây chuyền lấp lánh hoặc bông tai kim cương sẽ tạo thêm sự lộng lẫy, giúp bạn nổi bật trong mọi bữa tiệc.\n', 250000.00, 2, NULL, 'Canvas', 'Chanel', '1 năm', 170000.00),
(21, 'Túi shopper lớn', 'Túi shopper kích thước lớn làm từ chất liệu canvas, với thiết kế đơn giản và tiện dụng, lý tưởng cho những ai cần một chiếc túi để mang theo nhiều đồ đạc. Đây là chiếc túi lý tưởng cho những chuyến đi mua sắm, đi làm hoặc du lịch. Bạn có thể dễ dàng đựng sách vở, laptop, hoặc đồ dùng cá nhân mà không cần lo lắng về không gian chứa đựng.\nPhối cùng:\n\nTrang phục: Túi shopper lớn phù hợp với nhiều phong cách trang phục khác nhau như áo sơ mi, áo thun, quần jean hoặc váy midi.\n\nGiày: Một đôi giày bệt hoặc giày loafer sẽ rất hợp với áo sơ mi caro hoặc những bộ trang phục casual.\n\nPhụ kiện: Một chiếc kính râm hoặc đồng hồ lớn sẽ tạo điểm nhấn cho bộ trang phục của bạn khi đi làm hoặc đi chơi.\n', 220000.00, 2, NULL, 'Da PU', 'Gucci', '1 năm', 180000.00),
(22, 'Túi xách công sở', 'Túi xách công sở này làm từ chất liệu da cao cấp, mang lại vẻ chuyên nghiệp và sang trọng. Thiết kế rộng rãi, túi có thể chứa các vật dụng cần thiết cho công việc hàng ngày như laptop, tài liệu, và các phụ kiện cá nhân khác. Với phong cách thanh lịch và tính năng tiện dụng, đây là sự lựa chọn hoàn hảo cho những ai cần một chiếc túi vừa sang trọng lại vừa có thể đáp ứng nhu cầu công sở.\nPhối cùng:\n\nTrang phục: Túi xách công sở kết hợp hoàn hảo với áo sơ mi, chân váy bút chì, hoặc quần tây.\n\nGiày: Giày cao gót hoặc giày bệt sẽ tạo nên vẻ chuyên nghiệp và thanh lịch trong môi trường công sở.\n\nPhụ kiện: Một chiếc đồng hồ sang trọng hoặc bông tai ngọc trai sẽ là điểm nhấn hoàn hảo cho trang phục công sở.\n\n', 400000.00, 2, NULL, 'Canvas', 'Chanel', '1 năm', 280000.00),
(23, 'Túi đeo vai boho', 'Túi đeo vai boho này mang đến sự phóng khoáng, tự do với những chi tiết đính tua rua tinh tế. Chất liệu vải và thiết kế bohemian sẽ tạo thêm điểm nhấn đặc biệt cho phong cách thời trang của bạn. Phù hợp cho các buổi đi chơi, du lịch, hoặc các sự kiện ngoài trời, túi này sẽ khiến bạn nổi bật trong mọi tình huống với vẻ ngoài tự nhiên, giản dị nhưng đầy cuốn hút.\nPhối cùng:\n\nTrang phục: Túi đeo vai boho dễ dàng kết hợp với các bộ trang phục nhẹ nhàng, như váy maxi, áo thun rộng, quần short hay những bộ đồ đi biển.\n\nGiày: Giày sandal đế xuồng hoặc giày bệt sẽ hoàn thiện phong cách bohemian tự do, phóng khoáng.\n\nPhụ kiện: Một chiếc băng đô hoa hoặc vòng tay nhiều lớp sẽ giúp bạn thêm phần cuốn hút khi kết hợp với túi đeo vai boho này.\n', 280000.00, 2, NULL, 'Da PU', 'Gucci', 'Bảo hành trọn đời', 230000.00),
(24, 'Túi backpack vải', 'Túi backpack vải này mang lại phong cách trẻ trung, năng động, lý tưởng cho các hoạt động ngoài trời hoặc những chuyến du lịch. Chất liệu vải bền bỉ giúp bảo vệ đồ đạc bên trong túi một cách an toàn, trong khi thiết kế tiện dụng với nhiều ngăn giúp bạn dễ dàng sắp xếp đồ dùng. Đây là một sản phẩm lý tưởng cho những ai yêu thích sự tiện lợi và phong cách năng động.\nPhối cùng:\n\nTrang phục: Túi backpack vải là lựa chọn lý tưởng cho các trang phục năng động như áo thun, quần jean, hoặc set đồ thể thao.\n\nGiày: Giày thể thao hoặc giày bệt sẽ giúp bạn có một phong cách năng động.\n\nPhụ kiện: Một chiếc mũ lưỡi trai hoặc kính râm sẽ là lựa chọn tuyệt vời khi kết hợp với túi backpack vải này.\n\n', 320000.00, 2, NULL, 'Da PU', 'Gucci', 'Bảo hành trọn đời', 240000.00),
(25, 'Túi xách tay cầm', 'Túi xách tay cầm này được làm từ chất liệu da PU, mang lại vẻ sang trọng và tiện dụng. Thiết kế tay cầm giúp bạn dễ dàng cầm nắm và mang theo, trong khi kích thước vừa phải giúp chứa đựng các vật dụng cần thiết mà không quá cồng kềnh. Đây là chiếc túi lý tưởng cho các buổi tiệc, dạo phố, hoặc các sự kiện không chính thức.\nPhối cùng:\n\nTrang phục: Túi xách tay cầm dễ dàng phối hợp với các bộ trang phục thanh lịch như áo sơ mi, váy midi hoặc chân váy bút chì.\n\nGiày: Giày cao gót hoặc giày loafer sẽ mang đến sự sang trọng cho bạn.\n\nPhụ kiện: Một chiếc đồng hồ mạ vàng hoặc bông tai ngọc trai sẽ làm tăng thêm vẻ đẹp thanh thoát cho bộ trang phục của bạn.\n', 360000.00, 2, NULL, 'Da PU', 'Gucci', 'Bảo hành trọn đời', 280000.00),
(26, 'Túi đeo chéo da', 'Túi đeo chéo chất liệu da cao cấp với khóa kéo chắc chắn, mang lại sự sang trọng và bảo mật cho người sử dụng. Thiết kế gọn nhẹ giúp bạn dễ dàng mang theo những vật dụng cần thiết mà không cảm thấy cồng kềnh. Túi này rất phù hợp cho những dịp đi chơi, dạo phố, hoặc đi làm hàng ngày khi bạn cần một chiếc túi tiện dụng nhưng vẫn đầy phong cách.\nPhối cùng:\n\nTrang phục: Túi đeo chéo da sẽ hợp với nhiều phong cách thời trang khác nhau, từ váy đơn giản đến quần short, áo thun.\n\nGiày: Giày thể thao hoặc giày bệt sẽ tạo vẻ năng động và thoải mái cho bạn.\n\nPhụ kiện: Một chiếc vòng tay mảnh hoặc kính mát sẽ là lựa chọn lý tưởng để hoàn thiện phong cách trẻ trung của bạn.\n\n', 340000.00, 2, NULL, 'Da PU', 'Gucci', 'Bảo hành trọn đời', 300000.00),
(27, 'Túi tote họa tiết', 'Túi tote họa tiết hoa này được làm từ chất liệu vải bền bỉ và nhẹ nhàng. Với thiết kế rộng rãi, túi có thể chứa đựng nhiều đồ vật như sách vở, ví, điện thoại và các vật dụng cá nhân khác. Họa tiết hoa trên túi giúp tạo điểm nhấn cho trang phục của bạn, mang lại vẻ ngoài trẻ trung và năng động. Đây là chiếc túi lý tưởng cho những chuyến đi chơi, mua sắm, hoặc thậm chí là đi làm hàng ngày.\nPhối cùng:\n\nTrang phục: Túi tote họa tiết sẽ rất hợp với những bộ trang phục đơn giản như áo thun, váy maxi hoặc quần jean.\n\nGiày: Giày bệt hoặc sandal đế bệt sẽ giúp bạn có một vẻ ngoài thoải mái và tự do.\n\nPhụ kiện: Một chiếc kính râm và đồng hồ thể thao sẽ giúp bạn có vẻ ngoài phóng khoáng và đầy phong cách.\n\n', 190000.00, 2, NULL, 'Vải', 'Chanel', 'Bảo hành trọn đời', 170000.00),
(31, 'Áo sơ mi trắng', 'Áo sơ mi trắng này được làm từ chất liệu cotton cao cấp, giúp bạn cảm thấy thoải mái và dễ chịu trong suốt cả ngày dài. Thiết kế form slimfit tôn lên đường nét cơ thể, mang lại vẻ ngoài thanh lịch và chuyên nghiệp. Đây là một món đồ không thể thiếu trong tủ đồ của bất kỳ ai, phù hợp cho môi trường công sở, các buổi họp hoặc các sự kiện yêu cầu sự trang nhã và lịch sự.\nPhối cùng:\n\nTrang phục: Áo sơ mi trắng là món đồ cơ bản có thể kết hợp với bất kỳ kiểu trang phục nào, từ quần jean, chân váy, đến quần tây công sở.\n\nGiày: Giày cao gót hoặc giày oxford sẽ tạo thêm sự thanh lịch cho bạn.\n\nPhụ kiện: Một chiếc đồng hồ tinh tế hoặc vòng cổ mảnh sẽ tạo điểm nhấn nhẹ nhàng cho bộ trang phục công sở của bạn.\n', 250000.00, 3, NULL, 'Cotton', 'Zara', '12 tháng', 210000.00),
(32, 'Áo thun cổ tròn', 'Áo thun cổ tròn làm từ chất liệu cotton mềm mại, giúp bạn cảm thấy thoải mái và dễ chịu trong mọi hoàn cảnh. Với thiết kế đơn giản, áo thun này là sự lựa chọn hoàn hảo cho những buổi dạo phố, đi chơi hoặc nghỉ ngơi trong những ngày hè nóng bức. Sự linh hoạt trong thiết kế khiến bạn có thể kết hợp áo với nhiều trang phục khác nhau.\nPhối cùng:\n\nTrang phục: Áo thun cổ tròn dễ dàng kết hợp với các bộ đồ như quần jean, chân váy midi hoặc shorts cho một phong cách casual thoải mái.\n\nGiày: Giày thể thao hoặc giày bệt sẽ giúp bạn có một phong cách năng động.\n\nPhụ kiện: Một chiếc vòng tay hoặc kính râm sẽ là lựa chọn tuyệt vời để hoàn thiện phong cách này.\n', 180000.00, 3, NULL, 'Cotton', 'Zara', '12 tháng', 130000.00),
(33, 'Áo polo basic', 'Áo polo cơ bản này được làm từ chất liệu thun pique, mang lại sự thoải mái và phóng khoáng. Đây là sự lựa chọn lý tưởng cho những buổi đi chơi ngoài trời, các hoạt động thể thao, hoặc các cuộc gặp gỡ bạn bè. Thiết kế cổ polo giúp tạo vẻ ngoài chỉn chu nhưng vẫn giữ được sự năng động và trẻ trung.\nPhối cùng:\n\nTrang phục: Áo polo cơ bản có thể kết hợp với quần short, quần jean hoặc chân váy để tạo nên một vẻ ngoài năng động, nhưng không kém phần thanh lịch.\n\nGiày: Giày thể thao hoặc giày oxford sẽ giúp bạn có một phong cách thể thao nhưng vẫn lịch sự.\n\nPhụ kiện: Một chiếc đồng hồ thể thao và kính mát sẽ là điểm nhấn cho bộ trang phục này.\n', 220000.00, 3, NULL, 'Cotton', 'Zara', '12 tháng', 150000.00),
(34, 'Áo sơ mi caro', 'Áo sơ mi caro này được làm từ chất liệu cotton thoáng mát, với họa tiết caro cổ điển mang đến sự trẻ trung và thời trang. Áo thích hợp cho những ngày dạo phố, đi làm, hoặc các cuộc gặp gỡ không quá trang trọng. Chất liệu cotton nhẹ nhàng giúp bạn cảm thấy thoải mái trong suốt cả ngày dài.\nPhối cùng:\n\nTrang phục: Áo sơ mi caro có thể kết hợp với quần jean, quần kaki, hoặc chân váy để tạo phong cách thời trang thoải mái và thanh lịch.\n\nGiày: Giày bệt hoặc giày oxford sẽ rất hợp với áo sơ mi caro.\n\nPhụ kiện: Một chiếc đồng hồ đơn giản hoặc bông tai nhỏ sẽ giúp bạn thêm phần tinh tế.\n\n', 270000.00, 3, NULL, 'Cotton', 'Zara', '12 tháng', 180000.00),
(35, 'Áo hoodie trơn', 'Áo hoodie trơn này làm từ chất liệu nỉ bông ấm áp, lý tưởng cho những ngày thu đông se lạnh. Với thiết kế đơn giản và thanh lịch, áo hoodie này rất dễ phối với quần jean hoặc quần thể thao, mang đến sự thoải mái cho các hoạt động ngoài trời hoặc dạo phố. Đây là lựa chọn lý tưởng cho những ngày nghỉ cuối tuần hoặc các buổi đi chơi.\nPhối cùng:\n\nTrang phục: Áo hoodie trơn phù hợp với quần thể thao, quần jean hoặc quần short cho phong cách năng động và thoải mái.\n\nGiày: Giày thể thao hoặc giày bệt sẽ là lựa chọn hoàn hảo cho phong cách này.\n\nPhụ kiện: Một chiếc mũ lưỡi trai hoặc kính râm sẽ là lựa chọn tuyệt vời khi kết hợp với áo hoodie trơn.\n', 350000.00, 3, NULL, 'Nỉ bông', 'Adidas', '12 tháng', 260000.00),
(45, 'Áo khoác bomber', 'Áo khoác bomber, chất liệu dù, phong cách trẻ trungÁo khoác bomber này được làm từ chất liệu dù, mang lại sự trẻ trung, năng động và bảo vệ bạn khỏi những cơn gió lạnh. Thiết kế đơn giản nhưng không kém phần thời trang giúp bạn dễ dàng phối đồ, từ các bộ trang phục đi làm cho đến những bộ đồ thể thao. Áo khoác này rất phù hợp cho các buổi gặp gỡ bạn bè, đi chơi hoặc tham gia các hoạt động ngoài trời.\nPhối cùng:\n\nTrang phục: Áo khoác bomber dễ dàng kết hợp với các bộ đồ như quần jean, áo thun hoặc chân váy cho một phong cách trẻ trung, năng động.\n\nGiày: Giày thể thao hoặc giày boot sẽ giúp bạn tạo phong cách cá tính.\n\nPhụ kiện: Một chiếc kính mát to và đồng hồ thể thao sẽ là lựa chọn tuyệt vời để hoàn thiện phong cách này.\n', 450000.00, 3, NULL, 'Dù', 'Nike', '6 tháng', 400000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_khuyenmai`
--

CREATE TABLE `sanpham_khuyenmai` (
  `id` int(11) NOT NULL,
  `khuyenmai_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham_khuyenmai`
--

INSERT INTO `sanpham_khuyenmai` (`id`, `khuyenmai_id`, `sanpham_id`) VALUES
(12, 2, 11),
(24, 18, 3),
(25, 8, 14);

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
(120, 45, 4, 35),
(245, 1, 1, 50),
(246, 1, 2, 60),
(247, 1, 3, 40),
(248, 1, 4, 30);

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
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `chitietdonhangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `chitietgiohangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `donhangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `giohangid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  MODIFY `hinhanhid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `khuyenmaiid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mausac`
--
ALTER TABLE `mausac`
  MODIFY `mausacid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `sanphamid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `sanpham_khuyenmai`
--
ALTER TABLE `sanpham_khuyenmai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sanpham_size`
--
ALTER TABLE `sanpham_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

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
