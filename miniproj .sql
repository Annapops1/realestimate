-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 03:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `miniproj`
--

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(300) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interest`
--

CREATE TABLE `interest` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interest`
--

INSERT INTO `interest` (`id`, `user_id`, `property_id`, `status`) VALUES
(8, 18, 11, 0),
(9, 0, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `state` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `place` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `state`, `district`, `place`) VALUES
(1, 'Andhra Pradesh', 'Anantapur', 'Anantapur'),
(2, 'Andhra Pradesh', 'Anantapur', 'Guntakal'),
(3, 'Andhra Pradesh', 'Anantapur', 'Kadiri'),
(4, 'Andhra Pradesh', 'Chittoor', 'Chittoor'),
(5, 'Andhra Pradesh', 'Chittoor', 'Tirupati'),
(6, 'Andhra Pradesh', 'Chittoor', 'Vellore'),
(7, 'Andhra Pradesh', 'Chittoor', 'Puttur'),
(8, 'Karnataka', 'Bengaluru', 'Bengaluru'),
(9, 'Karnataka', 'Bengaluru', 'Whitefield'),
(10, 'Karnataka', 'Bengaluru', 'Koramangala'),
(11, 'Karnataka', 'Mysuru', 'Mysuru'),
(12, 'Karnataka', 'Mysuru', 'Hunsur'),
(13, 'Karnataka', 'Mysuru', 'Nanjangud'),
(14, 'Kerala', 'Kochi', 'Kochi'),
(15, 'Kerala', 'Kochi', 'Ernakulam'),
(16, 'Kerala', 'Kozhikode', 'Kozhikode'),
(17, 'Maharashtra', 'Mumbai', 'Mumbai'),
(18, 'Maharashtra', 'Mumbai', 'Thane'),
(19, 'Maharashtra', 'Pune', 'Pune'),
(20, 'Tamil Nadu', 'Chennai', 'Chennai'),
(21, 'Tamil Nadu', 'Chennai', 'Tambaram'),
(22, 'Tamil Nadu', 'Coimbatore', 'Coimbatore'),
(23, 'Telangana', 'Hyderabad', 'Hyderabad'),
(24, 'Telangana', 'Hyderabad', 'Secunderabad'),
(25, 'Telangana', 'Warangal', 'Warangal'),
(26, 'Gujarat', 'Ahmedabad', 'Ahmedabad'),
(27, 'Gujarat', 'Surat', 'Surat'),
(28, 'Gujarat', 'Vadodara', 'Vadodara'),
(29, 'Rajasthan', 'Jaipur', 'Jaipur'),
(30, 'Rajasthan', 'Jaipur', 'Ajmer'),
(31, 'Rajasthan', 'Udaipur', 'Udaipur'),
(32, 'West Bengal', 'Kolkata', 'Kolkata'),
(33, 'West Bengal', 'Howrah', 'Howrah'),
(34, 'West Bengal', 'Darjeeling', 'Darjeeling'),
(35, 'Uttar Pradesh', 'Lucknow', 'Lucknow'),
(36, 'Uttar Pradesh', 'Agra', 'Agra'),
(37, 'Uttar Pradesh', 'Varanasi', 'Varanasi'),
(38, 'Punjab', 'Amritsar', 'Amritsar'),
(39, 'Punjab', 'Ludhiana', 'Ludhiana'),
(40, 'Punjab', 'Jalandhar', 'Jalandhar'),
(41, 'Haryana', 'Gurugram', 'Gurugram'),
(42, 'Haryana', 'Faridabad', 'Faridabad'),
(43, 'Haryana', 'Ambala', 'Ambala'),
(44, 'Bihar', 'Patna', 'Patna'),
(45, 'Bihar', 'Gaya', 'Gaya'),
(46, 'Bihar', 'Bhagalpur', 'Bhagalpur'),
(47, 'Odisha', 'Bhubaneswar', 'Bhubaneswar'),
(48, 'Odisha', 'Cuttack', 'Cuttack'),
(49, 'Odisha', 'Rourkela', 'Rourkela'),
(50, 'Assam', 'Guwahati', 'Guwahati'),
(51, 'Assam', 'Dibrugarh', 'Dibrugarh'),
(52, 'Assam', 'Silchar', 'Silchar'),
(53, 'Himachal Pradesh', 'Shimla', 'Shimla'),
(54, 'Himachal Pradesh', 'Dharamshala', 'Dharamshala'),
(55, 'Himachal Pradesh', 'Kullu', 'Kullu'),
(56, 'Jammu and Kashmir', 'Srinagar', 'Srinagar'),
(57, 'Jammu and Kashmir', 'Jammu', 'Jammu'),
(58, 'Jammu and Kashmir', 'Anantnag', 'Anantnag'),
(59, 'Chhattisgarh', 'Raipur', 'Raipur'),
(60, 'Chhattisgarh', 'Bilaspur', 'Bilaspur'),
(61, 'Chhattisgarh', 'Durg', 'Durg'),
(62, 'Uttarakhand', 'Dehradun', 'Dehradun'),
(63, 'Uttarakhand', 'Haridwar', 'Haridwar'),
(64, 'Uttarakhand', 'Nainital', 'Nainital'),
(65, 'Goa', 'Panaji', 'Panaji'),
(66, 'Goa', 'Margao', 'Margao'),
(67, 'Goa', 'Mapusa', 'Mapusa'),
(68, 'Andhra Pradesh', 'Anantapur', 'Anantapur'),
(69, 'Andhra Pradesh', 'Anantapur', 'Guntakal'),
(70, 'Andhra Pradesh', 'Anantapur', 'Kadiri'),
(71, 'Andhra Pradesh', 'Chittoor', 'Chittoor'),
(72, 'Andhra Pradesh', 'Chittoor', 'Tirupati'),
(73, 'Andhra Pradesh', 'Chittoor', 'Vellore'),
(74, 'Andhra Pradesh', 'Chittoor', 'Puttur'),
(75, 'Andhra Pradesh', 'Krishna', 'Vijayawada'),
(76, 'Andhra Pradesh', 'Krishna', 'Machilipatnam'),
(77, 'Andhra Pradesh', 'Krishna', 'Nuzvid'),
(78, 'Andhra Pradesh', 'Kurnool', 'Kurnool'),
(79, 'Andhra Pradesh', 'Kurnool', 'Nandikotkur'),
(80, 'Andhra Pradesh', 'Kurnool', 'Adoni'),
(81, 'Andhra Pradesh', 'Prakasam', 'Ongole'),
(82, 'Andhra Pradesh', 'Prakasam', 'Chirala'),
(83, 'Andhra Pradesh', 'Srikakulam', 'Srikakulam'),
(84, 'Andhra Pradesh', 'Srikakulam', 'Amadalavalasa'),
(85, 'Andhra Pradesh', 'Visakhapatnam', 'Visakhapatnam'),
(86, 'Andhra Pradesh', 'Visakhapatnam', 'Anakapalle'),
(87, 'Andhra Pradesh', 'Vizianagaram', 'Vizianagaram'),
(88, 'Andhra Pradesh', 'Vizianagaram', 'Parvathipuram'),
(89, 'Arunachal Pradesh', 'Papum Pare', 'Naharlagun'),
(90, 'Arunachal Pradesh', 'Lower Subansiri', 'Ziro'),
(91, 'Arunachal Pradesh', 'Tawang', 'Tawang'),
(92, 'Arunachal Pradesh', 'West Kameng', 'Bomdila'),
(93, 'Assam', 'Guwahati', 'Guwahati'),
(94, 'Assam', 'Dibrugarh', 'Dibrugarh'),
(95, 'Assam', 'Silchar', 'Silchar'),
(96, 'Assam', 'Jorhat', 'Jorhat'),
(97, 'Assam', 'Nagaon', 'Nagaon'),
(98, 'Bihar', 'Patna', 'Patna'),
(99, 'Bihar', 'Gaya', 'Gaya'),
(100, 'Bihar', 'Bhagalpur', 'Bhagalpur'),
(101, 'Bihar', 'Muzaffarpur', 'Muzaffarpur'),
(102, 'Bihar', 'Darbhanga', 'Darbhanga'),
(103, 'Chhattisgarh', 'Raipur', 'Raipur'),
(104, 'Chhattisgarh', 'Bilaspur', 'Bilaspur'),
(105, 'Chhattisgarh', 'Durg', 'Durg'),
(106, 'Chhattisgarh', 'Korba', 'Korba'),
(107, 'Chhattisgarh', 'Jagdalpur', 'Jagdalpur'),
(108, 'Goa', 'North Goa', 'Panaji'),
(109, 'Goa', 'South Goa', 'Margao'),
(110, 'Gujarat', 'Ahmedabad', 'Ahmedabad'),
(111, 'Gujarat', 'Surat', 'Surat'),
(112, 'Gujarat', 'Vadodara', 'Vadodara'),
(113, 'Gujarat', 'Rajkot', 'Rajkot'),
(114, 'Gujarat', 'Bhavnagar', 'Bhavnagar'),
(115, 'Haryana', 'Gurugram', 'Gurugram'),
(116, 'Haryana', 'Faridabad', 'Faridabad'),
(117, 'Haryana', 'Ambala', 'Ambala'),
(118, 'Haryana', 'Hisar', 'Hisar'),
(119, 'Himachal Pradesh', 'Shimla', 'Shimla'),
(120, 'Himachal Pradesh', 'Dharamshala', 'Dharamshala'),
(121, 'Himachal Pradesh', 'Kullu', 'Kullu'),
(122, 'Himachal Pradesh', 'Mandi', 'Mandi'),
(123, 'Jammu and Kashmir', 'Srinagar', 'Srinagar'),
(124, 'Jammu and Kashmir', 'Jammu', 'Jammu'),
(125, 'Jammu and Kashmir', 'Anantnag', 'Anantnag'),
(126, 'Jammu and Kashmir', 'Baramulla', 'Baramulla'),
(127, 'Jharkhand', 'Ranchi', 'Ranchi'),
(128, 'Jharkhand', 'Jamshedpur', 'Jamshedpur'),
(129, 'Jharkhand', 'Dhanbad', 'Dhanbad'),
(130, 'Jharkhand', 'Bokaro', 'Bokaro'),
(131, 'Karnataka', 'Bengaluru', 'Bengaluru'),
(132, 'Karnataka', 'Mysuru', 'Mysuru'),
(133, 'Karnataka', 'Mangaluru', 'Mangaluru'),
(134, 'Karnataka', 'Hubli', 'Hubli'),
(135, 'Karnataka', 'Belagavi', 'Belagavi'),
(136, 'Kerala', 'Kochi', 'Kochi'),
(137, 'Kerala', 'Thiruvananthapuram', 'Thiruvananthapuram'),
(138, 'Kerala', 'Kozhikode', 'Kozhikode'),
(139, 'Kerala', 'Kollam', 'Kollam'),
(140, 'Kerala', 'Malappuram', 'Malappuram'),
(141, 'Madhya Pradesh', 'Bhopal', 'Bhopal'),
(142, 'Madhya Pradesh', 'Indore', 'Indore'),
(143, 'Madhya Pradesh', 'Gwalior', 'Gwalior'),
(144, 'Madhya Pradesh', 'Jabalpur', 'Jabalpur'),
(145, 'Madhya Pradesh', 'Ujjain', 'Ujjain'),
(146, 'Maharashtra', 'Mumbai', 'Mumbai'),
(147, 'Maharashtra', 'Pune', 'Pune'),
(148, 'Maharashtra', 'Nagpur', 'Nagpur'),
(149, 'Maharashtra', 'Thane', 'Thane'),
(150, 'Maharashtra', 'Nashik', 'Nashik'),
(151, 'Manipur', 'Imphal', 'Imphal'),
(152, 'Manipur', 'Churachandpur', 'Churachandpur'),
(153, 'Manipur', 'Bishnupur', 'Bishnupur'),
(154, 'Meghalaya', 'Shillong', 'Shillong'),
(155, 'Meghalaya', 'Tura', 'Tura'),
(156, 'Meghalaya', 'Jowai', 'Jowai'),
(157, 'Mizoram', 'Aizawl', 'Aizawl'),
(158, 'Mizoram', 'Lunglei', 'Lunglei'),
(159, 'Nagaland', 'Kohima', 'Kohima'),
(160, 'Nagaland', 'Dimapur', 'Dimapur'),
(161, 'Odisha', 'Bhubaneswar', 'Bhubaneswar'),
(162, 'Odisha', 'Cuttack', 'Cuttack'),
(163, 'Odisha', 'Rourkela', 'Rourkela'),
(164, 'Odisha', 'Berhampur', 'Berhampur'),
(165, 'Punjab', 'Amritsar', 'Amritsar'),
(166, 'Punjab', 'Ludhiana', 'Ludhiana'),
(167, 'Punjab', 'Jalandhar', 'Jalandhar'),
(168, 'Rajasthan', 'Jaipur', 'Jaipur'),
(169, 'Rajasthan', 'Udaipur', 'Udaipur'),
(170, 'Rajasthan', 'Ajmer', 'Ajmer'),
(171, 'Rajasthan', 'Jodhpur', 'Jodhpur'),
(172, 'Sikkim', 'Gangtok', 'Gangtok'),
(173, 'Tamil Nadu', 'Chennai', 'Chennai'),
(174, 'Tamil Nadu', 'Coimbatore', 'Coimbatore'),
(175, 'Tamil Nadu', 'Madurai', 'Madurai'),
(176, 'Telangana', 'Hyderabad', 'Hyderabad'),
(177, 'Telangana', 'Warangal', 'Warangal'),
(178, 'Telangana', 'Khammam', 'Khammam'),
(179, 'Tripura', 'Agartala', 'Agartala'),
(180, 'Uttar Pradesh', 'Lucknow', 'Lucknow'),
(181, 'Uttar Pradesh', 'Agra', 'Agra'),
(182, 'Uttar Pradesh', 'Varanasi', 'Varanasi'),
(183, 'Uttar Pradesh', 'Kanpur', 'Kanpur'),
(184, 'Uttarakhand', 'Dehradun', 'Dehradun'),
(185, 'Uttarakhand', 'Haridwar', 'Haridwar'),
(186, 'Uttarakhand', 'Nainital', 'Nainital'),
(187, 'West Bengal', 'Kolkata', 'Kolkata'),
(188, 'West Bengal', 'Howrah', 'Howrah'),
(189, 'West Bengal', 'Darjeeling', 'Darjeeling'),
(190, 'West Bengal', 'Siliguri', 'Siliguri'),
(191, 'Andaman and Nicobar Islands', 'Port Blair', 'Port Blair'),
(192, 'Chandigarh', 'Chandigarh', 'Chandigarh'),
(193, 'Dadra and Nagar Haveli and Daman and Diu', 'Daman', 'Daman'),
(194, 'Lakshadweep', 'Kavaratti', 'Kavaratti'),
(195, 'Delhi', 'New Delhi', 'New Delhi');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(225) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `size_sqft` int(11) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `property_type` enum('plot','house') NOT NULL,
  `transaction_type` enum('rent','buy') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `user_id`, `address`, `city`, `state`, `size_sqft`, `bedrooms`, `bathrooms`, `place`, `district`, `size`, `description`, `photo`, `property_type`, `transaction_type`, `title`, `price`) VALUES
