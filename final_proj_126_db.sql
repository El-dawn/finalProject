-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 07:42 AM
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
-- Database: `final proj 126 db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(100) NOT NULL,
  `post_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `user_name`, `date`, `comment`) VALUES
(1, 1, 15, 'admin', '2024-05-28', 'test comment'),
(5, 1, 19, '1234', '2024-05-29', 'idk really bro'),
(6, 2, 19, '1234', '2024-05-29', 'haaaaalp'),
(7, 1, 19, '1234', '2024-05-29', 'ikaw na bro');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `post_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(31, 19, 3),
(33, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `image` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `name`, `title`, `content`, `image`, `date`, `likes`) VALUES
(1, 15, 'admin', 'test lang danay', 'bqwertyuio\r\nasduyugyfv\r\n\r\nasdhfvbkasbd\r\n\r\nAFGGSDUAYGYHB\r\njhdabfhbfd\r\nafshfdkadsjhbd', 'default.png', '2024-05-15', 1),
(2, 15, 'admin', 'test 2', 'this should be second', '', '2024-05-08', 0),
(3, 19, 'asd', 'testssdf', 'qwerfd', '', '0000-00-00', 1),
(4, 19, 'asd', '3qwefadv', 'qwefvfgshjuyrhb\r\nasfsghgdfvASAGDf\r\nfsagre\r\ngFDSgtryetbgsdfvfrefsd', '', '0000-00-00', 0),
(5, 18, '12345`', '123456', '1234567uhgfs', '6656bf7a2c707.png', '2024-05-29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `about` varchar(120) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `pronouns` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `about`, `username`, `email`, `password`, `image`, `date_of_birth`, `pronouns`) VALUES
(13, 'qe2rwer', '1234563', 'qwerfd', 'test@test.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', '0000-00-00', '1234'),
(14, 'qwe', '', '', '123@123gmail.com', 'f4542db9ba30f7958ae42c113dd87ad21fb2eddb', '', '0000-00-00', ''),
(15, 'admin', '', '', 'gg@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'test.png', '0000-00-00', ''),
(16, 'blahblah', '', '', 'blah@blah.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '', '0000-00-00', ''),
(17, 'asd', '1234', '', 'asd@asd1.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '', '0000-00-00', '1234'),
(18, '12345`', '1234', '1234', '12345@qwe.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '6656bf381166f.png', '1999-06-29', '1234'),
(19, 'asd', 'I am the bone of my sword', '123', '123@123.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '6656af90d9afa.png', '2023-06-07', 'they/them'),
(20, 'qwe', '', '', '123@qwe.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', ' - 2024.05.28 - 07.49.24am.', '0000-00-00', ''),
(21, 'asdkkakd', '', '', '1@1.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '', '0000-00-00', ''),
(22, 'sdn', '', '', '12@12.com', '7b52009b64fd0a2a49e6d8a939753077792b0554', '66557b794009f.png', '1970-01-01', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
