-- Adminer 4.2.5 MySQL dump

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

DROP TABLE IF EXISTS `apps`;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(255) CHARACTER SET latin1 NOT NULL,
  `getter` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `apps` (`id`, `app`, `getter`) VALUES
(1, 'home', 'home'),
(2, 'dictionary', 'dictionary'),
(3, 'dictionary', 'getword'),
(4, 'login',  'login'),
(7, 'lemma',  'lemma'),
(11,  'login',  'logout'),
(12,  'delete', 'deleteword'),
(13,  'flag', 'flag'),
(19,  'wap',  'wap'),
(20,  'admin',  'admin'),
(22,  'search', 'search'),
(25,  'alphabet', 'alphabet'),
(26,  'generate', 'generate'),
(27,  'addword',  'addword'),
(28,  'editorlanguage', 'editorlanguage'),
(29,  'phonology',  'phonology'),
(30,  'batch_translate',  'batch_translate'),
(31,  'batch_translate',  'translate'),
(32,  'lemma',  'discuss-lemma'),
(33,  'lemma',  'edit-lemma'),
(34,  'profile',  'profile'),
(35,  'dashboard',  'dashboard');

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
  KEY `word_id` (`word_id`),
  KEY `description` (`description`(191)),
  CONSTRAINT `audio_words_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audio_words` (`id`, `word_id`, `audio_file`, `description`) VALUES
(2, 17, 'kat.ogg',  'The Netherlands'),
(3, 1,  'https://pkaudio.herokuapp.com/nl-nl/man',  'nl-nl');

DROP TABLE IF EXISTS `aux_conditions`;
CREATE TABLE `aux_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aux_id` int(11) NOT NULL,
  `aux_placement` int(11) NOT NULL,
  `submode_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aux_id` (`aux_id`),
  KEY `submode_group_id` (`submode_group_id`),
  CONSTRAINT `aux_conditions_ibfk_1` FOREIGN KEY (`aux_id`) REFERENCES `words` (`id`),
  CONSTRAINT `aux_conditions_ibfk_3` FOREIGN KEY (`submode_group_id`) REFERENCES `submode_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `aux_conditions` (`id`, `aux_id`, `aux_placement`, `submode_group_id`) VALUES
(1, 5,  0,  10),
(2, 2,  0,  2);

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
(1, 'masculine',  'm',  0,  0),
(2, 'feminine', 'f',  0,  0),
(3, 'neuter', 'nt.',  0,  0),
(4, 'indefinite', 'indef.', 0,  0),
(5, 'Regular verb', 'rg.',  0,  0),
(6, 'Irregular verb', 'ir. v.', 0,  0),
(7, 'definite', 'def.', 0,  0),
(8, 'First person', '1',  0,  0),
(9, 'Second person',  '2',  0,  0),
(10,  'Third person masculine', '3 m.', 0,  0),
(11,  'Third person feminine',  '3 f.', 0,  0),
(12,  'Third person neuter',  '3 n.', 0,  0),
(13,  'common gender',  'cg.',  0,  0);

DROP TABLE IF EXISTS `classification_apply`;
CREATE TABLE `classification_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `classification_apply` (`id`, `classification_id`, `type_id`) VALUES
(1, 1,  1),
(3, 2,  1),
(5, 3,  1),
(7, 10, 4),
(9, 5,  2),
(10,  6,  2),
(13,  4,  10),
(14,  7,  10),
(15,  1,  4),
(16,  1,  10),
(17,  3,  4),
(18,  8,  4),
(19,  9,  4),
(20,  11, 4),
(21,  12, 4),
(22,  13, 5),
(23,  3,  5);

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

DROP TABLE IF EXISTS `descriptions`;
CREATE TABLE `descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translation_id` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  CONSTRAINT `descriptions_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `descriptions` (`id`, `translation_id`, `content`) VALUES
(1, 1,  'A (adult) living being of the male gender'),
(4, 54, '\'we\' is the unstressed form');

DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `parent_discussion` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`),
  CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `discussions` (`id`, `word_id`, `parent_discussion`, `user_id`, `points`, `content`, `post_date`, `last_update`) VALUES
