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


SET NAMES utf8mb4;

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `articles` (`id`, `name`, `url`) VALUES
(0, 'Homepage', 'home');

DROP TABLE IF EXISTS `article_revisions`;
CREATE TABLE `article_revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `revision_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `revision_note` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_undone` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `user_id` (`user_id`),
  KEY `language_locale` (`language_locale`),
  KEY `revision_date` (`revision_date`),
  CONSTRAINT `article_revisions_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_revisions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `article_revisions_ibfk_3` FOREIGN KEY (`language_locale`) REFERENCES `languages` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `article_revisions` (`id`, `article_id`, `user_id`, `language_locale`, `revision_date`, `name`, `content`, `revision_note`, `is_undone`) VALUES
(30,  0,  -1, 'EN', '2018-03-19 23:45:09',  '', 'This is the default English wiki home page of **Donut** *the dictionary toolkit*, change the contents to anything you like.',  'System created home page', 0);

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
(1, 'common gender',  'cg.'),
(2, 'neuter', 'nt.'),
(3, 'regular',  'rg.');

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
(3, 'positive', 'pos.', '0',  0),
(4, 'comparative',  'comp.',  '0',  0),
(5, 'superlative',  'sl.',  '0',  0);

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
(3, 3,  26),
(4, 4,  26),
(6, 5,  26);

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SETTING_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `SETTING_VALUE` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `SETTING_INPUT` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `config` (`id`, `SETTING_NAME`, `SETTING_VALUE`, `SETTING_INPUT`) VALUES
(1, 'ENABLE_QUERY_CACHING', '0',  'input'),
(2, 'QC_TIME',  '100000', 'input'),
(3, 'SITE_TITLE', 'Example Dictionary', 'input'),
(4, 'LOGO_TITLE', 'Example Dictionary', 'input'),
(5, 'HOMEPAGE', 'home', 'input'),
(12,  'SITE_DESC',  '', 'input'),
(13,  'ACTIVE_LOCALE',  'English',  'input'),
(14,  'ENABLE_REGISTER',  '1',  'input'),
(15,  'REGISTER_DEFAULT_ROLE',  '3',  'input'),
(16,  'ENABLE_DEFINITIONS', '1',  'input'),
(17,  'LOGO_SYMBOL',  'fa-book',  'input'),
(18,  'MAIL_FROM',  'noreply@localhost',  'input'),
(19,  'ENABLE_ACTIVATION_MAIL', '1',  'input'),
(20,  'ENABLE_TOS', '1',  'input'),
(21,  'MAIL_FROM_NAME', 'Donut dictionary', 'input'),
(22,  'REGISTER_ADMIN_ACTIVATION',  '0',  'input'),
(23,  'PAGE_MARGIN',  '6%', 'input'),
(24,  'ALWAYS_SHOW_LAST_UPDATE',  '0',  'input'),
(25,  'PERMISSION_CREATE_LEMMAS', '-3', 'input'),
(27,  'HEADER_CSS_BACKGROUND',  'background-color: #121D23;', 'input'),
(28,  'HEADER_CSS_HSEARCH', '', 'input'),
(29,  'ACCENT_COLOR_1', '#3454d1;', 'input'),
(30,  'ACCENT_COLOR_2', '#C62B4A;', 'input'),
(31,  'MENU_BREAK', '1',  ''),
(32,  'WIKI_LOCALE',  'EN', 'input'),
(33,  'TEST', '\\\'hoi\\',  'input'),
(34,  'NUMBER_OF_DOORS_IN_THIS_HOUSE',  '\\\'3\\',  'input'),
(35,  'ACCENT_COLOR_3', '#256BD7;', 'input');

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
(35,  1337, 'From Old Dutch *katta*', '1120'),
(36,  1345, '', '21st of January, 2009');

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
(41,  'a',  'A',  1,  '', '', 0),
(42,  'b',  'B',  1,  '', '', 0),
(43,  'c',  'C',  1,  '', '', 0),
(44,  'd',  'D',  1,  '', '', 0),
(45,  'e',  'E',  1,  '', '', 0),
(46,  'f',  'F',  1,  '', '', 0),
(47,  'g',  'G',  1,  '', '', 0),
(48,  'h',  'H',  1,  '', '', 0),
(49,  'i',  'I',  1,  '', '', 0),
(50,  'j',  'J',  1,  '', '', 0),
(51,  'k',  'K',  1,  '', '', 0),
(52,  'l',  'L',  1,  '', '', 0),
(53,  'm',  'M',  1,  '', '', 0),
(54,  'n',  'N',  1,  '', '', 0),
(55,  'o',  'O',  1,  '', '', 0),
(56,  'p',  'P',  1,  '', '', 0),
(57,  'q',  'Q',  1,  '', '', 0),
(58,  'r',  'R',  1,  '', '', 0),
(59,  's',  'S',  1,  '', '', 0),
(60,  't',  'T',  1,  '', '', 0),
(61,  'u',  'U',  1,  '', '', 0),
(62,  'v',  'V',  1,  '', '', 0),
(63,  'w',  'W',  1,  '', '', 0),
(64,  'x',  'X',  1,  '', '', 0),
(65,  'y',  'Y',  1,  '', '', 0),
(66,  'z',  'Z',  1,  '', '', 0);

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
(1, 'man en vrouw', 1,  '2017-04-17 10:12:53'),
(2, 'Als kat en hond leven',  1,  '2017-04-17 10:12:53'),
(3, 'De kat uit de boom kijken',  1,  '2017-04-17 10:12:53'),
(4, 'Een kat in de zak kopen.', 1,  '2017-04-17 10:12:53'),
(5, 'Ik heb daar wel een mannetje voor.', 0,  '2017-05-15 12:54:49'),
(6, 'de boot afhouden', 0,  '2017-05-31 18:26:42'),
(7, 'de boot is aan', 0,  '2017-05-31 18:26:50'),
(8, 'uit de boot vallen', 0,  '2017-05-31 18:26:59'),
(9, 'We varen in het weekend met onze boot.', 0,  '2017-05-31 18:27:25'),
(10,  'Het mannatje bouwt een nest.', 0,  '2017-06-07 14:33:33'),
(11,  'Man, man, man, wat een weer',  0,  '2017-06-09 01:07:14'),
(12,  'Maak dat de kat wijs', 0,  '2017-08-07 13:13:44');

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
(1, 4,  1337, 'kat'),
(2, 12, 1337, 'kat'),
(3, 3,  1337, 'kat'),
(4, 2,  1337, 'kat'),
(5, 9,  1343, 'boot');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `languages` (`id`, `name`, `showname`, `hidden_native_entry`, `flag`, `activated`, `locale`, `color`) VALUES
(0, 'Dutch',  'Dutch',  0,  'nl.png', 1,  'NL', '#3B66D6'),
(1, 'English',  'English',  0,  'gb.png', 1,  'EN', '#D33B3B'),
(15,  'Swedish',  'Swedish',  0,  'se.png', 0,  'SV', '#E5C839');

