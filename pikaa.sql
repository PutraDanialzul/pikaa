-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2025 at 03:47 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pikaa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ADMIN_SECRET` text NOT NULL COMMENT 'The secret code for each admin.',
  `SECRET_ID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`SECRET_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='The secret code for each admin.';

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ADMIN_SECRET`, `SECRET_ID`) VALUES
('abc', 2),
('def', 9);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `FEEDBACK_ID` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The ID of the feedback. ',
  `SENDER_NAME` text NOT NULL COMMENT 'The name of the sender. ',
  `SENDER_GENDER` text NOT NULL COMMENT 'The gender of the sender. ',
  `SENDER_EMAIL` text NOT NULL COMMENT 'The email of the sender. ',
  `FEEDBACK_TYPE` text NOT NULL COMMENT 'The type of the feedback sent. ',
  `FEEDBACK_TEXT` longtext NOT NULL COMMENT 'The feedback being sent. ',
  `FEEDBACK_TIME` text NOT NULL COMMENT 'The information about the time the feedback being sent. ',
  PRIMARY KEY (`FEEDBACK_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='The data about each feedback. ';

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FEEDBACK_ID`, `SENDER_NAME`, `SENDER_GENDER`, `SENDER_EMAIL`, `FEEDBACK_TYPE`, `FEEDBACK_TEXT`, `FEEDBACK_TIME`) VALUES
(1, 'Putra', 'male', 'putradanialzul765@gmail.com', 'enquiry', 'This is a test for the feedback system. Anything that is included here does not really mean anything. I really hope that everything that I have done is meaningful and working to ensure that I have a bright future ahead.', '11:54:20 PM 27/11/2025'),
(4, 'Khairul', 'nonbinary', 'khairul@encem.my', 'enquiry', 'I love you so much. uwu', '05:55:29 PM 01/12/2025');

-- --------------------------------------------------------

--
-- Table structure for table `song`
--

DROP TABLE IF EXISTS `song`;
CREATE TABLE IF NOT EXISTS `song` (
  `SONG_ID` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The id of the song.',
  `SONG_TITLE` text NOT NULL COMMENT 'The title of the song.',
  `SONG_GENRE` text NOT NULL COMMENT 'The genre(s) of the song.',
  `SONG_ARTIST` text NOT NULL COMMENT 'The artist of the song.',
  `SONG_RELEASE_YEAR` int UNSIGNED NOT NULL COMMENT 'Which year the song was released.',
  `SONG_LYRICS` longtext NOT NULL COMMENT 'The lyrics of the song.',
  `SONG_COVER_URL` text NOT NULL COMMENT 'The link to the song cover image.',
  `SONG_VIDEO_URL` text NOT NULL COMMENT 'The link to the song music video.',
  `SONG_MUSICS_URL` text NOT NULL COMMENT 'The link to the song music sound.',
  PRIMARY KEY (`SONG_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='The information about each song';

--
-- Dumping data for table `song`
--

INSERT INTO `song` (`SONG_ID`, `SONG_TITLE`, `SONG_GENRE`, `SONG_ARTIST`, `SONG_RELEASE_YEAR`, `SONG_LYRICS`, `SONG_COVER_URL`, `SONG_VIDEO_URL`, `SONG_MUSICS_URL`) VALUES
(4, 'Never Gonna Give You Up', 'dance-pop', 'Rick Astley', 1897, 'We\'re no strangers to love\r\nYou know the rules and so do I\r\nA full commitment\'s what I\'m thinking of\r\nYou wouldn\'t get this from any other guy\r\n\r\nI just wanna tell you how I\'m feeling\r\nGotta make you understand\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you\r\n\r\nWe\'ve known each other for so long\r\nYour heart\'s been aching, but you\'re too shy to say it\r\nInside, we both know what\'s been going on\r\nWe know the game and we\'re gonna play it\r\n\r\nAnd if you ask me how I\'m feeling\r\nDon\'t tell me you\'re too blind to see\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you\r\n\r\n(Ooh, give you up)\r\n(Ooh, give you up)\r\nNever gonna give, never gonna give\r\n(Give you up)\r\nNever gonna give, never gonna give\r\n(Give you up)\r\n\r\nWe\'ve known each other for so long\r\nYour heart\'s been aching, but you\'re too shy to say it\r\nInside, we both know what\'s been going on\r\nWe know the game and we\'re gonna play it\r\n\r\nI just wanna tell you how I\'m feeling\r\nGotta make you understand\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you\r\n\r\nNever gonna give you up\r\nNever gonna let you down\r\nNever gonna run around and desert you\r\nNever gonna make you cry\r\nNever gonna say goodbye\r\nNever gonna tell a lie and hurt you', 'https://upload.wikimedia.org/wikipedia/en/3/34/RickAstleyNeverGonnaGiveYouUp7InchSingleCover.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'https://open.spotify.com/embed/track/4uLU6hMCjMI75M1A2tKUQC?utm_source=generator&theme=0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