(4, 1,  3,  2,  0,  'Jaweljawel!',  '2017-01-23 02:00:59',  '0000-00-00 00:00:00'),
(33,  1,  31, 1,  1,  'But I am stupid',  '2017-01-23 03:50:01',  '0000-00-00 00:00:00'),
(35,  32, 0,  1,  1,  'Jij en je, should they be two different lemmas?',  '2017-01-23 11:17:47',  '0000-00-00 00:00:00'),
(36,  32, 35, 1,  1,  'I don\'t know. Would that be useful?', '2017-01-23 12:07:05',  '0000-00-00 00:00:00'),
(39,  10, 38, 1,  1,  'Third level',  '2017-01-23 12:27:07',  '0000-00-00 00:00:00'),
(41,  10, 39, 1,  1,  'Fourth level', '2017-01-23 12:27:19',  '0000-00-00 00:00:00'),
(42,  10, 41, 1,  1,  'Fifth level',  '2017-01-23 12:27:26',  '0000-00-00 00:00:00'),
(43,  10, 42, 1,  1,  'Sixth level',  '2017-01-23 12:27:34',  '0000-00-00 00:00:00'),
(44,  10, 43, 1,  1,  'Seventh level',  '2017-01-23 12:27:55',  '0000-00-00 00:00:00'),
(45,  10, 44, 1,  1,  'Eighth level', '2017-01-23 12:28:05',  '0000-00-00 00:00:00'),
(46,  10, 45, 1,  1,  '9th level',  '2017-01-23 13:16:10',  '0000-00-00 00:00:00'),
(47,  10, 46, 1,  1,  '10th level', '2017-01-23 13:16:34',  '0000-00-00 00:00:00'),
(48,  44, 0,  1,  1,  'First',  '2017-01-23 13:50:50',  '0000-00-00 00:00:00'),
(52,  1,  51, 1,  1,  'Never mind, I don\'t think it\'s that important...', '2017-01-23 15:10:18',  '0000-00-00 00:00:00'),
(58,  1,  57, 1,  1,  'Eventuelt äkta maka.', '2017-01-23 16:27:55',  '0000-00-00 00:00:00'),
(59,  1,  55, 1,  1,  'karl kan va, och kanske även gubbe??', '2017-01-23 16:28:40',  '0000-00-00 00:00:00'),
(60,  1,  59, 1,  1,  'Gubbe i betydelse av \'een oude mannetje\'', '2017-01-23 16:28:59',  '0000-00-00 00:00:00'),
(66,  62, 0,  1,  1,  '**Etymology**\n[[62]] might come from [[maan]] right?',  '2017-01-30 00:28:14',  '0000-00-00 00:00:00'),
(67,  62, 66, 1,  1,  '(heeft niks met [[man]] te doen...)',  '2017-01-30 00:39:48',  '0000-00-00 00:00:00'),
(68,  10, 47, 1,  1,  '11th level', '2017-01-30 09:42:29',  '0000-00-00 00:00:00'),
(69,  61, 0,  1,  1,  'Maybe from [[maand]] ??',  '2017-01-30 13:53:57',  '0000-00-00 00:00:00'),
(70,  16, 0,  1,  1,  'test', '2017-01-31 00:21:27',  '2017-01-31 00:25:57'),
(71,  16, 0,  1,  1,  '', '2017-01-31 00:22:40',  '0000-00-00 00:00:00'),
(72,  16, 0,  1,  1,  'aaa',  '2017-01-31 00:22:42',  '0000-00-00 00:00:00'),
(73,  16, 70, 1,  1,  'vind ik ook [[Qe]]', '2017-01-31 00:27:50',  '0000-00-00 00:00:00'),
(74,  16, 0,  1,  1,  '[[1]]',  '2017-01-31 00:31:53',  '0000-00-00 00:00:00'),
(75,  16, 73, 1,  1,  '[[maan]] [[kat]] [[kater]] [[ao|katers]]', '2017-01-31 00:42:26',  '0000-00-00 00:00:00'),
(76,  7,  0,  1,  1,  '[[Xd|werkte]]',  '2017-01-31 01:31:50',  '0000-00-00 00:00:00'),
(77,  78, 0,  1,  1,  '[[p5m|test]] test',  '2017-01-31 02:12:34',  '0000-00-00 00:00:00'),
(78,  10, 68, 1,  1,  '12th level', '2017-02-05 19:40:59',  '0000-00-00 00:00:00'),
(79,  18, 0,  1,  1,  'Poesje miauw, kom eens gauw, \nik heb lekkere melk voor jou!', '2017-02-06 00:28:36',  '0000-00-00 00:00:00'),
(82,  1,  0,  1,  1,  'man\n',  '2017-02-12 15:08:24',  '0000-00-00 00:00:00'),
(83,  1,  0,  1,  1,  '**HOI**',  '2017-02-12 15:10:26',  '0000-00-00 00:00:00'),
(84,  1,  83, 1,  1,  'OOK HALLO',  '2017-02-12 15:10:45',  '0000-00-00 00:00:00'),
(85,  87, 0,  1,  1,  'This is a lemma!', '2017-02-15 01:28:27',  '0000-00-00 00:00:00'),
(86,  87, 85, 1,  1,  'No, this is a lemma - dicsussion page!', '2017-02-15 01:30:46',  '0000-00-00 00:00:00'),
(87,  44, 0,  1,  1,  'test\n', '2017-02-18 00:47:46',  '0000-00-00 00:00:00'),
(89,  44, 87, 1,  1,  '/ ', '2017-02-18 00:48:08',  '0000-00-00 00:00:00'),
(90,  10, 0,  1,  1,  'Test', '2017-02-18 13:56:48',  '0000-00-00 00:00:00'),
(91,  10, 90, 1,  1,  'Test', '2017-02-18 13:56:54',  '0000-00-00 00:00:00'),
(92,  10, 91, 1,  1,  'test', '2017-02-18 13:57:03',  '0000-00-00 00:00:00'),
(93,  10, 0,  1,  1,  '[[Ma]]', '2017-02-18 13:57:18',  '0000-00-00 00:00:00'),
(94,  20, 0,  1,  1,  'This is a test discussion! :)',  '2017-02-18 14:05:18',  '0000-00-00 00:00:00'),
(95,  20, 94, 1,  1,  'Word links are cool! [[YG]] is a type of [[Ma]]',  '2017-02-18 14:05:49',  '0000-00-00 00:00:00'),
(96,  7,  76, 1,  1,  'Discussion threads are cool!', '2017-02-18 14:16:21',  '0000-00-00 00:00:00'),
(97,  7,  96, 1,  1,  ' Word links as well: [[ja]] [[Ma]] [[VX]]',  '2017-02-18 14:16:44',  '0000-00-00 00:00:00'),
(98,  7,  97, 1,  1,  'Oops, VX does not exist',  '2017-02-18 14:16:58',  '0000-00-00 00:00:00');

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
  CONSTRAINT `etymology_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `etymology` (`id`, `word_id`, `cognates_translations`, `cognates_native`, `desc`, `first_attestation`) VALUES
(1, 1,  '1',  '', 'From Old Dutch man, from Proto-Germanic *mann-, probably ultimately from Proto-Indo-European *man-.',  '900-1000 AD.'),
(3, 17, '23', '23', 'Herkomst onbekend. Verwant met [[kater]].',  '1162');

DROP TABLE IF EXISTS `graphemes`;
CREATE TABLE `graphemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `in_alphabet` int(11) NOT NULL DEFAULT '1',
  `groupstring` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CON',
  `ipa` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `quality` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `graphemes` (`id`, `grapheme`, `in_alphabet`, `groupstring`, `ipa`, `quality`, `order`) VALUES
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
(37,  'z',  1,  'CON',  '', '', 27),
(38,  'ij', 1,  'VOW',  'p_20', '', 25),
(39,  'ui', 0,  'VOW',  'p_21', '', 0);

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


