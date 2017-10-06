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
  KEY `word_id_2` (`word_id_2`),
  CONSTRAINT `antonyms_ibfk_1` FOREIGN KEY (`word_id_1`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `antonyms_ibfk_2` FOREIGN KEY (`word_id_2`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `untill` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `classifications`;
CREATE TABLE `classifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `classifications` (`id`, `name`, `short_name`) VALUES
(1,	'common gender',	'cg.'),
(2,	'neuter',	'nt.'),
(3,	'regular',	'rg.');

DROP TABLE IF EXISTS `cognates`;
CREATE TABLE `cognates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id_1` int(11) NOT NULL,
  `word_id_2` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id_1` (`word_id_1`),
  KEY `word_id_2` (`word_id_2`),
  CONSTRAINT `cognates_ibfk_1` FOREIGN KEY (`word_id_1`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cognates_ibfk_2` FOREIGN KEY (`word_id_2`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `columns`;
CREATE TABLE `columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` varchar(255) NOT NULL,
  `hidden_native_entry_short` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `columns` (`id`, `name`, `short_name`, `hidden_native_entry`, `hidden_native_entry_short`) VALUES
(3,	'positive',	'pos.',	'0',	0),
(4,	'comparative',	'comp.',	'0',	0),
(5,	'superlative',	'sl.',	'0',	0);

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `column_apply`;
CREATE TABLE `column_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `column_id` (`column_id`),
  KEY `mode_id` (`mode_id`),
  CONSTRAINT `column_apply_ibfk_1` FOREIGN KEY (`column_id`) REFERENCES `columns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `column_apply_ibfk_2` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `column_apply` (`id`, `column_id`, `mode_id`) VALUES
(3,	3,	26),
(4,	4,	26),
(6,	5,	26);

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SETTING_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `SETTING_VALUE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `config` (`id`, `SETTING_NAME`, `SETTING_VALUE`) VALUES
(1,	'ENABLE_QUERY_CACHING',	'0'),
(2,	'QC_TIME',	'100000'),
(3,	'SITE_TITLE',	'Donut'),
(4,	'LOGO_TITLE',	'Example Dictionary'),
(5,	'HOMEPAGE',	'home'),
(12,	'SITE_DESC',	''),
(13,	'ACTIVE_LOCALE',	'English'),
(14,	'ENABLE_REGISTER',	'1'),
(15,	'REGISTER_DEFAULT_ROLE',	'3'),
(16,	'ENABLE_DEFINITIONS',	'1'),
(17,	'LOGO_SYMBOL',	'dots-horizontal-circle'),
(18,	'MAIL_FROM',	'noreply@localhost'),
(19,	'ENABLE_ACTIVATION_MAIL',	'1'),
(20,	'ENABLE_TOS',	'1'),
(21,	'MAIL_FROM_NAME',	'Donut dictionary'),
(22,	'REGISTER_ADMIN_ACTIVATION',	'0'),
(23,	'PAGE_MARGIN',	'8.5'),
(24,	'ALWAYS_SHOW_LAST_UPDATE',	'0'),
(25,	'PERMISSION_CREATE_LEMMAS',	'-3'),
(27,	'HEADER_CSS_BACKGROUND',	'background-color: #111b21;\r\nbackground-image: linear-gradient(#111b21, #222c32);'),
(28,	'HEADER_CSS_HSEARCH',	''),
(29,	'ACCENT_COLOR_1',	'#3B66D6');

DROP TABLE IF EXISTS `derivation`;
CREATE TABLE `derivation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activated` int(11) NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` int(11) NOT NULL,
  `in_set` int(11) NOT NULL DEFAULT '1',
  `output_lexcat` int(11) DEFAULT NULL,
  `output_gramcat` int(11) DEFAULT NULL,
  `output_tag` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleset` (`ruleset`),
  KEY `output_lexcat` (`output_lexcat`),
  KEY `output_gramcat` (`output_gramcat`),
  KEY `output_tag` (`output_tag`),
  CONSTRAINT `derivation_ibfk_1` FOREIGN KEY (`output_tag`) REFERENCES `subclassifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `derivation_gramcat`;
CREATE TABLE `derivation_gramcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `derivation_id` int(11) NOT NULL,
  `gramcat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `derivation_id` (`derivation_id`),
  KEY `gramcat_id` (`gramcat_id`),
  CONSTRAINT `derivation_gramcat_ibfk_1` FOREIGN KEY (`derivation_id`) REFERENCES `derivation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `derivation_gramcat_ibfk_2` FOREIGN KEY (`gramcat_id`) REFERENCES `classifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `derivation_lexcat`;
CREATE TABLE `derivation_lexcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `derivation_id` int(11) NOT NULL,
  `lexcat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `derivation_id` (`derivation_id`),
  KEY `lexcat_id` (`lexcat_id`),
  CONSTRAINT `derivation_lexcat_ibfk_1` FOREIGN KEY (`derivation_id`) REFERENCES `derivation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `derivation_lexcat_ibfk_2` FOREIGN KEY (`lexcat_id`) REFERENCES `types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `derivation_tags`;
CREATE TABLE `derivation_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `derivation_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `derivation_id` (`derivation_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `derivation_tags_ibfk_1` FOREIGN KEY (`derivation_id`) REFERENCES `derivation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `derivation_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `subclassifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `etymology`;
CREATE TABLE `etymology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `desc` text NOT NULL,
  `first_attestation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `etymology_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `etymology_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `etymology` (`id`, `word_id`, `desc`, `first_attestation`) VALUES
(35,	1337,	'From Old Dutch *katta*',	'1120');

DROP TABLE IF EXISTS `graphemes`;
CREATE TABLE `graphemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uppercase` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `in_alphabet` int(11) NOT NULL DEFAULT '1',
  `ipa` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `quality` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sorter` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme` (`grapheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes` (`id`, `grapheme`, `uppercase`, `in_alphabet`, `ipa`, `quality`, `sorter`) VALUES
(41,	'a',	'A',	1,	'',	'',	0),
(42,	'b',	'B',	1,	'',	'',	0),
(43,	'c',	'C',	1,	'',	'',	0),
(44,	'd',	'D',	1,	'',	'',	0),
(45,	'e',	'E',	1,	'',	'',	0),
(46,	'f',	'F',	1,	'',	'',	0),
(47,	'g',	'G',	1,	'',	'',	0),
(48,	'h',	'H',	1,	'',	'',	0),
(49,	'i',	'I',	1,	'',	'',	0),
(50,	'j',	'J',	1,	'',	'',	0),
(51,	'k',	'K',	1,	'',	'',	0),
(52,	'l',	'L',	1,	'',	'',	0),
(53,	'm',	'M',	1,	'',	'',	0),
(54,	'n',	'N',	1,	'',	'',	0),
(55,	'o',	'O',	1,	'',	'',	0),
(56,	'p',	'P',	1,	'',	'',	0),
(57,	'q',	'Q',	1,	'',	'',	0),
(58,	'r',	'R',	1,	'',	'',	0),
(59,	's',	'S',	1,	'',	'',	0),
(60,	't',	'T',	1,	'',	'',	0),
(61,	'u',	'U',	1,	'',	'',	0),
(62,	'v',	'V',	1,	'',	'',	0),
(63,	'w',	'W',	1,	'',	'',	0),
(64,	'x',	'X',	1,	'',	'',	0),
(65,	'y',	'Y',	1,	'',	'',	0),
(66,	'z',	'Z',	1,	'',	'',	0);

DELIMITER ;;

CREATE TRIGGER `graphemes_uc` BEFORE INSERT ON `graphemes` FOR EACH ROW
BEGIN
  IF (NEW.uppercase IS NULL OR NEW.uppercase = '') THEN
    SET NEW.uppercase = UCASE(NEW.grapheme);
  END IF;
END;;

CREATE TRIGGER `graphemes_bu` BEFORE UPDATE ON `graphemes` FOR EACH ROW
BEGIN
  IF (NEW.uppercase IS NULL OR NEW.uppercase = '') THEN
    SET NEW.uppercase = UCASE(NEW.grapheme);
  END IF;
END;;

DELIMITER ;

DROP TABLE IF EXISTS `graphemes_groups`;
CREATE TABLE `graphemes_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupstring` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme` (`grapheme`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes_groups` (`id`, `grapheme`, `groupstring`) VALUES
(79,	'a',	'VOW'),
(80,	'b',	'CON'),
(81,	'c',	'CON'),
(82,	'd',	'CON'),
(83,	'e',	'VOW'),
(84,	'f',	'CON'),
(85,	'g',	'CON'),
(86,	'h',	'CON'),
(87,	'i',	'VOW'),
(88,	'j',	'CON'),
(89,	'k',	'CON'),
(90,	'l',	'CON'),
(91,	'm',	'CON'),
(92,	'n',	'CON'),
(93,	'o',	'VOW'),
(94,	'p',	'CON'),
(95,	'q',	'CON'),
(96,	'r',	'CON'),
(97,	's',	'CON'),
(98,	't',	'CON'),
(99,	'u',	'VOW'),
(100,	'v',	'CON'),
(101,	'w',	'CON'),
(102,	'x',	'CON'),
(103,	'y',	'VOW'),
(104,	'z',	'CON');

DROP TABLE IF EXISTS `homophones`;
CREATE TABLE `homophones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id_1` int(11) NOT NULL,
  `word_id_2` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `word_id_1` (`word_id_1`),
  KEY `word_id_2` (`word_id_2`),
  CONSTRAINT `homophones_ibfk_1` FOREIGN KEY (`word_id_1`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homophones_ibfk_2` FOREIGN KEY (`word_id_2`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `idioms`;
CREATE TABLE `idioms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `idioms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `idioms_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idioms` (`id`, `idiom`, `user_id`, `created_on`) VALUES
(1,	'man en vrouw',	1,	'2017-04-17 10:12:53'),
(2,	'Als kat en hond leven',	1,	'2017-04-17 10:12:53'),
(3,	'De kat uit de boom kijken',	1,	'2017-04-17 10:12:53'),
(4,	'Een kat in de zak kopen.',	1,	'2017-04-17 10:12:53'),
(5,	'Ik heb daar wel een mannetje voor.',	0,	'2017-05-15 12:54:49'),
(6,	'de boot afhouden',	0,	'2017-05-31 18:26:42'),
(7,	'de boot is aan',	0,	'2017-05-31 18:26:50'),
(8,	'uit de boot vallen',	0,	'2017-05-31 18:26:59'),
(9,	'We varen in het weekend met onze boot.',	0,	'2017-05-31 18:27:25'),
(10,	'Het mannatje bouwt een nest.',	0,	'2017-06-07 14:33:33'),
(11,	'Man, man, man, wat een weer',	0,	'2017-06-09 01:07:14'),
(12,	'Maak dat de kat wijs',	0,	'2017-08-07 13:13:44');

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
  CONSTRAINT `idiom_translations_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `idiom_translations_ibfk_3` FOREIGN KEY (`idiom_id`) REFERENCES `idioms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idiom_translations_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idiom_translations` (`id`, `idiom_id`, `language_id`, `translation`, `specification`) VALUES
(1,	1,	1,	'husband and wife',	''),
(3,	3,	1,	'See which way the wind blows',	''),
(4,	2,	1,	'To live like cat and dog / fight a lot',	''),
(5,	4,	1,	'buying a pig in a poke',	'');

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
(1,	4,	1337,	'kat'),
(2,	12,	1337,	'kat'),
(3,	3,	1337,	'kat'),
(4,	2,	1337,	'kat');

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `showname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hidden_native_entry` int(11) NOT NULL,
  `flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `activated` int(11) NOT NULL,
  `locale` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `languages` (`id`, `name`, `showname`, `hidden_native_entry`, `flag`, `activated`, `locale`, `color`) VALUES
(0,	'Dutch',	'Dutch',	0,	'nl.png',	1,	'NL',	'#3B66D6'),
(1,	'English',	'English',	0,	'gb.png',	1,	'EN',	'#D33B3B'),
(15,	'Swedish',	'Swedish',	0,	'se.png',	1,	'SE',	'#E5C839');

DROP TABLE IF EXISTS `lemmatization`;
CREATE TABLE `lemmatization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inflected_form` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lemma_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lemma_id` (`lemma_id`),
  CONSTRAINT `lemmatization_ibfk_1` FOREIGN KEY (`lemma_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lemmatization` (`id`, `inflected_form`, `hash`, `lemma_id`) VALUES
(673,	'katten',	'3_1_1',	1337),
(675,	'katten',	'3_2_1',	1337),
(676,	'boommen',	'3_1_1',	1338),
(677,	'boommen',	'3_2_1',	1338);

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `record` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `log` (`id`, `identifier`, `record`, `user_id`, `timestamp`) VALUES
(2,	'new_lemma',	67,	1,	'2017-09-02 19:21:49'),
(3,	'edit_lemma',	17,	1,	'2017-09-17 09:24:50'),
(4,	'edit_lemma',	65,	1,	'2017-09-17 09:37:45'),
(5,	'new_lemma',	0,	1,	'2017-09-17 13:54:42'),
(6,	'new_lemma',	68,	1,	'2017-09-17 21:23:49'),
(7,	'edit_lemma',	1,	1,	'2017-09-17 22:37:24'),
(8,	'new_lemma',	69,	1,	'2017-09-17 22:44:25'),
(9,	'edit_lemma',	42,	1,	'2017-09-20 22:17:04'),
(10,	'edit_lemma',	42,	1,	'2017-09-20 22:18:46'),
(11,	'edit_lemma',	18,	1,	'2017-09-21 07:45:14'),
(12,	'edit_lemma',	42,	1,	'2017-09-21 08:24:27'),
(13,	'edit_lemma',	38,	1,	'2017-09-21 08:25:15'),
(14,	'edit_lemma',	2,	3,	'2017-09-22 00:37:44'),
(15,	'new_lemma',	0,	1,	'2017-09-28 09:59:45'),
(16,	'edit_lemma',	42,	1,	'2017-09-29 08:13:31'),
(17,	'new_lemma',	1337,	1,	'2017-10-02 22:12:37'),
(18,	'edit_lemma',	1337,	1,	'2017-10-02 22:23:39'),
(19,	'new_lemma',	1338,	1,	'2017-10-02 22:29:30'),
(20,	'edit_lemma',	1337,	3,	'2017-10-04 11:28:59'),
(21,	'edit_lemma',	1337,	3,	'2017-10-04 11:35:45'),
(22,	'edit_lemma',	1337,	3,	'2017-10-04 11:37:29'),
(23,	'edit_lemma',	1338,	3,	'2017-10-04 23:46:05');

DROP TABLE IF EXISTS `modes`;
CREATE TABLE `modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `modes` (`id`, `name`, `short_name`, `hidden_native_entry`, `type_id`) VALUES
(1,	'normal form',	'nom',	0,	1),
(7,	'present simple',	'ps.',	0,	2),
(8,	'Past simple',	'pts.',	0,	2),
(21,	'Present perfect',	'pre per',	0,	2),
(22,	'Past perfect',	'pas per',	0,	2),
(23,	'diminutive',	'dim',	0,	1),
(24,	'Singular',	'sg',	0,	3),
(25,	'Plural',	'pl.',	0,	3),
(26,	'normal form',	'n',	0,	5);

DROP TABLE IF EXISTS `morphology`;
CREATE TABLE `morphology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` int(11) NOT NULL,
  `in_set` int(11) NOT NULL DEFAULT '0',
  `is_irregular` tinyint(4) NOT NULL,
  `is_aux` tinyint(4) NOT NULL,
  `aux_placement` tinyint(4) NOT NULL,
  `aux_mode_id` tinyint(4) NOT NULL,
  `is_stem` tinyint(4) NOT NULL,
  `irregular_form` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lemma_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleset` (`ruleset`),
  CONSTRAINT `morphology_ibfk_1` FOREIGN KEY (`ruleset`) REFERENCES `rulesets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology` (`id`, `name`, `rule`, `ruleset`, `in_set`, `is_irregular`, `is_aux`, `aux_placement`, `aux_mode_id`, `is_stem`, `irregular_form`, `lemma_id`) VALUES
(56,	'Plural',	'[]&EN;',	3,	1,	0,	0,	0,	0,	0,	'',	0),
(57,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'een boom',	127),
(58,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'bomen',	127),
(59,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'de boom',	127),
(60,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'de bomen',	127),
(61,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'een boompje',	127),
(62,	'',	'',	0,	0,	1,	0,	0,	0,	0,	'boompjes',	127);

DROP TABLE IF EXISTS `morphology_columns`;
CREATE TABLE `morphology_columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `column_id` (`column_id`),
  CONSTRAINT `morphology_columns_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_columns_ibfk_2` FOREIGN KEY (`column_id`) REFERENCES `columns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `morphology_gramcat`;
CREATE TABLE `morphology_gramcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gramcat_id` int(11) NOT NULL,
  `morphology_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gramcat_id` (`gramcat_id`),
  KEY `morphology_id` (`morphology_id`),
  CONSTRAINT `morphology_gramcat_ibfk_1` FOREIGN KEY (`gramcat_id`) REFERENCES `classifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_gramcat_ibfk_2` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
(21,	56,	1);

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
(41,	56,	1),
(42,	57,	1),
(43,	58,	1),
(44,	59,	1),
(45,	60,	1),
(46,	61,	23),
(47,	62,	23);

DROP TABLE IF EXISTS `morphology_numbers`;
CREATE TABLE `morphology_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `number_id` (`number_id`),
  CONSTRAINT `morphology_numbers_ibfk_1` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE,
  CONSTRAINT `morphology_numbers_ibfk_2` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology_numbers` (`id`, `morphology_id`, `number_id`) VALUES
(44,	56,	3),
(45,	57,	1),
(46,	58,	3),
(47,	59,	1),
(48,	60,	3),
(49,	61,	1),
(50,	62,	3);

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
(25,	56,	1),
(26,	56,	2),
(27,	57,	1),
(28,	58,	1),
(29,	59,	2),
(30,	60,	2),
(31,	61,	1),
(32,	62,	1);

DROP TABLE IF EXISTS `morphology_tags`;
CREATE TABLE `morphology_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `morphology_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `morphology_id` (`morphology_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `morphology_tags_ibfk_3` FOREIGN KEY (`morphology_id`) REFERENCES `morphology` (`id`) ON DELETE CASCADE
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
(1,	'singular',	'sg.',	'0',	0),
(3,	'plural',	'pl',	'0',	0),
(8,	'positive',	'bf.',	'0',	0),
(9,	'comperative',	'comp.',	'0',	0),
(10,	'superlative',	'0',	'0',	0),
(11,	'first person',	'1',	'0',	0),
(12,	'second person',	'2',	'0',	0),
(13,	'third person',	'3',	'0',	0);

DROP TABLE IF EXISTS `number_apply`;
CREATE TABLE `number_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `number_id` (`number_id`),
  KEY `type_id` (`mode_id`),
  CONSTRAINT `number_apply_ibfk_1` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `number_apply_ibfk_2` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `number_apply` (`id`, `number_id`, `mode_id`) VALUES
(39,	1,	1),
(40,	3,	1),
(41,	1,	23),
(42,	3,	23),
(43,	1,	26),
(44,	3,	26);

DROP TABLE IF EXISTS `phonology_contexts`;
CREATE TABLE `phonology_contexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` int(11) NOT NULL,
  `in_set` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ruleset` (`ruleset`),
  CONSTRAINT `phonology_contexts_ibfk_1` FOREIGN KEY (`ruleset`) REFERENCES `rulesets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phonology_contexts` (`id`, `name`, `rule`, `ruleset`, `in_set`) VALUES
(3,	'vowel lengthening before endings',	'CON_[aeou]_CON.+.&T,D=>%%',	0,	1),
(4,	'variable vowel lengthening',	'CON_&A,E,O,U_CON.+.&T,D=>%%',	0,	1),
(5,	'variable &D => kofschip dental suffix t',	'[x,k,f,s,c,h,p].+_&D_=>t',	14,	1),
(6,	'z to s before endings',	'_z_+=>s',	0,	1),
(7,	'VOW.v to f',	'VOW_v_+=>f',	0,	1),
(8,	'no z at word end',	'_z_:>=>s',	0,	1),
(9,	'no v at word end',	'_v_:>=>f',	0,	1),
(10,	'no double b (hebbt -> hebt)',	'CON.VOW._b.b_+.&D,T=>b',	13,	1),
(11,	'no double b (hebbt -> hebt) 2',	'CON.VOW_b.b_+.t=>b',	0,	1),
(12,	'vowel lengthening at word end',	'*.CON_VOW_CON.:>=>%%',	0,	1),
(13,	'variable EE to e before z',	'CON_&EE_z=>e',	0,	1),
(14,	'vowel lengthening 3',	'<:CON._VOW.VOW__CON.+=>$1',	0,	1),
(15,	'trema e',	'VOW.+_e_=>ë',	0,	1),
(16,	's+en → z+en',	'[^r,n]_s_+.e.n=>z',	16,	1),
(19,	'lens => lenzen',	'_l.e.n.s_+.e.n=>lenz',	16,	1),
(20,	'plural, just n with -eerde',	'[e].[e].[r].[d].[e].+_&EN_=>n',	16,	1),
(21,	'dimunitves plural',	'je.+_&EN_=>s',	16,	1),
(22,	'plural open vowel',	'[aioyu].+_&EN_=>\\\'s',	16,	1),
(23,	'plural mute e',	'e.+_&EN_=>s',	0,	1),
(24,	'double consonant before plural ending',	'_CON_+.&EN=>%%',	0,	1),
(26,	'endes => enden',	'CON.ende.+_s_=>n',	0,	1),
(27,	'dim ng => nk',	'_ng_+.nkje=>',	0,	1),
(29,	'etje => pje if long vowel 1',	'(VOW)_(1)_CON.+.&ETJE=>#%#',	0,	1),
(30,	'double consonant before endings 2',	'CON.VOW_CON_+.&E=>%%',	0,	1),
(31,	'fototje => fotootje',	'CON_VOW_+.tje=>%%',	0,	1),
(33,	'double consonant before endings',	'^VOW.VOW_CON_+.^CON=>%%',	0,	1),
(34,	'etje => pje if long vowel 2',	'#.VOW.#.CON.+_&ETJE_=>pje',	0,	1),
(35,	'kofschip dental suffix t ',	'[x,k,f,s,c,h,p].+_&2DD_=>tt',	0,	1),
(36,	'kofschip dental suffix d',	'[^x,k,f,s,c,h,p].+_&2DD_=>dd',	0,	1),
(37,	'heid -> heden',	'h_e.i.d_d.+.&EN=>e',	16,	1),
(38,	'No double consonants before verb ending',	'(CON)_%_+=>%',	17,	1),
(39,	'&r vowel correction',	'(VOW)_%_CON.+.&r=>%',	19,	1),
(42,	'&er vowel correction',	'(VOW)_%_CON.+.&er.=>0',	19,	1);

DROP TABLE IF EXISTS `phonology_ipa_generation`;
CREATE TABLE `phonology_ipa_generation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleset` int(11) NOT NULL,
  `in_set` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleset` (`ruleset`),
  CONSTRAINT `phonology_ipa_generation_ibfk_1` FOREIGN KEY (`ruleset`) REFERENCES `rulesets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phonology_ipa_generation` (`id`, `name`, `rule`, `ruleset`, `in_set`) VALUES
(1,	'long o',	'_oo_=>oː',	5,	1),
(2,	'Long a',	'_aa_=>aː',	5,	1);

DROP TABLE IF EXISTS `row_native`;
CREATE TABLE `row_native` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL,
  `heading_id` int(255) NOT NULL,
  `word_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  KEY `row_id` (`row_id`),
  KEY `heading_id` (`heading_id`),
  CONSTRAINT `row_native_ibfk_1` FOREIGN KEY (`row_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `row_native_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `row_native_ibfk_3` FOREIGN KEY (`row_id`) REFERENCES `numbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `row_native_ibfk_4` FOREIGN KEY (`heading_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `rulesets`;
CREATE TABLE `rulesets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rulesets` (`id`, `name`, `parent`) VALUES
(0,	'/rules',	-1),
(3,	'/rules/nouns',	0),
(4,	'/rules/nouns/dimunitives',	3),
(5,	'/rules/IPA-rules',	0),
(13,	'/rules/verbs',	0),
(14,	'/rules/verbs/regular',	13),
(16,	'/rules/nouns/plural_contexts',	3),
(17,	'/rules/verbs/regular/present_simple',	14),
(18,	'/rules/adjectives',	0),
(19,	'/rules/adjectives/corrections',	18),
(20,	'/rules/new_folder',	0),
(21,	'/rules/new_folder/hh',	20),
(22,	'/rules/new_folder/hh/tst',	21);

DROP TABLE IF EXISTS `search_hits`;
CREATE TABLE `search_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hit_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `search_hits_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `search_hits_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `search_hits` (`id`, `word_id`, `user_id`, `hit_timestamp`) VALUES
(3906,	1337,	1,	'2017-10-03 00:12:43'),
(3907,	1338,	1,	'2017-10-03 00:29:35'),
(3908,	1338,	3,	'2017-10-05 01:45:25'),
(3909,	1337,	3,	'2017-10-05 13:02:33'),
(3910,	1337,	3,	'2017-10-06 00:33:38'),
(3911,	1337,	3,	'2017-10-06 01:33:49');

DROP TABLE IF EXISTS `subclassifications`;
CREATE TABLE `subclassifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `subclassifications` (`id`, `name`, `short_name`) VALUES
(1,	'countable',	'cnt.'),
(2,	'non-countable',	'n.c.');

DROP TABLE IF EXISTS `submodes`;
CREATE TABLE `submodes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submodes` (`id`, `name`, `short_name`, `hidden_native_entry`) VALUES
(1,	'indefinite',	'indef',	'0'),
(2,	'definite',	'def',	'0'),
(3,	'strong',	'str.',	'0'),
(4,	'weak',	'wk.',	'0'),
(5,	'First person',	'',	'DV'),
(6,	'Second person',	'',	'0'),
(7,	'Third Person',	'',	'0'),
(8,	'conjugation',	'sg conj p',	' '),
(9,	'conjugation',	'sg pn',	'0'),
(10,	'singular',	'sg.',	'0'),
(11,	'plural',	'pl',	'0'),
(12,	'nom.',	'n.',	'0');

DROP TABLE IF EXISTS `submode_apply`;
CREATE TABLE `submode_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submode_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`mode_id`),
  KEY `submode_id` (`submode_id`),
  CONSTRAINT `submode_apply_ibfk_2` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submode_apply_ibfk_3` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submode_apply` (`id`, `submode_id`, `mode_id`) VALUES
(42,	1,	1),
(43,	2,	1),
(44,	1,	23);

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


DROP TABLE IF EXISTS `threads`;
CREATE TABLE `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linked_to` int(11) NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thread_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `threads` (`id`, `linked_to`, `section`, `thread_id`, `title`, `content`, `user_id`, `post_date`, `update_date`) VALUES
(8,	17,	'lemma',	7,	'hjh',	'dsss',	1,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(10,	30,	'lemma',	9,	'fd',	'Wat vind jij er van?',	1,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(11,	30,	'lemma',	10,	'f',	'Ik weet het niet zo goed?',	1,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(13,	26,	'lemma',	12,	'aaaaa',	'aaa',	1,	'2017-08-30 17:27:59',	'2017-08-30 15:27:59'),
(15,	1,	'lemma',	14,	'RE:',	'echt niet',	1,	'2017-09-17 23:03:16',	'2017-09-17 21:03:16');

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
(2054,	1,	'cat',	'',	'2017-10-02 22:12:37',	1),
(2055,	15,	'katt',	'',	'2017-10-02 22:12:37',	1),
(2056,	1,	'tree',	'',	'2017-10-02 22:29:30',	1),
(2057,	15,	'träd',	'',	'2017-10-02 22:29:30',	1);

DROP TABLE IF EXISTS `translation_alternatives`;
CREATE TABLE `translation_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) NOT NULL,
  `alternative` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `translation_alternatives_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `translation_exceptions`;
CREATE TABLE `translation_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `translation_exceptions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `translation_exceptions_ibfk_5` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `translation_words`;
CREATE TABLE `translation_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `translation_id` int(11) NOT NULL,
  `specification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `translation_words_ibfk_5` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `translation_words_ibfk_6` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_words` (`id`, `word_id`, `translation_id`, `specification`) VALUES
(291,	1337,	2054,	''),
(292,	1337,	2055,	''),
(293,	1338,	2056,	''),
(294,	1338,	2057,	'');

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inflect` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `types` (`id`, `name`, `short_name`, `inflect`) VALUES
(1,	'noun',	'n.',	1),
(2,	'verb',	'v.',	1),
(3,	'adjective',	'adj.',	0);

DROP TABLE IF EXISTS `usage_notes`;
CREATE TABLE `usage_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `contents` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `usage_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `usage_notes_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `activated` int(11) NOT NULL,
  `might_be_banned` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `disable_notifications` int(11) NOT NULL,
  `about` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `longname`, `username`, `password`, `reg_date`, `role`, `avatar`, `activated`, `might_be_banned`, `email`, `disable_notifications`, `about`) VALUES
(-1,	'system',	'SYSTEM',	'root',	'2017-09-23 21:10:42',	0,	'',	1,	0,	'root@SYSTEM',	1,	''),
(0,	'Guest',	'guest',	'',	'2017-08-24 12:04:51',	4,	'',	1,	0,	'niet@veel.com',	0,	''),
(1,	'Thomas de Roo',	'blekerfeld',	'70674e943bcd2ce395ff619cff93c980f1cec914445cd69a30d612c7988e9966',	'2017-08-24 16:09:43',	0,	'https://avatars3.githubusercontent.com/u/13293128?v=3&s=460',	1,	0,	'thomas@localhost',	0,	''),
(3,	'Mr. Donut',	'donut',	'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07',	'2017-08-24 12:04:51',	0,	'',	1,	0,	'niet@veel.com',	0,	'');

DROP TABLE IF EXISTS `user_activation`;
CREATE TABLE `user_activation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `untill` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ipadress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_activation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_activation` (`id`, `user_id`, `untill`, `token`, `ipadress`) VALUES
(14,	0,	'2017-08-27 11:35:57',	'823739yP:0',	'::1'),
(15,	0,	'2017-08-27 11:35:57',	'823739yP:0',	'::1'),
(16,	0,	'2017-08-27 11:36:00',	'823738yP:0',	'::1'),
(17,	0,	'2017-08-27 11:36:29',	'8237bcyP:0',	'::1'),
(18,	0,	'2017-08-27 11:36:33',	'82377eyP:0',	'::1'),
(19,	1,	'2017-08-27 11:37:47',	'87426bkA:1',	'::1'),
(20,	0,	'2017-08-27 12:25:32',	'b658a2yP:0',	'::1'),
(21,	0,	'2017-08-27 12:27:04',	'b658b7yP:0',	'::1'),
(22,	0,	'2017-09-05 20:56:45',	'18fed2yP:0',	'::1'),
(23,	0,	'2017-09-24 08:37:25',	'18fec1yP:0',	'::1'),
(24,	0,	'2017-09-24 08:38:15',	'18fe1ayP:0',	'::1');

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
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `derivation` int(11) DEFAULT NULL,
  `derived_from` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `classification_id` (`classification_id`),
  KEY `created_by` (`created_by`),
  KEY `subclassification_id` (`subclassification_id`),
  KEY `words_dev` (`derivation`),
  KEY `der` (`derived_from`),
  CONSTRAINT `words_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `words_ibfk_6` FOREIGN KEY (`derivation`) REFERENCES `derivation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `words_ibfk_7` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `words_ibfk_8` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `words_ibfk_9` FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `words` (`id`, `native`, `lexical_form`, `ipa`, `hidden`, `type_id`, `classification_id`, `subclassification_id`, `created`, `updated`, `created_by`, `image`, `derivation`, `derived_from`) VALUES
(1337,	'kat',	'',	'kɑt',	0,	1,	1,	0,	'2017-10-04 11:37:29',	'2017-10-04 11:37:29',	3,	NULL,	NULL,	NULL),
(1338,	'boom',	'',	'bo:m',	0,	1,	1,	0,	'2017-10-04 23:46:05',	'2017-10-04 23:46:05',	3,	NULL,	NULL,	NULL);

-- 2017-10-06 00:11:13