DROP TABLE IF EXISTS `lemmalists`;
CREATE TABLE `lemmalists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `lemmalists_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `lemmalists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `lemmalists` (`id`, `name`, `parent`) VALUES
(-1,  '/',  -1),
(1, '/lemmalists',  -1);

DROP TABLE IF EXISTS `lemmalist_lemma`;
CREATE TABLE `lemmalist_lemma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lemma` int(11) NOT NULL,
  `lemmalist` int(11) NOT NULL,
  `in_set` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `lemma` (`lemma`),
  KEY `lemmalist` (`lemmalist`),
  CONSTRAINT `lemmalist_lemma_ibfk_1` FOREIGN KEY (`lemma`) REFERENCES `words` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lemmalist_lemma_ibfk_2` FOREIGN KEY (`lemmalist`) REFERENCES `lemmalists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `lemmalist_lemma` (`id`, `lemma`, `lemmalist`, `in_set`) VALUES
(1, 1354, 1,  1);

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
(700, 'manetje',  '1_1_1',  1342),
(702, 'manetje',  '1_2_1',  1342),
(704, 'manetjeen',  '3_1_1',  1342),
(705, 'manetjeen',  '3_2_1',  1342),
(706, 'tomaatje', '1_1_1',  1341),
(707, 'tomaatjeen', '3_1_1',  1341),
(708, 'tomaatje', '1_2_1',  1341),
(709, 'tomaatjeen', '3_2_1',  1341),
(710, 'bootje', '1_1_1',  1343),
(711, 'bootjeen', '3_1_1',  1343),
(712, 'bootje', '1_2_1',  1343),
(713, 'bootjeen', '3_2_1',  1343),
(714, 'boekje', '1_1_1',  1340),
(715, 'boekjeen', '3_1_1',  1340),
(716, 'boekje', '1_2_1',  1340),
(717, 'boekjeen', '3_2_1',  1340),
(718, 'boometje', '1_1_1',  1338),
(719, 'boometjeen', '3_1_1',  1338),
(720, 'boometje', '1_2_1',  1338),
(721, 'boometjeen', '3_2_1',  1338),
(722, 'katje',  '1_1_1',  1337),
(723, 'katjeen',  '3_1_1',  1337),
(724, 'katje',  '1_2_1',  1337),
(725, 'katjeen',  '3_2_1',  1337),
(726, 'selfietje',  '1_1_1',  1345),
(727, 'selfietjeen',  '3_1_1',  1345),
(728, 'selfietje',  '1_2_1',  1345),
(729, 'selfietjeen',  '3_2_1',  1345),
(730, 'wijzetje', '1_1_1',  1346),
(731, 'wijzetjeen', '3_1_1',  1346),
(732, 'wijzetje', '1_2_1',  1346),
(733, 'wijzetjeen', '3_2_1',  1346),
(734, 'walnootje',  '1_1_1',  1347),
(735, 'walnootjeen',  '3_1_1',  1347),
(736, 'walnootje',  '1_2_1',  1347),
(737, 'walnootjeen',  '3_2_1',  1347),
(738, 'opmerkingnkje',  '1_1_1',  1353),
(739, 'opmerkingnkjeen',  '3_1_1',  1353),
(740, 'opmerkingnkje',  '1_2_1',  1353),
(741, 'opmerkingnkjeen',  '3_2_1',  1353),
(742, 'grenenhoutje', '1_1_1',  1354),
(743, 'grenenhoutjeen', '3_1_1',  1354),
(744, 'grenenhoutje', '1_2_1',  1354),
(745, 'grenenhoutjeen', '3_2_1',  1354),
(754, 'ordje',  '1_1_1',  1355),
(755, 'ordjeen',  '3_1_1',  1355),
(756, 'ordje',  '1_2_1',  1355),
(757, 'ordjeen',  '3_2_1',  1355),
(758, 'huisje', '1_1_1',  1356),
(759, 'huisjeen', '3_1_1',  1356),
(760, 'huisje', '1_2_1',  1356),
(761, 'huisjeen', '3_2_1',  1356),
(762, 'steenje',  '1_1_1',  1357),
(763, 'steenjeen',  '3_1_1',  1357),
(764, 'steenje',  '1_2_1',  1357),
(765, 'steenjeen',  '3_2_1',  1357);

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
(2, 'new_lemma',  67, 1,  '2017-09-02 19:21:49'),
(3, 'edit_lemma', 17, 1,  '2017-09-17 09:24:50'),
(4, 'edit_lemma', 65, 1,  '2017-09-17 09:37:45'),
(5, 'new_lemma',  0,  1,  '2017-09-17 13:54:42'),
(6, 'new_lemma',  68, 1,  '2017-09-17 21:23:49'),
(7, 'edit_lemma', 1,  1,  '2017-09-17 22:37:24'),
(8, 'new_lemma',  69, 1,  '2017-09-17 22:44:25'),
(9, 'edit_lemma', 42, 1,  '2017-09-20 22:17:04'),
(10,  'edit_lemma', 42, 1,  '2017-09-20 22:18:46'),
(11,  'edit_lemma', 18, 1,  '2017-09-21 07:45:14'),
(12,  'edit_lemma', 42, 1,  '2017-09-21 08:24:27'),
(13,  'edit_lemma', 38, 1,  '2017-09-21 08:25:15'),
(14,  'edit_lemma', 2,  3,  '2017-09-22 00:37:44'),
(15,  'new_lemma',  0,  1,  '2017-09-28 09:59:45'),
(16,  'edit_lemma', 42, 1,  '2017-09-29 08:13:31'),
(17,  'new_lemma',  1337, 1,  '2017-10-02 22:12:37'),
(18,  'edit_lemma', 1337, 1,  '2017-10-02 22:23:39'),
(19,  'new_lemma',  1338, 1,  '2017-10-02 22:29:30'),
(20,  'edit_lemma', 1337, 3,  '2017-10-04 11:28:59'),
(21,  'edit_lemma', 1337, 3,  '2017-10-04 11:35:45'),
(22,  'edit_lemma', 1337, 3,  '2017-10-04 11:37:29'),
(23,  'edit_lemma', 1338, 3,  '2017-10-04 23:46:05'),
(24,  'new_lemma',  1339, 3,  '2017-10-17 23:45:49'),
(25,  'new_lemma',  1340, 1,  '2017-11-18 22:34:23'),
(26,  'edit_lemma', 1340, 1,  '2017-11-18 22:34:51'),
(27,  'new_lemma',  1341, 1,  '2017-11-18 23:06:05'),
(28,  'new_lemma',  1342, 1,  '2017-11-18 23:56:00'),
(29,  'new_lemma',  1343, 3,  '2017-12-05 15:16:29'),
(30,  'new_lemma',  1344, 3,  '2017-12-08 10:23:44'),
(31,  'edit_lemma', 1338, 3,  '2017-12-29 07:18:54'),
(32,  'new_lemma',  1345, 1,  '2018-02-19 12:05:59'),
(33,  'edit_lemma', 1342, 3,  '2018-03-01 23:53:55'),
(34,  'new_lemma',  1346, 3,  '2018-03-03 00:25:06'),
(35,  'new_lemma',  1347, 1,  '2018-03-10 02:10:17'),
(36,  'new_lemma',  0,  1,  '2018-03-10 02:10:31'),
(37,  'new_lemma',  0,  1,  '2018-03-12 21:38:25'),
(38,  'new_lemma',  1351, 1,  '2018-03-12 21:40:38'),
(39,  'new_lemma',  1352, 1,  '2018-03-12 21:42:11'),
(40,  'new_lemma',  1353, 1,  '2018-03-12 23:04:03'),
(41,  'new_lemma',  1354, 1,  '2018-03-12 23:26:14'),
(42,  'edit_lemma', 1354, 1,  '2018-03-12 23:26:44'),
(43,  'edit_lemma', 1354, 1,  '2018-03-12 23:27:39'),
(44,  'new_lemma',  1355, 1,  '2018-03-12 23:38:12'),
(45,  'edit_lemma', 1355, 1,  '2018-03-12 23:45:02'),
(46,  'edit_lemma', 1355, 1,  '2018-03-12 23:47:34'),
(47,  'edit_lemma', 1355, 1,  '2018-03-12 23:48:01'),
(48,  'edit_lemma', 1355, 1,  '2018-03-12 23:49:01'),
(49,  'edit_lemma', 1337, 3,  '2018-03-13 11:13:30'),
(50,  'edit_lemma', 1354, 3,  '2018-03-13 14:37:00'),
(51,  'edit_lemma', 1351, 3,  '2018-03-13 14:37:30'),
(52,  'edit_lemma', 1351, 3,  '2018-03-13 14:37:52'),
(53,  'new_lemma',  1356, 3,  '2018-03-13 14:40:05'),
(54,  'edit_lemma', 1356, 3,  '2018-03-13 14:41:28'),
(55,  'edit_lemma', 1347, 3,  '2018-03-13 14:43:32'),
(56,  'new_lemma',  1357, 3,  '2018-03-13 14:44:14'),
(57,  'edit_lemma', 1357, 3,  '2018-03-13 14:44:33'),
(58,  'edit_lemma', 1357, 3,  '2018-03-13 14:47:17'),
(59,  'edit_lemma', 1357, 3,  '2018-03-13 14:47:34'),
(60,  'edit_lemma', 1356, 3,  '2018-03-13 15:01:40'),
(61,  'edit_lemma', 1356, 3,  '2018-03-13 15:11:49'),
(62,  'edit_lemma', 1356, 3,  '2018-03-13 15:12:04');

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
(1, 'normal form',  'nom',  0,  1),
(7, 'present simple', 'ps.',  0,  2),
(8, 'Past simple',  'pts.', 0,  2),
(21,  'Present perfect',  'pre per',  0,  2),
(22,  'Past perfect', 'pas per',  0,  2),
(23,  'diminutive', 'dim',  0,  1),
(24,  'Singular', 'sg', 0,  3),
(25,  'Plural', 'pl.',  0,  3),
(26,  'normal form',  'n',  0,  5);

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
  `sorter` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ruleset` (`ruleset`),
  CONSTRAINT `morphology_ibfk_1` FOREIGN KEY (`ruleset`) REFERENCES `rulesets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology` (`id`, `name`, `rule`, `ruleset`, `in_set`, `is_irregular`, `is_aux`, `aux_placement`, `aux_mode_id`, `is_stem`, `irregular_form`, `lemma_id`, `sorter`) VALUES