DROP TABLE IF EXISTS `idioms`;
CREATE TABLE `idioms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idiom` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idioms` (`id`, `idiom`) VALUES
(1, 'man en vrouw'),
(2, 'Als kat en hond leven'),
(3, 'De kat uit de boom kijken'),
(4, 'Een kat in de zak kopen');

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
  KEY `word_id` (`word_id`),
  KEY `idiom_id` (`idiom_id`),
  CONSTRAINT `idiom_words_ibfk_3` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`),
  CONSTRAINT `idiom_words_ibfk_4` FOREIGN KEY (`idiom_id`) REFERENCES `idioms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `idiom_words` (`id`, `idiom_id`, `word_id`, `keyword`) VALUES
(5, 1,  1,  'man'),
(6, 2,  17, 'kat'),
(8, 3,  17, 'kat'),
(9, 4,  17, 'kat');

DROP TABLE IF EXISTS `inflections`;
CREATE TABLE `inflections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applies_submode_group` int(11) NOT NULL,
  `submode_group_id` int(11) NOT NULL,
  `irregular` int(11) NOT NULL,
  `irregular_word_id` int(11) NOT NULL,
  `irregular_override` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  `subclassification_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `submode_id` int(11) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `change_frome` varchar(255) NOT NULL,
  `change_to` varchar(255) NOT NULL,
  `trim_begin` varchar(255) NOT NULL,
  `trim_end` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `submode_group_id` (`submode_group_id`),
  KEY `number_id` (`number_id`),
  KEY `mode_id` (`mode_id`),
  KEY `submode_id` (`submode_id`),
  KEY `classification_id` (`classification_id`),
  KEY `type_id` (`type_id`),
  KEY `irregular_word_id` (`irregular_word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `inflections` (`id`, `applies_submode_group`, `submode_group_id`, `irregular`, `irregular_word_id`, `irregular_override`, `type_id`, `classification_id`, `subclassification_id`, `number_id`, `mode_id`, `submode_id`, `prefix`, `suffix`, `change_frome`, `change_to`, `trim_begin`, `trim_end`) VALUES
(5, 1,  4,  0,  0,  '', 2,  0,  0,  0,  0,  0,  '', 'en', '', '', '', 'en'),
(6, 0,  0,  0,  0,  '', 2,  5,  0,  1,  7,  5,  '', '', '', '', '', 'en'),
(7, 0,  0,  0,  0,  '', 2,  5,  0,  1,  7,  6,  '', 't',  '', '', '', 'en'),
(8, 0,  0,  0,  0,  '', 2,  5,  0,  1,  7,  7,  '', 't',  '', '', '', 'en'),
(9, 1,  5,  0,  0,  '', 2,  0,  0,  0,  0,  0,  '', 'ten',  '', '', '', 'en'),
(10,  1,  6,  0,  0,  '', 2,  0,  0,  0,  0,  0,  '', 'te', '', '', '', 'en'),
(11,  1,  7,  1,  5,  'hebben', 2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(12,  0,  0,  1,  5,  'heb',  2,  6,  0,  1,  7,  5,  '', '', '', '', '', ''),
(13,  0,  0,  1,  5,  'hebt', 2,  6,  0,  1,  7,  6,  '', '', '', '', '', ''),
(14,  0,  0,  1,  5,  'heeft',  2,  6,  0,  1,  7,  7,  '', '', '', '', '', ''),
(15,  1,  9,  1,  5,  'had',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(16,  1,  8,  1,  5,  'hadden', 2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(17,  1,  10, 0,  0,  '', 2,  0,  0,  0,  0,  0,  'ge', 't',  '', '', '', 'en'),
(18,  1,  11, 1,  5,  'gehad',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(22,  0,  0,  1,  14, 'verlies',  2,  6,  0,  1,  7,  5,  '', '', '', '', '', ''),
(23,  0,  0,  1,  14, 'verliest', 2,  6,  0,  1,  7,  6,  '', '', '', '', '', ''),
(25,  1,  7,  1,  14, 'verliezen',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(26,  0,  0,  1,  14, 'verliest', 2,  6,  0,  1,  7,  7,  '', '', '', '', '', ''),
(27,  1,  11, 1,  14, 'verloren', 2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(28,  1,  9,  1,  14, 'verloor',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(29,  1,  8,  1,  14, 'verloren', 2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(31,  0,  0,  1,  11, 'hem',  4,  1,  0,  1,  6,  9,  '', '', '', '', '', ''),
(32,  1,  13, 0,  0,  '', 1,  0,  0,  0,  0,  0,  '', 'netje',  '', '', '', ''),
(34,  1,  14, 0,  0,  '', 1,  0,  0,  0,  0,  0,  '', 'nen',  '', '', '', ''),
(35,  1,  15, 1,  31, 'mij',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(36,  1,  15, 1,  31, 'me', 4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(37,  1,  15, 1,  11, 'hem',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(38,  1,  15, 1,  28, 'haar', 4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(39,  1,  15, 1,  28, 'd\'r', 4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(40,  1,  15, 1,  11, '\'m',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(41,  1,  15, 1,  32, 'jou',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(42,  1,  15, 1,  32, 'je', 4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(43,  1,  15, 1,  33, 'je', 4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(44,  1,  15, 1,  33, 'jou',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(45,  1,  15, 1,  34, 'ons',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(46,  1,  15, 1,  35, 'ons',  4,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(48,  0,  0,  0,  0,  '', 5,  13, 4,  8,  24, 3,  '', 'e',  '', '', '', ''),
(49,  0,  0,  0,  0,  '', 5,  3,  4,  8,  24, 4,  '', 'e',  '', '', '', ''),
(50,  0,  0,  0,  0,  '', 5,  13, 4,  8,  24, 4,  '', 'e',  '', '', '', ''),
(51,  0,  0,  0,  0,  '', 5,  13, 3,  9,  24, 4,  '', 'ere',  '', '', '', ''),
(52,  0,  0,  0,  0,  '', 5,  13, 3,  9,  24, 3,  '', 'ere',  '', '', '', ''),
(53,  0,  0,  0,  0,  '', 5,  3,  3,  9,  24, 4,  '', 'ere',  '', '', '', ''),
(54,  0,  0,  0,  0,  '', 5,  3,  3,  9,  24, 3,  '', 'er', '', '', '', ''),
(58,  0,  0,  0,  0,  '', 5,  13, 3,  10, 24, 4,  '', 'ste',  '', '', '', ''),
(59,  0,  0,  0,  0,  '', 5,  13, 3,  10, 24, 3,  '', 'ste',  '', '', '', ''),
(60,  0,  0,  0,  0,  '', 5,  3,  3,  10, 24, 4,  '', 'ste',  '', '', '', ''),
(61,  0,  0,  0,  0,  '', 5,  3,  3,  10, 24, 3,  '', 'st', '', '', '', ''),
(62,  0,  0,  0,  0,  '', 5,  13, 4,  8,  25, 3,  '', 'e',  '', '', '', ''),
(63,  0,  0,  0,  0,  '', 5,  3,  4,  8,  25, 4,  '', 'e',  '', '', '', ''),
(64,  0,  0,  0,  0,  '', 5,  13, 4,  8,  25, 4,  '', 'e',  '', '', '', ''),
(65,  0,  0,  0,  0,  '', 5,  13, 3,  9,  25, 4,  '', 'ere',  '', '', '', ''),
(66,  0,  0,  0,  0,  '', 5,  13, 3,  9,  25, 3,  '', 'ere',  '', '', '', ''),
(67,  0,  0,  0,  0,  '', 5,  3,  3,  9,  25, 4,  '', 'ere',  '', '', '', ''),
(68,  0,  0,  0,  0,  '', 5,  3,  3,  9,  25, 3,  '', 'ere',  '', '', '', ''),
(69,  0,  0,  0,  0,  '', 5,  13, 3,  10, 25, 4,  '', 'ste',  '', '', '', ''),
(70,  0,  0,  0,  0,  '', 5,  13, 3,  10, 25, 3,  '', 'ste',  '', '', '', ''),
(71,  0,  0,  0,  0,  '', 5,  3,  3,  10, 25, 4,  '', 'ste',  '', '', '', ''),
(72,  0,  0,  0,  0,  '', 5,  3,  3,  10, 25, 3,  '', 'ste',  '', '', '', ''),
(77,  0,  0,  0,  0,  '', 5,  3,  4,  8,  25, 3,  '', 'e',  '', '', '', ''),
(78,  1,  7,  1,  79, 'zijn', 2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(79,  0,  0,  1,  79, 'ben',  2,  6,  0,  1,  7,  5,  '', '', '', '', '', ''),
(80,  0,  0,  1,  79, 'bent', 2,  6,  0,  1,  7,  6,  '', '', '', '', '', ''),
(81,  0,  0,  1,  79, 'is', 2,  6,  0,  1,  7,  7,  '', '', '', '', '', ''),
(82,  1,  9,  1,  79, 'was',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(83,  1,  8,  1,  79, 'waren',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', ''),
(84,  1,  11, 1,  79, 'geweest',  2,  0,  0,  0,  0,  0,  '', '', '', '', '', '');

DROP TABLE IF EXISTS `inflections_exclude_from_table`;
CREATE TABLE `inflections_exclude_from_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exclude_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exclude_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inflections_exclude_from_table` (`id`, `exclude_table`, `from_table`, `exclude_id`, `from_id`) VALUES
(2, 'numbers',  'modes',  10, 26);

DROP TABLE IF EXISTS `inflection_cache`;
CREATE TABLE `inflection_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inflection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `word_id` int(11) NOT NULL,
  `inflection_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `inflection_cache_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`)
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
(585, 'jonger', 82, '4cd6edb84f9657e55a1f922766f8ef58'),
(586, 'jongst', 82, '747230e27465321b64d246c67142c0ff'),
(587, 'leuker', 69, '08a4948d772d85f5f936f2d66445d46c'),
(588, 'leukst', 69, 'dddd3245725628985e28c1044dd94bbf'),
(589, 'test', 88, '8bf73c1e802b8e2a11e970c171f7cb6f'),
(590, 'testt',  88, '07faa339cbdf6c2f5d86aba01f6b37e2'),
(591, 'testte', 88, 'ec721811e61b2ea5fa515964ceb95067'),
(592, 'testten',  88, '7839a539755e35dc00b94115f3402515'),
(593, 'getestt',  88, 'd4d21c00f71ddb8db3bc9dceb9e54b15'),
(599, 'goede',  29, 'bbbf8fb3a8cdbd052fa5ce60516f9fac'),
(602, 'betere', 29, '31f43dcc0353f362d0ab49b1beb2c7f9'),
(605, 'beste',  29, '8c3ff81f1e5941a1889ee15850fea0b6'),
(606, 'beter',  29, '08a4948d772d85f5f936f2d66445d46c'),
(607, 'best', 29, 'dddd3245725628985e28c1044dd94bbf');

DROP TABLE IF EXISTS `inflection_scripts`;
CREATE TABLE `inflection_scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `execute_prior_to_inflection` int(11) NOT NULL DEFAULT '0',
  `execute_always` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inflection_scripts` (`id`, `name`, `execute_prior_to_inflection`, `execute_always`, `weight`) VALUES
(1, 'sample', 1,  0,  0);

DROP TABLE IF EXISTS `inflection_script_submode_groups`;
CREATE TABLE `inflection_script_submode_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `script_id` int(11) NOT NULL,
  `submode_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `script_id` (`script_id`),
  KEY `submode_group_id` (`submode_group_id`),
  CONSTRAINT `inflection_script_submode_groups_ibfk_1` FOREIGN KEY (`script_id`) REFERENCES `inflection_scripts` (`id`),
  CONSTRAINT `inflection_script_submode_groups_ibfk_2` FOREIGN KEY (`submode_group_id`) REFERENCES `submode_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inflection_script_submode_groups` (`id`, `script_id`, `submode_group_id`) VALUES
(1, 1,  18);

DROP TABLE IF EXISTS `ipa_c`;
CREATE TABLE `ipa_c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_mode_id` int(11) NOT NULL,
  `c_place_id` int(11) NOT NULL,
  `c_articulation_id` int(11) NOT NULL,
  `is_copy` int(11) NOT NULL DEFAULT '0',
  `symbol` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `c_mode_id` (`c_mode_id`),
  KEY `c_place_id` (`c_place_id`),
  KEY `c_articulation_id` (`c_articulation_id`),
  CONSTRAINT `ipa_c_ibfk_1` FOREIGN KEY (`c_mode_id`) REFERENCES `ipa_c_mode` (`id`),
  CONSTRAINT `ipa_c_ibfk_2` FOREIGN KEY (`c_place_id`) REFERENCES `ipa_c_place` (`id`),
  CONSTRAINT `ipa_c_ibfk_3` FOREIGN KEY (`c_articulation_id`) REFERENCES `ipa_c_articulation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_c` (`id`, `c_mode_id`, `c_place_id`, `c_articulation_id`, `is_copy`, `symbol`, `active`) VALUES
(1, 1,  1,  1,  0,  'm̥', 0),
(2, 2,  1,  1,  0,  'm',  1),
(3, 2,  2,  1,  0,  'ɱ',  0),
(4, 2,  3,  1,  0,  'n̪', 0),
(5, 2,  4,  1,  0,  'n',  0),
(6, 2,  6,  1,  0,  'ɳ',  1),
(7, 2,  7,  1,  0,  'ȵ',  0),
(8, 2,  8,  1,  0,  'ɲ',  1),
(9, 2,  10, 1,  0,  'ŋ',  1),
(10,  2,  11, 1,  0,  'ɴ',  0),
(11,  1,  1,  2,  0,  'p',  1),
(12,  2,  1,  2,  0,  'b',  1),
(13,  1,  2,  2,  0,  'p̪', 0),
(14,  2,  2,  2,  0,  'b̪', 0),
(15,  1,  3,  2,  0,  't̪', 0),
(16,  2,  3,  2,  0,  'd̪', 0),
(17,  1,  4,  2,  0,  't',  1),
(18,  2,  4,  2,  0,  'd',  1),
(19,  1,  6,  2,  0,  'ʈ',  1),
(20,  2,  6,  2,  0,  'ɖ',  1),
(21,  1,  7,  2,  0,  'ȶ',  0),
(22,  2,  7,  2,  0,  'd̂', 0),
(23,  1,  8,  2,  0,  'c',  0),
(24,  2,  8,  2,  0,  'ɟ',  0),
(25,  2,  10, 2,  0,  'g',  1),
(26,  1,  10, 2,  0,  'k',  1),
(27,  1,  11, 2,  0,  'q',  0),
(28,  2,  11, 2,  0,  'ɢ',  0),
(29,  2,  13, 2,  0,  'ʡ',  0),
(30,  2,  14, 2,  0,  'ʔ',  1),
(31,  1,  1,  3,  0,  'ɸ',  0),
(32,  2,  1,  3,  0,  'β',  0),
(33,  1,  2,  3,  0,  'f',  1),
(34,  2,  2,  3,  0,  'v',  1),
(35,  1,  3,  3,  0,  'θ',  0),
(36,  2,  3,  3,  0,  'ð',  0),
(37,  1,  4,  3,  0,  's',  1),
(38,  2,  4,  3,  0,  'z',  1),
(39,  1,  5,  3,  0,  'ʃ',  0),
(40,  2,  5,  3,  0,  'ʒ',  1),
(41,  1,  6,  3,  0,  'ʂ',  1),
(42,  2,  6,  3,  0,  'ʐ',  0),
(44,  1,  7,  3,  0,  'ɕ',  0),
(45,  2,  7,  3,  0,  'ʑ',  0),
(46,  1,  8,  3,  0,  'ç',  0),
(47,  2,  8,  3,  0,  'ʝ',  0),
(48,  1,  10, 3,  0,  'x',  0),
(49,  2,  10, 3,  0,  'ɣ',  0),
(50,  1,  11, 3,  0,  'χ',  0),
(51,  2,  11, 3,  0,  'ʁ',  0),
(52,  1,  12, 3,  0,  'ħ',  0),
(53,  2,  12, 3,  0,  'ʕ',  0),
(54,  1,  13, 3,  0,  'ʜ',  0),
(55,  2,  13, 3,  0,  'ʢ',  0),
(56,  1,  14, 3,  0,  'h',  0),
(57,  2,  14, 3,  0,  'ɦ',  1),
(58,  1,  15, 3,  0,  'ɧ',  0),
(68,  1,  1,  4,  0,  'pɸ', 0),
(69,  2,  1,  4,  0,  'bβ', 0),
(70,  1,  2,  4,  0,  'p̪f',  0),
(71,  2,  2,  4,  0,  'b̪v',  0),
(72,  1,  3,  4,  0,  't̪θ',  0),
(73,  2,  3,  4,  0,  'd̪ð',  0),
(74,  1,  4,  4,  0,  't͡s',  0),
(75,  2,  4,  4,  0,  'd͡z',  0),
(76,  1,  5,  4,  0,  't͡ʃ',  0),
(77,  2,  5,  4,  0,  'd͡ʒ',  0),
(78,  1,  6,  4,  0,  'ʈʂ', 0),
(79,  2,  6,  4,  0,  'ɖʐ', 0),
(80,  1,  7,  4,  0,  't͡ɕ',  0),
(81,  2,  7,  4,  0,  'd͡ʑ',  0),
(82,  1,  8,  4,  0,  'c͡ç',  0),
(83,  2,  8,  4,  0,  'ɟʝ', 0),
(84,  1,  10, 4,  0,  'k͡x',  0),
(85,  2,  10, 4,  0,  'g͡ɣ',  0),
(86,  1,  11, 4,  0,  'q͡χ',  0),
(87,  2,  11, 4,  0,  'ɢʁ', 0),
(88,  1,  15, 4,  0,  't͡ɬ',  0),
(89,  2,  15, 4,  0,  'd͡ɮ',  0),
(90,  2,  4,  5,  0,  'l',  1),
(91,  2,  6,  5,  0,  'ɭ',  0),
(92,  2,  7,  5,  0,  'ȴ',  0),
(93,  2,  8,  5,  0,  'ʎ',  0),
(94,  2,  10, 5,  0,  'ʟ',  0),
(95,  2,  15, 5,  0,  'ɫ',  0),
(96,  1,  4,  6,  0,  'ɬ',  0),
(97,  2,  4,  6,  0,  'ɮ',  0),
(98,  2,  4,  7,  0,  'ɺ',  0),
(99,  2,  2,  8,  0,  'ʋ',  1),
(100, 2,  4,  8,  0,  'ɹ',  0),
(101, 2,  6,  8,  0,  'ɻ',  0),
(102, 2,  8,  8,  0,  'j',  1),
(103, 1,  9,  8,  0,  'ʍ',  0),
(104, 2,  9,  8,  0,  'w',  1),
(105, 2,  10, 8,  0,  'ɰ',  0),
(106, 2,  15, 8,  0,  'ɥ',  0),
(107, 2,  1,  9,  0,  'ʙ',  0),
(108, 2,  4,  9,  0,  'r',  0),
(109, 2,  11, 9,  0,  'ʀ',  0),
(110, 2,  2,  10, 0,  'ⱱ',  0),
(111, 2,  4,  10, 0,  'ɾ',  1),
(112, 2,  6,  10, 0,  'ɽ',  0),
(113, 1,  1,  11, 0,  'ʘ',  0),
(114, 2,  3,  11, 0,  'ǀ',  0),
(115, 2,  4,  11, 0,  'ǃ',  0),
(116, 2,  6,  11, 0,  '‼',  0),
(117, 2,  7,  11, 0,  'ǁ',  0),
(118, 2,  8,  11, 0,  'ǂ',  0),
(119, 2,  10, 11, 0,  'ʞ',  0),
(120, 2,  1,  12, 0,  'ɓ',  0),
(121, 2,  3,  12, 0,  'ɗ',  0),
(122, 2,  4,  12, 0,  'ɗ',  0),
(123, 2,  6,  12, 0,  'ᶑ',  0),
(124, 2,  8,  12, 0,  'ʄ',  0),
(125, 2,  10, 12, 0,  'ɠ',  0),
(126, 2,  13, 12, 0,  'ʛ',  0);

DROP TABLE IF EXISTS `ipa_c_articulation`;
CREATE TABLE `ipa_c_articulation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_c_articulation` (`id`, `name`) VALUES
(1, 'Nasal'),
(2, 'Plosive'),
(3, 'Fricative'),
(4, 'Affricate'),
(5, 'Lateral approximant'),
(6, 'Lateral fricative'),
(7, 'Lateral flap'),
(8, 'Approximant  '),
(9, 'Trill'),
(10,  'Flap'),
(11,  'Click'),
(12,  'Implosive');

DROP TABLE IF EXISTS `ipa_c_mode`;
CREATE TABLE `ipa_c_mode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_c_mode` (`id`, `name`) VALUES
(1, 'Voiceless'),
(2, 'Voiced');

DROP TABLE IF EXISTS `ipa_c_place`;
CREATE TABLE `ipa_c_place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_c_place` (`id`, `name`) VALUES
(1, 'Bilabial'),
(2, 'Labiodental'),
(3, 'Dental'),
(4, 'Alveolar'),
(5, 'Postalveolar'),
(6, 'Retroflex'),
(7, 'Alveolopalatal'),
(8, 'Palatal'),
(9, 'Labiovelar'),
(10,  'Velar'),
(11,  'Uvular'),
(12,  'Pharyngeal'),
(13,  'Epiglottal'),
(14,  'Glottal'),
(15,  'Other');

DROP TABLE IF EXISTS `ipa_polyphthongs`;
CREATE TABLE `ipa_polyphthongs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_polyphthongs` (`id`, `combination`) VALUES
(20,  'v_22,v_2'),
(21,  'v_23,v_3');

DROP TABLE IF EXISTS `ipa_regex`;
CREATE TABLE `ipa_regex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `search` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `replace` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `examples` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ipa_regex` (`id`, `sort`, `search`, `replace`, `examples`) VALUES
(2, 1,  '^sch', 'sχ', ''),
(3, 2,  '[o][o](?=[-CON-]{2}|[-CON-]\\b)|(?<!o)[o](?=[-CON-][^-CON-])|[o][o]\\b|^[o]\\b', 'oʊ̯',  'sch[oo]l,sch[o]len'),
(4, 0,  'l\\b', 'l',  ''),
(5, 0,  '(?<!o)[o](?=[-CON-]{2}|[-CON-]\\b)', 'ɔ',  'z[o]n'),
(6, 2,  '[a][a](?=[-CON-]{2}|[-CON-]\\b)|(?<!a)[a](?=[-CON-][^-CON-])|[a][a]\\b|^[a]\\b', 'a:', ''),
(7, 0,  '(?<!a)[a](?=[-CON-]{2}|[-CON-]\\b)', 'ɑ',  ''),
(8, 0,  '^g', 'ɣ',  ''),
(9, 0,  'oe', 'u',  ''),
(10,  0,  '(?<![eio])e\\b', 'ə',  ''),
(11,  0,  '[i][e](?=[-CON-]{2}|[-CON-]\\b)|[i](?!e)(?=[-CON-][^-CON-])|[i][e]\\b|^[i]\\b',  'i',  ''),
(12,  0,  '(?<!i)[i](?=[-CON-]{2}|[-CON-]\\b)', 'v_8',  ''),
(13,  0,  '[n][g]', 'c_9',  'koni[ng]'),
(14,  -4, 'ui', 'œy̯',  'h[ui]s');

DROP TABLE IF EXISTS `ipa_v`;
CREATE TABLE `ipa_v` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_mode_id` int(11) NOT NULL,
  `v_place_id` int(11) NOT NULL,
  `v_articulation_id` int(11) NOT NULL,
  `is_copy` int(11) NOT NULL DEFAULT '0',
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `v_mode_id` (`v_mode_id`),
  KEY `v_place_id` (`v_place_id`),
  KEY `v_articulation_id` (`v_articulation_id`),
  CONSTRAINT `ipa_v_ibfk_1` FOREIGN KEY (`v_mode_id`) REFERENCES `ipa_v_mode` (`id`),
  CONSTRAINT `ipa_v_ibfk_2` FOREIGN KEY (`v_place_id`) REFERENCES `ipa_v_place` (`id`),
  CONSTRAINT `ipa_v_ibfk_3` FOREIGN KEY (`v_articulation_id`) REFERENCES `ipa_v_articulation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_v` (`id`, `v_mode_id`, `v_place_id`, `v_articulation_id`, `is_copy`, `symbol`, `active`) VALUES
(2, 1,  1,  1,  0,  'i',  1),
(3, 2,  1,  1,  0,  'y',  1),
(4, 1,  3,  1,  0,  'ɨ',  0),
(5, 2,  3,  1,  0,  'ʉ',  1),
(6, 1,  5,  1,  0,  'ɯ',  0),
(7, 2,  5,  1,  0,  'u',  1),
(8, 1,  2,  2,  0,  'ɪ',  1),
(9, 2,  2,  2,  0,  'ʏ',  1),
(10,  1,  3,  2,  0,  'ɪ̈', 0),
(11,  2,  3,  2,  0,  'ʊ̈', 0),
(12,  2,  4,  2,  0,  'ʊ',  0),
(13,  1,  1,  3,  0,  'e',  0),
(14,  2,  1,  3,  0,  'ø',  0),
(15,  1,  3,  3,  0,  'ɘ',  0),
(16,  2,  3,  3,  0,  'ɵ',  0),
(17,  1,  5,  3,  0,  'ɤ',  0),
(18,  2,  5,  3,  0,  'o',  0),
(19,  1,  1,  4,  0,  'e̞', 0),
(20,  1,  3,  4,  0,  'ə',  1),
(21,  2,  5,  4,  0,  'o̞', 0),
(22,  1,  1,  5,  0,  'ɛ',  1),
(23,  2,  1,  5,  0,  'œ',  1),
(24,  1,  3,  5,  0,  'ɜ',  0),
(25,  2,  3,  5,  0,  'ɞ',  0),
(26,  1,  5,  5,  0,  'ʌ',  0),
(27,  2,  5,  5,  0,  'ɔ',  1),
(28,  1,  1,  6,  0,  'æ',  0),
(29,  2,  3,  6,  0,  'ɐ',  0),
(30,  1,  1,  7,  0,  'a',  1),
(31,  2,  1,  7,  0,  'ɶ',  0),
(32,  1,  3,  7,  0,  'ä',  0),
(33,  1,  5,  7,  0,  'ɑ',  1),
(34,  2,  5,  7,  0,  'ɒ',  0),
(35,  1,  1,  5,  1,  'ɛ:', 1);

DROP TABLE IF EXISTS `ipa_v_articulation`;
CREATE TABLE `ipa_v_articulation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_v_articulation` (`id`, `name`) VALUES
(1, 'Close'),
(2, 'Near-close'),
(3, 'Close-mid'),
(4, 'Mid'),
(5, 'Open-mid'),
(6, 'Near-open'),
(7, 'Open');

DROP TABLE IF EXISTS `ipa_v_mode`;
CREATE TABLE `ipa_v_mode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_v_mode` (`id`, `name`) VALUES
(1, 'Rounded'),
(2, 'Unrounded');

DROP TABLE IF EXISTS `ipa_v_place`;
CREATE TABLE `ipa_v_place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ipa_v_place` (`id`, `name`) VALUES
(1, 'Front'),
(2, 'Near-front'),
(3, 'Central'),
(4, 'Near-back'),
(5, 'Back');

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
(0, 'Dutch',  0,  'nld',  1,  'nl-NL',  ''),
(1, 'English',  0,  'eng',  1,  'en-GB',  ''),
(8, 'Polish', 0,  'pl', 1,  '', ''),
(10,  'Swedish',  0,  'se', 1,  '', '');

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
(9, 8,  2,  0),
(11,  21, 2,  0),
(12,  22, 2,  0),
(13,  23, 1,  1),
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
(10,  'superlative',  '0',  '0',  0);

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
(31,  1,  1),
(32,  3,  1),
(33,  1,  4),
(34,  3,  4);

DROP TABLE IF EXISTS `preview_inflections`;
CREATE TABLE `preview_inflections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `submode_id` int(255) NOT NULL,
  `no_aux` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `number_id` (`number_id`),
  KEY `mode_id` (`mode_id`),
  KEY `submode_id` (`submode_id`),
  CONSTRAINT `preview_inflections_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`),
  CONSTRAINT `preview_inflections_ibfk_2` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`),
  CONSTRAINT `preview_inflections_ibfk_3` FOREIGN KEY (`mode_id`) REFERENCES `modes` (`id`),
  CONSTRAINT `preview_inflections_ibfk_4` FOREIGN KEY (`submode_id`) REFERENCES `submodes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `preview_inflections` (`id`, `name`, `type_id`, `number_id`, `mode_id`, `submode_id`, `no_aux`) VALUES
(1, 'indef.', 1,  1,  1,  1,  0),
(2, 'pl.',  1,  3,  1,  1,  0),
(3, 'pp. ', 2,  1,  21, 5,  1),
(4, 'pst.', 2,  1,  8,  5,  0),
(5, 'obj.', 4,  1,  6,  9,  0);

DROP TABLE IF EXISTS `settings_boolean`;
CREATE TABLE `settings_boolean` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `settings_boolean` (`id`, `name`, `value`) VALUES
(1, 'prodrop',  0);

DROP TABLE IF EXISTS `stems`;
CREATE TABLE `stems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `applies_submode_group` int(11) NOT NULL,
  `submode_group_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  `subclassification_id` int(11) NOT NULL,
  `mode_id` int(11) NOT NULL,
  `submode_id` int(255) NOT NULL,
  `number_id` int(11) NOT NULL,
  `stem_override` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  KEY `classification_id` (`classification_id`),
  KEY `subclassification_id` (`subclassification_id`),
  KEY `mode_id` (`mode_id`),
  KEY `submode_id` (`submode_id`),
  KEY `number_id` (`number_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `stems` (`id`, `word_id`, `applies_submode_group`, `submode_group_id`, `classification_id`, `subclassification_id`, `mode_id`, `submode_id`, `number_id`, `stem_override`) VALUES
