-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2023 at 02:14 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ovas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_list`
--

CREATE TABLE `appointment_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `schedule` date NOT NULL,
  `owner_name` text NOT NULL,
  `contact` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `category_id` int(30) NOT NULL,
  `breed` text NOT NULL,
  `age` varchar(50) NOT NULL,
  `service_ids` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `sched_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_list`
--

INSERT INTO `appointment_list` (`id`, `code`, `schedule`, `owner_name`, `contact`, `email`, `address`, `category_id`, `breed`, `age`, `service_ids`, `status`, `sched_id`, `date_created`, `date_updated`) VALUES
(1, 'EDDFC-2023010001', '2023-01-10', 'Lil sheesh', '0912345789', 'Shesh@gmail.com', 'Here City', 1, 'chiken', '5 mos. old', '3,1', 0, NULL, '2023-01-10 15:55:21', NULL),
(2, 'EDDFC-2023010006', '2023-01-12', 'Aleng Marites', '09888754466', 'marites@gmail.com', 'Sample address', 2, 'chiken', '8 mos', '5,1', 1, NULL, '2023-01-12 16:27:54', '2022-01-04 17:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Active, 1 = Delete',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Chicken Check up', 0, '2023-01-04 10:31:11', NULL),
(2, 'Chicken Vaccine', 0, '2023-01-04 10:31:11', NULL),
(3, 'Chicken Disease information', 0, '2023-01-04 10:31:11', NULL),
(4, 'test', 1, '2022-01-04 10:33:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_list`
--

CREATE TABLE `message_list` (
  `id` int(30) NOT NULL,
  `fullname` text NOT NULL,
  `contact` text NOT NULL,
  `email` text NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_list`
--

INSERT INTO `message_list` (`id`, `fullname`, `contact`, `email`, `message`, `status`, `date_created`) VALUES
(1, 'Keeko Paa', '09666258629', 'paalorsmen@gmail.com', 'Sakit sa manko', 1, '2023-01-04 17:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `scheduler`
--

CREATE TABLE `scheduler` (
  `id` int(11) NOT NULL,
  `timestart` time DEFAULT NULL,
  `timeend` time DEFAULT NULL,
  `schedule` date DEFAULT NULL,
  `slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_list`
--

CREATE TABLE `service_list` (
  `id` int(30) NOT NULL,
  `category_ids` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `fee` float NOT NULL DEFAULT 0,
  `delete_flag` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_list`
--

INSERT INTO `service_list` (`id`, `category_ids`, `name`, `description`, `fee`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, '1', 'Check-up', '<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Curabitur facilisis consequat lacinia. Curabitur luctus nunc ac libero mattis, id bibendum mauris pretium. Donec vulputate ac velit ac laoreet. Aliquam cursus feugiat turpis, id posuere nisl ornare sit amet. Duis pharetra quam vel risus semper vestibulum. Aliquam lacinia sit amet dolor a viverra. Ut sit amet turpis laoreet, euismod dui at, accumsan lacus. Fusce est nunc, consectetur ac neque at, commodo faucibus ipsum. Nullam rutrum dapibus pulvinar. Mauris eget magna id nisl consequat mollis vitae id purus. Cras eros tellus, fringilla et dictum quis, vulputate id erat. Aliquam erat volutpat.</span><br></p>', 1700, 0, '2022-01-04 11:14:24', NULL),
(2, '2', 'Vaccination', '<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Ut fringilla velit quis condimentum mattis. Sed egestas ligula imperdiet orci elementum, eu aliquet sem cursus. Vestibulum maximus ex ut mi lobortis ultricies. Ut id congue ipsum. Donec porttitor a nunc a egestas. Ut non urna eget erat vestibulum eleifend. Phasellus blandit dui non neque cursus, id viverra turpis aliquet. Sed tempor nisl a ipsum porta, eget iaculis sem venenatis. Sed ac dolor sagittis, interdum leo ut, sagittis risus. Etiam suscipit, orci eget hendrerit malesuada, nisl mauris semper dolor, non accumsan nisl nibh ac augue. Integer vel lectus quis quam suscipit pharetra quis in lectus. Nulla bibendum ex sed accumsan laoreet. Cras et elit vitae sapien faucibus luctus. Morbi leo nibh, viverra vitae elit ac, luctus elementum urna.</span><br></p>', 500, 0, '2022-01-04 11:15:09', NULL),
(3, '3', 'Mycoplasmosis', '<p><span style=\"color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px; text-align: justify;\">Mycoplasma is a very small, slow-growing bacteria-like organism. There are\r\nlots of different types of Mycoplasma that affect different animals, humans,\r\nand even plants and insects. Several types of Mycoplasma affect chickens,\r\nturkeys, and other birds. Two types, Mycoplasma gallisepticum and\r\nMycoplasma synoviae (MS), could make your birds sick and occasionally\r\nsome birds may die from it, especially if they have other infections. Mycoplasma spreads very easily\r\nbetween birds and because the bacteria grow slowly it may take a while before you even notice that\r\nyour birds are sick. Once birds get Mycoplasma they will always have it, even after they recover and\r\ndon’t look sick. Even if only one bird has Mycoplasma, all the other birds will catch it; any new birds\r\nadded to the flock will also catch it.</span><br></p>', 500, 0, '2022-01-04 11:16:24', '2022-01-04 11:17:00'),
(4, '3', 'Fowl Cholera', '<p>Fowl cholera is a bacterial infection caused by Pasteurella multocida. This infection is also referred to\r\nas avian pasteurellosis and avian hemorrhagic septicemia. All poultry species, including gamebirds\r\nand waterfowl, are susceptible to this type of bacteria. Domestic birds, including pets or birds at the\r\nzoo, and wild birds can be infected. Fowl cholera usually strikes birds older than six weeks of age.\r\nInfected birds are a major source of infection, spreading bacteria through secretions from the mouth,\r\nnose, and eyes that contaminate feed and water. This type of bacteria is not typically shed in fecal\r\nmaterial and  it can be treated with antibiotics. <br></p>', 250, 0, '2022-01-04 11:16:38', '2022-01-04 11:17:08'),
(5, '1', 'deleted', '<p>test</p>', 123, 1, '2022-01-04 11:17:34', '2022-01-04 11:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Early Disease Detection for chickens'),
(6, 'short_name', 'EDD for CHICKEN'),
(11, 'logo', 'uploads/logo.jpg'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.jpg'),
(15, 'content', 'Array'),
(16, 'email', 'paalorsmen@gmail.com'),
(17, 'contact', '09666258629'),
(18, 'from_time', '11:00'),
(19, 'to_time', '21:30'),
(20, 'address', ' Street, Zamboanga City'),
(23, 'max_appointment', '20'),
(24, 'clinic_schedule', '10:00 AM - 6:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '0=not verified, 1 = verified',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `status`, `date_added`, `date_updated`) VALUES
(1, 'Kelvin', NULL, 'Paa', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/admin.jpg', NULL, 1, 1, '2021-01-20 14:02:37', '2021-12-14 15:47:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_list`
--
ALTER TABLE `appointment_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_list`
--
ALTER TABLE `message_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduler`
--
ALTER TABLE `scheduler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_list`
--
ALTER TABLE `service_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_list`
--
ALTER TABLE `appointment_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `message_list`
--
ALTER TABLE `message_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_list`
--
ALTER TABLE `service_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment_list`
--
ALTER TABLE `appointment_list`
  ADD CONSTRAINT `appointment_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