(56,  'Plural', '[]&EN;', 3,  1,  0,  0,  0,  0,  0,  '', 0,  0),
(63,  't third person', '[-en]&T',  17, 1,  0,  0,  0,  0,  0,  '', 0,  0),
(64,  'Dim. rule',  '[]&ETJE?$m;&ETJE?$ng:;nkje?$ng;etje?$an;tje?!$m?!$n?!$an?$uin;tje?$VOW;je?&ELSE',  4,  1,  0,  0,  0,  0,  0,  '', 0,  -1);

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
(21,  56, 1),
(22,  63, 2),
(23,  64, 1);

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
(41,  56, 1),
(48,  63, 7),
(49,  64, 1);

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
(44,  56, 3),
(51,  63, 1),
(52,  64, 1),
(53,  64, 3);

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
(25,  56, 1),
(26,  56, 2),
(33,  63, 6),
(34,  63, 7),
(35,  64, 1),
(36,  64, 2);

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
(1, 'singular', 'sg.',  '0',  0),
(3, 'plural', 'pl', '0',  0),
(8, 'positive', 'bf.',  '0',  0),
(9, 'comperative',  'comp.',  '0',  0),
(10,  'superlative',  '0',  '0',  0),
(11,  'first person', '1',  '0',  0),
(12,  'second person',  '2',  '0',  0),
(13,  'third person', '3',  '0',  0);

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
(39,  1,  1),
(40,  3,  1),
(41,  1,  23),
(42,  3,  23),
(43,  1,  26),
(44,  3,  26);

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
(1, '&EN s',  't.j.e < &EN; > = s', 0,  1),
(2, '&EN ssst.j.e < &EN > = s', 't.j.e < &EN > = s',  0,  1);

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
(1, 'UI diphtongue',  '< ui > = œʏ̯', 5,  1);

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
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `rulesets_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `rulesets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rulesets` (`id`, `name`, `parent`) VALUES
(-1,  '/',  -1),
(0, '/rules', -1),
(3, '/rules/nouns', 0),
(4, '/rules/nouns/dimunitives', 3),
(5, '/rules/IPA-rules', 0),
(13,  '/rules/verbs', 0),
(14,  '/rules/verbs/regular', 13),
(16,  '/rules/nouns/plural_contexts', 3),
(17,  '/rules/verbs/regular/present_simple',  14),
(18,  '/rules/adjectives',  0),
(19,  '/rules/adjectives/corrections',  18),
(23,  '/rules/ortography',  0),
(24,  '/rules/no_rules_at_all', 0);

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
(3906,  1337, 1,  '2017-10-03 00:12:43'),
(3907,  1338, 1,  '2017-10-03 00:29:35'),
(3908,  1338, 3,  '2017-10-05 01:45:25'),
(3909,  1337, 3,  '2017-10-05 13:02:33'),
(3910,  1337, 3,  '2017-10-06 00:33:38'),
(3911,  1337, 3,  '2017-10-06 01:33:49'),
(3912,  1337, 1,  '2017-10-18 00:26:03'),
(3913,  1337, 3,  '2017-10-18 01:26:53'),
(3914,  1339, 3,  '2017-10-18 01:45:55'),
(3915,  1337, 3,  '2017-11-12 19:36:36'),
(3916,  1337, 3,  '2017-11-12 20:07:33'),
(3917,  1339, 1,  '2017-11-18 21:42:08'),
(3918,  1337, 1,  '2017-11-18 21:42:55'),
(3919,  1337, 1,  '2017-11-18 22:51:15'),
(3920,  1340, 1,  '2017-11-18 23:34:38'),
(3921,  1341, 1,  '2017-11-19 00:06:16'),
(3922,  1337, 1,  '2017-11-19 00:08:54'),
(3923,  1342, 1,  '2017-11-19 00:57:37'),
(3924,  1342, 1,  '2017-11-19 01:00:04'),
(3925,  1342, 1,  '2017-11-19 10:45:39'),
(3926,  1341, 0,  '2017-12-03 17:39:09'),
(3927,  1342, 0,  '2017-12-05 00:17:37'),
(3928,  1338, 1,  '2017-12-05 00:25:38'),
(3929,  1342, 1,  '2017-12-05 00:33:01'),
(3930,  1342, 0,  '2017-12-05 01:18:02'),
(3931,  1341, 1,  '2017-12-05 15:57:54'),
(3932,  1341, 1,  '2017-12-05 16:00:09'),
(3933,  1343, 3,  '2017-12-05 16:16:42'),
(3934,  1338, 3,  '2017-12-06 01:45:03'),
(3935,  1342, 3,  '2017-12-08 00:53:09'),
(3936,  1344, 3,  '2017-12-08 11:23:53'),
(3937,  1339, 3,  '2017-12-21 13:54:57'),
(3938,  1342, 3,  '2017-12-21 13:55:38'),
(3939,  1337, 0,  '2017-12-23 22:03:51'),
(3940,  1342, 1,  '2017-12-23 23:54:33'),
(3941,  1342, 1,  '2017-12-24 00:03:00'),
(3942,  1342, 0,  '2017-12-24 01:23:12'),
(3943,  1341, 3,  '2017-12-25 00:50:51'),
(3944,  1342, 3,  '2017-12-25 00:52:02'),
(3945,  1342, 3,  '2017-12-25 12:48:33'),
(3946,  1339, 1,  '2017-12-25 18:12:29'),
(3947,  1342, 1,  '2017-12-26 23:59:32'),
(3948,  1337, 1,  '2017-12-27 00:15:27'),
(3949,  1342, 1,  '2017-12-27 00:16:37'),
(3950,  1337, 1,  '2017-12-27 00:19:41'),
(3951,  1342, 1,  '2017-12-27 00:23:47'),
(3952,  1337, 1,  '2017-12-27 00:26:49'),
(3953,  1342, 1,  '2017-12-27 11:27:47'),
(3954,  1342, 1,  '2017-12-28 00:27:57'),
(3955,  1342, 0,  '2017-12-29 08:10:17'),
(3956,  1340, 0,  '2017-12-29 08:14:00'),
(3957,  1344, 0,  '2017-12-29 08:15:27'),
(3958,  1341, 3,  '2017-12-29 08:26:02'),
(3959,  1337, 3,  '2018-01-27 17:14:38'),
(3960,  1342, 1,  '2018-01-29 21:40:15'),
(3961,  1342, 1,  '2018-02-04 21:33:29'),
(3962,  1345, 1,  '2018-02-19 13:06:02'),
(3963,  1342, 1,  '2018-02-21 00:23:00'),
(3964,  1342, 3,  '2018-03-03 01:23:22'),
(3965,  1342, 0,  '2018-03-04 00:41:37'),
(3966,  1346, 0,  '2018-03-04 00:41:52'),
(3967,  1342, 0,  '2018-03-04 00:42:40'),
(3968,  1346, 0,  '2018-03-04 00:52:46'),
(3969,  1342, 0,  '2018-03-04 00:52:50'),
(3970,  1346, 0,  '2018-03-04 00:53:01'),
(3971,  1342, 0,  '2018-03-04 00:53:03'),
(3972,  1346, 0,  '2018-03-04 00:53:41'),
(3973,  1342, 0,  '2018-03-04 00:55:20'),
(3974,  1346, 0,  '2018-03-04 00:55:24'),
(3975,  1342, 0,  '2018-03-04 12:35:11'),
(3976,  1346, 0,  '2018-03-04 12:35:29'),
(3977,  1342, 0,  '2018-03-04 14:45:08'),
(3978,  1346, 0,  '2018-03-04 14:45:12'),
(3979,  1342, 0,  '2018-03-04 14:45:13'),
(3980,  1341, 0,  '2018-03-04 14:47:49'),
(3981,  1341, 1,  '2018-03-04 19:34:47'),
(3982,  1342, 1,  '2018-03-04 19:34:49'),
(3983,  1341, 1,  '2018-03-04 19:34:50'),
(3984,  1342, 1,  '2018-03-04 19:34:52'),
(3985,  1342, 0,  '2018-03-05 00:46:45'),
(3986,  1342, 1,  '2018-03-09 01:31:24'),
(3987,  1342, 1,  '2018-03-09 22:40:32'),
(3988,  1346, 1,  '2018-03-09 22:40:34'),
(3989,  1342, 1,  '2018-03-09 22:41:24'),
(3990,  1342, 1,  '2018-03-09 23:33:54'),
(3991,  1341, 1,  '2018-03-09 23:37:29'),
(3992,  1346, 1,  '2018-03-09 23:37:31'),
(3993,  1342, 1,  '2018-03-09 23:47:55'),
(3994,  1342, 1,  '2018-03-10 00:03:41'),
(3995,  1346, 1,  '2018-03-10 00:04:06'),
(3996,  1342, 1,  '2018-03-10 00:04:30'),
(3997,  1346, 1,  '2018-03-10 00:37:11'),
(3998,  1342, 1,  '2018-03-10 00:37:58'),
(3999,  1346, 1,  '2018-03-10 00:38:46'),
(4000,  1342, 1,  '2018-03-10 00:39:03'),
(4001,  1341, 1,  '2018-03-10 00:47:43'),
(4002,  1342, 1,  '2018-03-10 00:47:45'),
(4003,  1344, 1,  '2018-03-10 00:47:46'),
(4004,  1341, 1,  '2018-03-10 00:47:50'),
(4005,  1342, 1,  '2018-03-10 00:47:51'),
(4006,  1342, 1,  '2018-03-10 01:01:00'),
(4007,  1346, 1,  '2018-03-10 01:01:06'),
(4008,  1342, 1,  '2018-03-10 01:06:41'),
(4009,  1344, 1,  '2018-03-10 01:57:10'),
(4010,  1341, 1,  '2018-03-10 01:58:53'),
(4011,  1345, 1,  '2018-03-10 02:16:43'),
(4012,  1342, 0,  '2018-03-10 02:19:15'),
(4013,  1342, 1,  '2018-03-10 03:04:02'),
(4014,  1347, 1,  '2018-03-10 03:10:52'),
(4015,  1342, 1,  '2018-03-10 03:12:36'),
(4016,  1347, 1,  '2018-03-10 06:24:15'),
(4017,  1342, 1,  '2018-03-10 07:01:09'),
(4018,  1342, 1,  '2018-03-10 13:52:42'),
(4019,  1341, 1,  '2018-03-10 13:57:48'),
(4020,  1342, 1,  '2018-03-10 13:57:55'),
(4021,  1337, 1,  '2018-03-10 14:27:21'),
(4022,  1342, 1,  '2018-03-10 15:07:33'),
(4023,  1346, 1,  '2018-03-10 15:11:35'),
(4024,  1342, 1,  '2018-03-10 15:11:37'),
(4025,  1342, 1,  '2018-03-11 00:18:39'),
(4026,  1342, 1,  '2018-03-11 01:06:52'),
(4027,  1346, 1,  '2018-03-11 01:07:01'),
(4028,  1342, 1,  '2018-03-11 01:11:40'),
(4029,  1337, 1,  '2018-03-11 01:14:51'),
(4030,  1346, 1,  '2018-03-11 01:16:22'),
(4031,  1337, 1,  '2018-03-11 01:16:31'),
(4032,  1342, 1,  '2018-03-11 15:51:20'),
(4033,  1346, 1,  '2018-03-11 16:06:10'),
(4034,  1342, 1,  '2018-03-11 17:29:18'),
(4035,  1346, 1,  '2018-03-11 17:29:20'),
(4036,  1342, 1,  '2018-03-11 17:30:18'),
(4037,  1342, 1,  '2018-03-12 01:12:31'),
(4038,  1342, 1,  '2018-03-12 10:23:07'),
(4039,  1342, 1,  '2018-03-12 20:42:27'),
(4040,  1342, 1,  '2018-03-12 21:00:28'),
(4041,  1346, 1,  '2018-03-12 22:35:25'),
(4042,  1351, 1,  '2018-03-12 22:41:09'),
(4043,  1352, 1,  '2018-03-12 22:42:27'),
(4044,  1352, 1,  '2018-03-12 23:05:33'),
(4045,  1342, 1,  '2018-03-12 23:31:44'),
(4046,  1351, 1,  '2018-03-12 23:32:18'),
(4047,  1352, 1,  '2018-03-12 23:36:19'),
(4048,  1342, 1,  '2018-03-12 23:45:30'),
(4049,  1346, 1,  '2018-03-12 23:53:06'),
(4050,  1342, 1,  '2018-03-13 00:00:28'),
(4051,  1353, 1,  '2018-03-13 00:04:10'),
(4052,  1354, 1,  '2018-03-13 00:26:31'),
(4053,  1352, 1,  '2018-03-13 00:27:58'),
(4054,  1354, 1,  '2018-03-13 00:31:16'),
(4055,  1342, 1,  '2018-03-13 00:36:27'),
(4056,  1355, 1,  '2018-03-13 00:44:20'),
(4057,  1346, 1,  '2018-03-13 00:50:09'),
(4058,  1351, 1,  '2018-03-13 00:52:35'),
(4059,  1342, 1,  '2018-03-13 00:56:50'),
(4060,  1342, 1,  '2018-03-13 01:13:08'),
(4061,  1351, 3,  '2018-03-13 01:45:12'),
(4062,  1342, 3,  '2018-03-13 01:46:19'),
(4063,  1351, 3,  '2018-03-13 01:47:31'),
(4064,  1342, 3,  '2018-03-13 01:51:31'),
(4065,  1337, 3,  '2018-03-13 12:13:02'),
(4066,  1342, 3,  '2018-03-13 14:11:25'),
(4067,  1351, 3,  '2018-03-13 15:37:20'),
(4068,  1356, 3,  '2018-03-13 15:41:21'),
(4069,  1347, 3,  '2018-03-13 15:43:24'),
(4070,  1357, 3,  '2018-03-13 15:44:27'),
(4071,  1356, 3,  '2018-03-13 16:01:33'),
(4072,  1351, 3,  '2018-03-20 01:01:06'),
(4073,  1342, 3,  '2018-03-20 01:02:58');

DROP TABLE IF EXISTS `subclassifications`;
CREATE TABLE `subclassifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `subclassifications` (`id`, `name`, `short_name`) VALUES
(1, 'countable',  'cnt.'),
(2, 'non-countable',  'n.c.');