(2, 29, 1,  18, 0,  0,  0,  0,  0,  'bet'),
(3, 29, 1,  19, 0,  0,  0,  0,  0,  'be');

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
(5, 'compund superlative',  'adj-cs.',  0,  0);

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
(9, 'conjugation',  'sg pn',  '0',  4);

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
(24,  5,  1),
(25,  6,  1),
(26,  7,  1);

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
(5, 15, 14, 100),
(6, 8,  15, 25),
(7, 17, 18, 100),
(8, 25, 1,  50),
(9, 10, 25, 25),
(10,  17, 23, 50);

DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `translation` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `translations_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `translations` (`id`, `language_id`, `translation`) VALUES
(1, 1,  'man'),
(2, 1,  'husband'),
(3, 1,  'work'),
(4, 1,  'function'),
(5, 1,  'woman'),
(6, 1,  'wife'),
(7, 1,  'have'),
(10,  1,  'he'),
(11,  1,  'uncle'),
(13,  1,  'lose'),
(15,  1,  'win'),
(23,  1,  'cat'),
(25,  1,  'pussy'),
(26,  1,  'feline'),
(30,  1,  'vagina'),
(32,  1,  'kitten'),
(33,  0,  'persoon'),
(34,  1,  'one'),
(35,  1,  'everyone'),
(36,  1,  'tomcat'),
(38,  1,  'hangover'),
(39,  1,  'mister'),
(42,  0,  'mijnheer'),
(45,  1,  'she'),
(48,  1,  'good'),
(49,  1,  'ice'),
(50,  1,  'ice cream'),
(51,  1,  'I'),
(52,  1,  'you'),
(54,  1,  'we'),
(55,  1,  'potatoe'),
(56,  1,  'earth'),
(57,  1,  'all'),
(58,  1,  'pet'),
(72,  1,  'party'),
(73,  1,  'feast'),
(74,  1,  'celebration'),
(79,  1,  'pancake'),
(80,  1,  'fool'),
(81,  1,  'pancake'),
(82,  1,  'pancake'),
(83,  1,  'pancake'),
(86,  1,  'stone'),
(87,  1,  'stone'),
(88,  1,  'manner'),
(89,  1,  'way'),
(90,  1,  'mood of speech'),
(91,  1,  'sage'),
(92,  1,  'wise man'),
(93,  1,  'nose'),
(94,  1,  'moon'),
(95,  1,  'month'),
(96,  1,  'bad'),
(97,  1,  'lemon'),
(98,  1,  'Lithviscian'),
(99,  1,  'boy'),
(100, 1,  'rain'),
(101, 1,  'hail'),
(102, 1,  'picnic'),
(103, 1,  'dog'),
(104, 1,  'hound'),
(105, 1,  'pleasant'),
(106, 1,  'nice'),
(107, 1,  'enoyable'),
(108, 1,  'likable'),
(109, 1,  'good-looking'),
(110, 1,  'quote'),
(111, 1,  'tree'),
(112, 1,  'wooden object'),
(113, 1,  'pretty'),
(114, 1,  'beautiful'),
(115, 1,  'nice'),
(116, 1,  'better'),
(117, 1,  'best'),
(118, 1,  'bad'),
(119, 1,  'worse'),
(120, 1,  'worst'),
(131, 1,  'book'),
(132, 1,  'bread'),
(198, 8,  'mężczyzna'),
(199, 8,  'pan'),
(200, 8,  'człowiek'),
(201, 1,  'a'),
(202, 1,  'an'),
(203, 1,  'cheer'),
(204, 1,  'shout'),
(205, 1,  'husband'),
(206, 1,  'the'),
(207, 1,  'little man'),
(208, 1,  'male'),
(209, 1,  'guy that preforms odd jobs'),
(210, 1,  'little cat'),
(211, 1,  'vice'),
(212, 8,  'mać'),
(213, 8,  'pracować'),
(214, 8,  'działać'),
(215, 8,  'udać się'),
(216, 8,  'wiwatować'),
(217, 8,  'kobieta'),
(218, 8,  'żona'),
(219, 8,  'żona'),
(220, 8,  'on'),
(221, 8,  ''),
(222, 8,  'kot'),
(223, 8,  'kot'),
(224, 8,  'kot'),
(225, 8,  'pochwa'),
(226, 8,  'kot'),
(227, 8,  'kocur'),
(228, 8,  'kocurek'),
(229, 8,  'pan'),
(230, 8,  'ona'),
(231, 8,  'kocur'),
(232, 8,  'kac'),
(233, 1,  'onion'),
(234, 8,  'cebula'),
(235, 8,  'pies'),
(242, 8,  'ja'),
(243, 8,  'mnie'),
(244, 1,  'me'),
(245, 1,  'I'),
(246, 1,  'me'),
(247, 8,  'dobry'),
(248, 8,  'dobrze'),
(249, 8,  'zdrowy'),
(250, 8,  'odpowiedni'),
(251, 8,  'lody'),
(252, 8,  'ty'),
(253, 8,  'ty'),
(254, 8,  'my'),
(255, 8,  'my'),
(256, 8,  'ziemniak'),
(257, 8,  'kartoffel'),
(258, 10, 'man'),
(259, 10, 'make'),
(260, 10, 'gubbe'),
(261, 10, 'karl'),
(262, 10, 'en'),
(263, 10, 'ett'),
(264, 10, 'ha'),
(265, 10, 'jobba'),
(266, 10, 'jubla'),
(267, 10, 'kvinna'),
(268, 10, 'make'),
(269, 10, 'hustru'),
(270, 10, 'han'),
(271, 10, 'farbror'),
(272, 10, 'morbror'),
(273, 10, 'tappa'),
(274, 10, 'förlora'),
(275, 10, 'vinna'),
(276, 10, 'den'),
(277, 10, 'det'),
(278, 10, '-en'),
(279, 10, '-et'),
(280, 10, '-na'),
(281, 10, 'katt'),
(282, 10, 'man'),
(283, 10, 'hon'),
(284, 10, 'bra'),
(285, 10, 'god'),
(286, 10, 'is'),
(287, 10, 'glass'),
(288, 10, 'jag'),
(289, 10, 'du'),
(290, 10, 'du'),
(291, 10, 'vi'),
(292, 10, 'vi'),
(293, 10, 'potatis'),
(294, 10, 'jord'),
(295, 10, 'mark'),
(296, 10, 'alla'),
(297, 10, 'allt'),
(298, 10, 'klappa'),
(299, 10, 'smeka'),
(300, 10, 'alla'),
(301, 10, 'allihopa'),
(302, 10, 'fest'),
(303, 10, 'kallas'),
(304, 10, 'bok'),
(305, 10, 'sten'),
(306, 10, 'sätt'),
(307, 10, 'viss'),
(308, 10, 'näsa'),
(309, 10, 'miss'),
(310, 10, 'madam'),
(311, 10, 'madam'),
(312, 10, 'miss'),
(313, 10, 'madam'),
(314, 10, 'miss'),
(315, 8,  'chleb'),
(316, 10, 'kul'),
(317, 10, 'rolig'),
(318, 10, 'fin'),
(319, 10, 'trevlig'),
(320, 10, 'snygg'),
(321, 10, 'vara'),
(323, 1,  'miss'),
(325, 1,  'be'),
(326, 1,  'laundry'),
(327, 1,  'wax'),
(328, 1,  'ugly'),
(329, 1,  'young'),
(330, 1,  'young'),
(331, 1,  'young'),
(332, 1,  'you'),
(333, 1,  'you guys'),
(334, 1,  ''),
(335, 1,  'they'),
(336, 1,  'wish'),
(337, 8,  'dom'),
(338, 1,  'house'),
(339, 1,  'without'),
(340, 10, 'testa'),
(341, 10, 'försöka'),
(342, 10, '');

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
  KEY `word_id` (`word_id`),
  KEY `language_id` (`language_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `translation_exceptions_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_exceptions` (`id`, `word_id`, `language_id`, `user_id`) VALUES