(11, 18, '', '', 'Arunachal Pradesh', 0, 0, 0, 'Naharlagun', 'Papum Pare', 44, '', 'img_6.jpeg', 'plot', 'rent', NULL, '1200'),
(12, 18, '', '', 'Assam', 0, 0, 0, 'Hojai Town', 'Hojai', 55, '', 'img_2.jpeg', 'plot', 'rent', NULL, '140000'),
(14, 18, '', '', 'Assam', 0, 0, 0, 'Hojai Town', 'Hojai', 55, '', 'img_2.jpeg', 'plot', 'rent', NULL, '140000'),
(16, 18, '', '', 'Haryana', 0, 0, 0, 'Odhan', 'Sirsa', 45, '', 'img_3.jpeg', 'plot', 'rent', NULL, '22'),
(17, 18, '', '', 'Kerala', 0, 0, 0, 'Pala', 'Kottayam', 67, '', '119839221_10158707832459938_8829261770152765425_o.jpg', 'plot', 'rent', NULL, '9990'),
(18, 18, '', '', 'Arunachal Pradesh', 0, 0, 0, 'Naharlagun', 'Papum Pare', 36, '', 'image_1.jpeg', 'plot', 'rent', NULL, '140000'),
(19, 18, '', '', 'Kerala', 0, 0, 0, 'Chittur', 'Palakkad', 99, '', 'img_4.jpeg', 'plot', 'rent', NULL, '15888'),
(33, 18, '', '', 'Arunachal Pradesh', 0, 2, 0, 'Seppa', 'East Kameng', 0, '', 'img_4.jpeg', 'house', 'buy', NULL, '12323'),
(39, 24, '', '', 'Jammu and Kashmir', 0, 5, 3, '0', 'Rajouri', 0, '', 'images (1).jpeg', 'house', 'rent', NULL, '25000'),
(40, 18, '', '', 'Andhra Pradesh', 0, 0, 0, 'Anantapur', 'Anantapur', 15, '', '508800543M-1722678078053.jpg', 'plot', 'buy', NULL, '200000'),
(41, 18, '', '', 'Kerala', 1, 0, 0, 'Kuttanadu', 'Alappuzha', 0, '', 'B612_20220923_083032_992.jpg', 'plot', 'rent', NULL, '18000'),
(42, 18, '', '', 'Kerala', 0, 4, 3, '0', 'Ernakulam', 0, '', 'images (1).jpeg', 'house', 'rent', NULL, '15000'),
(43, 18, '', '', 'Assam', 0, 3, 2, 'Sonari', 'Charaideo', 0, '', 'download (1).jpeg', 'house', 'buy', NULL, '50000'),
(44, 18, '', '', 'Goa', 0, 3, 4, 'Panaji', 'North Goa', 0, '', 'images (3).jpeg', 'house', 'buy', NULL, '0.04'),
(45, 18, '', '', 'Chandigarh', 0, 3, 2, 'Chandigarh', 'Chandigarh', 0, '', 'images (6).jpeg', 'house', 'buy', NULL, '15000'),
(46, 18, '', '', 'Andhra Pradesh', 0, 2, 3, 'Anantapur', 'Anantapur', 0, '', 'images (2).jpeg', 'house', 'buy', NULL, '75000'),
(47, 18, '', '', 'Kerala', 0, 5, 3, 'Kochi', 'Kochi', 0, '', 'images (2).jpeg,images (3).jpeg,images (4).jpeg', 'house', 'buy', NULL, '75000'),
(52, 18, '', '', 'Telangana', 0, 0, 0, 'Warangal', 'Warangal', 100000000, '', 'house_1.jpg,images (1).jpeg,images (2).jpeg', 'plot', 'buy', NULL, '1200'),
(53, 18, '', '', 'Karnataka', 0, 2, 3, '0', 'Mysuru', 0, '', 'house_1.jpg,images (1).jpeg,images (2).jpeg', 'house', 'rent', NULL, '75000'),
(54, 18, '', '', 'Andhra Pradesh', 87, 0, 0, 'Guntakal', 'Anantapur', 0, '', 'house_1.jpg,images (1).jpeg,images (2).jpeg,images (3).jpeg', 'plot', 'rent', NULL, '12323');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `fullname` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `is_active`, `fullname`, `photo`) VALUES
(13, 'anie', '$2y$10$NReH8LooL2sddY2jsDu9Ve0dXzwedoQDVy4bWw7blDG2zygVTlQTi', 'annaottaplackal@gmail.com', '9207014288', 'Ottaplackal (H)', 0, NULL, NULL),
(17, 'beenapops', '$2y$10$fkI4felyI7x2LEgew.M8iOs6JOhHWKSE/XEBI0gJAPdEkX50FbCKa', 'annapops2025@mca.ajce.in', '9207014288', 'Ottaplackal (H)', 1, 'Beena Pops', 'IMG-20201119-WA0214.jpg'),
(18, 'annapops', '$2y$10$b4MJR.VS77Kixf1DSJx7K.J2ryQ2Se45Xqlr/3P2y/LVTP.eR.grC', 'popsanna8@gmail.com', '9207014288', 'Ottaplackal (H)', 1, 'Anna Pops', 'ANNA-POPS_psc.jpg'),
(19, 'annapopss', '$2y$10$DNAKr0lolGyjXC25dAy0Au3.6MYukk0OO1WrKl5OlkYm2MFerBGj.', 'popsanna8@gmail.com', '9207014288', 'Ottaplackal (H)', 1, 'Anna Pops', '12483.jpg'),
(20, 'ajulkjose', '$2y$10$dzZqFjizE8p2ZJ9qNLWlf.9U9xafYnoEWpFRiAdTBQqd0eGmhzwca', 'ajulkjose@gmail.com', '8078234246', 'Kallarackal H', 1, 'Ajul K Jose', 'IMG-20220904-WA0196.jpg'),
(21, '   ', '$2y$10$flxXMXrGQ.ZX3dJ5QA.7kOp1TEw1/RA8A9ExsD9GNJDHCNjMV4O/.', 'annapops2025jhh@mca.ajce.in', '09207014288', 'Ottaplackal (H)', 1, 'Anna Poppachan', 'IMG_20221210_212537.jpg'),
(22, 'poppachan', '$2y$10$wWuHFFwb4KsHm4oJ3Vg/1epVjXoqF3RQWJUW.hlqKmK9dxeYk9.Eu', 'biyaaby2002@gmail.com', '9207014288', 'Ottaplackal (H)', 1, 'AMAL JYOTHI COLLEGE OF ENGINEERING', 'customer_usecase.png'),
(23, 'poppachan12', '$2y$10$0UkapgcMK6afbLmh56SvSuT/PpTP0PziA9I1ypCBiNYNI8/EPr2SG', 'anmighanm@gmail.com', '9207014288', 'Ottaplackal (H)', 1, 'Anna Pops', 'Designer.png'),
(24, 'poppachan1', '$2y$10$RbJtnBgNs860FExakKmPr./V0xkad2Eg2IB4Bdm/FcgEDsA3IgUF.', 'poppachanjottaplavan@gmail.com', '8547330348', 'Ottaplackal (H)', 1, 'Poppachan J', 'B612_20220923_083032_992.jpg'),
(25, 'popachan', '$2y$10$yRCKyy.eMH1zazb9p/VBPeS/gG0B0T6/k7BoMi085lJLcrMzUx2fq', 'ajulkjose2025@mca.ajce.in', '9207014288', 'Ottaplackal (H)', 1, 'Poppachan J', '001.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`);

--
-- Indexes for table `interest`
--
ALTER TABLE `interest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `interest`
--
ALTER TABLE `interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
