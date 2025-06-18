-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 20, 2025 lúc 09:54 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shopdongho2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbox`
--

CREATE TABLE `chatbox` (
  `idchatbox` int(11) NOT NULL,
  `idnguoidung` int(11) NOT NULL,
  `noidungchat` text NOT NULL,
  `role` int(11) NOT NULL,
  `thoigian` datetime NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbox`
--

INSERT INTO `chatbox` (`idchatbox`, `idnguoidung`, `noidungchat`, `role`, `thoigian`, `ngaytao`) VALUES
(75, 13, 'đia chỉ  của shop ở đâu vâyj', 0, '2025-04-17 09:52:17', '2025-04-17 07:52:17'),
(76, 13, 'Địa chỉ của Watch Shop Luxury là: Số 68, Phường 2, Thành phố Vĩnh Long. Nếu bạn cần thêm thông tin hoặc hỗ trợ gì khác, đừng ngần ngại hỏi nhé!', 1, '2025-04-17 09:52:22', '2025-04-17 07:52:22'),
(77, 17, 'tôi muốn tìm đồng hồ tầm giá 500 đến 1 tỉ', 0, '2025-04-17 10:14:28', '2025-04-17 08:14:28'),
(79, 13, 'ágsdg', 1, '2025-04-17 11:05:08', '2025-04-17 09:05:08'),
(80, 17, 'cảm ơn ạ', 1, '2025-04-17 11:09:34', '2025-04-17 09:09:34'),
(81, 13, 'tìm rolex datejust', 0, '2025-04-17 18:59:46', '2025-04-17 16:59:46'),
(82, 13, 'Tìm thấy 3 sản phẩm phù hợp:\n<div class=\"product-list\">\n    <a href=\"chi_tiet_san_pham.php?id=31\" class=\"product-card\">\n        <img src=\"imageproduct/1744571319_278381rbr-0006.jpg\" class=\"product-image\">\n        <span class=\"product-name\">Rolex Datejust 31 278381rbr-0006</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=37\" class=\"product-card\">\n        <img src=\"imageproduct/1744573300_avr-3.jpg\" class=\"product-image\">\n        <span class=\"product-name\">Rolex Datejust Wimbledon</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=38\" class=\"product-card\">\n        <img src=\"imageproduct/1744599951_dong-ho-rolex-datejust-126334-mat-xanh-navy.png\" class=\"product-image\">\n        <span class=\"product-name\">Rolex Datejust 126334 Navy</span>\n    </a>\n</div>', 1, '2025-04-17 18:59:53', '2025-04-17 16:59:53'),
(83, 13, 'shop này nằm ở đâu', 0, '2025-04-17 19:00:14', '2025-04-17 17:00:14'),
(84, 13, 'Shop Watch Shop Luxury nằm ở địa chỉ: Số 68, Phường 2, Thành phố Vĩnh Long. Nếu bạn cần thêm thông tin gì khác, hãy cho tôi biết!', 1, '2025-04-17 19:00:17', '2025-04-17 17:00:17'),
(85, 13, 'cách thức mua hàng như thế nào', 0, '2025-04-17 19:00:28', '2025-04-17 17:00:28'),
(86, 13, 'Chào bạn,\n\nCảm ơn bạn đã quan tâm đến Watch Shop Luxury. Để mua hàng tại cửa hàng của chúng tôi, bạn có thể thực hiện theo các bước sau:\n\n1. **Tham quan cửa hàng**: Địa chỉ của chúng tôi là Số 68, Phường 2, Thành phố Vĩnh Long. Bạn có thể đến trực tiếp để trải nghiệm không gian trưng bày sang trọng và xem các mẫu đồng hồ.\n\n2. **Tư vấn sản phẩm**: Đội ngũ chuyên viên của chúng tôi sẽ sẵn sàng tư vấn cho bạn về các sản phẩm, lịch sử đồng hồ, cũng như phong cách cá nhân hóa phù hợp với bạn.\n\n3. **Chọn sản phẩm**: Sau khi được tư vấn, bạn có thể lựa chọn mẫu đồng hồ mà bạn yêu thích.\n\n4. **Thanh toán**: Chúng tôi chấp nhận nhiều hình thức thanh toán bao gồm chuyển khoản, tiền mặt, thanh toán qua thẻ tín dụng và hỗ trợ trả góp 0%.\n\n5. **Nhận sản phẩm**: Sau khi hoàn tất thanh toán, bạn sẽ nhận được sản phẩm kèm theo hộp, sổ và thẻ bảo hành điện tử toàn cầu.\n\nNếu bạn không thể đến cửa hàng, bạn cũng có thể liên hệ với chúng tôi qua email: watchshopluxury68@gmail.com hoặc số điện thoại: 0901 686 123 để được hỗ trợ đặt hàng.\n\nHy vọng thông tin này hữu ích cho bạn. Nếu có thêm câu hỏi nào, đừng ngần ngại hỏi nhé!\n\nTrân trọng,\nWatch Shop Luxury.', 1, '2025-04-17 19:00:38', '2025-04-17 17:00:38'),
(87, 13, 'oki bây giờ tôi là muốn mua đồng hồ có chống nước, tôi là nữ', 0, '2025-04-17 19:01:30', '2025-04-17 17:01:30'),
(88, 13, 'Tìm thấy 1 sản phẩm phù hợp:\n<div class=\"product-list\">\n    <a href=\"chi_tiet_san_pham.php?id=27\" class=\"product-card\">\n        <img src=\"imageproduct/1744571047_dong-ho-hublot-big-bang-one-click-king-gold-white-pave-33mm-485-oe-2210-rw-1604.png\" class=\"product-image\">\n        <span class=\"product-name\">Hublot Big Bang One Click King Gold White Pave</span>\n    </a>\n</div>', 1, '2025-04-17 19:01:36', '2025-04-17 17:01:36'),
(89, 13, 'đẹp đó', 0, '2025-04-17 19:02:18', '2025-04-17 17:02:18'),
(90, 13, 'Cảm ơn bạn đã quan tâm! Nếu bạn có bất kỳ câu hỏi nào khác về sản phẩm hoặc cần thêm thông tin, hãy cho tôi biết nhé!', 1, '2025-04-17 19:02:21', '2025-04-17 17:02:21'),
(91, 13, 'còn cái nào ko', 0, '2025-04-17 19:02:23', '2025-04-17 17:02:23'),
(92, 13, '\'Tìm thấy 4 sản phẩm phù hợp:\n<div class=\"product-list\">\n    <a href=\"chi_tiet_san_pham.php?id=27\" class=\"product-card\">\n        <img src=\"imageproduct/1744571047_dong-ho-hublot-big-bang-one-click-king-gold-white-pave-33mm-485-oe-2210-rw-1604.png\" class=\"product-image\">\n        <span class=\"product-name\">Hublot Big Bang One Click King Gold White Pave</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=28\" class=\"product-card\">\n        <img src=\"imageproduct/1744572814_126710blro.jpeg\" class=\"product-image\">\n        <span class=\"product-name\">Rolex GMT-Master II 126710BLRO</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=29\" class=\"product-card\">\n        <img src=\"imageproduct/1744571368_dong-ho-hublot-big-bang-integrated-king-gold-rainbow-42mm-451-ox-1180-ox-3999.png\" class=\"product-image\">\n        <span class=\"product-name\">Hublot Big Bang Integrated King Gold Rainbow</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=30\" class=\"product-card\">\n        <img src=\"imageproduct/1744572472_hublot-classic-fusion-king-gold-45mm.png\" class=\"product-image\">\n        <span class=\"product-name\">Hublot Classic Fusion King Gold</span>\n    </a>\n</div>\'', 1, '2025-04-17 19:02:36', '2025-04-17 17:02:36'),
(93, 13, 'tôi muốn đồng hồ vàng trắng', 0, '2025-04-17 19:02:48', '2025-04-17 17:02:48'),
(94, 13, 'Tìm thấy 3 sản phẩm phù hợp:\n<div class=\"product-list\">\n    <a href=\"chi_tiet_san_pham.php?id=37\" class=\"product-card\">\n        <img src=\"imageproduct/1744573300_avr-3.jpg\" class=\"product-image\">\n        <span class=\"product-name\">Rolex Datejust Wimbledon</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=38\" class=\"product-card\">\n        <img src=\"imageproduct/1744599951_dong-ho-rolex-datejust-126334-mat-xanh-navy.png\" class=\"product-image\">\n        <span class=\"product-name\">Rolex Datejust 126334 Navy</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=46\" class=\"product-card\">\n        <img src=\"imageproduct/1744661473_audemars-piguet-royal-oak-frosted-gold-67653bc-gg-1263bc-02.png\" class=\"product-image\">\n        <span class=\"product-name\">Audemars Piguet Royal Oak Frosted Gold</span>\n    </a>\n    <a href=\"chi_tiet_san_pham.php?id=48\" class=\"product-card\">\n        <img src=\"imageproduct/1744662062_dong-ho-audemars-piguet-royal-oak-selfwinding-37mm-15551bc-zz-1356bc-01.png\" class=\"product-image\">\n        <span class=\"product-name\">Audemars Piguet Royal Oak Selfwinding</span>\n    </a>\n</div>', 1, '2025-04-17 19:02:56', '2025-04-17 17:02:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chinhsachbaohanh`
--

CREATE TABLE `chinhsachbaohanh` (
  `id_chinh_sach` int(11) NOT NULL,
  `noi_dung_chinh_sach` text NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ten_chinh_sách` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chinhsachbaohanh`
--

INSERT INTO `chinhsachbaohanh` (`id_chinh_sach`, `noi_dung_chinh_sach`, `ngay_tao`, `ten_chinh_sách`) VALUES
(1, 'Bảo Hành 3 năm', '2025-04-14 19:23:57', 'Chính sách cho đồng hồ tầm thấp'),
(2, 'Bảo Hành 5 Năm', '2025-04-13 20:15:37', 'Bảo Hành cho Rolex');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idchitietdonhang` int(11) NOT NULL,
  `iddonhang` int(11) NOT NULL,
  `idsanpham` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `giaban` bigint(20) NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idchitietdonhang`, `iddonhang`, `idsanpham`, `soluong`, `giaban`, `ngaytao`) VALUES
(45, 60, 30, 1, 450000000, '2025-04-13 19:49:45'),
(46, 61, 32, 2, 652000000, '2025-04-14 02:56:51'),
(47, 62, 29, 1, 889000000, '2025-04-14 02:57:58'),
(48, 63, 44, 1, 900000000, '2025-04-14 17:18:20'),
(49, 64, 44, 1, 900000000, '2025-04-14 17:18:24'),
(50, 65, 32, 1, 652000000, '2025-04-14 17:39:29'),
(51, 66, 37, 1, 352000000, '2025-04-14 17:41:51'),
(52, 67, 29, 1, 889000000, '2025-04-14 17:43:03'),
(53, 68, 37, 1, 352000000, '2025-04-14 17:45:02'),
(54, 69, 29, 1, 889000000, '2025-04-14 17:47:23'),
(55, 70, 37, 10, 352000000, '2025-04-14 18:55:16'),
(56, 71, 39, 1, 3020000000, '2025-04-14 19:19:07'),
(57, 72, 35, 1, 1230000000, '2025-04-14 19:39:28'),
(58, 73, 35, 1, 1230000000, '2025-04-14 19:42:35'),
(60, 75, 32, 1, 652000000, '2025-04-17 15:26:35'),
(61, 76, 32, 1, 652000000, '2025-04-17 15:42:29'),
(62, 77, 32, 1, 652000000, '2025-04-17 15:43:15'),
(63, 78, 32, 1, 652000, '2025-04-17 15:48:23'),
(64, 79, 32, 1, 652000, '2025-04-17 15:49:55'),
(65, 80, 32, 1, 652000, '2025-04-17 16:07:39'),
(66, 81, 32, 2, 652000, '2025-04-17 16:30:04'),
(67, 82, 32, 2, 652000, '2025-04-17 16:42:32'),
(68, 83, 32, 2, 652000, '2025-04-17 16:43:46'),
(69, 84, 32, 1, 652000, '2025-04-17 16:47:41'),
(70, 85, 32, 1, 652000, '2025-04-17 16:57:00'),
(71, 86, 32, 1, 652000, '2025-04-17 16:58:18'),
(72, 87, 29, 1, 889000000, '2025-04-17 17:07:00'),
(73, 88, 38, 1, 305000000, '2025-04-17 17:09:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `idchitietgiohang` int(11) NOT NULL,
  `idgiohang` int(11) NOT NULL,
  `idsanpham` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `giaban` bigint(20) NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietthanhtoan`
--

CREATE TABLE `chitietthanhtoan` (
  `idthanhtoan` int(11) NOT NULL,
  `phuongthuctt` text NOT NULL,
  `tongtien` bigint(20) NOT NULL,
  `trangthai` text NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `magiaodich` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietthanhtoan`
--

INSERT INTO `chitietthanhtoan` (`idthanhtoan`, `phuongthuctt`, `tongtien`, `trangthai`, `ngaytao`, `magiaodich`) VALUES
(1, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:16', 'ORDER17440001564472'),
(2, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:19', 'ORDER17440001599017'),
(3, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:19', 'ORDER17440001593732'),
(4, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:19', 'ORDER17440001593781'),
(5, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:19', 'ORDER17440001597432'),
(6, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:20', 'ORDER17440001602364'),
(7, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:20', 'ORDER17440001605619'),
(8, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:29:20', 'ORDER17440001608315'),
(9, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:30:39', 'ORDER17440002396570'),
(10, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:31:58', 'ORDER17440003181160'),
(11, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:34:12', 'ORDER17440004521176'),
(12, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:36:37', 'ORDER17440005978478'),
(13, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:44:27', 'ORDER17440010675500'),
(14, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:52:56', 'ORDER17440015766590'),
(15, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 04:54:19', 'ORDER17440016591775'),
(16, 'cod', 30455, 'Chờ thanh toán', '2025-04-07 08:00:21', 'ORDER17440128219964'),
(17, 'cod', 30230, 'Chờ thanh toán', '2025-04-07 08:02:17', 'ORDER17440129376290'),
(18, 'cod', 30115, 'Chờ thanh toán', '2025-04-07 08:10:07', 'ORDER17440134073408'),
(19, 'cod', 30675, 'Chờ thanh toán', '2025-04-07 08:19:43', 'ORDER17440139831980'),
(20, 'cod', 30225, 'Chờ thanh toán', '2025-04-07 08:24:49', 'ORDER17440142892041'),
(21, 'cod', 30325, 'Chờ thanh toán', '2025-04-07 08:26:44', 'ORDER17440144046559'),
(24, 'cod', 30115, 'Chưa thanh toán', '2025-04-07 09:00:12', NULL),
(25, 'cod', 450030000, 'Chưa thanh toán', '2025-04-13 19:49:41', NULL),
(26, 'cod', 450030000, 'Chưa thanh toán', '2025-04-13 19:49:45', NULL),
(27, 'cod', 1304030000, 'Chưa thanh toán', '2025-04-14 02:56:51', NULL),
(28, 'cod', 889030000, 'Chưa thanh toán', '2025-04-14 02:57:58', NULL),
(29, 'cod', 900030000, 'Chưa thanh toán', '2025-04-14 17:18:20', NULL),
(30, 'cod', 900030000, 'Chưa thanh toán', '2025-04-14 17:18:24', NULL),
(31, 'cod', 652030000, 'Chưa thanh toán', '2025-04-14 17:39:29', NULL),
(32, 'cod', 352030000, 'Chưa thanh toán', '2025-04-14 17:41:51', NULL),
(33, 'cod', 889030000, 'Chưa thanh toán', '2025-04-14 17:43:03', NULL),
(34, 'cod', 352030000, 'Chưa thanh toán', '2025-04-14 17:45:02', NULL),
(35, 'cod', 889030000, 'Chưa thanh toán', '2025-04-14 17:47:23', NULL),
(36, 'cod', 3520030000, 'Chưa thanh toán', '2025-04-14 18:55:16', NULL),
(37, 'cod', 3020030000, 'Chưa thanh toán', '2025-04-14 19:19:07', NULL),
(38, 'cod', 1230030000, 'Chưa thanh toán', '2025-04-14 19:39:28', NULL),
(39, 'cod', 1230030000, 'Chưa thanh toán', '2025-04-14 19:42:35', NULL),
(40, 'cod', 652030000, 'Chưa thanh toán', '2025-04-15 05:00:53', NULL),
(41, 'cod', 652030000, 'Chưa thanh toán', '2025-04-17 15:26:35', NULL),
(42, 'paypal', 652030000, 'Chưa thanh toán', '2025-04-17 15:42:29', ''),
(43, 'paypal', 652030000, 'Chưa thanh toán', '2025-04-17 15:43:15', ''),
(44, 'paypal', 682000, 'Chưa thanh toán', '2025-04-17 15:48:23', ''),
(45, 'paypal', 682000, 'Chưa thanh toán', '2025-04-17 15:49:55', ''),
(46, 'paypal', 682000, 'Đã thanh toán', '2025-04-17 16:07:39', '{\"id\":\"8BN58192H86117804\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"29.65\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"4NF13542FL537910E\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"29.65\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T16:07:39Z\",\"update_time\":\"2025-04-17T16:07:39Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T16:07:32Z\",\"update_time\":\"2025-04-17T16:07:39Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/8BN58192H86117804\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(47, 'paypal', 1334000, 'Đã thanh toán', '2025-04-17 16:30:04', '{\"id\":\"0EC36223ES723834E\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"58.00\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"9FE76407PY083205E\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"58.00\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T16:30:04Z\",\"update_time\":\"2025-04-17T16:30:04Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T16:29:51Z\",\"update_time\":\"2025-04-17T16:30:05Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/0EC36223ES723834E\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(48, 'cod', 1334000, 'Chưa thanh toán', '2025-04-17 16:42:32', ''),
(49, 'paypal', 1334000, 'Đã thanh toán', '2025-04-17 16:43:46', '{\"id\":\"1WD9588869745732X\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"58.00\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"58G58415JW524171J\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"58.00\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T16:43:47Z\",\"update_time\":\"2025-04-17T16:43:47Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T16:43:38Z\",\"update_time\":\"2025-04-17T16:43:47Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/1WD9588869745732X\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(50, 'cod', 682000, 'Chưa thanh toán', '2025-04-17 16:47:41', ''),
(51, 'cod', 682000, 'Chưa thanh toán', '2025-04-17 16:56:59', ''),
(52, 'paypal', 682000, 'Đã thanh toán', '2025-04-17 16:58:18', '{\"id\":\"51988476CP3946648\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"29.65\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"50D713444R178614W\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"29.65\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T16:58:18Z\",\"update_time\":\"2025-04-17T16:58:18Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T16:58:11Z\",\"update_time\":\"2025-04-17T16:58:18Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/51988476CP3946648\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(53, 'paypal', 889030000, 'Đã thanh toán', '2025-04-17 17:07:00', '{\"id\":\"4DL09463NA964604J\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"38653.48\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"9YL31625RM805901D\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"38653.48\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T17:07:00Z\",\"update_time\":\"2025-04-17T17:07:00Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T17:04:43Z\",\"update_time\":\"2025-04-17T17:07:00Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/4DL09463NA964604J\",\"rel\":\"self\",\"method\":\"GET\"}]}'),
(54, 'paypal', 305030000, 'Đã thanh toán', '2025-04-17 17:09:12', '{\"id\":\"6228679386313224F\",\"intent\":\"CAPTURE\",\"status\":\"COMPLETED\",\"purchase_units\":[{\"reference_id\":\"default\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"13262.17\"},\"payee\":{\"email_address\":\"sb-mmuvb34967637@business.example.com\",\"merchant_id\":\"W74D7CKYMSU2L\"},\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"1 Main St\",\"admin_area_2\":\"San Jose\",\"admin_area_1\":\"CA\",\"postal_code\":\"95131\",\"country_code\":\"US\"}},\"payments\":{\"captures\":[{\"id\":\"3HW6228555422864E\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"13262.17\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"create_time\":\"2025-04-17T17:09:13Z\",\"update_time\":\"2025-04-17T17:09:13Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-h8b7m35480212@personal.example.com\",\"payer_id\":\"SRB4W6ABSZA3N\",\"address\":{\"country_code\":\"US\"}},\"create_time\":\"2025-04-17T17:09:05Z\",\"update_time\":\"2025-04-17T17:09:13Z\",\"links\":[{\"href\":\"https://api.sandbox.paypal.com/v2/checkout/orders/6228679386313224F\",\"rel\":\"self\",\"method\":\"GET\"}]}');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `iddanhgia` int(11) NOT NULL,
  `idsanpham` int(11) NOT NULL,
  `idnguoidung` int(11) NOT NULL,
  `sosao` int(11) NOT NULL,
  `noidung` text NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`iddanhgia`, `idsanpham`, `idnguoidung`, `sosao`, `noidung`, `ngaytao`, `trangthai`) VALUES
(1, 28, 17, 5, 'Đẹp dữ vậy trời', '2025-04-14 18:52:23', 1),
(2, 43, 17, 4, 'đẹp dữ trời, có bán thiếu không shop', '2025-04-14 19:21:15', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `iddanhmuc` int(11) NOT NULL,
  `tendanhmuc` text NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`iddanhmuc`, `tendanhmuc`, `ngaytao`, `trangthai`) VALUES
(6, 'HUBLOT', '2025-04-01 02:00:29', 1),
(7, 'ROLEX', '2025-04-01 02:00:38', 1),
(8, 'PATEK PHILIPPE', '2025-04-13 15:15:13', 1),
(9, 'CHOPARD', '2025-04-13 15:13:42', 1),
(10, 'AUDEMARS PIGUET', '2025-04-13 15:15:55', 1),
(11, 'RICHARD MILLE', '2025-04-13 15:18:10', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `iddonhang` int(11) NOT NULL,
  `idnguoidung` int(11) NOT NULL,
  `tennguoidat` text NOT NULL,
  `ngaydat` datetime DEFAULT current_timestamp(),
  `trangthai` text NOT NULL,
  `tongtien` bigint(20) NOT NULL,
  `diachigiao` text NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `phuongthuctt` varchar(50) NOT NULL,
  `idthanhtoan` int(11) NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`iddonhang`, `idnguoidung`, `tennguoidat`, `ngaydat`, `trangthai`, `tongtien`, `diachigiao`, `sdt`, `phuongthuctt`, `idthanhtoan`, `ngaytao`) VALUES
(60, 17, 'Hoài Phong', '2025-04-14 02:49:45', 'Hoàn thành', 450030000, 'aa, Phường Him Lam, Thành phố Điện Biên Phủ, Tỉnh Điện Biên', '0329476944', 'cod', 26, '2025-04-14 02:55:04'),
(61, 13, 'Tân', '2025-04-14 09:56:51', 'Đã xác nhận', 1304030000, 'Tiệm Bia Nhựt, Xã Kim Sơn, Huyện Châu Thành, Tỉnh Tiền Giang', '0329476912', 'cod', 27, '2025-04-14 02:58:16'),
(62, 13, 'Minh Đạt', '2025-04-14 09:57:58', 'Hoàn thành', 889030000, 'Gần tiệm bia Nhựt, Xã Kim Sơn, Huyện Châu Thành, Tỉnh Tiền Giang', '0329476901', 'cod', 28, '2025-04-15 05:15:21'),
(63, 17, 'Phong', '2025-04-15 00:18:20', 'Hoàn thành', 900030000, 'Kiên giang, Xã Đồng Văn, Huyện Yên Lạc, Tỉnh Vĩnh Phúc', '0923244111', 'cod', 29, '2025-04-15 05:15:14'),
(64, 17, 'Phong', '2025-04-15 00:18:24', 'Hoàn thành', 900030000, 'Kiên giang, Xã Đồng Văn, Huyện Yên Lạc, Tỉnh Vĩnh Phúc', '0923244111', 'cod', 30, '2025-04-15 05:15:09'),
(65, 17, 'Phong', '2025-04-15 00:39:29', 'Hoàn thành', 652030000, 'Kiên giang, Xã Mão Điền, Thị xã Thuận Thành, Tỉnh Bắc Ninh', '1234567890', 'cod', 31, '2025-04-15 05:15:27'),
(66, 17, 'Phong', '2025-04-15 00:41:51', 'Chờ xác nhận', 352030000, 'Kiên giang, Xã Thống Nhất, Huyện Gia Lộc, Tỉnh Hải Dương', '1234567890', 'cod', 32, '2025-04-14 17:41:51'),
(67, 17, 'Phong', '2025-04-15 00:43:03', 'Hoàn thành', 889030000, 'Kiên giang, Xã Vũ Di, Huyện Vĩnh Tường, Tỉnh Vĩnh Phúc', '1234567890', 'cod', 33, '2025-04-15 05:15:04'),
(68, 17, 'ấdđ', '2025-04-15 00:45:02', 'Hoàn thành', 352030000, 'Kiên giang, Xã Hùng Thắng, Huyện Bình Giang, Tỉnh Hải Dương', '1234567890', 'cod', 34, '2025-04-15 05:14:56'),
(69, 17, 'alo', '2025-04-15 00:47:23', 'Chờ xác nhận', 889030000, 'Kiên giang, Xã Đồng Cương, Huyện Yên Lạc, Tỉnh Vĩnh Phúc', '1234567890', 'cod', 35, '2025-04-14 17:47:23'),
(70, 17, 'Phong', '2025-04-15 01:55:16', 'Hoàn thành', 3520030000, 'Kiên giang, Xã Hiền Quan, Huyện Tam Nông, Tỉnh Phú Thọ', '1234567890', 'cod', 36, '2025-04-15 05:15:00'),
(71, 17, 'Phong', '2025-04-15 02:19:07', 'Hoàn thành', 3020030000, 'Kiên giang, Thị trấn Lâm Thao, Huyện Lâm Thao, Tỉnh Phú Thọ', '1234567890', 'cod', 37, '2025-04-15 05:14:52'),
(72, 17, 'Phong', '2025-04-15 02:39:28', 'Hoàn thành', 1230030000, 'Kiên giang, Thị trấn Đạo Đức, Huyện Bình Xuyên, Tỉnh Vĩnh Phúc', '1234567890', 'cod', 38, '2025-04-15 05:14:49'),
(73, 17, 'Phong', '2025-04-15 02:42:35', 'Hoàn thành', 1230030000, 'Kiên giang, Thị trấn Hữu Lũng, Huyện Hữu Lũng, Tỉnh Lạng Sơn', '1234567890', 'cod', 39, '2025-04-15 05:14:45'),
(75, 13, 'ầdf', '2025-04-17 22:26:35', 'Chờ xác nhận', 652030000, 'ầdsf, Xã Vô Điếm, Huyện Bắc Quang, Tỉnh Hà Giang', '1234567890', 'cod', 41, '2025-04-17 15:26:35'),
(76, 13, 'sdgg', '2025-04-17 22:42:29', 'Chuẩn bị đơn', 652030000, 'ưqầ, Xã Nam Quang, Huyện Bảo Lâm, Tỉnh Cao Bằng', '1234567890', 'paypal', 42, '2025-04-17 16:32:57'),
(77, 13, 'sdgg', '2025-04-17 22:43:15', 'Chuẩn bị đơn', 652030000, 'ưqầ, Xã Nam Quang, Huyện Bảo Lâm, Tỉnh Cao Bằng', '1234567890', 'paypal', 43, '2025-04-17 16:33:01'),
(78, 13, 'phong củi', '2025-04-17 22:48:23', 'Chờ xác nhận', 682000, 'sdgsểy, Xã Thanh Lâm, Huyện Ba Chẽ, Tỉnh Quảng Ninh', '1234567890', 'paypal', 44, '2025-04-17 15:48:23'),
(79, 13, 'sdgfsdgf', '2025-04-17 22:49:55', 'Đã xác nhận', 682000, 'dfg, Xã Thượng Hà, Huyện Bảo Lạc, Tỉnh Cao Bằng', '1234567890', 'paypal', 45, '2025-04-17 16:34:37'),
(80, 13, 'sdfg', '2025-04-17 23:07:39', 'Đã thanh toán', 682000, 'dfsdfs, Xã Vô Điếm, Huyện Bắc Quang, Tỉnh Hà Giang', '1234567890', 'paypal', 46, '2025-04-17 16:07:39'),
(81, 13, 'gdfg', '2025-04-17 23:30:04', 'Chuẩn bị đơn', 1334000, 'dsgsdfg, Xã Phìn Hồ, Huyện Sìn Hồ, Tỉnh Lai Châu', '1234567890', 'paypal', 47, '2025-04-17 16:33:03'),
(82, 13, 'dfghdsfgh', '2025-04-17 23:42:32', 'Chờ xác nhận', 1334000, 'dsfsdg, Xã Tân Bình, Huyện Đầm Hà, Tỉnh Quảng Ninh', '1234567890', 'cod', 48, '2025-04-17 16:42:32'),
(83, 13, 'dfghdsfgh', '2025-04-17 23:43:46', 'Chuẩn bị đơn', 1334000, 'dsfsdg, Xã Tân Bình, Huyện Đầm Hà, Tỉnh Quảng Ninh', '1234567890', 'paypal', 49, '2025-04-17 16:43:46'),
(84, 13, 'dfhgdfh', '2025-04-17 23:47:41', 'Chờ xác nhận', 682000, 'dfsd, Xã Nấm Dẩn, Huyện Xín Mần, Tỉnh Hà Giang', '1234567890', 'cod', 50, '2025-04-17 16:47:41'),
(85, 13, 'àá', '2025-04-17 23:57:00', 'Chờ xác nhận', 682000, 'âf, Xã Vô Điếm, Huyện Bắc Quang, Tỉnh Hà Giang', '1234567890', 'cod', 51, '2025-04-17 16:57:00'),
(86, 13, 'vsdgv', '2025-04-17 23:58:18', 'Chuẩn bị đơn', 682000, 'adfsdgfsd, Xã Thái Long, Thành phố Tuyên Quang, Tỉnh Tuyên Quang', '1234567890', 'paypal', 52, '2025-04-17 16:58:18'),
(87, 13, 'Hoài Phong', '2025-04-18 00:07:00', 'Chuẩn bị đơn', 889030000, 'Kiên giang, Xã Quảng Chính, Huyện Hải Hà, Tỉnh Quảng Ninh', '0329476855', 'paypal', 53, '2025-04-17 17:07:00'),
(88, 13, 'áa', '2025-04-18 00:09:12', 'Chuẩn bị đơn', 305030000, 'aaa, Xã Ngọc Thanh, Thành phố Phúc Yên, Tỉnh Vĩnh Phúc', '1234567890', 'paypal', 54, '2025-04-17 17:09:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `idgiohang` int(11) NOT NULL,
  `idnguoidung` int(11) NOT NULL,
  `tongtien` bigint(20) DEFAULT 0,
  `ngaycapnhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`idgiohang`, `idnguoidung`, `tongtien`, `ngaycapnhat`, `ngaytao`) VALUES
(6, 13, 0, '2025-04-07 15:09:38', '2025-04-07 08:09:38'),
(7, 17, 0, '2025-04-14 02:49:07', '2025-04-13 19:49:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gioithieu`
--

CREATE TABLE `gioithieu` (
  `id` int(11) NOT NULL,
  `noi_dung_html` longtext NOT NULL,
  `noi_dung_text` text NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gioithieu`
--

INSERT INTO `gioithieu` (`id`, `noi_dung_html`, `noi_dung_text`, `ngaytao`, `trangthai`) VALUES
(1, '', 'Giới thiệu về Watch Shop Luxury\r\n\r\nChủ shop: Trần Hoài Phong\r\nĐịa chỉ:  Số 68, Phường 2, Thành phố Vĩnh Long\r\nEmail: watchshopluxury68@gmail.com\r\nSố điện thoại: 0901 686 123\r\nNgày thành lập: 12/07/2023\r\n\r\nHành trình từ đam mê đến đẳng cấp\r\n\r\nWatch Shop Luxury ra đời từ niềm đam mê sâu sắc với nghệ thuật chế tác đồng hồ – nơi kỹ thuật đỉnh cao hòa quyện cùng tinh thần tinh tế của thời gian. Chúng tôi không chỉ là nơi mua bán đồng hồ, mà là nơi kết nối giữa các tín đồ yêu thích phong cách sống thượng lưu với những biểu tượng thời gian đến từ Thụy Sĩ, Đức, Nhật và nhiều quốc gia khác.\r\n\r\nTrong suốt hành trình từ năm 2023 đến nay, Watch Shop Luxury đã từng bước khẳng định vị thế là một trong những cửa hàng đồng hồ cao cấp được tin tưởng và lựa chọn bởi giới doanh nhân, nghệ sĩ, người nổi tiếng và các nhà sưu tầm khắt khe nhất.\r\n\r\nChứng nhận & Đối tác uy tín\r\n\r\nAuthorized Luxury Watch Retailer – Đại lý bán lẻ chính thức của nhiều thương hiệu nổi tiếng\r\nSwiss Watch Certification – Chứng nhận nhập khẩu và phân phối đồng hồ Thụy Sĩ chính hãng\r\nBảo chứng xác thực từ WatchCert – Đối tác kiểm định quốc tế hàng đầu\r\nChứng nhận Đơn vị Kinh doanh Uy tín 2024 – Vietnam Trusted Brand\r\nThành viên của Hiệp hội Đồng hồ Việt Nam (VAWA)\r\nĐối tác chiến lược của các showroom quốc tế tại Singapore, Dubai, Tokyo, Geneva\r\nThế mạnh của chúng tôi\r\n\r\nSản phẩm độc quyền & phiên bản giới hạn: Luôn cập nhật các mẫu limited, sản phẩm hiếm, đấu giá độc quyền.\r\n100% hàng chính hãng: Có đầy đủ hộp, sổ, thẻ bảo hành điện tử toàn cầu.\r\nChính sách bảo hành minh bạch: Bảo hành từ 2 đến 5 năm tùy từng thương hiệu, hỗ trợ trọn đời sau mua.\r\nTư vấn chuyên sâu: Đội ngũ chuyên viên được đào tạo về lịch sử đồng hồ, kỹ thuật máy cơ và phong cách cá nhân hóa.\r\nHậu mãi đẳng cấp: Vệ sinh miễn phí, hỗ trợ đổi mẫu, kiểm tra định kỳ miễn phí suốt vòng đời sản phẩm.\r\nDịch vụ săn đồng hồ toàn cầu: Bạn muốn một mẫu đã ngừng sản xuất? Hãy để chúng tôi tìm giúp bạn.\r\nThanh toán linh hoạt: Chấp nhận chuyển khoản, tiền mặt, thanh toán qua thẻ tín dụng, hỗ trợ trả góp 0%.\r\nKhông gian trưng bày sang trọng: Mỗi khách hàng đều được trải nghiệm không gian boutique theo tiêu chuẩn châu Âu – riêng tư, đẳng cấp, và tinh tế.\r\nCam kết của chúng tôi\r\nTại Watch Shop Luxury, chúng tôi cam kết mang đến cho khách hàng không chỉ là một chiếc đồng hồ, mà là một tác phẩm nghệ thuật đỉnh cao, đại diện cho đẳng cấp, cá tính và thời gian. Với sự minh bạch, uy tín và tận tâm, chúng tôi tự hào là người bạn đồng hành của những quý ông và quý cô biết trân trọng giá trị thật.', '2025-04-17 06:00:56', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinhanhsanpham`
--

CREATE TABLE `hinhanhsanpham` (
  `idhinhanh` int(11) NOT NULL,
  `idsanpham` int(11) NOT NULL,
  `duongdan` text NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hinhanhsanpham`
--

INSERT INTO `hinhanhsanpham` (`idhinhanh`, `idsanpham`, `duongdan`, `ngaytao`) VALUES
(44, 32, 'imageproduct/1744563382_additional_341-px-130-rx-114.jpg', '2025-04-13 16:56:22'),
(45, 32, 'imageproduct/1744563382_additional_341-px-130-rx-114-1.jpg', '2025-04-13 16:56:22'),
(49, 36, 'imageproduct/1744570220_additional_92939004-254138282299947-3161462900451704832-n.jpg', '2025-04-13 18:50:20'),
(52, 28, 'imageproduct/1744572814_additional_126710blro-2.jpeg', '2025-04-13 19:33:34'),
(53, 28, 'imageproduct/1744572814_additional_126710blro-3.jpeg', '2025-04-13 19:33:34'),
(54, 37, 'imageproduct/1744573300_additional_z4255282964282_e0b0ada0da36aaf4b7f19c086ebc06f9.jpg', '2025-04-13 19:41:40'),
(55, 37, 'imageproduct/1744573300_additional_z4255282986743_ca43dd8d811592f3d3d246ccf6f75ff8.jpg', '2025-04-13 19:41:40'),
(56, 37, 'imageproduct/1744573300_additional_z4255283000304_b42c2c7d9d9028f726f74d7e92915edd.jpg', '2025-04-13 19:41:40'),
(57, 39, 'imageproduct/1744600182_additional_patek_philippe_nautilus_5712-1a-001.jpg', '2025-04-14 03:09:42'),
(58, 39, 'imageproduct/1744600182_additional_patek_philippe_nautilus_5712-1a-001_1.jpg', '2025-04-14 03:09:42'),
(59, 40, 'imageproduct/1744600403_additional_patek_philippe_aquanaut_5068r-010_1.jpg', '2025-04-14 03:13:23'),
(60, 41, 'imageproduct/1744600613_additional_patek_philippe_5205r-001_complications_annual_calendar.jpg', '2025-04-14 03:16:53'),
(61, 43, 'imageproduct/1744601226_additional_323551367-1810538599304923-3219505951910868893-n.jpg', '2025-04-14 03:27:06'),
(62, 43, 'imageproduct/1744601226_additional_347395586-998546691301799-1786115975192945110-n.jpg', '2025-04-14 03:27:06'),
(63, 46, 'imageproduct/1744661473_additional_67653bc-gg-1263bc-02-1.jpg', '2025-04-14 20:11:13'),
(65, 46, 'imageproduct/1744661473_additional_67653bc-gg-1263bc-02-5.jpg', '2025-04-14 20:11:13'),
(66, 47, 'imageproduct/1744661757_additional_310816792-394049289603347-7847131195089127046-n.jpg', '2025-04-14 20:15:57'),
(67, 47, 'imageproduct/1744661757_additional_310966847-394049216270021-3529324264124046537-n.jpg', '2025-04-14 20:15:57'),
(68, 47, 'imageproduct/1744661757_additional_311009029-394049269603349-1238715380752357532-n.jpg', '2025-04-14 20:15:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `idkhuyenmai` int(11) NOT NULL,
  `tenkhuyenmai` text NOT NULL,
  `gia_giam` bigint(20) NOT NULL,
  `ngaybatdau` datetime NOT NULL,
  `ngayketthuc` datetime NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyenmai`
--

INSERT INTO `khuyenmai` (`idkhuyenmai`, `tenkhuyenmai`, `gia_giam`, `ngaybatdau`, `ngayketthuc`, `ngaytao`) VALUES
(6, 'Mùa hè rực rỡ', 1000000, '2025-04-07 10:05:16', '2025-04-18 15:05:16', '2025-04-13 19:47:43'),
(7, 'giảm chơi', 2000000, '2025-04-07 05:52:26', '2025-04-18 10:52:26', '2025-04-13 19:47:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaiday`
--

CREATE TABLE `loaiday` (
  `id_loai_day` int(11) NOT NULL,
  `ten_loai_day` text NOT NULL,
  `mo_ta_loai_day` text NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaiday`
--

INSERT INTO `loaiday` (`id_loai_day`, `ten_loai_day`, `mo_ta_loai_day`, `ngay_tao`, `trangthai`) VALUES
(1, 'Dây đeo Jubilee', '', '2025-04-13 16:53:12', 1),
(3, 'Dây đeo cao su', '', '2025-04-13 16:53:04', 1),
(4, 'Platinum', '', '2025-04-14 19:37:11', 1),
(5, 'Dây đeo da cá sấu', '', '2025-04-13 19:28:09', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaimay`
--

CREATE TABLE `loaimay` (
  `id_loai_may` int(11) NOT NULL,
  `ten_loai_may` text NOT NULL,
  `mo_ta_loai_may` text NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaimay`
--

INSERT INTO `loaimay` (`id_loai_may`, `ten_loai_may`, `mo_ta_loai_may`, `ngay_tao`, `trangthai`) VALUES
(1, 'Automatic - Caliber 2236', '', '2025-04-13 15:39:47', 1),
(2, 'Calibre 2236, Manufacture Rolex', '', '2025-04-14 19:38:35', 1),
(3, 'halbertec motorsdasd', 'sd', '2025-04-14 19:39:00', 0),
(4, 'Automatic - HUB4300', '', '2025-04-13 16:52:44', 1),
(5, 'Automatic 3235', '', '2025-04-14 19:38:39', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `idnguoidung` int(11) NOT NULL,
  `tendangnhap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `matkhau` varchar(50) NOT NULL,
  `ngaytao` timestamp NULL DEFAULT current_timestamp(),
  `role` int(11) NOT NULL,
  `cccd` varchar(20) NOT NULL,
  `trangthai` int(1) NOT NULL,
  `hoten` varchar(50) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`idnguoidung`, `tendangnhap`, `email`, `sdt`, `matkhau`, `ngaytao`, `role`, `cccd`, `trangthai`, `hoten`, `reset_token`, `reset_token_expiry`) VALUES
(13, 'dat09', 'phanminhthang321@gmail.com', '', 'e10adc3949ba59abbe56e057f20f883e', '2025-04-04 17:23:06', 0, '', 1, '', NULL, NULL),
(15, 'nhân viên', 'nv@gmail.com', '', 'e10adc3949ba59abbe56e057f20f883e', '2025-04-12 06:13:13', 1, '', 1, '', NULL, NULL),
(16, 'admin', 'admin1@gmail.com', '', 'e10adc3949ba59abbe56e057f20f883e', '2025-04-13 05:25:12', 2, '', 1, '', '8f1ae7ab7859b9719ac10b738139b774936865a96d0b88a043ec17b966f22471837958498d2b031afe94a741e644431ba471', '2025-04-15 07:53:18'),
(17, 'hoaiphong', 'afongminhluong@gmail.com', '', 'e10adc3949ba59abbe56e057f20f883e', '2025-04-13 16:04:37', 0, '', 1, '', 'd5d27b9a570bddb6929e710e734750f09ad63e005245cac765d046d6a002928f374a2c19416458d608caf2dfdc8a77d1a10b', '2025-04-15 07:52:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `idnhacungcap` int(11) NOT NULL,
  `tennhacungcap` text NOT NULL,
  `diachi` text NOT NULL,
  `sdt` varchar(15) NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhacungcap`
--

INSERT INTO `nhacungcap` (`idnhacungcap`, `tennhacungcap`, `diachi`, `sdt`, `ngaytao`) VALUES
(1, 'Minh Đạt Watch', 'Tiền Giang 63', '0329476321', '2025-04-13 18:51:40'),
(2, 'Hoài Phong Watch', 'Kiên Giang', '0329478655', '2025-04-13 18:51:04'),
(3, 'Anh Bắc', 'Cần thơ gần vĩnh long', '0329473211', '2025-04-13 18:52:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `idsanpham` int(11) NOT NULL,
  `tensanpham` text NOT NULL,
  `mota` text DEFAULT NULL,
  `giaban` bigint(20) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 0,
  `iddanhmuc` int(11) DEFAULT NULL,
  `idkhuyenmai` int(11) DEFAULT NULL,
  `gianhap` bigint(20) NOT NULL,
  `idnhacungcap` int(11) NOT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bosuutap` text NOT NULL,
  `loaimay` int(11) NOT NULL,
  `chatlieuvo` text NOT NULL,
  `loaiday` int(11) NOT NULL,
  `matkinh` text NOT NULL,
  `mausac` text NOT NULL,
  `kichthuoc` text NOT NULL,
  `doday` text NOT NULL,
  `chongnuoc` text NOT NULL,
  `tinhnangdacbiet` text NOT NULL,
  `chinhsachbaohanh` int(11) NOT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT 1,
  `path_anh_goc` text NOT NULL,
  `gioitinh` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`idsanpham`, `tensanpham`, `mota`, `giaban`, `soluong`, `iddanhmuc`, `idkhuyenmai`, `gianhap`, `idnhacungcap`, `ngaytao`, `bosuutap`, `loaimay`, `chatlieuvo`, `loaiday`, `matkinh`, `mausac`, `kichthuoc`, `doday`, `chongnuoc`, `tinhnangdacbiet`, `chinhsachbaohanh`, `trangthai`, `path_anh_goc`, `gioitinh`) VALUES
(27, ' Hublot Big Bang One Click King Gold White Pave ', 'Trẻ trung và hiện đại, đồng hồ Hublot Big Bang One Click King Gold White Pave 33mm không chỉ là công cụ hữu ích trong việc quản lí thời gian mà còn là một món đồ phụ kiện trang sức tô điểm cho phong cách của các quý cô.', 500000000, 10, 6, NULL, 450000000, 2, '2025-04-13 19:04:19', 'Big Bang', 4, 'Vàng hồng 18k, Kim cương', 3, 'Sapphire', 'Trắng', '33', '10', '10', '', 1, 1, 'imageproduct/1744571047_dong-ho-hublot-big-bang-one-click-king-gold-white-pave-33mm-485-oe-2210-rw-1604.png', 'Nữ'),
(28, 'Rolex GMT-Master II 126710BLRO', 'Đem đến Baselworld 2018, Rolex trang bị cho chiếc GMT-Master II số hiệu 126710BLRO bộ vỏ khung kích thước 40mm, nhưng trong chất liệu Oystersteel, cách Rolex gọi tên thép 904L của hãng.', 1400000000, 4, 7, NULL, 1350000000, 1, '2025-04-13 20:15:58', 'GMT-Master II', 1, 'Thép 904L', 1, 'Sapphire', 'Trắng', '40', '15', '10', '', 2, 1, 'imageproduct/1744572814_126710blro.jpeg', 'Nam'),
(29, 'Hublot Big Bang Integrated King Gold Rainbow', 'Những chiếc đồng hồ cực kỳ cao cấp theo chủ đề rainbow (cầu vồng) đang là một trong nhiều xu hướng hiện hành.', 890000000, 5, 6, 6, 860000000, 1, '2025-04-17 17:07:00', 'Big Bang', 1, 'Vàng hồng 18k', 1, 'Sapphire', 'Rainbow', '42', '8', '10', '', 1, 1, 'imageproduct/1744571368_dong-ho-hublot-big-bang-integrated-king-gold-rainbow-42mm-451-ox-1180-ox-3999.png', 'Unisex'),
(30, 'Hublot Classic Fusion King Gold ', 'Với hình ảnh đặc trưng là những chiếc đồng hồ khoẻ khoắn, cứng cáp, cá tính nhưng vẫn toát lên sự thanh lịch, cổ điển, một chiếc Hublot Classic Fusion King Gold 45 chắc chắn sẽ không làm bạn thất vọng. Mặt số hút mắt màu đen của bầu trời đêm huyền ảo, từ những chiếc kim cho đến cọc số đều được chế tạo từ chất liệu vàng King Gold cao cấp với từng góc cạnh được thiết kế mềm mại, bóng bẩy. ', 450000000, 16, 6, NULL, 420000000, 1, '2025-04-13 19:49:45', 'Classic Fusion ', 4, '18k King Gold', 5, 'Sapphire', 'Đen vàng', '45', '15', '5', '', 1, 1, 'imageproduct/1744572472_hublot-classic-fusion-king-gold-45mm.png', 'Unisex'),
(31, 'Rolex Datejust 31 278381rbr-0006', 'Rolex Datejust 278381rbr-0006 là một trong những mẫu đồng hồ sang trọng dành cho phái đẹp. Mặt số màu nâu đầy sang trọng. Trái tim đồng hồ là bộ máy tự lên cót, cung cấp khả năng trữ năng lượng trong khoảng 55 giờ.', 768000000, 10, 7, NULL, 720000000, 3, '2025-04-13 19:08:39', 'Datejust', 1, 'Thép, vàng Everose 18K, Kim cương', 1, 'Sapphire', 'jstsj', '31', '15', '10', '', 1, 1, 'imageproduct/1744571319_278381rbr-0006.jpg', 'Nam'),
(32, 'Hublot Big Bang King Gold Chronograph ', 'Dáng vẻ khỏe khoắn, chất liệu độc đáo là những gì giới mộ điệu đánh giá cao về những mẫu đồng hồ Hublot trong vài năm trở lại gần đây. Với tuyên ngôn “Art of Fusion”, từng thiết kế có trong mỗi bộ sưu tập của Hublot là sự lồng ghép của hai yếu tố cổ điển và hiện đại, đi từ chất liệu đến màu sắc. ', 652000, 19, 6, NULL, 520000, 1, '2025-04-17 16:58:18', 'Big bang', 4, 'Vàng hồng 18K', 3, 'Sapphire', 'Đen vàng', '41', '9.3', '10', 'mạnh lắm', 1, 1, 'imageproduct/1744563382_big-bang-king-gold-chronograph-diamonds-41mm-341-px-130-rx-114.png', 'Unisex'),
(35, 'Patek Philippe Nautilus 5712/1R-001', 'Patek Philippe Nautilus 5712/1R-001 là phiên bản được ra mắt cùng thời điểm với Nautilus 5811/1G-001, cỗ máy tạo dấu ấn mạnh mẽ ngay lần chạm mắt đầu tiên và là sự bổ sung cần thiết mà ngành công nghiệp đồng hồ đang cần.', 1230000000, 10, 8, NULL, 1200000000, 2, '2025-04-14 19:42:35', ' Nautilus', 4, 'Vàng hồng 18k', 4, 'Sapphire', 'Vàng', '40', '9', '6', '', 1, 1, 'imageproduct/1744571591_patek-philippe-nautilus-5712-1r-001.png', 'Nam'),
(36, 'Richard Mille RM 030 Carbon NTPT Ultimate Edition', '', 900000000, 8, 11, NULL, 850000000, 1, '2025-04-13 19:44:43', 'RM 30-01', 1, 'Carbon', 3, 'Sapphire', 'Đen', '42', '17', '10', '', 1, 1, 'imageproduct/1744570164_rm-35-02.png', 'Nam'),
(37, 'Rolex Datejust Wimbledon ', 'Đối với thương hiệu Rolex thì dòng Datejust từ trước đến nay vẫn được biết đến như một thiết kế đơn giản, thanh lịch và dễ phối đồ. Đồng hồ Rolex Datejust 36mm 126234 Mặt số Wimbledon sở hữu có một mặt số với tông màu kỳ lạ, có thể làm cho nhiều người cảm thấy không hợp mắt. Trên nền mặt số xám bạc chải tia, các mốc giờ được thiết kế với màu đen và viền xanh lá cây. Quả thật, nếu chỉ nhìn trên ảnh thì nhiều người sẽ khẳng định rằng đây là một thiết kế xấu, không xứng đáng với một thương hiệu như Rolex.', 352000000, 10, 7, NULL, 322000000, 2, '2025-04-14 19:18:12', 'Datejust', 1, 'Thép, niềng vàng trắng 18k', 1, 'Sapphire', 'Trắng', '36', '15', '10', '', 1, 1, 'imageproduct/1744573300_avr-3.jpg', 'Nam'),
(38, 'Rolex Datejust 126334 Navy', '', 305000000, 5, 7, NULL, 290000000, 2, '2025-04-17 17:09:12', 'Datejust', 5, 'Thép, niềng vàng trắng 18k', 1, 'Sapphire', 'Trắng', '36', '15', '10', '', 2, 1, 'imageproduct/1744599951_dong-ho-rolex-datejust-126334-mat-xanh-navy.png', 'Nam'),
(39, 'Patek Philippe Nautilus 5712/1A-001', 'Nautilus 5712/1A-001 được sáng tạo và chế tác bởi nghệ nhân Gerald Genta. Ông là cha đẻ của rất nhiều mẫu đồng hồ bạc tỷ trên thế giới. Không dễ gì để sở hữu được một “đứa con tinh thần” của Gerald Genta. Được đeo trên tay chiếc đồng hồ là kết tinh tâm huyết, trí tuệ và công sức của Gerald Genta trở thành nỗi thèm khát của bất kỳ tín đồ nào.', 3020000000, 4, 8, NULL, 3020000000, 1, '2025-04-14 19:19:07', 'Nautilus', 5, 'Stainless Steel', 4, 'Sapphire ', 'Trắng', '40', '15', '5', '', 2, 1, 'imageproduct/1744600182_patek-philippe-nautilus-5712-1a-001.png', 'Nam'),
(40, 'Patek Philippe Aquanaut 5068R-010', '', 920000000, 4, 8, NULL, 900000000, 3, '2025-04-14 03:13:23', 'Nautilus', 5, 'Vàng hồng 18k', 3, 'Sapphire', 'Trắng', '35,6', '10', '5', '', 2, 1, 'imageproduct/1744600403_patek_philippe_aquanaut_5068r-010.png', 'Nữ'),
(41, 'Patek Philippe 5205R-001 Annual Calendar', 'Patek Philippe 5205R-001 Complications Annual Calendar là chiếc đồng hồ tuyệt đẹp của dòng Complications trứ danh, được giới siêu giàu trên khắp thế giới săn đón gắt gao từ khi mới ra mắt. Mặt số trắng cùng bộ kim vàng hồng tỏa sáng rực rỡ được ví như “phép màu” hút hồn bất cứ ai quan sát kiệt tác nghệ thuật này đủ lâu', 1240000000, 7, 8, NULL, 1200000000, 3, '2025-04-14 03:16:53', 'Complications', 5, 'Vàng hồng 18k', 5, 'Sapphire', '', '38,5', '10', '5', '', 2, 1, 'imageproduct/1744600613_patek-philippe-complications-5205r-001.png', 'Nam'),
(42, ' Richard Mille RM 30-01', 'Với RM 30-01 Automatic mới trang bị rô tô có thể tự ngắt, thương hiệu đang thể hiện khả năng tạo ra cảm xúc mãnh liệt bắt nguồn từ sự xuất sắc trong chuyên môn kỹ thuật của mình. Việc kiểm tra tỉ mỉ mặt số kép của chiếc đồng hồ kết hợp giữa titanium và sapphire cũng là điểm nhấn tạo nên âm sắc.', 865000000, 3, 11, NULL, 845000000, 1, '2025-04-14 03:22:13', 'RM 30-01', 5, 'Vàng hồng 18k', 3, 'Sapphire', 'Đen', '42', '17.59', '5', '', 2, 1, 'imageproduct/1744600933_richard-mille-rm-30-01.png', 'Unisex'),
(43, ' Richard Mille RM 72-01 Ceramic', 'Đồng hồ RM 72-01 Lifestyle In-House Chronograph được ra mắt vào năm 2020, là chiếc đồng hồ đầu tiên của Richard Mille tự hào sở hữu bộ máy tự động được trang bị chức năng flyback phát triển hoàn toàn in-house. Ban đầu được chế tác với bộ vỏ bằng vàng hoặc titanium, giờ đây chiếc đồng hồ này xuất hiện với bộ vỏ bằng gốm TZP màu đen hoặc ATZ trắng', 900000000, 8, 11, NULL, 870000000, 3, '2025-04-14 03:27:06', 'RM 72-01', 5, 'Vàng,Titanium ', 3, 'Sapphire', 'Trắng', '38', '18', '3', '', 2, 1, 'imageproduct/1744601226_richard-mille-rm-72-01-ceramic.png', 'Unisex'),
(44, 'Richard Mille RM 72-01', 'Kể từ năm 2008, thương hiệu đã chế tạo các phiên bản đặc biệt cho Le Mans Classic, thường có đồng hồ bấm giờ và được thiết kế theo màu sắc chính thức của cuộc đua là xanh lá cây và trắng', 900000000, 3, 11, NULL, 850000000, 2, '2025-04-14 17:18:24', 'RM 72-01', 1, 'Quartz TPT', 3, 'Sapphire', 'xanh lá', '38', '11', '5', '', 2, 1, 'imageproduct/1744601380_dong-ho-richard-mille-rm-72-01.png', 'Unisex'),
(46, 'Audemars Piguet Royal Oak Frosted Gold', 'Mẫu đồng hồ Audemars Piguet Royal Oak mang mã hiệu 67653BC.GG.1263BC.02 chính là sự lựa chọn hoàn hảo cho phái đẹp với vẻ ngoài ấn tượng, độ dày chỉ 7mm thanh mảnh trên cổ tay đem lại sự thoải mái và vẻ đẹp vượt thời gian.', 2250000000, 8, 10, NULL, 2050000000, 2, '2025-04-14 20:27:37', 'Royal Oak', 5, 'Thép, niềng vàng trắng 18k', 4, 'Sapphire', 'Trắng', '33', '7', '5', '', 2, 1, 'imageproduct/1744661473_audemars-piguet-royal-oak-frosted-gold-67653bc-gg-1263bc-02.png', 'Nam'),
(47, 'Audemars Piguet Royal Oak Selfwinding Chronograph 50th Anniversary', 'Ra mắt vào năm 2022 nhân dịp kỷ niệm 50 năm sưu tập đồng hồ Royal Oak mang tính biểu tượng, Audemars Piguet Royal Oak Selfwinding Chronograph 50th Anniversary 41mm giữ nhịp là một chiếc đồng hồ thể thao sang trọng với các quy tắc thẩm mỹ giống như chiếc Royal Oak ban đầu do Gérald Genta thiết kế, đồng thời cũng được cải tiến về thiết kế vỏ, dây đeo và mặt số, làm tăng thêm sức hấp dẫn đương đại của bộ sưu tập cũng như tăng sức hút khi ngự trị rên cổ tay các vị chủ nhân.', 1128000000, 8, 10, NULL, 1108000000, 3, '2025-04-14 20:15:57', 'Royal Oak', 5, 'Vàng hồng 18K', 4, 'Sapphire', 'Đen vàng', '41', '8', '5', '', 2, 1, 'imageproduct/1744661757_audemars-piguet-royal-oak-selfwinding-chronograph-50th-anniversary-41mm-26240or-oo-1320or-02.png', 'Nam'),
(48, 'Audemars Piguet Royal Oak Selfwinding', 'Audemars Piguet Royal Oak Selfwinding 37mm Ref. 15551BC.ZZ.1356BC.01 là chiếc đồng hồ thể thao, khoẻ khoắn và vô cùng sang trọng. Được cho ra mắt vào năm 2023, đây là một phiên bản bổ sung thú vị cho bộ sưu tập Royal Oak với thiết kế bát giác mang tính biểu tượng cũng như mặt số “Grande Tapisserie” màu xanh lam vô cùng ấn tượng.', 1340000000, 6, 10, NULL, 1300000000, 1, '2025-04-14 20:21:02', 'Royal Oak', 5, 'Vàng trắng 18k, Kim cương cắt baguette', 4, 'Sapphire', 'Trắng', '37', '7', '5', '', 2, 1, 'imageproduct/1744662062_dong-ho-audemars-piguet-royal-oak-selfwinding-37mm-15551bc-zz-1356bc-01.png', 'Nam'),
(49, 'Audemars Piguet Royal Oak Offshore Selfwinding - Music Edition', 'Audemars Piguet Royal Oak Offshore Selfwinding - Music Edition 37mm được lấy cảm hứng từ thế giới âm nhạc. Mặt số Tapisserie màu xanh lam đính đá quý gợi lên màn hình kỹ thuật số của bộ điều chỉnh âm thanh Equaliser trong phòng thu và các bộ đẩy của nó gợi lên các bộ trộn âm thanh. Đồng hồ được trang bị dây đeo cao su với hệ thống dây đeo có thể hoán đổi cho nhau để tạo thêm nét thể thao đương đại.', 4653000000, 9, 10, NULL, 4603000000, 2, '2025-04-15 04:52:21', 'Royal Oak', 5, 'Vàng trắng 18k', 4, 'Sapphire', 'màu xanh lam', '37', '6', '5', '', 2, 1, 'imageproduct/1744662586_audemars-piguet-royal-oak-offshore-selfwinding-music-edition-37mm-77601bc-yy-d343ca-01.png', 'Nam');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chatbox`
--
ALTER TABLE `chatbox`
  ADD PRIMARY KEY (`idchatbox`),
  ADD KEY `nguoidungid` (`idnguoidung`);

--
-- Chỉ mục cho bảng `chinhsachbaohanh`
--
ALTER TABLE `chinhsachbaohanh`
  ADD PRIMARY KEY (`id_chinh_sach`);

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`idchitietdonhang`),
  ADD KEY `madonhang` (`iddonhang`),
  ADD KEY `masanpham` (`idsanpham`);

--
-- Chỉ mục cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`idchitietgiohang`),
  ADD KEY `magiohang` (`idgiohang`),
  ADD KEY `masanpham` (`idsanpham`);

--
-- Chỉ mục cho bảng `chitietthanhtoan`
--
ALTER TABLE `chitietthanhtoan`
  ADD PRIMARY KEY (`idthanhtoan`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`iddanhgia`),
  ADD KEY `masanpham` (`idsanpham`),
  ADD KEY `manguoidung` (`idnguoidung`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`iddanhmuc`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddonhang`),
  ADD KEY `manguoidung` (`idnguoidung`),
  ADD KEY `idthanhtoan` (`idthanhtoan`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`idgiohang`),
  ADD UNIQUE KEY `manguoidung` (`idnguoidung`);

--
-- Chỉ mục cho bảng `gioithieu`
--
ALTER TABLE `gioithieu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD PRIMARY KEY (`idhinhanh`),
  ADD KEY `masanpham` (`idsanpham`);

--
-- Chỉ mục cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`idkhuyenmai`);

--
-- Chỉ mục cho bảng `loaiday`
--
ALTER TABLE `loaiday`
  ADD PRIMARY KEY (`id_loai_day`);

--
-- Chỉ mục cho bảng `loaimay`
--
ALTER TABLE `loaimay`
  ADD PRIMARY KEY (`id_loai_may`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`idnguoidung`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Chỉ mục cho bảng `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`idnhacungcap`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`idsanpham`),
  ADD KEY `madanhmuc` (`iddanhmuc`),
  ADD KEY `makhuyenmai` (`idkhuyenmai`),
  ADD KEY `nhacungcapid` (`idnhacungcap`),
  ADD KEY `loaimay` (`loaimay`),
  ADD KEY `loaiday` (`loaiday`),
  ADD KEY `chinh_sach_bao_hanh` (`chinhsachbaohanh`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chatbox`
--
ALTER TABLE `chatbox`
  MODIFY `idchatbox` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT cho bảng `chinhsachbaohanh`
--
ALTER TABLE `chinhsachbaohanh`
  MODIFY `id_chinh_sach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `idchitietdonhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `idchitietgiohang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT cho bảng `chitietthanhtoan`
--
ALTER TABLE `chitietthanhtoan`
  MODIFY `idthanhtoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `iddanhgia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `iddanhmuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddonhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `idgiohang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `gioithieu`
--
ALTER TABLE `gioithieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  MODIFY `idhinhanh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `idkhuyenmai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `loaiday`
--
ALTER TABLE `loaiday`
  MODIFY `id_loai_day` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `loaimay`
--
ALTER TABLE `loaimay`
  MODIFY `id_loai_may` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `idnguoidung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `idnhacungcap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `idsanpham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chatbox`
--
ALTER TABLE `chatbox`
  ADD CONSTRAINT `chatbox_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`);

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`iddonhang`) REFERENCES `donhang` (`iddonhang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`idsanpham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `chitietgiohang_ibfk_1` FOREIGN KEY (`idgiohang`) REFERENCES `giohang` (`idgiohang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietgiohang_ibfk_2` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`idsanpham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`idsanpham`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`) ON DELETE CASCADE,
  ADD CONSTRAINT `donhang_ibfk_2` FOREIGN KEY (`idthanhtoan`) REFERENCES `chitietthanhtoan` (`idthanhtoan`);

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hinhanhsanpham`
--
ALTER TABLE `hinhanhsanpham`
  ADD CONSTRAINT `hinhanhsanpham_ibfk_1` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`idsanpham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `chinh_sach_bao_hanh` FOREIGN KEY (`chinhsachbaohanh`) REFERENCES `chinhsachbaohanh` (`id_chinh_sach`),
  ADD CONSTRAINT `loaiday` FOREIGN KEY (`loaiday`) REFERENCES `loaiday` (`id_loai_day`),
  ADD CONSTRAINT `loaimay` FOREIGN KEY (`loaimay`) REFERENCES `loaimay` (`id_loai_may`),
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`iddanhmuc`) REFERENCES `danhmuc` (`iddanhmuc`) ON DELETE SET NULL,
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`idkhuyenmai`) REFERENCES `khuyenmai` (`idkhuyenmai`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `sanpham_ibfk_3` FOREIGN KEY (`idnhacungcap`) REFERENCES `nhacungcap` (`idnhacungcap`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
