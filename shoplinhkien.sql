-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 11:22 AM
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
  `soluong` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`chitietdonhangid`, `madonhang`, `masanpham`, `soluong`, `gia`) VALUES
(1, 1, 2, 8, 465000.00),
(2, 2, 5, 1, 43000.00),
(3, 3, 1, 2, 60000.00),
(4, 4, 12, 1, 139000.00),
(8, 7, 11, 1, 36000.00),
(10, 13, 17, 2, 1000.00),
(11, 13, 18, 1, 42000.00),
(14, 17, 19, 2, 139000.00),
(19, 22, 16, 1, 25000.00),
(21, 24, 16, 1, 25000.00),
(23, 26, 1, 1, 60000.00),
(25, 27, 17, 1, 1000.00),
(26, 28, 19, 1, 139000.00),
(27, 28, 16, 1, 25000.00),
(28, 29, 20, 1, 260000.00),
(30, 30, 19, 1, 139000.00),
(31, 31, 16, 1, 25000.00),
(32, 32, 16, 1, 25000.00),
(33, 33, 11, 1, 36000.00),
(34, 34, 20, 1, 260000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `chitietgiohangid` int(11) NOT NULL,
  `magiohang` int(11) NOT NULL,
  `masanpham` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `gia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`chitietgiohangid`, `magiohang`, `masanpham`, `soluong`, `gia`) VALUES
(10, 2, 11, 2, 0.00),
(25, 1, 12, 1, 0.00);

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
(1, 'Cảm biến'),
(2, 'Board mạch'),
(3, 'Module'),
(4, 'Combo'),
(5, 'Linh kiện rời');

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
(1, 7, 'Nguyễn Văn S', '2025-02-13 16:26:29', 'Chờ xác nhận', 3720000.00, '111b Nguyễn Huệ Phường 2 vĩnh long', '0843029049', 'COD'),
(2, 6, 'Nguyễn Văn B', '2025-02-14 23:24:05', 'Xác nhận đơn', 73000.00, '123', '0914928282', 'BANK'),
(3, 6, 'Nguyễn Văn B', '2025-02-14 23:29:48', 'Chờ xác nhận', 150000.00, '123', '0914928282', 'BANK'),
(4, 6, 'Nguyễn Văn C', '2025-02-15 22:18:53', 'Đang vận chuyển', 169000.00, '123', '0914928283', 'BANK'),
(7, 7, 'Nguyễn Văn X', '2025-02-16 03:38:34', 'Chờ xác nhận', 66000.00, '123', '0914928283', 'CARD'),
(13, 7, 'Nguyễn Văn Z', '2025-02-16 12:41:49', 'Chờ xác nhận', 74000.00, '123', '0914928283', 'COD'),
(17, 7, 'Nguyễn Văn Q', '2025-02-16 12:43:11', 'Chờ xác nhận', 308000.00, '123', '0914928283', 'COD'),
(20, 7, 'Nguyễn Văn A', '2025-02-16 12:51:32', 'Chờ xác nhận', 50000.00, '1234', '0914928283', 'COD'),
(22, 7, 'Nguyễn Văn E', '2025-02-16 12:51:58', 'Chờ xác nhận', 25000.00, '123', '0914928283', 'COD'),
(24, 7, 'Nguyễn Văn R', '2025-02-16 12:53:09', 'Chờ xác nhận', 25000.00, '123', '0914928283', 'COD'),
(26, 7, 'Nguyễn Văn T', '2025-02-16 12:53:40', 'Chờ xác nhận', 60000.00, '123', '0914928283', 'COD'),
(27, 7, 'Nguyễn Văn Y', '2025-02-16 13:50:53', 'Chờ xác nhận', 81000.00, '123', '0914928283', 'CARD'),
(28, 7, 'Nguyễn Văn U', '2025-02-16 13:54:42', 'Đang vận chuyển', 194000.00, '123', '0914928283', 'MOMO'),
(29, 7, 'Nguyễn Văn I', '2025-02-16 19:37:39', 'Chờ xác nhận', 340000.00, '111c', '091727445', 'BANK'),
(30, 7, 'Nguyễn Văn P', '2025-02-16 19:38:41', 'Chờ xác nhận', 169000.00, '444', '09194322121', 'COD'),
(31, 7, 'nguyễn thị W', '2025-02-16 19:45:14', 'Chờ xác nhận', 55000.00, '5555', '09120484393', 'COD'),
(32, 11, 'nguyễn thị s', '2025-02-16 19:54:01', 'Chờ xác nhận', 55000.00, '111', '0912827121', 'COD'),
(33, 12, 'nguyễn văn y', '2025-02-16 23:30:53', 'Xác nhận đơn', 66000.00, '124', '0912827121', 'COD'),
(34, 7, 'Nguyễn Văn P', '2025-03-01 20:05:15', 'Chờ xác nhận', 290000.00, '123', '0914928283', 'COD');

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
(1, 7, 2319000.00, '2025-02-13 15:02:36'),
(2, 6, 0.00, '2025-02-14 23:23:32'),
(3, 12, 0.00, '2025-02-16 23:30:26');

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
(1, 1, 'Arduino LilyPad ATmega328P 16M.jpg'),
(2, 2, 'Arduino Mega 2560 ADK.jpg'),
(3, 3, 'Arduino Nano 3.0 LGT8F328P.jpg'),
(4, 4, 'Arduino Pro Mini 3.3V 8Mhz.jpg'),
(5, 5, 'Arduino Promini LGT328P SSOP20.jpg'),
(6, 10, 'Combo Cân định lượng (Loadcell tự chọn).jpg'),
(7, 13, 'Combo module thực hành công nghệ 8 Cảm biến vật cản.jpg'),
(8, 11, 'Combo module thực hành công nghệ 8 Cảm biến ánh sáng.jpg'),
(9, 8, 'Cảm biến chuyển động radar LD1030 3.3V 10GHz.jpg'),
(10, 7, 'Cảm biến chất lượng không khí MP-135.jpg'),
(11, 6, 'Cảm biến cân nặng Loadcell 200Kg.jpg'),
(12, 9, 'Cảm biến hướng gió RS-FX-I20 4-20mA.jpg'),
(13, 14, 'rời Cáp mạng LAN bấm sẵn hai đầu CAT6 2 mét.jpg'),
(14, 15, 'rời Jack nối LED 3P-10mm 2 đầu 15cm.jpg'),
(15, 17, 'rời Ốc lục giác inox 304 đầu tròn M4x16 (1 con).jpg'),
(16, 18, 'Module 4 Relay 12VDC (Kích mức thấp).jpg'),
(17, 19, 'Module Đồng hồ đo nhiệt độ type K 800 độ C Màu xanh lá.jpg'),
(18, 20, 'Module Màn hình LCD TFT 3.2 inch ILI9341 giao tiếp SPI.jpg'),
(20, 12, 'Combo module thực hành công nghệ 8 Cảm biến độ ẩm đất.jpg'),
(21, 16, 'rời Khớp truyền động khác phương Cardan 3.17mm-3.17mm.jpg');

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

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`khuyenmaiid`, `tenkhuyenmai`, `giatri`, `ngaybatdau`, `ngayketthuc`) VALUES
(3, 'valentine', 50000.00, '2025-02-15 19:25:13', '2025-02-28 01:25:13'),
(4, 'phunuvietnam', 20000.00, '2025-02-18 17:51:00', '2025-02-28 18:52:00'),
(6, 'phu nu viet nam', 20000.00, '2025-03-01 14:05:46', '2025-03-17 20:05:46');

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
  `tonkho` int(11) NOT NULL DEFAULT 0,
  `madanhmuc` int(11) DEFAULT NULL,
  `makhuyenmai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`sanphamid`, `tensanpham`, `mota`, `gia`, `tonkho`, `madanhmuc`, `makhuyenmai`) VALUES