(2, 2,  8,  1),
(3, 16, 8,  1),
(4, 20, 8,  1),
(5, 22, 8,  1),
(6, 27, 8,  1),
(7, 19, 10, 1),
(8, 20, 10, 1);

DROP TABLE IF EXISTS `translation_words`;
CREATE TABLE `translation_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `translation_id` int(11) NOT NULL,
  `specification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`translation_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `translation_words_ibfk_1` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`),
  CONSTRAINT `translation_words_ibfk_2` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`)
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
(11,  14, 13, ''),
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
(54,  25, 39, ''),
(56,  25, 42, 'dated'),
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
(107, 59, 91, ''),
(108, 59, 92, ''),
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
(216, 8,  203,  ''),
(217, 8,  204,  ''),
(218, 10, 205,  ''),
(219, 16, 206,  ''),
(220, 20, 207,  ''),
(221, 20, 208,  'animal kingdom'),
(222, 20, 209,  'informal'),
(223, 24, 210,  ''),
(225, 5,  212,  ''),
(226, 7,  213,  'like a job'),
(227, 7,  214,  'function (like a machine)'),
(228, 7,  215,  'succeed'),
(229, 8,  216,  ''),
(230, 9,  217,  ''),
(231, 9,  218,  ''),
(232, 10, 219,  ''),
(233, 11, 220,  ''),
(235, 17, 222,  ''),
(237, 18, 224,  ''),
(238, 18, 225,  'vulgar'),
(239, 19, 226,  ''),
(241, 24, 228,  ''),
(242, 25, 229,  ''),
(243, 28, 230,  ''),
(244, 23, 231,  ''),
(245, 23, 232,  ''),
(246, 75, 233,  ''),
(247, 75, 234,  ''),
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
(279, 8,  266,  ''),
(280, 9,  267,  ''),
(281, 10, 268,  ''),
(282, 10, 269,  ''),
(283, 11, 270,  ''),
(286, 14, 273,  ''),
(287, 14, 274,  ''),
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
(350, 87, 339,  ''),
(351, 88, 340,  ''),
(352, 88, 341,  '');

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `native_hidden_entry` int(11) NOT NULL,
  `native_hidden_entry_short` int(11) NOT NULL,
  `inflect_classifications` int(11) NOT NULL,
  `inflect_not` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `types` (`id`, `name`, `short_name`, `native_hidden_entry`, `native_hidden_entry_short`, `inflect_classifications`, `inflect_not`) VALUES
(1, 'noun', 'n.', 0,  0,  0,  0),
(2, 'verb', 'v',  0,  0,  0,  0),
(3, 'name', 'nm.',  0,  0,  0,  0),
(4, 'pronoun',  'pn.',  0,  0,  0,  0),
(5, 'adjective',  'adj.', 0,  0,  1,  0),
(6, 'adverb', 'adv',  0,  0,  0,  1),
(7, 'conjunction',  'conj.',  0,  0,  0,  1),
(8, 'preposition',  'pp', 0,  0,  0,  1),
(9, 'postposition', 'posp', 0,  0,  0,  1),
(10,  'article',  'art.', 0,  0,  0,  0),
(11,  'determiner', 'det.', 0,  0,  0,  0),
(12,  'prefix', 'pre.', 0,  0,  0,  1),
(13,  'suffix', 'suf.', 0,  0,  0,  1),
(14,  'phrase', 'phr.', 0,  0,  0,  1);

DROP TABLE IF EXISTS `usage_notes`;
CREATE TABLE `usage_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word_id` (`word_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `usage_notes_ibfk_1` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`),
  CONSTRAINT `usage_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usage_notes` (`id`, `word_id`, `last_update`, `created_on`, `user_id`, `note`) VALUES
(1, 1,  '2017-02-15 23:41:20',  '2017-02-15 23:38:50',  1,  '* The normal plural is *mannen*. The unchanged form man is used after numerals only; it refers to the size of a group rather than a number of individuals. For example: In totaal verloren er 5000 man hun leven in die slag. (“5000 men altogether lost their lives in that battle.”)\r\n\r\n* Compound words with -man as their last component often take -lieden or -lui in the plural, rather than -mannen. For example: brandweerman ‎(“firefighter”) → brandweerlieden (alongside brandweerlui and brandweermannen).\r\n');

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
(1, 'Thomas de Roo',  'Thomas', 'd88aad0fd193b6ac9c03db2edddf9d1402956df84e709932dd2c4b70e5dc7f1b', 8,  '2017-02-18 17:20:23',  0),
(2, '', 'Charlie',  'd88aad0fd193b6ac9c03db2edddf9d1402956df84e709932dd2c4b70e5dc7f1b', 1,  '2017-02-12 17:41:56',  0),
(3, 'Donut User', 'donut',  'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', 1,  '2017-02-20 10:21:28',  0);

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL COMMENT '-1, 0, 1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `votes` (`id`, `user_id`, `table_name`, `table_id`, `value`) VALUES
(1, 1,  'discussions',  10, 1);

DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `native` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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

INSERT INTO `words` (`id`, `native`, `ipa`, `hidden`, `type_id`, `classification_id`, `subclassification_id`, `derivation_of`, `derivation_type`, `derivation_name`, `derivation_clonetranslations`, `derivation_show_in_title`, `created`, `updated`, `created_by`, `image`) VALUES
(1, 'man',  'mɑn',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-13 11:40:04',  1,  NULL),
(2, 'een',  '', 0,  10, 1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(5, 'hebben', '', 0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(7, 'werken', 'wərkm',  0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-18 14:17:52',  1,  NULL),
(8, 'juichen',  '', 0,  2,  5,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(9, 'vrouw',  'vrɑu̯',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(10,  'echtgenoot', 'ɛxtxəˌnot',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(11,  'hij',  '', 0,  4,  10, 2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(14,  'verliezen',  '', 0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(15,  'winnen', '', 0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(16,  'de', '', 0,  10, 1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(17,  'kat',  'kɑt',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  'kat.png'),
(18,  'poes', 'pus',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(19,  'katje',  'kɑtjə',  0,  1,  3,  0,  17, 0,  1,  1,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(20,  'mannetje', 'mɑn.ətjə', 0,  1,  3,  0,  1,  0,  1,  0,  1,  '2017-02-06 00:21:03',  '2017-02-15 23:57:43',  1,  NULL),
(22,  'men',  'mɛn',  0,  4,  3,  0,  1,  4,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(23,  'kater',  'kaːtə̣r',  0,  1,  1,  0,  17, 1,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(24,  'katertje', 'kaːtərtjə',  0,  1,  3,  0,  23, 0,  1,  1,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(25,  'meneer', 'məˈ neːr', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(27,  'meneertje',  '', 0,  1,  3,  0,  25, 0,  1,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(28,  'zij',  '', 0,  4,  11, 2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(29,  'goed', 'xut',  0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(31,  'ik', 'ik', 0,  4,  8,  2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(32,  'jij',  'jɛi̯/, (unstressed) /jə',  0,  4,  9,  2,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(33,  'je', '', 0,  4,  9,  2,  32, 4,  0,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(34,  'wij',  'ʋɛi̯', 0,  4,  8,  3,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(35,  'we', 'ʋə', 0,  4,  8,  3,  34, 4,  0,  0,  1,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(40,  'aardappel',  'ɑ:rdappəl',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(41,  'aarde',  'ɑ:rdə',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(42,  'alle', 'ɑl:ə', 0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(43,  'aaien',  '', 0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(44,  'allemaal', 'llem', 0,  4,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-18 00:49:18',  1,  NULL),
(51,  'feest',  '', 0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(56,  'boek', 'buk',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(57,  'steen',  'steː:n', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(58,  'wijze',  'wɛi̯zə', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(59,  'wijze',  'wɛi̯zə', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(60,  'neus', 'nøs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(61,  'maan', 'ma:n', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(62,  'maand',  'ma:nt',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(64,  'citroen',  'siˈtrun',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  'lemons.jpg'),
(66,  'hagelen',  'ha:xell:en', 0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(67,  'picknick', '', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(68,  'hond', 'hond', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(69,  'leuk', 'løːk', 0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(71,  'boom', 'bo:m', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(72,  'mooi', 'moːi̯',  0,  5,  13, 0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(73,  'slecht', 'slɛxt',  0,  5,  13, 4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(74,  'brood',  'bro:d',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(75,  'ui', 'œy̯',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(78,  'mevrouw',  'mevrouw',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(79,  'zijn', 'zɛi̯n',  0,  2,  6,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(80,  'was',  'ʋɑs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(81,  'was',  'ʋɑs',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(82,  'jong', 'jɔŋ',  0,  5,  1,  4,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(83,  'jullie', '', 0,  4,  9,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(84,  'zij',  '', 0,  4,  12, 0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(85,  'wensen', '', 0,  2,  5,  0,  0,  0,  0,  0,  0,  '2017-02-06 00:21:03',  '2017-02-12 17:39:50',  1,  NULL),
(86,  'huis', 'ɦœʏ̯s',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-02-12 17:53:05',  '2017-02-12 17:53:39',  NULL, NULL),
(87,  'zonder', 'zonder', 0,  8,  1,  0,  0,  0,  0,  0,  0,  '2017-02-12 23:54:33',  '2017-02-13 11:49:11',  NULL, NULL),
(88,  'testen', 'tɛst.m', 0,  2,  5,  1,  0,  0,  0,  0,  0,  '2017-02-18 14:19:50',  NULL, NULL, NULL);

-- 2017-02-20 10:21:58
