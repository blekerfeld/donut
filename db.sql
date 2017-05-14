-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `antonyms`;
CREATE TABLE `antonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id_1` int(11) NOT NULL,
  `word_id_2` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id_1` (`word_id_1`),
  KEY `word_id_2` (`word_id_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `antonyms` (`id`, `word_id_1`, `word_id_2`, `score`) VALUES
(1, 1,  9,  100),
(2, 1,  28, 50),
(3, 78, 25, 0),
(4, 17, 68, 100);

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `audio_idiom`;
CREATE TABLE `audio_idiom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom_id` int(11) NOT NULL,
  `audio_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idiom_id` (`idiom_id`),
  KEY `description` (`description`(191)),
  CONSTRAINT `audio_idiom_ibfk_1` FOREIGN KEY (`idiom_id`) REFERENCES `idioms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audio_idiom` (`id`, `idiom_id`, `audio_file`, `description`) VALUES
(1, 2,  'https://pkaudio.herokuapp.com/nl-nl/Als kat en hond leven',  ''),
(2, 3,  'https://pkaudio.herokuapp.com/nl-nl/De kat uit de boom kijken',  ''),
(3, 4,  'https://pkaudio.herokuapp.com/nl-nl/Een kat in de zak kopen',  '');

DROP TABLE IF EXISTS `audio_words`;
CREATE TABLE `audio_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `audio_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `description` (`description`(191)),
  KEY `word_id` (`word_id`),
  CONSTRAINT `audio_words_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audio_words` (`id`, `word_id`, `audio_file`, `description`) VALUES
(2, 17, 'kat.ogg',  'The Netherlands'),
(3, 1,  'https://pkaudio.herokuapp.com/nl-nl/man',  'nl-nl');

DROP TABLE IF EXISTS `classifications`;
CREATE TABLE `classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `native_hidden_entry` int(11) NOT NULL,
  `native_hidden_entry_short` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `classifications` (`id`, `name`, `short_name`, `native_hidden_entry`, `native_hidden_entry_short`) VALUES
(1, 'masculine',  'm.', 0,  0),
(2, 'feminine', 'f.', 0,  0),
(3, 'neuter', 'nt.',  0,  0),
(4, 'indefinite', 'indef.', 0,  0),
(5, 'Regular verb', 'rg.',  0,  0),
(6, 'masculine',  'ir. v.', 0,  0),
(7, 'masculine',  'def.', 0,  0),
(8, 'First person', '1',  0,  0),
(9, 'masculine',  '2',  0,  0),
(10,  'masculine',  '3 m.', 0,  0),
(11,  'masculine',  '3 f.', 0,  0),
(12,  'masculine',  '3 n.', 0,  0),
(13,  'masculine',  'cg.',  0,  0);

DROP TABLE IF EXISTS `classification_apply`;
CREATE TABLE `classification_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `classification_apply` (`id`, `classification_id`, `type_id`) VALUES
(25,  1,  1),
(26,  1,  4),
(27,  1,  10),
(28,  1,  11),
(41,  2,  1),
(42,  3,  1),
(46,  2,  11),
(47,  3,  4);

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SETTING_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `SETTING_VALUE` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `config` (`id`, `SETTING_NAME`, `SETTING_VALUE`) VALUES
(1, 'ENABLE_QUERY_CACHING', '0'),
(2, 'QC_TIME',  '100000'),
(3, 'SITE_TITLE', 'Donut Example Dictionary'),
(4, 'LOGO_TITLE', 'donut */ example*'),
(5, 'HOMEPAGE', 'home'),
(6, 'WIKI_ENABLE_HISTORY',  '1'),
(7, 'WIKI_HISTORY_ONLY_LOGGED', '0'),
(8, 'WIKI_ALLOW_GUEST_EDITING', '1'),
(9, 'WIKI_ALLOW_DISCUSSION_GUEST',  '0'),
(10,  'WIKI_ENABLE_DISCUSSION', '1'),
(11,  'HOME_TEXT',  '*Welcome to this dictionary*\r\n\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mauris turpis, feugiat non nulla vel, iaculis tincidunt erat. Aenean ac euismod mi. Nullam feugiat felis sed venenatis laoreet. Vestibulum sodales nisl vitae ex dignissim maximus. Nam hendrerit sed dolor et convallis. Phasellus nec ipsum eget eros porttitor accumsan. Duis pretium malesuada dui, vitae lobortis dolor faucibus sit amet. Donec interdum, turpis id pretium interdum, ante eros sagittis elit, vel aliquam elit est vel ex. Nullam nulla risus, fringilla ac posuere ut, convallis pretium magna. Fusce pellentesque quis erat vel dignissim. Curabitur in augue vel nisi laoreet placerat. Phasellus dapibus augue sed ex interdum, vulputate tristique nunc congue. Aenean efficitur sapien at libero tempor efficitur. Pellentesque facilisis posuere leo at elementum. Donec ac lectus nec lorem consequat dictum. Nulla facilisi. '),
(12,  'SITE_DESC',  ''),
(13,  'ACTIVE_LOCALE',  'English');