DROP TABLE IF EXISTS `submodes`;
CREATE TABLE `submodes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `submodes` (`id`, `name`, `short_name`, `hidden_native_entry`) VALUES
(1, 'indefinite', 'indef',  '0'),
(2, 'definite', 'def',  '0'),
(3, 'strong', 'str.', '0'),
(4, 'weak', 'wk.',  '0'),
(5, 'First person', '', 'DV'),
(6, 'Second person',  '', '0'),
(7, 'Third Person', '', '0'),
(8, 'conjugation',  'sg conj p',  ' '),
(9, 'conjugation',  'sg pn',  '0'),
(10,  'singular', 'sg.',  '0'),
(11,  'plural', 'pl', '0'),
(12,  'nom.', 'n.', '0');

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
(42,  1,  1),
(43,  2,  1),
(44,  1,  23);

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
(8, 17, 'lemma',  7,  'hjh',  'dsss', 1,  '0000-00-00 00:00:00',  '0000-00-00 00:00:00'),
(10,  30, 'lemma',  9,  'fd', 'Wat vind jij er van?', 1,  '0000-00-00 00:00:00',  '0000-00-00 00:00:00'),
(11,  30, 'lemma',  10, 'f',  'Ik weet het niet zo goed?',  1,  '0000-00-00 00:00:00',  '0000-00-00 00:00:00'),
(13,  26, 'lemma',  12, 'aaaaa',  'aaa',  1,  '2017-08-30 17:27:59',  '2017-08-30 15:27:59'),
(15,  1,  'lemma',  14, 'RE:',  'echt niet',  1,  '2017-09-17 23:03:16',  '2017-09-17 21:03:16'),
(17,  1337, 'lemma',  16, 'asdfds', 'adfdsfs',  3,  '2017-10-18 00:22:52',  '2017-10-17 22:22:52'),
(19,  1338, 'lemma',  18, 'Yes',  'Yes',  3,  '2017-12-29 08:22:20',  '2017-12-29 07:22:20'),
(20,  1338, 'lemma',  19, 'alright',  'That', 3,  '2017-12-29 08:22:30',  '2017-12-29 07:22:30');

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
(2054,  1,  'cat',  '', '2017-10-02 22:12:37',  1),
(2056,  1,  'tree', '', '2017-10-02 22:29:30',  1),
(2057,  15, 'träd', '', '2017-10-02 22:29:30',  1),
(2058,  1,  'bear', '', '2017-10-17 23:45:49',  3),
(2059,  15, 'bära', '', '2017-10-17 23:45:49',  3),
(2060,  1,  'book', '', '2017-11-18 22:34:51',  1),
(2061,  15, 'bok',  '', '2017-11-18 22:34:51',  1),
(2062,  15, 'tomat',  '', '2017-11-18 23:07:20',  1),
(2063,  0,  'man',  '', '2017-11-18 23:56:00',  1),
(2064,  1,  'man',  '', '2017-11-18 23:56:00',  1),
(2065,  15, 'man',  '', '2017-12-05 14:52:48',  1),
(2066,  1,  'tomato', '', '2017-12-05 14:53:00',  1),
(2067,  1,  'boat', '', '2017-12-25 12:28:02',  1),
(2068,  15, 'båt',  '', '2018-02-02 21:33:40',  1),
(2069,  15, 'skep', '', '2018-02-02 21:33:40',  1),
(2070,  0,  'göra', '', '2018-02-02 21:39:45',  1),
(2071,  0,  'selfie', '', '2018-02-19 12:05:59',  1),
(2072,  1,  'selfie', '', '2018-02-19 12:05:59',  1),
(2073,  15, 'selfie', '', '2018-02-19 12:05:59',  1),
(2074,  1,  'sage', '', '2018-03-03 00:25:06',  3),
(2075,  1,  'wise man', '', '2018-03-03 00:25:06',  3),
(2076,  1,  'make', '', '2018-03-10 00:56:32',  1),
(2077,  15, 'laga', '', '2018-03-10 00:56:46',  1),
(2078,  15, 'göra', '', '2018-03-10 00:56:46',  1),
(2079,  1,  'walnut', '', '2018-03-10 02:10:17',  1),
(2080,  1,  'remark', '', '2018-03-12 23:04:03',  1),
(2081,  1,  'observation',  '', '2018-03-12 23:04:03',  1),
(2082,  1,  'comment',  '', '2018-03-12 23:04:03',  1),
(2083,  1,  'pine wood',  '', '2018-03-12 23:26:14',  1),
(2084,  1,  'awful',  '', '2018-03-13 00:44:29',  3),
(2085,  1,  'nice', '', '2018-03-13 00:44:47',  3),
(2086,  1,  'pleasant', '', '2018-03-13 00:44:47',  3),
(2087,  1,  'bad ass',  '', '2018-03-13 00:44:47',  3),
(2088,  1,  'det här',  '', '2018-03-13 00:44:55',  3),
(2089,  1,  'house',  '', '2018-03-13 14:40:04',  3),
(2090,  1,  'dwelling', '', '2018-03-13 14:40:05',  3),
(2091,  1,  'stone',  '', '2018-03-13 14:44:14',  3);

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

INSERT INTO `translation_exceptions` (`id`, `word_id`, `language_id`, `user_id`) VALUES
(1, 1343, 15, 3),
(2, 1344, 15, 3);

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
(291, 1337, 2054, ''),
(293, 1338, 2056, ''),
(294, 1338, 2057, ''),
(295, 1339, 2058, ''),
(296, 1339, 2059, ''),
(297, 1340, 2060, ''),
(298, 1340, 2061, ''),
(299, 1341, 2062, ''),
(300, 1342, 2063, ''),
(301, 1342, 2064, ''),
(302, 1342, 2065, ''),
(303, 1341, 2066, ''),
(304, 1343, 2067, ''),
(305, 1343, 2068, ''),
(306, 1343, 2069, ''),
(307, 1344, 2070, ''),
(308, 1345, 2071, ''),
(309, 1345, 2072, ''),
(310, 1345, 2073, ''),
(311, 1346, 2074, ''),
(312, 1346, 2075, ''),
(313, 1344, 2076, ''),
(314, 1344, 2077, ''),
(315, 1344, 2078, ''),
(316, 1347, 2079, ''),
(317, 1353, 2080, ''),
(318, 1353, 2081, ''),
(319, 1353, 2082, ''),
(320, 1354, 2083, ''),
(321, 1351, 2084, ''),
(322, 1352, 2085, ''),
(323, 1352, 2086, ''),
(324, 1352, 2087, ''),
(325, 1355, 2088, ''),
(326, 1356, 2089, ''),
(327, 1356, 2090, ''),
(328, 1357, 2091, '');

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inflect` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `types` (`id`, `name`, `short_name`, `inflect`) VALUES
(1, 'noun', 'n.', 1),
(2, 'verb', 'v.', 1),
(3, 'adjective',  'adj.', 1),
(4, 'adverb', 'av.',  0);

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
(-1,  'system', 'SYSTEM', 'root', '2017-09-23 21:10:42',  0,  '', 1,  0,  'root@SYSTEM',  1,  ''),
(0, 'Guest',  'guest',  '', '2017-08-24 12:04:51',  4,  '', 1,  0,  'niet@veel.com',  0,  ''),
(1, 'Thomas de Roo',  'blekerfeld', '70674e943bcd2ce395ff619cff93c980f1cec914445cd69a30d612c7988e9966', '2017-08-24 16:09:43',  0,  'https://avatars3.githubusercontent.com/u/13293128?v=3&s=460',  1,  0,  'thomas@localhost', 0,  ''),
(3, 'Mr. Donut',  'donut',  'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', '2017-08-24 12:04:51',  0,  '', 1,  0,  'niet@veel.com',  0,  '');

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
(14,  0,  '2017-08-27 11:35:57',  '823739yP:0', '::1'),
(15,  0,  '2017-08-27 11:35:57',  '823739yP:0', '::1'),
(16,  0,  '2017-08-27 11:36:00',  '823738yP:0', '::1'),
(17,  0,  '2017-08-27 11:36:29',  '8237bcyP:0', '::1'),
(18,  0,  '2017-08-27 11:36:33',  '82377eyP:0', '::1'),
(19,  1,  '2017-08-27 11:37:47',  '87426bkA:1', '::1'),
(20,  0,  '2017-08-27 12:25:32',  'b658a2yP:0', '::1'),
(21,  0,  '2017-08-27 12:27:04',  'b658b7yP:0', '::1'),
(22,  0,  '2017-09-05 20:56:45',  '18fed2yP:0', '::1'),
(23,  0,  '2017-09-24 08:37:25',  '18fec1yP:0', '::1'),
(24,  0,  '2017-09-24 08:38:15',  '18fe1ayP:0', '::1'),
(25,  0,  '2017-10-20 22:32:19',  '18fe95mN:0', '::1'),
(26,  0,  '2018-01-30 16:40:49',  '18fefemN:0', '::1'),
(27,  0,  '2018-03-11 01:25:05',  '18fee6mN:0', '::1');

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
  CONSTRAINT `words_ibfk_8` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `words` (`id`, `native`, `lexical_form`, `ipa`, `hidden`, `type_id`, `classification_id`, `subclassification_id`, `created`, `updated`, `created_by`, `image`, `derivation`, `derived_from`) VALUES