(1, 'Arduino LilyPad ATmega328P', '– SRAM: 1 KB\r\n– EEPROM: 512 bytes\r\n– Tần số thạch anh: 8 MHz\r\n– Điện áp hoạt động: 2.7 – 5.5 V\r\n– Nguồn cấp: 2.7 – 5.5 V\r\n– Dòng DC ở mỗi chân I/O: 40 mA\r\n– Bộ nhớ Flash: 16 KB (2KB sử dụng cho Bootloader)                                                                                                                                ', 40000.00, 20, 2, NULL),
(2, 'Arduino Mega 2560 ADK', 'Arduino Mega 2560 ADK Vi điều khiển chính: ATmega2560, IC nạp và giao tiếp UART: ATmega16U2. Số chân Digital: 54 (15 chân PWM). Số chân Analog: 16', 465000.00, 15, 2, NULL),
(3, 'Arduino Nano 3.0 LGT8F328P\r\n', 'Arduino Nano 3.0 LGT8F328P có thể sử dụng nguồn 3V3 và 5V chạy ở tần số 16M, khả năng tương thích tuyệt vời, EEPROM: 1Kb', 56000.00, 10, 2, NULL),
(4, 'Arduino Pro Mini 3.3V 8Mhz', 'Arduino Pro Mini 3.3V 8Mhz vi điều khiển: ATmega328. Mạch điện áp hoạt động: 3,3V Bộ nhớ flash: 32KB trong đó 2 KB được sử dụng bởi bộ tải khởi động', 50000.00, 20, 2, NULL),
(5, 'Arduino Promini LGT328P ', 'Arduino Promini LGT328P SSOP20 Vi điều khiển: LGT8F328P. Điện áp làm việc: 5V. EEPROM: 1Kb', 43000.00, 15, 2, NULL),
(6, 'Cảm biến cân nặng Loadcell ', '– Loadcell 200kg\n– Phạm vi quá tải an toàn: 120%\n– Phạm vi quá tải tối đa: 150%\n– Trọng lượng: 570g\n– Kích thước chung: 148.5x44x35.6mm (dài x rộng x cao)\n– Chiều dài cable: ~ 1.5 mét\n– Sơ đồ chân:\n+ Cặp nguồn: trắng + đen\n+ Cặp tín hiệu: đỏ + xanh\n+ Dây mass: không bọc gì hoặc màu vàng', 153000.00, 24, 1, NULL),
(7, 'Cảm biến chất lượng không khí ', 'Cảm biến chất lượng không khí MP-135 sử dụng để kiểm tra chất lượng không khí trong môi trường, hoạt động trên nguyên lí thay đổi độ dẫn điện của các lớp vật liệu, nồng độ các chất trong khí càng cao thì độ dẫn điện càng lớn, từ đó biến đổi thành tín hiệu ngõ ra. Cảm biến có độ nhạy cao khả năng phản hồi nhanh, tiêu thụ điện năng thấp, ổn định, tuổi thọ cao, độ nhạy có thể điều chỉnh được bằng biến trở.', 110000.00, 30, 1, NULL),
(8, 'Cảm biến chuyển động radar ', '– Model: LD1030\n– Tần số: 10.525GHz (10.40 ~ 10.65GHz)\n– Điện áp cấp: 3.3 ~ 12VDC (khuyên dùng 5VDC)\n– Dòng tiêu thụ: 16mA Max (VCC =3.3~12VDC)\n– Góc phát hiện: lắp đặt trên trần góc rộng 110°±10°\n– Khoảng cách phát hiện: 2~6 mét (cảm biến phía trước)\n– Điện áp chân OUT: 3.2~ 3.4V\n– Thời gian trễ: 2-5S (có thể tùy chỉnh bằng cách thay đổi điện trở R16)\n– Nhiệt độ làm việc: -20°C ~ +85°C\n– Độ ẩm: 10~95%RH\n– Kích thước: 40x8x2.3mm (dài x rộng x dày)', 28000.00, 40, 1, NULL),
(9, 'Cảm biến hướng gió RS-FX-I20', '– Nguồn cung cấp: 10 VDC – 30 VDC\r\n– Công suất tiêu thụ tối đa: 1.2 W\r\n– Tín hiệu: Dòng 4 ma – 20 ma\r\n– Dải đo: 8 hướng\r\n– Thời gian phản hồi: < 0.5m/s\r\n– Chiều dài cab: 2 mét\r\n– Phụ kiện gồm: 1 cảm biến hướng gió + cab 2 mét + ốc vít', 1200000.00, 50, 1, NULL),
(10, 'Combo Cân định lượng (Loadcell tự chọn)', '– Bộ sản phẩm Combo Cân Định lượng tự ráp\r\n– Điện áp: 5-24VDC\r\n– Dòng làm việc: ~1A\r\n– Màn hình hiển thị: LCD 2004\r\n– Cân tối đa: 20.000g (20kg) (liên hệ shop để tùy chỉnh lên 150kg)\r\n– Độ phân giải: 1g\r\n– Trọng lượng bộ sản phẩm: 1.5kg', 1363000.00, 12, 4, NULL),
(11, 'Combo module thực hành công nghệ 8 Cảm biến ánh sáng', '– Bài tập 1: Cảm biến ánh sáng\r\n– Bài tập 2: Cảm biến nhiệt độ\r\n– Bài tập 3: Cảm biến độ ẩm đất\r\n– Bài tập phụ: Cảm biến vật cản', 86000.00, 5, 4, NULL),
(12, 'Combo module thực hành công nghệ 8 Cảm biến độ ẩm đất', '– Bài tập 1: Cảm biến ánh sáng\r\n– Bài tập 2: Cảm biến nhiệt độ\r\n– Bài tập 3: Cảm biến độ ẩm đất\r\n– Bài tập phụ: Cảm biến vật cản', 139000.00, 7, 4, NULL),
(13, 'Combo module thực hành công nghệ 8 Cảm biến vật cản', '– Bài tập 1: Cảm biến ánh sáng\r\n– Bài tập 2: Cảm biến nhiệt độ\r\n– Bài tập 3: Cảm biến độ ẩm đất\r\n– Bài tập phụ: Cảm biến vật cản', 65000.00, 5, 4, NULL),
(14, 'Cáp mạng LAN bấm sẵn hai đầu CAT6 2 mét\r\n', '– Chiều dài: 2 mét\n– Khối lượng: 54g\n– Loại dây: CAT6\n– Loại đầu cắm: RJ45\n– Tốc độ truyền: có thể lên tới 1000Mbps\n– Số lõi: 8 lõi', 25000.00, 6, 5, NULL),
(15, 'Jack nối LED 3P-10mm 2 đầu 15cm', '– 2 đầu nối led\r\n– Sử dụng với LED: 3 pin\r\n– Yêu cầu chiều ngang LED: 10mm\r\n– Chiều dài: 15cm', 7000.00, 19, 5, NULL),
(16, 'Khớp truyền động khác phương Cardan', '– Tên: khớp nối trục cardan\n– Kích thước trục: Trục 3.17mm sang trục 3.17mm\n– Chất liệu: thép mạ\n– Kích thước tổng quát:11 – 24 mm (đường kính x chiều dài)\n– Bộ sản phẩm gồm: 1 cardan + 2 ốc lục giác\n– Trọng lượng: ~ 10g', 25000.00, 6, 5, NULL),
(17, 'Ốc lục giác inox 304 đầu tròn M4x16 (1 con)\r\n', '– Đường kính (d): 4mm\n– Chiều dài (L): 16mm\n– Trọng lượng 1 ốc M4x16: ~3g\n\n– Hình dạng đầu: Đầu tròn (đầu tròn)\n– Chất liệu: inox 304\n\n– Bề mặt: màu bạc\n– Kích thước cụ thể (vui lòng xem hình kích thước)', 1000.00, 5, 5, NULL),
(18, 'Module 4 Relay 12VDC (Kích mức thấp)', '– Điện áp cấp: 12V DC\r\n– Điện áp kích hoạt relay: 0V\r\n– Dòng tiêu thụ 1 relay: khoảng 80mA\r\n– Dòng đóng cắt: 10A/250V AC  || 10A/30V DC\r\n– Kích thước: 75x50x20mm\r\n– Cân nặng: 50g', 42000.00, 16, 3, NULL),
(19, 'Module Đồng hồ đo nhiệt độ type K 800 độ C Màu xanh lá', '– Màu sắc: Xanh lá\r\n– Điện áp nguồn: 12VDC\r\n– Màn hình: LED 7 đoạn 0.56 inch\r\n– Phạm vi nhiệt độ: -30 – 800°C\r\n– Đầu dò nhiệt độ: Type K\r\n– Sai số: ± 0,3 ° C\r\n– Chip công nghiệp\r\n– Độ chính xác cao\r\n– Chống nhiễu\r\n– Bảo vệ kết nối ngược cực\r\n– Tốc độ làm mới: 4 lần / giây :\r\n– Khối lượng: 31g', 139000.00, 13, 3, NULL),
(20, 'Module Màn hình LCD TFT 3.2 inch ', '– Kích thước: 3.2 inch\n– Điện áp hoạt động: 3.3V / 5V\n– Loại: TFT, không cảm ứng\n– Độ phân giải: 320×240\n– IC điều khiển: ILI9341\n– Hỗ trợ hiển thị 16BIT RGB với 65K màu\n– Tích hợp khe cắm thẻ SD\n– Giao diện hiển thị: SPI 4 dây\n– Khu vực hiển thị hiệu quả: 48.6×64.8 mm\n– Khối lượng: 52g', 260000.00, 43, 3, NULL);

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
(1, 3, 11),
(2, 3, 12),
(3, 4, 20),
(5, 6, 2),
(6, 6, 1),
(7, 6, 2),
(8, 6, 20);

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
  ADD KEY `masanpham` (`masanpham`);

--
-- Indexes for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`chitietgiohangid`),
  ADD KEY `magiohang` (`magiohang`),
  ADD KEY `masanpham` (`masanpham`);

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
  MODIFY `hinhanhid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `khuyenmaiid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `sanphamid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sanpham_khuyenmai`
--
ALTER TABLE `sanpham_khuyenmai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE;

--
-- Constraints for table `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `chitietgiohang_ibfk_1` FOREIGN KEY (`magiohang`) REFERENCES `giohang` (`giohangid`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietgiohang_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE;

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
-- Constraints for table `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD CONSTRAINT `yeuthich_ibfk_1` FOREIGN KEY (`manguoidung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeuthich_ibfk_2` FOREIGN KEY (`masanpham`) REFERENCES `sanpham` (`sanphamid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