DROP TABLE IF EXISTS `derivations`;
CREATE TABLE `derivations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `derivations` (`id`, `name`, `short_name`) VALUES
(1, 'diminutive', 'dim.'),
(2, 'pronoun',  'pn.');

DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `parent_discussion` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `discussions_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `discussions` (`id`, `word_id`, `parent_discussion`, `user_id`, `points`, `content`, `table_name`, `post_date`, `last_update`) VALUES
(4, 1,  3,  2,  0,  'Jaweljawel!',  '', '2017-01-23 02:00:59',  '0000-00-00 00:00:00'),
(33,  1,  31, 1,  1,  'But I am stupid',  '', '2017-01-23 03:50:01',  '0000-00-00 00:00:00'),
(35,  32, 0,  1,  1,  'Jij en je, should they be two different lemmas?',  '', '2017-01-23 11:17:47',  '0000-00-00 00:00:00'),
(36,  32, 35, 1,  1,  'I don\'t know. Would that be useful?', '', '2017-01-23 12:07:05',  '0000-00-00 00:00:00'),
(39,  10, 38, 1,  1,  'Third level',  '', '2017-01-23 12:27:07',  '0000-00-00 00:00:00'),
(41,  10, 39, 1,  1,  'Fourth level', '', '2017-01-23 12:27:19',  '0000-00-00 00:00:00'),
(42,  10, 41, 1,  1,  'Fifth level',  '', '2017-01-23 12:27:26',  '0000-00-00 00:00:00'),
(43,  10, 42, 1,  1,  'Sixth level',  '', '2017-01-23 12:27:34',  '0000-00-00 00:00:00'),
(44,  10, 43, 1,  1,  'Seventh level',  '', '2017-01-23 12:27:55',  '0000-00-00 00:00:00'),
(45,  10, 44, 1,  1,  'Eighth level', '', '2017-01-23 12:28:05',  '0000-00-00 00:00:00'),
(46,  10, 45, 1,  1,  '9th level',  '', '2017-01-23 13:16:10',  '0000-00-00 00:00:00'),
(47,  10, 46, 1,  1,  '10th level', '', '2017-01-23 13:16:34',  '0000-00-00 00:00:00'),
(48,  44, 0,  1,  1,  'First',  '', '2017-01-23 13:50:50',  '0000-00-00 00:00:00'),
(52,  1,  51, 1,  1,  'Never mind, I don\'t think it\'s that important...', '', '2017-01-23 15:10:18',  '0000-00-00 00:00:00'),
(58,  1,  57, 1,  1,  'Eventuelt äkta maka.', '', '2017-01-23 16:27:55',  '0000-00-00 00:00:00'),
(59,  1,  55, 1,  1,  'karl kan va, och kanske även gubbe??', '', '2017-01-23 16:28:40',  '0000-00-00 00:00:00'),
(60,  1,  59, 1,  1,  'Gubbe i betydelse av \'een oude mannetje\'', '', '2017-01-23 16:28:59',  '0000-00-00 00:00:00'),
(66,  62, 0,  1,  1,  '**Etymology**\n[[62]] might come from [[maan]] right?',  '', '2017-01-30 00:28:14',  '0000-00-00 00:00:00'),
(67,  62, 66, 1,  1,  '(heeft niks met [[man]] te doen...)',  '', '2017-01-30 00:39:48',  '0000-00-00 00:00:00'),
(68,  10, 47, 1,  1,  '11th level', '', '2017-01-30 09:42:29',  '0000-00-00 00:00:00'),
(69,  61, 0,  1,  1,  'Maybe from [[maand]] ??',  '', '2017-01-30 13:53:57',  '0000-00-00 00:00:00'),
(70,  16, 0,  1,  1,  'test', '', '2017-01-31 00:21:27',  '2017-01-31 00:25:57'),
(71,  16, 0,  1,  1,  '', '', '2017-01-31 00:22:40',  '0000-00-00 00:00:00'),
(72,  16, 0,  1,  1,  'aaa',  '', '2017-01-31 00:22:42',  '0000-00-00 00:00:00'),
(73,  16, 70, 1,  1,  'vind ik ook [[Qe]]', '', '2017-01-31 00:27:50',  '0000-00-00 00:00:00'),
(74,  16, 0,  1,  1,  '[[1]]',  '', '2017-01-31 00:31:53',  '0000-00-00 00:00:00'),
(75,  16, 73, 1,  1,  '[[maan]] [[kat]] [[kater]] [[ao|katers]]', '', '2017-01-31 00:42:26',  '0000-00-00 00:00:00'),
(76,  7,  0,  1,  1,  '[[Xd|werkte]]',  '', '2017-01-31 01:31:50',  '0000-00-00 00:00:00'),
(77,  78, 0,  1,  1,  '[[p5m|test]] test',  '', '2017-01-31 02:12:34',  '0000-00-00 00:00:00'),
(78,  10, 68, 1,  1,  '12th level', '', '2017-02-05 19:40:59',  '0000-00-00 00:00:00'),
(79,  18, 0,  1,  1,  'Poesje miauw, kom eens gauw, \nik heb lekkere melk voor jou!', '', '2017-02-06 00:28:36',  '0000-00-00 00:00:00'),
(82,  1,  0,  1,  1,  'man\n',  '', '2017-02-12 15:08:24',  '0000-00-00 00:00:00'),
(83,  1,  0,  1,  1,  '**HOI**',  '', '2017-02-12 15:10:26',  '0000-00-00 00:00:00'),
(84,  1,  83, 1,  1,  'OOK HALLO',  '', '2017-02-12 15:10:45',  '0000-00-00 00:00:00'),
(87,  44, 0,  1,  1,  'test\n', '', '2017-02-18 00:47:46',  '0000-00-00 00:00:00'),
(89,  44, 87, 1,  1,  '/ ', '', '2017-02-18 00:48:08',  '0000-00-00 00:00:00'),
(90,  10, 0,  1,  1,  'Test', '', '2017-02-18 13:56:48',  '0000-00-00 00:00:00'),
(91,  10, 90, 1,  1,  'Test', '', '2017-02-18 13:56:54',  '0000-00-00 00:00:00'),
(92,  10, 91, 1,  1,  'test', '', '2017-02-18 13:57:03',  '0000-00-00 00:00:00'),
(93,  10, 0,  1,  1,  '[[Ma]]', '', '2017-02-18 13:57:18',  '0000-00-00 00:00:00'),
(94,  20, 0,  1,  1,  'This is a test discussion! :)',  '', '2017-02-18 14:05:18',  '0000-00-00 00:00:00'),
(95,  20, 94, 1,  1,  'Word links are cool! [[YG]] is a type of [[Ma]]',  '', '2017-02-18 14:05:49',  '0000-00-00 00:00:00'),
(96,  7,  76, 1,  1,  'Discussion threads are cool!', '', '2017-02-18 14:16:21',  '0000-00-00 00:00:00'),
(97,  7,  96, 1,  1,  ' Word links as well: [[ja]] [[Ma]] [[VX]]',  '', '2017-02-18 14:16:44',  '0000-00-00 00:00:00'),
(98,  7,  97, 1,  1,  'Oops, VX does not exist',  '', '2017-02-18 14:16:58',  '0000-00-00 00:00:00'),
(99,  17, 0,  3,  1,  'Meow!!', '', '2017-02-25 17:23:51',  '0000-00-00 00:00:00'),
(107, 1,  100,  3,  1,  'hoi',  'discussions',  '2017-02-26 00:45:52',  '0000-00-00 00:00:00'),
(108, 1,  100,  3,  1,  'hoi',  'discussions',  '2017-02-26 00:46:02',  '0000-00-00 00:00:00'),
(109, 1,  100,  3,  1,  'hoi',  'discussions',  '2017-02-26 00:46:36',  '0000-00-00 00:00:00'),
(110, 1,  100,  3,  1,  '', 'discussions',  '2017-02-26 00:46:45',  '0000-00-00 00:00:00'),
(111, 1,  100,  3,  1,  'hoi',  'discussions',  '2017-02-26 00:46:54',  '0000-00-00 00:00:00'),
(112, 28, 0,  3,  1,  '[[28]]', 'wiki', '2017-03-20 18:57:27',  '0000-00-00 00:00:00'),
(113, 28, 0,  3,  1,  '[[28]]', 'wiki', '2017-03-20 18:57:31',  '0000-00-00 00:00:00'),
(114, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:35',  '0000-00-00 00:00:00'),
(115, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:35',  '0000-00-00 00:00:00'),
(116, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:35',  '0000-00-00 00:00:00'),
(117, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:36',  '0000-00-00 00:00:00'),
(118, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:36',  '0000-00-00 00:00:00'),
(119, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:38',  '0000-00-00 00:00:00'),
(120, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:39',  '0000-00-00 00:00:00'),
(121, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:40',  '0000-00-00 00:00:00'),
(122, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:42',  '0000-00-00 00:00:00'),
(123, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:57:42',  '0000-00-00 00:00:00'),
(124, 28, 0,  3,  1,  '[[28]]  hoi\n',  'wiki', '2017-03-20 18:58:09',  '0000-00-00 00:00:00'),
(125, 28, 0,  3,  1,  'hoi\n',  'wiki', '2017-03-20 18:58:12',  '0000-00-00 00:00:00'),
(126, 28, 0,  3,  1,  'hoi\n',  'wiki', '2017-03-20 18:58:12',  '0000-00-00 00:00:00'),
(127, 28, 0,  3,  1,  'hoi\n',  'wiki', '2017-03-20 18:58:12',  '0000-00-00 00:00:00'),
(136, 1,  0,  3,  1,  'man',  'wiki', '2017-03-20 18:59:19',  '0000-00-00 00:00:00'),
(138, 1,  0,  3,  1,  '[[1]]',  'wiki', '2017-03-20 19:03:36',  '0000-00-00 00:00:00'),
(139, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:21',  '0000-00-00 00:00:00'),
(140, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:25',  '0000-00-00 00:00:00'),
(141, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:27',  '0000-00-00 00:00:00'),
(142, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:27',  '0000-00-00 00:00:00'),
(143, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:27',  '0000-00-00 00:00:00'),
(144, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:27',  '0000-00-00 00:00:00'),
(145, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(146, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(147, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(148, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(149, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(150, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(151, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:28',  '0000-00-00 00:00:00'),
(152, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:30',  '0000-00-00 00:00:00'),
(153, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:30',  '0000-00-00 00:00:00'),
(154, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:31',  '0000-00-00 00:00:00'),
(155, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:31',  '0000-00-00 00:00:00'),
(156, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:43',  '0000-00-00 00:00:00'),
(157, 1,  136,  1,  1,  'reply',  'wiki', '2017-03-27 20:53:45',  '0000-00-00 00:00:00');

DROP TABLE IF EXISTS `etymology`;
CREATE TABLE `etymology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `cognates_translations` varchar(255) NOT NULL COMMENT 'format: 1,3,5,90',
  `cognates_native` varchar(255) NOT NULL COMMENT 'format: 1,3,5,90',
  `desc` text NOT NULL,
  `first_attestation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `etymology_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `etymology` (`id`, `word_id`, `cognates_translations`, `cognates_native`, `desc`, `first_attestation`) VALUES
(1, 1,  '1',  '', 'From Old Dutch man, from Proto-Germanic *mann-, probably ultimately from Proto-Indo-European *man-.',  '900-1000 AD.'),
(3, 17, '23', '23', 'Herkomst onbekend. Verwant met [[kater]].',  '1162');

DROP TABLE IF EXISTS `gram_groups`;
CREATE TABLE `gram_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gram_groups` (`id`, `name`) VALUES
(1, 'Countable nouns -en (indef)'),
(2, 'Countable nouns -en (def)'),
(3, 'noun indef'),
(4, 'noun def'),
(5, 'verb, just stem'),
(6, 'verb, stem + t'),
(7, 'verb, stem + en'),
(8, 'verb, stem + en');

DROP TABLE IF EXISTS `gram_groups_aux`;
CREATE TABLE `gram_groups_aux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gram_group_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `inflect` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `placement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gram_group_id` (`gram_group_id`),
  KEY `word_id` (`word_id`),
  KEY `mode_id` (`mode_id`),
  CONSTRAINT `gram_groups_aux_ibfk_1` FOREIGN KEY (`gram_group_id`) REFERENCES `gram_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gram_groups_aux_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gram_groups_aux_ibfk_3` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gram_groups_aux` (`id`, `gram_group_id`, `word_id`, `inflect`, `mode_id`, `placement`) VALUES
(2, 2,  16, 1,  1,  0),
(3, 3,  2,  1,  1,  0),
(4, 4,  16, 1,  1,  0);

DROP TABLE IF EXISTS `gram_groups_irregular`;
CREATE TABLE `gram_groups_irregular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gram_group_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `stem` int(11) NOT NULL,
  `irregular_form` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gram_group_id` (`gram_group_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `gram_groups_irregular_ibfk_1` FOREIGN KEY (`gram_group_id`) REFERENCES `gram_groups` (`id`),
  CONSTRAINT `gram_groups_irregular_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`),
  CONSTRAINT `gram_groups_irregular_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `gram_groups_members`;
CREATE TABLE `gram_groups_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gram_group_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `submode_id` int(255) NOT NULL,
  `number_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `classification_id` int(11) NOT NULL DEFAULT '0',
  `subclassification_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mode_id` (`mode_id`),
  KEY `submode_id` (`submode_id`),
  KEY `number_id` (`number_id`),
  KEY `gram_group_id` (`gram_group_id`),
  CONSTRAINT `gram_groups_members_ibfk_1` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gram_groups_members_ibfk_2` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gram_groups_members_ibfk_5` FOREIGN KEY (`gram_group_id`) REFERENCES `gram_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gram_groups_members` (`id`, `gram_group_id`, `mode_id`, `submode_id`, `number_id`, `type_id`, `classification_id`, `subclassification_id`) VALUES
(5, 1,  1,  1,  3,  1,  0,  0),
(7, 2,  1,  2,  3,  1,  0,  0),
(9, 3,  1,  1,  1,  1,  0,  0),
(10,  4,  1,  2,  1,  0,  0,  0),
(11,  5,  7,  10, 11, 2,  0,  0),
(12,  6,  7,  10, 12, 2,  0,  0),
(13,  6,  7,  10, 13, 2,  0,  0),
(16,  7,  7,  11, 0,  0,  0,  0);

DROP TABLE IF EXISTS `graphemes`;
CREATE TABLE `graphemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `in_alphabet` int(11) NOT NULL DEFAULT '1',
  `groupstring` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CON',
  `ipa` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `quality` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sorter` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme` (`grapheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes` (`id`, `grapheme`, `in_alphabet`, `groupstring`, `ipa`, `quality`, `sorter`) VALUES
(12,  'a',  1,  'VOW',  '', '', 1),
(13,  'b',  1,  'CON',  '', '', 2),
(14,  'c',  1,  'CON',  '', '', 3),
(15,  'd',  1,  'CON',  '', '', 4),
(16,  'e',  1,  'VOW',  '', '', 5),
(17,  'f',  1,  'CON',  '', '', 6),
(18,  'g',  1,  'CON',  '', '', 7),
(19,  'h',  1,  'CON',  '', '', 8),
(20,  'i',  1,  'VOW',  '', '', 9),
(21,  'j',  1,  'VOW',  '', '', 10),
(22,  'k',  1,  'CON',  '', '', 11),
(23,  'l',  1,  'CON',  '', '', 12),
(24,  'm',  1,  'CON',  '', '', 13),
(25,  'n',  1,  'CON',  '', '', 14),
(26,  'o',  1,  'VOW',  '', '', 15),
(27,  'p',  1,  'CON',  '', '', 16),
(28,  'q',  1,  'CON',  '', '', 17),
(29,  'r',  1,  'CON',  '', '', 18),
(30,  's',  1,  'CON',  '', '', 19),
(31,  't',  1,  'CON',  '', '', 20),
(32,  'u',  1,  'VOW',  '', '', 21),
(33,  'v',  1,  'CON',  '', '', 22),
(34,  'w',  1,  'CON',  '', '', 23),
(35,  'x',  1,  'CON',  '', '', 24),
(36,  'y',  1,  'VOW',  '', '', 26),
(37,  'z',  1,  'CON',  '', '', 27);

DROP TABLE IF EXISTS `graphemes_groups`;
CREATE TABLE `graphemes_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupstring` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme` (`grapheme`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes_groups` (`id`, `grapheme`, `groupstring`) VALUES
(79,  'a',  'VOW'),
(80,  'b',  'CON'),
(81,  'c',  'CON'),
(82,  'd',  'CON'),
(83,  'e',  'VOW'),
(84,  'f',  'CON'),
(85,  'g',  'CON'),
(86,  'h',  'CON'),
(87,  'i',  'VOW'),
(88,  'j',  'CON'),
(89,  'k',  'CON'),
(90,  'l',  'CON'),
(91,  'm',  'CON'),
(92,  'n',  'CON'),
(93,  'o',  'VOW'),
(94,  'p',  'CON'),
(95,  'q',  'CON'),
(96,  'r',  'CON'),
(97,  's',  'CON'),
(98,  't',  'CON'),
(99,  'u',  'VOW'),
(100, 'v',  'CON'),
(101, 'w',  'CON'),
(102, 'x',  'CON'),
(103, 'y',  'VOW'),
(104, 'z',  'CON');

DROP TABLE IF EXISTS `graphemes_ipa_c`;
CREATE TABLE `graphemes_ipa_c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme_id` int(11) NOT NULL,
  `ipa_c_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme_id` (`grapheme_id`),
  KEY `ipa_c_id` (`ipa_c_id`),
  CONSTRAINT `graphemes_ipa_c_ibfk_1` FOREIGN KEY (`grapheme_id`) REFERENCES `graphemes` (`id`),
  CONSTRAINT `graphemes_ipa_c_ibfk_2` FOREIGN KEY (`ipa_c_id`) REFERENCES `ipa_c` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes_ipa_c` (`id`, `grapheme_id`, `ipa_c_id`) VALUES
(1, 24, 2);

DROP TABLE IF EXISTS `graphemes_ipa_v`;
CREATE TABLE `graphemes_ipa_v` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme_id` int(11) NOT NULL,
  `ipa_v_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme_id` (`grapheme_id`),
  KEY `ipa_v_id` (`ipa_v_id`),
  CONSTRAINT `graphemes_ipa_v_ibfk_1` FOREIGN KEY (`grapheme_id`) REFERENCES `graphemes` (`id`),
  CONSTRAINT `graphemes_ipa_v_ibfk_2` FOREIGN KEY (`ipa_v_id`) REFERENCES `ipa_v` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `homophones`;
CREATE TABLE `homophones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id_1` int(11) NOT NULL,
  `word_id_2` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `homophones` (`id`, `word_id_1`, `word_id_2`, `score`) VALUES
(1, 1,  2,  100);

DROP TABLE IF EXISTS `idioms`;
CREATE TABLE `idioms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `idioms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idioms` (`id`, `idiom`, `user_id`, `created_on`) VALUES
(1, 'man en vrouw', 1,  '2017-04-17 10:12:53'),
(2, 'Als kat en hond leven',  1,  '2017-04-17 10:12:53'),
(3, 'De kat uit de boom kijken',  1,  '2017-04-17 10:12:53'),
(4, 'Een kat in de zak kopen.', 1,  '2017-04-17 10:12:53');

DROP TABLE IF EXISTS `idiom_translations`;
CREATE TABLE `idiom_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `translation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idiom_id` (`idiom_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `idiom_translations_ibfk_1` FOREIGN KEY (`idiom_id`) REFERENCES `idioms` (`id`),
  CONSTRAINT `idiom_translations_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idiom_translations` (`id`, `idiom_id`, `language_id`, `translation`, `specification`) VALUES
(1, 1,  1,  'husband and wife', ''),
(3, 3,  1,  'See which way the wind blows', ''),
(4, 2,  1,  'To live like cat and dog / fight a lot', ''),
(5, 4,  1,  'buying a pig in a poke', '');

DROP TABLE IF EXISTS `idiom_words`;
CREATE TABLE `idiom_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idiom_id` (`idiom_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `idiom_words_ibfk_4` FOREIGN KEY (`idiom_id`) REFERENCES `idioms` (`id`),
  CONSTRAINT `idiom_words_ibfk_5` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idiom_words` (`id`, `idiom_id`, `word_id`, `keyword`) VALUES
(5, 1,  1,  'man'),
(6, 2,  17, 'kat'),
(8, 3,  17, 'kat'),
(9, 4,  17, 'kat'),
(32,  1,  9,  'vrouw');

DROP TABLE IF EXISTS `inflection_cache`;
CREATE TABLE `inflection_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inflection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `word_id` int(11) NOT NULL,
  `inflection_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `inflection_cache_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inflection_cache` (`id`, `inflection`, `word_id`, `inflection_hash`) VALUES
(549, 'leuke',  69, 'bbbf8fb3a8cdbd052fa5ce60516f9fac'),
(552, 'leukere',  69, '31f43dcc0353f362d0ab49b1beb2c7f9'),
(555, 'leukste',  69, '8c3ff81f1e5941a1889ee15850fea0b6'),
(558, 'werk', 7,  '8bf73c1e802b8e2a11e970c171f7cb6f'),
(559, 'werkt',  7,  '07faa339cbdf6c2f5d86aba01f6b37e2'),
(560, 'werkte', 7,  'ec721811e61b2ea5fa515964ceb95067'),
(561, 'werkten',  7,  '7839a539755e35dc00b94115f3402515'),
(562, 'gewerkt',  7,  'd4d21c00f71ddb8db3bc9dceb9e54b15'),
(563, 'wens', 85, '510f95aa3a9a9ebe7ccfa3cee2e94e49'),
(564, 'wenst',  85, 'a5e2431ab1e111a2f88cb06f57870c79'),
(565, 'wenste', 85, '389ee389ebc73ceb480ff4d7fb2effde'),
(566, 'wensten',  85, '7193ce9211a6b574531724f5154d9fec'),
(567, 'gewenst',  85, 'a6e22a1b2d2e225c090d07a97122040f'),
(568, 'jonge',  82, 'f9dfc45f94459e25cec1177959d980ca'),
(571, 'jongere',  82, 'aea48f06c8b4ede4347036903a90d1f4'),
(574, 'jongste',  82, '98ef73dcb4b6f5916ad105a42adf8001'),
(599, 'goede',  29, 'bbbf8fb3a8cdbd052fa5ce60516f9fac'),
(602, 'betere', 29, '31f43dcc0353f362d0ab49b1beb2c7f9'),
(605, 'beste',  29, '8c3ff81f1e5941a1889ee15850fea0b6'),
(606, 'beter',  29, '08a4948d772d85f5f936f2d66445d46c'),
(607, 'best', 29, 'dddd3245725628985e28c1044dd94bbf'),
(610, 'winn', 15, '8bf73c1e802b8e2a11e970c171f7cb6f'),
(611, 'winnt',  15, '07faa339cbdf6c2f5d86aba01f6b37e2'),
(612, 'winnte', 15, 'ec721811e61b2ea5fa515964ceb95067'),
(613, 'winnten',  15, '7839a539755e35dc00b94115f3402515'),
(614, 'gewinnt',  15, 'd4d21c00f71ddb8db3bc9dceb9e54b15'),
(615, 'leuker', 69, '08a4948d772d85f5f936f2d66445d46c'),
(616, 'leukst', 69, 'dddd3245725628985e28c1044dd94bbf'),
(625, 'jonger', 82, '4cd6edb84f9657e55a1f922766f8ef58'),
(626, 'jongst', 82, '747230e27465321b64d246c67142c0ff');

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hidden_native_entry` int(11) NOT NULL,
  `flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `activated` int(11) NOT NULL,
  `locale` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `alphabet` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `languages` (`id`, `name`, `hidden_native_entry`, `flag`, `activated`, `locale`, `alphabet`) VALUES
(0, 'Dutch',  0,  'nl', 1,  'NL', ''),
(1, 'English',  0,  'gb', 1,  'EN', ''),
(8, 'Polish', 0,  'pl', 1,  'PL', ''),
(10,  'Swedish',  0,  'se', 1,  'SV', ''),
(11,  'Hungarian',  0,  'hu', 1,  'HU', '');

DROP TABLE IF EXISTS `modes`;
CREATE TABLE `modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` int(11) NOT NULL,
  `mode_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `modes` (`id`, `name`, `short_name`, `hidden_native_entry`, `mode_type_id`) VALUES
(1, 'subject form', 'nom',  0,  2),
(6, 'object form',  'acc.', 0,  2),
(7, 'present simple', 'ps.',  0,  1),
(8, 'Past simple',  'pts.', 0,  1),
(21,  'Present perfect',  'pre per',  0,  1),
(22,  'Past perfect', 'pas per',  0,  1),
(23,  'diminutive', 'dim',  0,  5),
(24,  'Singular', 'sg', 0,  3),
(25,  'Plural', 'pl.',  0,  3),
(26,  'Partative',  'part', 0,  4);

DROP TABLE IF EXISTS `mode_apply`;
CREATE TABLE `mode_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ignore_submodes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mode_id` (`mode_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `mode_apply_ibfk_1` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mode_apply_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `mode_apply` (`id`, `mode_id`, `type_id`, `ignore_submodes`) VALUES
(1, 1,  1,  0),
(2, 1,  3,  0),
(3, 1,  4,  0),
(5, 1,  10, 0),
(6, 1,  11, 0),
(8, 7,  2,  0),
(14,  6,  4,  0),
(15,  24, 5,  0),
(16,  25, 5,  0),
(17,  26, 5,  0);

DROP TABLE IF EXISTS `mode_types`;
CREATE TABLE `mode_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `mode_types` (`id`, `name`) VALUES
(1, 'verbal'),
(2, 'noun case'),
(3, 'adjectival case'),
(4, 'single conjugation type'),
(5, 'noun mode');

DROP TABLE IF EXISTS `morphology`;
CREATE TABLE `morphology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology` (`id`, `name`, `rule`) VALUES
(4, 'Plural endings -> phonological variable',  '[-en]&EN;'),
(5, 'verb, just stem',  '[-en]'),
(6, 'verb, stem + t', '[-en]t'),
(7, 'Verb -en plural test', '[-en]-en'),
(8, 'Nieuwe regel', 'hoi'),
(9, 'Past participle',  'ge?!^ver+?!^be+?!^ge+[-en]&D'),
(10,  'Past particple', 'ge?!^ver+?!^ge+?!^be+[-en]&D'),
(11,  'Past particple', 'ge?!^ver+?!^ge+?!^be+[-en]&D'),
(12,  'test', '[]en'),
(13,  'test', 'test'),
(14,  'test', 'test'),
(15,  'test2',  '[]test'),
(16,  'test2',  '[]test'),
(17,  'test', '[]test'),
(18,  'wide scope test only nouns', '[]-noun');

DROP TABLE IF EXISTS `morphology_gramcat`;
CREATE TABLE `morphology_gramcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `gramcat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `gramcat_id` (`gramcat_id`),
  CONSTRAINT `morphology_gramcat_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_gramcat_ibfk_2` FOREIGN KEY (`gramcat_id`) REFERENCES `classifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_gramcat` (`id`, `morphology_id`, `gramcat_id`) VALUES
(2, 7,  5),
(4, 16, 2);

DROP TABLE IF EXISTS `morphology_irregular`;
CREATE TABLE `morphology_irregular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `stem` int(11) NOT NULL,
  `irregular_form` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `morphology_irregular_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`),
  CONSTRAINT `morphology_irregular_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_irregular_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `morphology_lexcat`;
CREATE TABLE `morphology_lexcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `lexcat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `lexcat_id` (`lexcat_id`),
  CONSTRAINT `morphology_lexcat_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_lexcat_ibfk_2` FOREIGN KEY (`lexcat_id`) REFERENCES `types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_lexcat` (`id`, `morphology_id`, `lexcat_id`) VALUES
(1, 7,  2),
(3, 12, 1),
(7, 16, 1),
(8, 17, 7),
(9, 18, 1);

DROP TABLE IF EXISTS `morphology_modes`;
CREATE TABLE `morphology_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `mode_id` (`mode_id`),
  CONSTRAINT `morphology_modes_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_modes_ibfk_2` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_modes` (`id`, `morphology_id`, `mode_id`) VALUES
(1, 7,  7);

DROP TABLE IF EXISTS `morphology_numbers`;
CREATE TABLE `morphology_numbers` (
  `id` int(11) NOT NULL,
  `morphology_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  KEY `morphology_id` (`morphology_id`),
  KEY `number_id` (`number_id`),
  CONSTRAINT `morphology_numbers_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_numbers_ibfk_2` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_numbers` (`id`, `morphology_id`, `number_id`) VALUES
(1, 7,  11),
(2, 7,  12),
(3, 7,  13);

DROP TABLE IF EXISTS `morphology_submodes`;
CREATE TABLE `morphology_submodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `submode_id` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `submode_id` (`submode_id`),
  CONSTRAINT `morphology_submodes_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_submodes_ibfk_2` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_submodes` (`id`, `morphology_id`, `submode_id`) VALUES
(1, 7,  11);

DROP TABLE IF EXISTS `morphology_tags`;
CREATE TABLE `morphology_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `morphology_tags_ibfk_3` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_tags_ibfk_4` FOREIGN KEY (`tag_id`) REFERENCES `subclassifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `numbers`;
CREATE TABLE `numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` varchar(255) NOT NULL,
  `hidden_native_entry_short` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `numbers` (`id`, `name`, `short_name`, `hidden_native_entry`, `hidden_native_entry_short`) VALUES
(1, 'singular', 'sg.',  '0',  0),
(3, 'plural', 'pl', '0',  0),
(8, 'positive', 'bf.',  '0',  0),
(9, 'comperative',  'comp.',  '0',  0),
(10,  'superlative',  '0',  '0',  0),
(11,  'first person', '1',  '0',  0),
(12,  'second person',  '2',  '0',  0),
(13,  '3rd person', '3',  '0',  0);

DROP TABLE IF EXISTS `number_apply`;
CREATE TABLE `number_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number_id` int(11) NOT NULL,
  `mode_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `number_id` (`number_id`),
  KEY `type_id` (`mode_type_id`),
  CONSTRAINT `number_apply_ibfk_1` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `number_apply_ibfk_2` FOREIGN KEY (`mode_type_id`) REFERENCES `mode_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `number_apply` (`id`, `number_id`, `mode_type_id`) VALUES
(25,  1,  2),
(28,  8,  3),
(29,  9,  3),
(30,  10, 3),
(33,  1,  4),
(34,  3,  4),
(35,  3,  2),
(36,  11, 1),
(37,  12, 1),
(38,  13, 1);

DROP TABLE IF EXISTS `phonology_contexts`;
CREATE TABLE `phonology_contexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phonology_contexts` (`id`, `name`, `rule`) VALUES
(3, 'vowel lengthening before endings', 'CON_[aeou]_CON.+.&T,D=>%%'),
(4, 'variable vowel lengthening', 'CON_&A,E,O,U_CON.+.&T,D=>%%'),
(5, 'kofschip dental suffix', '[x,k,f,s,c,h,p].+_&D_=>t'),
(6, 'z to s before endings',  '_z_+=>s'),
(7, 'VOW.v to f', 'VOW_v_+=>f'),
(8, 'no z at word end', '_z_:>=>s'),
(9, 'no v at word end', '_v_:>=>f'),
(10,  'no double b (hebbt -> hebt)',  'CON.VOW._b.b_+.&D,T=>b'),
(11,  'no double b (hebbt -> hebt) 2',  'CON.VOW_b.b_+.t=>b'),
(12,  'vowel lengthening at word end',  '*.CON_VOW_CON.:>=>%%'),
(13,  'variable EE to e before z',  'CON_&EE_z=>e'),
(14,  'vowel lengthening 3',  '<:CON._VOW.VOW__CON.+=>$1'),
(15,  'trema e',  'VOW.+_e_=>ë'),
(16,  's+en → z+en',  '[^r,n]_s_+.e.n=>z'),
(19,  'lens => lenzen', '_l.e.n.s_+.e.n=>lenz'),
(20,  'plural phonological context 1',  '[e].[e].[r].[d].[e].+_&EN_=>n'),
(21,  'plural dimunitves',  'je.+_&EN_=>s'),
(22,  'plural open vowel',  '[aioyu].+_&EN_=>\'s'),
(23,  'plural mute e',  'e.+_&EN_=>s'),
(24,  'double consonant before plural ending',  '_CON_+.&EN=>%%'),
(26,  'endes => enden', 'CON.ende.+_s_=>n'),
(27,  'dim ng => nk', '_ng_+.nkje=>'),
(29,  'etje => pje if long vowel 1',  '(VOW)_(1)_CON.+.&ETJE=>#%#'),
(30,  'double consonant before endings 2',  'CON.VOW_CON_+.&E=>%%'),
(31,  'fototje => fotootje',  'CON_VOW_+.tje=>%%'),
(33,  'double consonant before endings',  '^VOW.VOW_CON_+.^CON=>%%'),
(34,  'etje => pje if long vowel 2',  '#.VOW.#.CON.+_&ETJE_=>pje');

DROP TABLE IF EXISTS `phonology_ipa_generation`;
CREATE TABLE `phonology_ipa_generation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `row_native`;
CREATE TABLE `row_native` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `row_id` (`row_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `row_native_ibfk_1` FOREIGN KEY (`row_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `row_native_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings_boolean`;
CREATE TABLE `settings_boolean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings_boolean` (`id`, `name`, `value`) VALUES
(1, 'prodrop',  0);

DROP TABLE IF EXISTS `subclassifications`;
CREATE TABLE `subclassifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `native_hidden_entry` int(11) NOT NULL,
  `native_hidden_entry_short` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `subclassifications` (`id`, `name`, `short_name`, `native_hidden_entry`, `native_hidden_entry_short`) VALUES
(1, 'Class 1 verb', 'cl. 1',  0,  0),
(2, 'Singular', 'sg.',  0,  0),
(3, 'Plural', 'pl.',  0,  0),
(4, 'synthetical superlative',  'synthetical superlative',  0,  0),
(5, 'compund superlative',  'adj-cs.',  0,  0),
(6, 'countable',  'cnt.', 0,  0);

DROP TABLE IF EXISTS `subclassification_apply`;
CREATE TABLE `subclassification_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subclassification_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subclassification_id` (`subclassification_id`),
  KEY `classification_id` (`classification_id`),
  CONSTRAINT `subclassification_apply_ibfk_1` FOREIGN KEY (`subclassification_id`) REFERENCES `subclassifications` (`id`),
  CONSTRAINT `subclassification_apply_ibfk_2` FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `subclassification_apply` (`id`, `subclassification_id`, `classification_id`) VALUES
(2, 1,  5),
(3, 2,  8),
(4, 2,  9),
(5, 2,  10),
(6, 2,  11),
(7, 2,  12),
(8, 3,  8),
(9, 3,  9),
(10,  3,  10),
(11,  3,  11),
(12,  3,  12),
(13,  4,  3),
(14,  4,  13),
(15,  5,  13),
(16,  5,  3);

DROP TABLE IF EXISTS `submodes`;
CREATE TABLE `submodes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` varchar(255) NOT NULL,
  `mode_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mode_type_id` (`mode_type_id`),
  CONSTRAINT `submodes_ibfk_1` FOREIGN KEY (`mode_type_id`) REFERENCES `mode_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submodes` (`id`, `name`, `short_name`, `hidden_native_entry`, `mode_type_id`) VALUES
(1, 'indefinite', 'indef',  '0',  2),
(2, 'definite', 'def',  '0',  2),
(3, 'strong', 'str.', '0',  3),
(4, 'weak', 'wk.',  '0',  2),
(5, 'First person', '', 'DV', 1),
(6, 'Second person',  '', '0',  1),
(7, 'Third Person', '', '0',  1),
(8, 'conjugation',  'sg conj p',  ' ',  4),
(9, 'conjugation',  'sg pn',  '0',  4),
(10,  'singular', 'sg.',  '0',  1),
(11,  'plural', 'pl', '0',  1);

DROP TABLE IF EXISTS `submode_apply`;
CREATE TABLE `submode_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submode_id` int(11) NOT NULL,
  `mode_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`mode_type_id`),
  KEY `submode_id` (`submode_id`),
  CONSTRAINT `submode_apply_ibfk_2` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submode_apply_ibfk_3` FOREIGN KEY (`mode_type_id`) REFERENCES `mode_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submode_apply` (`id`, `submode_id`, `mode_type_id`) VALUES
(20,  1,  2),
(21,  3,  3),
(22,  8,  4),
(23,  4,  3),
(27,  2,  2),
(28,  10, 1),
(29,  11, 1);

DROP TABLE IF EXISTS `submode_groups`;
CREATE TABLE `submode_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submode_groups` (`id`, `name`, `type_id`) VALUES
(1, 'Verb - plural persons, perfect', 2),
(2, 'Noun - indefinite',  1),
(3, 'Noun - definite',  1),
(4, 'Verb - plural persons, present', 2),
(5, 'Verb - plural persons, past',  2),
(6, 'Verb - singular persons, past',  2),
(7, 'Irregular verbs, plural persons present',  2),
(8, 'Irregular verbs, plural persons past', 2),
(9, 'Irregular verbs, singular persons past', 2),
(10,  'Verb - regular past participle', 2),
(11,  'Irregular verbs, past participle', 2),
(12,  'Articles', 10),
(13,  'diminutive - sg.', 1),
(14,  'Noun - plural - en', 1),
(15,  'Pronouns - object form', 4),
(16,  'adj. base without e',  0),
(17,  'adj. base with e', 5),
(18,  'adj. comparative sg and pl', 5),
(19,  'adj. superlative sg and pl', 5);

DROP TABLE IF EXISTS `submode_group_members`;
CREATE TABLE `submode_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submode_group_id` int(11) NOT NULL,
  `submode_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `aux_mode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `submode_group_id` (`submode_group_id`),
  KEY `number_id` (`number_id`),
  KEY `submode_id` (`submode_id`),
  KEY `classification_id` (`classification_id`),
  KEY `mode_id` (`mode_id`),
  CONSTRAINT `submode_group_members_ibfk_4` FOREIGN KEY (`submode_group_id`) REFERENCES `submode_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submode_group_members_ibfk_5` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submode_group_members_ibfk_6` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submode_group_members_ibfk_7` FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`),
  CONSTRAINT `submode_group_members_ibfk_8` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submode_group_members` (`id`, `submode_group_id`, `submode_id`, `type_id`, `number_id`, `classification_id`, `mode_id`, `aux_mode_id`) VALUES
(1, 2,  1,  0,  1,  1,  1,  1),
(2, 2,  1,  0,  1,  2,  1,  1),
(4, 2,  1,  0,  1,  3,  1,  1),
(5, 2,  1,  0,  1,  1,  6,  6),
(6, 2,  1,  0,  1,  2,  6,  6),
(15,  4,  5,  0,  3,  5,  7,  7),
(16,  4,  6,  0,  3,  5,  7,  7),
(17,  4,  7,  0,  3,  5,  7,  7),
(18,  5,  5,  0,  3,  5,  8,  8),
(19,  5,  6,  0,  3,  5,  8,  8),
(20,  5,  7,  0,  3,  5,  8,  8),
(21,  6,  5,  0,  1,  5,  8,  8),
(22,  6,  6,  0,  1,  5,  8,  8),
(23,  6,  7,  0,  1,  5,  8,  8),
(24,  7,  5,  0,  3,  6,  7,  7),
(25,  7,  6,  0,  3,  6,  7,  7),
(26,  7,  7,  0,  3,  6,  7,  7),
(27,  8,  5,  0,  3,  6,  8,  8),
(28,  8,  6,  0,  3,  6,  8,  8),
(29,  8,  7,  0,  3,  6,  8,  8),
(30,  9,  5,  0,  1,  6,  8,  8),
(31,  9,  6,  0,  1,  6,  8,  8),
(32,  9,  7,  0,  1,  6,  8,  8),
(33,  10, 5,  0,  1,  5,  21, 7),
(34,  10, 6,  0,  1,  5,  21, 7),
(35,  10, 7,  0,  1,  5,  21, 7),
(36,  10, 5,  0,  3,  5,  21, 7),
(37,  10, 6,  0,  3,  5,  21, 7),
(38,  10, 7,  0,  3,  5,  21, 7),
(39,  10, 5,  0,  1,  5,  22, 8),
(40,  10, 6,  0,  1,  5,  22, 8),
(41,  10, 7,  0,  1,  5,  22, 8),
(42,  10, 5,  0,  3,  5,  22, 8),
(43,  10, 6,  0,  3,  5,  22, 8),
(44,  10, 7,  0,  3,  5,  22, 8),
(45,  11, 5,  0,  1,  6,  21, 7),
(46,  11, 6,  0,  1,  6,  21, 7),
(47,  11, 7,  0,  1,  6,  21, 7),
(48,  11, 5,  0,  3,  6,  21, 7),
(49,  11, 6,  0,  3,  6,  21, 7),
(50,  11, 7,  0,  3,  6,  21, 7),
(51,  11, 5,  0,  1,  6,  22, 8),
(52,  11, 6,  0,  1,  6,  22, 8),
(53,  11, 7,  0,  1,  6,  22, 8),
(54,  11, 5,  0,  3,  6,  22, 8),
(55,  11, 6,  0,  3,  6,  22, 8),
(56,  11, 7,  0,  3,  6,  22, 8),
(60,  13, 1,  0,  1,  1,  1,  23),
(61,  13, 1,  0,  1,  2,  1,  23),
(63,  12, 2,  0,  3,  1,  1,  1),
(65,  14, 1,  0,  3,  1,  1,  1),
(66,  14, 2,  0,  3,  1,  1,  1),
(67,  15, 9,  0,  1,  8,  6,  6),
(68,  15, 9,  0,  1,  9,  6,  6),
(69,  15, 9,  0,  1,  10, 6,  6),
(70,  15, 9,  0,  1,  11, 6,  6),
(71,  15, 9,  0,  1,  12, 6,  6),
(72,  13, 1,  1,  1,  1,  23, 23),
(73,  17, 4,  5,  1,  1,  24, 24),
(74,  18, 3,  5,  9,  13, 24, 24),
(75,  18, 3,  5,  9,  3,  24, 24),
(76,  18, 4,  5,  9,  13, 24, 24),
(77,  18, 4,  5,  9,  3,  24, 24),
(79,  17, 4,  5,  1,  1,  25, 25),
(80,  18, 3,  5,  9,  13, 25, 25),
(81,  18, 3,  5,  9,  3,  25, 25),
(82,  18, 4,  5,  9,  13, 25, 25),
(83,  18, 4,  5,  9,  3,  25, 25),
(86,  19, 3,  5,  10, 13, 24, 24),
(87,  19, 3,  5,  10, 3,  24, 24),
(88,  19, 4,  5,  10, 13, 24, 24),
(89,  19, 4,  5,  10, 3,  24, 24),
(90,  19, 3,  5,  10, 13, 25, 25),
(91,  19, 3,  5,  10, 3,  25, 25),
(92,  19, 4,  5,  10, 13, 25, 25),
(93,  19, 4,  5,  10, 3,  25, 25),
(94,  14, 1,  1,  1,  2,  1,  1);

DROP TABLE IF EXISTS `submode_native_numbers`;
CREATE TABLE `submode_native_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `native` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `submode_id` int(255) NOT NULL,
  `number_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `submode_id` (`submode_id`),
  KEY `number_id` (`number_id`),
  CONSTRAINT `submode_native_numbers_ibfk_1` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`),
  CONSTRAINT `submode_native_numbers_ibfk_2` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `submode_native_numbers` (`id`, `native`, `submode_id`, `number_id`) VALUES
(1, 'DV', 5,  1),
(2, 'pP', 6,  1),
(3, 'lp', 7,  1),
(4, 'xz', 7,  1),
(5, 'Nxk',  5,  3),
(6, 'qKm',  6,  3),
(7, 'XXj',  7,  3);

DROP TABLE IF EXISTS `synonyms`;
CREATE TABLE `synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id_1` int(11) NOT NULL,
  `word_id_2` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id_1` (`word_id_1`),
  KEY `word_id_2` (`word_id_2`),
  CONSTRAINT `synonyms_ibfk_3` FOREIGN KEY (`word_id_1`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `synonyms_ibfk_4` FOREIGN KEY (`word_id_2`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `synonyms` (`id`, `word_id_1`, `word_id_2`, `score`) VALUES
(1, 1,  10, 50),
(2, 1,  11, 100),
(3, 11, 10, 50),
(7, 17, 18, 100),
(10,  17, 23, 50);

DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `translation` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `translations_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `translations_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `translations` (`id`, `language_id`, `translation`, `description`, `created_on`, `user_id`) VALUES
(1, 10, 'man',  'An adult living being of the male gender', '2017-05-10 22:31:12',  1),
(2, 1,  'husband',  '', '2017-04-14 21:41:39',  1),
(3, 1,  'work', '', '2017-04-14 21:41:39',  1),
(4, 1,  'function', '', '2017-04-14 21:41:39',  1),
(5, 1,  'woman',  '', '2017-04-14 21:41:39',  1),
(6, 1,  'wife', '', '2017-04-14 21:41:39',  1),
(7, 1,  'have', '', '2017-04-14 21:41:39',  1),
(10,  1,  'he', '', '2017-04-14 21:41:39',  1),
(11,  1,  'uncle',  '', '2017-04-14 21:41:39',  1),
(13,  1,  'lose', '', '2017-04-14 21:41:39',  1),
(15,  1,  'win',  '', '2017-04-14 21:41:39',  1),
(23,  1,  'cat',  '', '2017-04-14 21:41:39',  1),
(25,  1,  'pussy',  '', '2017-04-14 21:41:39',  1),
(26,  1,  'feline', '', '2017-04-14 21:41:39',  1),
(30,  1,  'vagina', '', '2017-04-14 21:41:39',  1),
(32,  1,  'kitten', '', '2017-04-14 21:41:39',  1),
(33,  0,  'persoon',  '', '2017-04-14 21:41:39',  1),
(34,  1,  'one',  '', '2017-04-14 21:41:39',  1),
(35,  1,  'everyone', '', '2017-04-14 21:41:39',  1),
(36,  1,  'tomcat', '', '2017-04-14 21:41:39',  1),
(38,  1,  'hangover', '', '2017-04-14 21:41:39',  1),
(39,  1,  'mister', '', '2017-04-14 21:41:39',  1),
(42,  0,  'mijnheer', '', '2017-04-14 21:41:39',  1),
(45,  1,  'she',  '', '2017-04-14 21:41:39',  1),
(48,  1,  'good', '', '2017-04-14 21:41:39',  1),
(49,  1,  'ice',  '', '2017-04-14 21:41:39',  1),
(50,  1,  'ice cream',  '', '2017-04-14 21:41:39',  1),
(51,  1,  'I',  '', '2017-04-14 21:41:39',  1),
(52,  1,  'you',  '', '2017-04-14 21:41:39',  1),
(54,  1,  'we', '', '2017-04-14 21:41:39',  1),
(55,  1,  'potatoe',  '', '2017-04-14 21:41:39',  1),
(56,  1,  'earth',  '', '2017-04-14 21:41:39',  1),
(57,  1,  'all',  '', '2017-04-14 21:41:39',  1),
(58,  1,  'pet',  '', '2017-04-14 21:41:39',  1),
(72,  1,  'party',  '', '2017-04-14 21:41:39',  1),
(73,  1,  'feast',  '', '2017-04-14 21:41:39',  1),
(74,  1,  'celebration',  '', '2017-04-14 21:41:39',  1),
(79,  1,  'pancake',  '', '2017-04-14 21:41:39',  1),
(80,  1,  'fool', '', '2017-04-14 21:41:39',  1),
(81,  1,  'pancake',  '', '2017-04-14 21:41:39',  1),
(82,  1,  'pancake',  '', '2017-04-14 21:41:39',  1),
(83,  1,  'pancake',  '', '2017-04-14 21:41:39',  1),
(86,  1,  'stone',  '', '2017-04-14 21:41:39',  1),
(87,  1,  'stone',  '', '2017-04-14 21:41:39',  1),
(88,  1,  'manner', '', '2017-04-14 21:41:39',  1),
(89,  1,  'way',  '', '2017-04-14 21:41:39',  1),
(90,  1,  'mood of speech', '', '2017-04-14 21:41:39',  1),
(91,  1,  'sage', '', '2017-04-14 21:41:39',  1),
(92,  1,  'wise man', '', '2017-04-14 21:41:39',  1),
(93,  1,  'nose', '', '2017-04-14 21:41:39',  1),
(94,  1,  'moon', '', '2017-04-14 21:42:08',  1),
(95,  1,  'month',  '', '2017-04-14 21:42:08',  1),
(96,  1,  'bad',  '', '2017-04-14 21:42:08',  1),
(97,  1,  'lemon',  '', '2017-04-14 21:42:08',  1),
(98,  1,  'Lithviscian',  '', '2017-04-14 21:42:08',  1),
(99,  1,  'boy',  '', '2017-04-14 21:42:08',  1),
(100, 1,  'rain', '', '2017-04-14 21:42:08',  1),
(101, 1,  'hail', '', '2017-04-14 21:42:08',  1),
(102, 1,  'picnic', '', '2017-04-14 21:42:08',  1),
(103, 1,  'dog',  '', '2017-04-14 21:42:08',  1),
(104, 1,  'hound',  '', '2017-04-14 21:42:08',  1),
(105, 1,  'pleasant', '', '2017-04-14 21:42:08',  1),
(106, 1,  'nice', '', '2017-04-14 21:42:08',  1),
(107, 1,  'enoyable', '', '2017-04-14 21:42:08',  1),
(108, 1,  'likable',  '', '2017-04-14 21:42:08',  1),
(109, 1,  'good-looking', '', '2017-04-14 21:42:08',  1),
(110, 1,  'quote',  '', '2017-04-14 21:42:08',  1),
(111, 1,  'tree', '', '2017-04-14 21:42:08',  1),
(112, 1,  'wooden object',  '', '2017-04-14 21:42:08',  1),
(113, 1,  'pretty', '', '2017-04-14 21:42:08',  1),
(114, 1,  'beautiful',  '', '2017-04-14 21:42:08',  1),
(115, 1,  'nice', '', '2017-04-14 21:42:08',  1),
(116, 1,  'better', '', '2017-04-14 21:42:08',  1),
(117, 1,  'best', '', '2017-04-14 21:42:08',  1),
(118, 1,  'bad',  '', '2017-04-14 21:42:08',  1),
(119, 1,  'worse',  '', '2017-04-14 21:42:08',  1),
(120, 1,  'worst',  '', '2017-04-14 21:42:08',  1),
(131, 1,  'book', '', '2017-04-14 21:42:08',  1),
(132, 1,  'bread',  '', '2017-04-14 21:42:08',  1),
(198, 8,  'mężczyzna',  '', '2017-04-14 21:42:08',  1),
(199, 8,  'pan',  '', '2017-04-14 21:42:08',  1),
(200, 8,  'człowiek', '', '2017-04-14 21:42:08',  1),
(201, 1,  'a',  '', '2017-04-14 21:42:08',  1),
(202, 1,  'an', '', '2017-04-14 21:42:08',  1),
(203, 1,  'cheer',  '', '2017-04-14 21:42:08',  1),
(204, 1,  'shout',  '', '2017-04-14 21:42:08',  1),
(205, 1,  'husband',  '', '2017-04-14 21:42:08',  1),
(206, 1,  'the',  '', '2017-04-14 21:42:08',  1),
(207, 1,  'little man', '', '2017-04-14 21:42:08',  1),
(208, 1,  'male', '', '2017-04-14 21:42:08',  1),
(209, 1,  'guy that preforms odd jobs', '', '2017-04-14 21:42:08',  1),
(210, 1,  'little cat', '', '2017-04-14 21:42:08',  1),
(211, 1,  'vice', '', '2017-04-14 21:42:08',  1),
(212, 8,  'mać',  '', '2017-04-14 21:42:08',  1),
(213, 8,  'pracować', '', '2017-04-14 21:42:08',  1),
(214, 8,  'działać',  '', '2017-04-14 21:42:08',  1),
(215, 8,  'udać się', '', '2017-04-14 21:42:08',  1),
(216, 8,  'wiwatować',  '', '2017-04-14 21:42:08',  1),
(217, 8,  'kobieta',  '', '2017-04-14 21:42:08',  1),
(218, 8,  'żona', '', '2017-04-14 21:42:08',  1),
(219, 8,  'żona', '', '2017-04-14 21:42:08',  1),
(220, 8,  'on', '', '2017-04-14 21:42:08',  1),
(221, 8,  '', '', '2017-04-14 21:42:08',  1),
(222, 8,  'kot',  '', '2017-04-14 21:42:08',  1),
(223, 8,  'kot',  '', '2017-04-14 21:42:08',  1),
(224, 8,  'kot',  '', '2017-04-14 21:42:08',  1),
(225, 8,  'pochwa', '', '2017-04-14 21:42:08',  1),
(226, 8,  'kot',  '', '2017-04-14 21:42:08',  1),
(227, 8,  'kocur',  '', '2017-04-14 21:42:08',  1),
(228, 8,  'kocurek',  '', '2017-04-14 21:42:08',  1),
(229, 8,  'pan',  '', '2017-04-14 21:42:08',  1),
(230, 8,  'ona',  '', '2017-04-14 21:42:08',  1),
(231, 8,  'kocur',  '', '2017-04-14 21:42:08',  1),
(232, 8,  'kac',  '', '2017-04-14 21:42:08',  1),
(233, 1,  'onion',  '', '2017-04-14 21:42:08',  1),
(234, 8,  'cebula', '', '2017-04-14 21:42:08',  1),
(235, 8,  'pies', '', '2017-04-14 21:42:08',  1),
(242, 8,  'ja', '', '2017-04-14 21:42:08',  1),
(243, 8,  'mnie', '', '2017-04-14 21:42:08',  1),
(244, 1,  'me', '', '2017-04-14 21:42:08',  1),
(245, 1,  'I',  '', '2017-04-14 21:42:08',  1),
(246, 1,  'me', '', '2017-04-14 21:42:08',  1),
(247, 8,  'dobry',  '', '2017-04-14 21:42:08',  1),
(248, 8,  'dobrze', '', '2017-04-14 21:42:08',  1),
(249, 8,  'zdrowy', '', '2017-04-14 21:42:08',  1),
(250, 8,  'odpowiedni', '', '2017-04-14 21:42:08',  1),
(251, 8,  'lody', '', '2017-04-14 21:42:08',  1),
(252, 8,  'ty', '', '2017-04-14 21:42:08',  1),
(253, 8,  'ty', '', '2017-04-14 21:42:08',  1),
(254, 8,  'my', '', '2017-04-14 21:42:08',  1),
(255, 8,  'my', '', '2017-04-14 21:42:08',  1),
(256, 8,  'ziemniak', '', '2017-04-14 21:42:08',  1),
(257, 8,  'kartoffel',  '', '2017-04-14 21:42:08',  1),
(258, 10, 'man',  '', '2017-04-14 21:42:08',  1),
(259, 10, 'make', '', '2017-04-14 21:42:08',  1),
(260, 10, 'gubbe',  '', '2017-04-14 21:42:08',  1),
(261, 10, 'karl', '', '2017-04-14 21:42:08',  1),
(262, 10, 'en', '', '2017-04-14 21:42:08',  1),
(263, 10, 'ett',  '', '2017-04-14 21:42:08',  1),
(264, 10, 'ha', '', '2017-04-14 21:42:08',  1),
(265, 10, 'jobba',  '', '2017-04-14 21:42:08',  1),
(266, 10, 'jubla',  '', '2017-04-14 21:42:08',  1),
(267, 10, 'kvinna', '', '2017-04-14 21:42:08',  1),
(268, 10, 'make', '', '2017-04-14 21:42:08',  1),
(269, 10, 'hustru', '', '2017-04-14 21:42:08',  1),
(270, 10, 'han',  '', '2017-04-14 21:42:08',  1),
(271, 10, 'farbror',  '', '2017-04-14 21:42:08',  1),
(272, 10, 'morbror',  '', '2017-04-14 21:42:08',  1),
(273, 10, 'tappa',  '', '2017-04-14 21:42:08',  1),
(274, 10, 'förlora',  '', '2017-04-14 21:42:08',  1),
(275, 10, 'vinna',  '', '2017-04-14 21:42:08',  1),
(276, 10, 'den',  '', '2017-04-14 21:42:08',  1),
(277, 10, 'det',  '', '2017-04-14 21:42:08',  1),
(278, 10, '-en',  '', '2017-04-14 21:42:08',  1),
(279, 10, '-et',  '', '2017-04-14 21:42:08',  1),
(280, 10, '-na',  '', '2017-04-14 21:42:08',  1),
(281, 10, 'katt', '', '2017-04-14 21:42:08',  1),
(282, 10, 'man',  '', '2017-04-14 21:42:08',  1),
(283, 10, 'hon',  '', '2017-04-14 21:42:08',  1),
(284, 10, 'bra',  '', '2017-04-14 21:42:08',  1),
(285, 10, 'god',  '', '2017-04-14 21:42:08',  1),
(286, 10, 'is', '', '2017-04-14 21:42:08',  1),
(287, 10, 'glass',  '', '2017-04-14 21:42:08',  1),
(288, 10, 'jag',  '', '2017-04-14 21:42:08',  1),
(289, 10, 'du', '', '2017-04-14 21:42:08',  1),
(290, 10, 'du', '', '2017-04-14 21:42:08',  1),
(291, 10, 'vi', '', '2017-04-14 21:42:08',  1),
(292, 10, 'vi', '', '2017-04-14 21:42:08',  1),
(293, 10, 'potatis',  '', '2017-04-14 21:42:08',  1),
(294, 10, 'jord', '', '2017-04-14 21:42:08',  1),
(295, 10, 'mark', '', '2017-04-14 21:42:08',  1),
(296, 10, 'alla', '', '2017-04-14 21:42:08',  1),
(297, 10, 'allt', '', '2017-04-14 21:42:08',  1),
(298, 10, 'klappa', '', '2017-04-14 21:42:08',  1),
(299, 10, 'smeka',  '', '2017-04-14 21:42:08',  1),
(300, 10, 'alla', '', '2017-04-14 21:42:08',  1),
(301, 10, 'allihopa', '', '2017-04-14 21:42:08',  1),
(302, 10, 'fest', '', '2017-04-14 21:42:08',  1),
(303, 10, 'kallas', '', '2017-04-14 21:42:08',  1),
(304, 10, 'bok',  '', '2017-04-14 21:42:08',  1),
(305, 10, 'sten', '', '2017-04-14 21:42:08',  1),
(306, 10, 'sätt', '', '2017-04-14 21:42:08',  1),
(307, 10, 'viss', '', '2017-04-14 21:42:08',  1),
(308, 10, 'näsa', '', '2017-04-14 21:42:08',  1),
(309, 10, 'miss', '', '2017-04-14 21:42:08',  1),
(310, 10, 'madam',  '', '2017-04-14 21:42:08',  1),
(311, 10, 'madam',  '', '2017-04-14 21:42:08',  1),
(312, 10, 'miss', '', '2017-04-14 21:42:08',  1),
(313, 10, 'madam',  '', '2017-04-14 21:42:08',  1),
(314, 10, 'miss', '', '2017-04-14 21:42:08',  1),
(315, 8,  'chleb',  '', '2017-04-14 21:42:08',  1),
(316, 10, 'kul',  '', '2017-04-14 21:42:08',  1),
(317, 10, 'rolig',  '', '2017-04-14 21:42:08',  1),
(318, 10, 'fin',  '', '2017-04-14 21:42:08',  1),
(319, 10, 'trevlig',  '', '2017-04-14 21:42:08',  1),
(320, 10, 'snygg',  '', '2017-04-14 21:42:08',  1),
(321, 10, 'vara', '', '2017-04-14 21:42:08',  1),
(323, 1,  'miss', '', '2017-04-14 21:42:08',  1),
(325, 1,  'be', '', '2017-04-14 21:42:08',  1),
(326, 1,  'laundry',  '', '2017-04-14 21:42:08',  1),
(327, 1,  'wax',  '', '2017-04-14 21:42:08',  1),
(328, 1,  'ugly', '', '2017-04-14 21:42:08',  1),
(329, 1,  'young',  '', '2017-04-14 21:42:08',  1),
(330, 1,  'young',  '', '2017-04-14 21:42:08',  1),
(331, 1,  'young',  '', '2017-04-14 21:42:08',  1),
(332, 1,  'you',  '', '2017-04-14 21:42:08',  1),
(333, 1,  'you guys', '', '2017-04-14 21:42:08',  1),
(334, 1,  '', '', '2017-04-14 21:42:08',  1),
(335, 1,  'they', '', '2017-04-14 21:42:08',  1),
(336, 1,  'wish', '', '2017-04-14 21:42:08',  1),
(337, 8,  'dom',  '', '2017-04-14 21:42:08',  1),
(338, 1,  'house',  '', '2017-04-14 21:42:08',  1),
(339, 1,  'without',  '', '2017-04-14 21:42:08',  1),
(340, 10, 'testa',  '', '2017-04-14 21:42:08',  1),
(341, 10, 'försöka',  '', '2017-04-14 21:42:08',  1),
(342, 10, '', '', '2017-04-14 21:42:08',  1),
(343, 0,  'jeden',  '', '2017-04-22 08:00:45',  1),
(344, 8,  'blablabla',  '', '2017-04-14 21:42:08',  1),
(345, 8,  'blablabla',  '', '2017-04-14 21:42:08',  1),
(346, 8,  'man',  '', '2017-04-14 21:42:08',  1),
(347, 8,  '', '', '2017-04-14 21:42:08',  1),
(348, 8,  '', '', '2017-04-14 21:42:08',  1),
(349, 8,  '', '', '2017-04-14 21:42:08',  1),
(350, 8,  '', '', '2017-04-14 21:42:08',  1),
(351, 8,  '', '', '2017-04-14 21:42:08',  1),
(352, 8,  '', '', '2017-04-14 21:42:08',  1),
(353, 8,  '', '', '2017-04-14 21:42:08',  1),
(354, 1,  'sheep',  '', '2017-04-14 21:42:08',  1),
(355, 1,  'test', '', '2017-04-14 21:42:08',  1),
(356, 0,  '', '', '2017-04-14 21:42:08',  1),
(357, 1,  'girl', '', '2017-04-14 21:42:08',  1),
(358, 8,  'balblab',  '', '2017-04-14 21:42:08',  1),
(359, 8,  '', '', '2017-04-14 21:42:08',  1),
(360, 8,  '', '', '2017-04-14 21:42:08',  1),
(361, 8,  '', '', '2017-04-14 21:42:08',  1),
(362, 8,  '', '', '2017-04-14 21:42:08',  1),
(363, 8,  '', '', '2017-04-14 21:42:08',  1),
(364, 8,  '', '', '2017-04-14 21:42:08',  1),
(365, 8,  '', '', '2017-04-14 21:42:08',  1),
(366, 8,  '', '', '2017-04-14 21:42:08',  1),
(367, 8,  '', '', '2017-04-14 21:42:08',  1),
(368, 8,  '', '', '2017-04-14 21:42:08',  1),
(369, 1,  'test', '', '2017-04-30 13:43:51',  0),
(370, 1,  'mug',  '', '2017-05-11 10:21:19',  0),
(371, 1,  'cup',  '', '2017-05-11 10:22:19',  0),
(372, 1,  'glass',  '', '2017-05-11 10:22:01',  0);

DROP TABLE IF EXISTS `translation_alternatives`;
CREATE TABLE `translation_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) NOT NULL,
  `alternative` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `translation_alternatives_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_alternatives` (`id`, `translation_id`, `alternative`) VALUES
(1, 1,  'men'),
(4, 217,  'kobietami'),
(5, 325,  'been'),
(6, 325,  'being'),
(7, 325,  'is'),
(8, 325,  'was'),
(9, 325,  'am'),
(10,  1,  'male');

DROP TABLE IF EXISTS `translation_exceptions`;
CREATE TABLE `translation_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `translation_exceptions_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_exceptions` (`id`, `word_id`, `language_id`, `user_id`) VALUES
(2, 2,  8,  1),
(3, 16, 8,  1),
(4, 20, 8,  1),
(5, 22, 8,  1),
(6, 27, 8,  1),
(7, 19, 10, 1),
(8, 20, 10, 1),
(9, 15, 8,  1),
(10,  15, 8,  1),
(11,  15, 8,  1),
(12,  43, 8,  1),
(13,  43, 8,  1),
(14,  44, 8,  1),
(15,  51, 8,  1),
(16,  51, 8,  1),
(17,  56, 8,  1),
(18,  57, 8,  1),
(19,  58, 8,  1),
(21,  60, 8,  1),
(22,  56, 8,  1),
(23,  61, 8,  1),
(24,  62, 8,  1);

DROP TABLE IF EXISTS `translation_words`;
CREATE TABLE `translation_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `translation_id` int(11) NOT NULL,
  `specification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `translation_words_ibfk_3` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `translation_words_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_words` (`id`, `word_id`, `translation_id`, `specification`) VALUES
(1, 1,  1,  ''),
(3, 1,  2,  ''),
(4, 7,  3,  ''),
(5, 7,  4,  ''),
(6, 9,  5,  ''),
(7, 9,  6,  ''),
(8, 5,  7,  ''),
(9, 11, 10, ''),
(12,  15, 15, ''),
(13,  17, 23, ''),
(14,  17, 25, 'non-vulgar'),
(15,  17, 26, ''),
(16,  18, 23, ''),
(17,  18, 25, 'both non-vulgar and vulgar'),
(18,  18, 30, 'vulgar'),
(19,  19, 32, 'sometimes'),
(20,  1,  33, 'other meaning'),
(21,  22, 34, ''),
(22,  22, 35, 'collective'),
(23,  23, 36, ''),
(24,  23, 23, 'male'),
(25,  23, 38, ''),
(29,  27, 39, 'jokingly'),
(58,  28, 45, ''),
(61,  29, 48, ''),
(64,  31, 51, ''),
(65,  32, 52, ''),
(66,  33, 52, 'unstressed'),
(67,  34, 54, ''),
(68,  35, 54, ''),
(70,  40, 55, ''),
(71,  41, 56, ''),
(72,  42, 57, ''),
(73,  43, 58, ''),
(74,  44, 35, ''),
(75,  44, 57, ''),
(88,  51, 72, ''),
(89,  51, 73, 'event'),
(90,  51, 74, 'event'),
(102, 57, 86, 'small rock'),
(103, 57, 87, 'uncountable'),
(104, 58, 88, ''),
(105, 58, 89, ''),
(106, 58, 90, ''),
(109, 60, 93, ''),
(110, 61, 94, ''),
(111, 62, 95, ''),
(113, 64, 97, ''),
(115, 66, 101,  ''),
(116, 67, 102,  ''),
(117, 68, 103,  ''),
(118, 68, 104,  ''),
(119, 69, 105,  ''),
(120, 69, 106,  ''),
(121, 69, 107,  ''),
(122, 69, 108,  ''),
(123, 69, 109,  'about a person'),
(125, 71, 111,  ''),
(126, 71, 112,  'pole shaped'),
(127, 72, 113,  ''),
(128, 72, 114,  ''),
(129, 72, 115,  ''),
(130, 29, 116,  'comperative'),
(131, 29, 117,  'superlative'),
(132, 73, 118,  ''),
(133, 73, 119,  'comperative'),
(134, 73, 120,  'superlative'),
(144, 56, 131,  ''),
(145, 74, 132,  ''),
(211, 1,  198,  ''),
(212, 1,  199,  ''),
(213, 1,  200,  ''),
(214, 2,  201,  ''),
(215, 2,  202,  ''),
(218, 10, 2,  ''),
(219, 16, 206,  ''),
(220, 20, 207,  ''),
(221, 20, 208,  'animal kingdom'),
(222, 20, 209,  'informal'),
(223, 24, 210,  ''),
(225, 5,  212,  ''),
(226, 7,  213,  'like a job'),
(227, 7,  214,  'function (like a machine)'),
(228, 7,  215,  'succeed'),
(230, 9,  217,  ''),
(231, 9,  218,  ''),
(232, 10, 219,  ''),
(233, 11, 220,  ''),
(235, 17, 222,  ''),
(237, 18, 224,  ''),
(238, 18, 225,  'vulgar'),
(239, 19, 226,  ''),
(241, 24, 228,  ''),
(243, 28, 230,  ''),
(244, 23, 231,  ''),
(245, 23, 232,  ''),
(248, 68, 235,  ''),
(255, 31, 242,  ''),
(256, 31, 243,  ''),
(257, 31, 244,  ''),
(258, 31, 245,  ''),
(259, 31, 246,  ''),
(260, 29, 247,  ''),
(261, 29, 248,  'interjection'),
(262, 29, 249,  ''),
(263, 29, 250,  ''),
(265, 32, 252,  ''),
(266, 33, 253,  ''),
(267, 34, 254,  ''),
(268, 35, 255,  ''),
(269, 40, 256,  ''),
(270, 40, 257,  ''),
(271, 1,  258,  ''),
(272, 1,  259,  ''),
(273, 1,  260,  ''),
(274, 1,  261,  ''),
(275, 2,  262,  ''),
(276, 2,  263,  ''),
(277, 5,  264,  ''),
(278, 7,  265,  ''),
(280, 9,  267,  ''),
(281, 10, 268,  ''),
(282, 10, 269,  ''),
(283, 11, 270,  ''),
(288, 15, 275,  ''),
(289, 16, 276,  ''),
(290, 16, 277,  ''),
(291, 16, 278,  ''),
(292, 16, 279,  ''),
(293, 16, 280,  ''),
(294, 17, 281,  ''),
(295, 22, 282,  ''),
(296, 28, 283,  ''),
(297, 29, 284,  ''),
(298, 29, 285,  ''),
(301, 31, 288,  ''),
(302, 32, 289,  ''),
(303, 33, 290,  ''),
(304, 34, 291,  ''),
(305, 35, 292,  ''),
(306, 40, 293,  ''),
(307, 41, 294,  ''),
(308, 41, 295,  ''),
(309, 42, 296,  ''),
(310, 42, 297,  ''),
(311, 43, 298,  ''),
(312, 43, 299,  ''),
(313, 44, 300,  ''),
(314, 44, 301,  ''),
(315, 51, 302,  ''),
(316, 51, 303,  ''),
(317, 56, 304,  ''),
(318, 57, 305,  ''),
(319, 58, 306,  ''),
(320, 58, 307,  ''),
(321, 60, 308,  ''),
(324, 78, 313,  ''),
(325, 78, 314,  ''),
(326, 74, 315,  ''),
(327, 69, 316,  ''),
(328, 69, 317,  ''),
(329, 69, 318,  ''),
(330, 69, 319,  ''),
(331, 69, 320,  'about a person'),
(334, 78, 323,  ''),
(336, 79, 325,  ''),
(337, 80, 326,  ''),
(338, 81, 327,  ''),
(342, 82, 331,  ''),
(343, 83, 332,  ''),
(344, 83, 333,  ''),
(345, 84, 334,  ''),
(346, 84, 335,  ''),
(347, 85, 336,  ''),
(348, 86, 337,  ''),
(349, 86, 338,  ''),
(353, 2,  343,  ''),
(357, 15, 347,  ''),
(358, 41, 348,  ''),
(359, 42, 349,  ''),
(360, 15, 350,  ''),
(361, 41, 351,  ''),
(362, 15, 352,  ''),
(363, 15, 353,  ''),
(364, 88, 357,  ''),
(365, 64, 358,  ''),
(366, 66, 359,  ''),
(367, 66, 360,  ''),
(368, 67, 361,  ''),
(369, 67, 362,  ''),
(370, 69, 363,  ''),
(371, 69, 364,  ''),
(372, 71, 365,  ''),
(373, 71, 366,  ''),
(374, 17, 367,  ''),
(375, 72, 368,  ''),
(376, 90, 287,  '');

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `inflect_classifications` int(11) NOT NULL,
  `inflect_not` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `types` (`id`, `name`, `short_name`, `inflect_classifications`, `inflect_not`) VALUES
(1, 'noun', 'n.', 0,  0),
(2, 'verb', 'v',  0,  0),
(3, 'name', 'nm.',  0,  1),
(4, 'pronoun',  'pn.',  0,  0),
(5, 'adjective',  'adj.', 1,  0),
(7, 'conjunction',  'conj.',  0,  1),
(8, 'preposition',  'pp.',  0,  1),
(10,  'article',  'art.', 0,  0),
(11,  'determiner', 'det.', 0,  0),
(12,  'prefix', 'pre.', 0,  1),
(13,  'suffix', 'suf.', 0,  1),
(16,  'Adverb', 'adv.', 1,  1);

DROP TABLE IF EXISTS `usage_notes`;
CREATE TABLE `usage_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `usage_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `usage_notes_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usage_notes` (`id`, `word_id`, `last_update`, `created_on`, `user_id`, `note`) VALUES
(8, 1,  '2017-03-19 15:20:48',  '2017-03-19 15:20:48',  3,  '* The normal plural is *mannen*. The unchanged form man is used after numerals only; it refers to the size of a group rather than a number of individuals. For example: In totaal verloren er 5000 man hun leven in die slag. (“5000 men altogether lost their lives in that battle.”)\\n\\n* Compound words with -man as their last component often take -lieden or -lui in the plural, rather than -mannen. For example: brandweerman ‎(“firefighter”) → brandweerlieden (alongside brandweerlui and brandweermannen).\\n');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `editor_lang` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editor_lang` (`editor_lang`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`editor_lang`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `longname`, `username`, `password`, `editor_lang`, `reg_date`, `role`) VALUES
(0, 'Guest',  'guest',  '', 0,  '2017-03-30 22:54:37',  4),
(1, 'Thomas de Roo',  'blekerfeld', '70674e943bcd2ce395ff619cff93c980f1cec914445cd69a30d612c7988e9966', 8,  '2017-04-09 10:55:33',  0),
(2, '', 'Charlie',  'd88aad0fd193b6ac9c03db2edddf9d1402956df84e709932dd2c4b70e5dc7f1b', 1,  '2017-02-12 17:41:56',  0),
(3, 'Mr. Donut',  'donut',  'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', 1,  '2017-03-30 22:46:27',  0),
(4, 'John Sprinkle',  'sprinkle', 'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', 1,  '2017-04-03 19:27:40',  3);

DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `native` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lexical_form` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ipa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  `subclassification_id` int(11) NOT NULL,
  `derivation_of` int(11) DEFAULT '0',
  `derivation_type` int(11) DEFAULT '0',
  `derivation_name` int(11) DEFAULT '0',
  `derivation_clonetranslations` int(11) DEFAULT '0',
  `derivation_show_in_title` int(11) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `classification_id` (`classification_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `words_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`),
  CONSTRAINT `words_ibfk_2` FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`),
  CONSTRAINT `words_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `words` (`id`, `native`, `lexical_form`, `ipa`, `hidden`, `type_id`, `classification_id`, `subclassification_id`, `derivation_of`, `derivation_type`, `derivation_name`, `derivation_clonetranslations`, `derivation_show_in_title`, `created`, `updated`, `created_by`, `image`) VALUES
(1, 'man',  '', 'mɑn',  0,  1,  1,  6,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-04-30 17:20:12',  1,  NULL),
(2, 'een',  '', '', 0,  10, 1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(5, 'hebben', '', '', 0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(7, 'werken', '', 'wərkm',  0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-18 14:17:52',  1,  NULL),
(9, 'vrouw',  '', 'vrɑu̯',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(10,  'echtgenoot', '', 'ɛxtxəˌnot',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(11,  'hij',  '', '', 0,  4,  10, 2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(15,  'winnen', '', '', 0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(16,  'de', '', '', 0,  10, 1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(17,  'kat',  '', 'kɑt',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  'kat.png'),
(18,  'poes', '', 'pus',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(19,  'katje',  '', 'kɑtjə',  0,  1,  3,  0,  17, 0,  1,  1,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(20,  'mannetje', '', 'mɑn.ətjə', 0,  1,  3,  0,  1,  0,  1,  0,  1,  '2017-02-06 00:21:03',  '2017-02-15 23:57:43',  1,  NULL),
(22,  'men',  '', 'mɛn',  0,  4,  3,  0,  1,  4,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(23,  'kater',  '', 'kaːtə̣r',  0,  1,  1,  0,  17, 1,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(24,  'katertje', '', 'kaːtərtjə',  0,  1,  3,  0,  23, 0,  1,  1,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(27,  'meneertje',  '', '', 0,  1,  3,  0,  25, 0,  1,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(28,  'zij',  '', '', 0,  4,  11, 2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(29,  'goed', '', 'xut',  0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(31,  'ik', '', 'ik', 0,  4,  8,  2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(32,  'jij',  '', 'jɛi̯/, (unstressed) /jə',  0,  4,  9,  2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(33,  'je', '', '', 0,  4,  9,  2,  32, 4,  0,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(34,  'wij',  '', 'ʋɛi̯', 0,  4,  8,  3,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(35,  'we', '', 'ʋə', 0,  4,  8,  3,  34, 4,  0,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(40,  'aardappel',  '', 'ɑ:rdappəl',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(41,  'aarde',  '', 'ɑ:rdə',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(42,  'alle', '', 'ɑl:ə', 0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(43,  'aaien',  '', '', 0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(44,  'allemaal', '', 'llem', 0,  4,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-18 00:49:18',  1,  NULL),
(51,  'feest',  '', '', 0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(56,  'boek', '', 'buk',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(57,  'steen',  '', 'steː:n', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(58,  'wijze',  '', 'wɛi̯zə', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(60,  'neus', '', 'nøs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(61,  'maan', '', 'ma:n', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(62,  'maand',  '', 'ma:nt',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(64,  'citroen',  '', 'siˈtrun',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  'lemons.jpg'),
(66,  'hagelen',  '', 'ha:xell:en', 0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(67,  'picknick', '', '', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(68,  'hond', '', 'hond', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(69,  'leuk', '', 'løːk', 0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(71,  'boom', '', 'bo:m', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(72,  'mooi', '', 'moːi̯',  0,  5,  13, 0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(73,  'slecht', '', 'slɛxt',  0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(74,  'brood',  '', 'bro:d',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(78,  'mevrouw',  '', 'mevrouw',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(79,  'zijn', '', 'zɛi̯n',  0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(80,  'was',  '', 'ʋɑs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(81,  'was',  '', 'ʋɑs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(82,  'jong', '', 'jɔŋ',  0,  5,  1,  4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(83,  'jullie', '', '', 0,  4,  9,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(84,  'zij',  '', '', 0,  4,  12, 0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(85,  'wensen', '', '', 0,  2,  5,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(86,  'huis', '', 'ɦœʏ̯s',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-12 17:53:05',  '2017-02-12 17:53:39',  NULL, NULL),
(88,  'meisje', '', 'mɛɪ:sjɛ',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-03-31 20:12:43',  NULL, NULL, NULL),
(89,  'lezen',  'l&Ezen', 'le:zen', 0,  2,  1,  0,  0,  0,  0,  0,  0,  '2017-04-28 11:26:48',  NULL, NULL, NULL),
(90,  'beker',  'beker',  'be: . ker',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-05-11 10:21:04',  NULL, NULL, NULL);

-- 2017-05-14 00:32:00