(1337,  'kat',  '', 'kɑt',  0,  1,  1,  0,  '2018-03-13 11:13:30',  '2018-03-13 11:13:30',  3,  NULL, NULL, NULL),
(1338,  'boom', '', 'bo:m', 0,  1,  1,  0,  '2017-12-29 07:18:54',  '2017-12-29 07:18:54',  3,  NULL, NULL, NULL),
(1339,  'dragen', '', 'dra:x.ən', 0,  2,  3,  0,  '0000-00-00 00:00:00',  '2017-10-17 23:45:49',  3,  NULL, NULL, NULL),
(1340,  'boek', '', 'buk',  0,  1,  2,  0,  '2017-11-18 22:34:51',  '2017-11-18 22:34:51',  1,  NULL, NULL, NULL),
(1341,  'tomaat', '', 'tɔma:t', 0,  1,  1,  0,  '0000-00-00 00:00:00',  '2017-11-18 23:06:05',  1,  NULL, NULL, NULL),
(1342,  'man',  '', 'mɑn',  0,  1,  1,  0,  '2018-03-01 23:53:55',  '2018-03-01 23:53:55',  3,  NULL, NULL, NULL),
(1343,  'boot', '', 'bo:t', 0,  1,  1,  0,  '0000-00-00 00:00:00',  '2017-12-05 15:16:29',  3,  NULL, NULL, NULL),
(1344,  'maken',  '', 'ma:k.ən',  0,  2,  3,  0,  '0000-00-00 00:00:00',  '2017-12-08 10:23:44',  3,  NULL, NULL, NULL),
(1345,  'selfie', '', 'self.i', 0,  1,  1,  0,  '0000-00-00 00:00:00',  '2018-02-19 12:05:58',  1,  NULL, NULL, NULL),
(1346,  'wijze',  '', 'vɛɪzə',  0,  1,  1,  0,  '0000-00-00 00:00:00',  '2018-03-03 00:25:06',  3,  NULL, NULL, NULL),
(1347,  'walnoot',  '', 'vɑl.no:t', 0,  1,  1,  0,  '2018-03-13 14:43:32',  '2018-03-13 14:43:32',  3,  NULL, NULL, NULL),
(1351,  'verschrikkelijk',  '', 'ver.sxrɪ.kə.lək',  0,  3,  0,  0,  '2018-03-13 14:37:52',  '2018-03-13 14:37:52',  3,  NULL, NULL, NULL),
(1352,  'leuk', '', 'løk',  0,  3,  0,  0,  '0000-00-00 00:00:00',  '2018-03-12 21:42:11',  1,  NULL, NULL, NULL),
(1353,  'opmerking',  '', 'ɔpmɛrkɪŋ', 0,  1,  1,  0,  '0000-00-00 00:00:00',  '2018-03-12 23:04:03',  1,  NULL, NULL, NULL),
(1354,  'grenenhout', '', 'xre:nen.hɑut', 0,  1,  2,  0,  '2018-03-13 14:37:00',  '2018-03-13 14:37:00',  3,  NULL, NULL, NULL),
(1355,  'this', '', 'ðis',  0,  1,  0,  0,  '2018-03-12 23:49:01',  '2018-03-12 23:49:01',  1,  NULL, NULL, NULL),
(1356,  'huis', '', 'hœʏs', 0,  1,  2,  0,  '2018-03-13 15:12:04',  '2018-03-13 15:12:04',  3,  NULL, NULL, NULL),
(1357,  'steen',  '', 'ste:j.n',  0,  1,  1,  0,  '2018-03-13 14:47:34',  '2018-03-13 14:47:34',  3,  NULL, NULL, NULL);

-- 2018-03-20 00:28:04
