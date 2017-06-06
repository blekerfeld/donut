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
(8, 10, 15, 50),
(9301,  17, 13, 100),
(9302,  18, 6,  100);

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
(3, 'SITE_TITLE', 'Dutch dictionary'),
(4, 'LOGO_TITLE', 'Dutch dictionary'),
(5, 'HOMEPAGE', 'home'),
(6, 'WIKI_ENABLE_HISTORY',  '1'),
(7, 'WIKI_HISTORY_ONLY_LOGGED', '0'),
(8, 'WIKI_ALLOW_GUEST_EDITING', '1'),
(9, 'WIKI_ALLOW_DISCUSSION_GUEST',  '0'),
(10,  'WIKI_ENABLE_DISCUSSION', '1'),
(11,  'HOME_TEXT',  '*Welcome to this dictionary*\r\n\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mauris turpis, feugiat non nulla vel, iaculis tincidunt erat. Aenean ac euismod mi. Nullam feugiat felis sed venenatis laoreet. Vestibulum sodales nisl vitae ex dignissim maximus. Nam hendrerit sed dolor et convallis. Phasellus nec ipsum eget eros porttitor accumsan. Duis pretium malesuada dui, vitae lobortis dolor faucibus sit amet. Donec interdum, turpis id pretium interdum, ante eros sagittis elit, vel aliquam elit est vel ex. Nullam nulla risus, fringilla ac posuere ut, convallis pretium magna. Fusce pellentesque quis erat vel dignissim. Curabitur in augue vel nisi laoreet placerat. Phasellus dapibus augue sed ex interdum, vulputate tristique nunc congue. Aenean efficitur sapien at libero tempor efficitur. Pellentesque facilisis posuere leo at elementum. Donec ac lectus nec lorem consequat dictum. Nulla facilisi. '),
(12,  'SITE_DESC',  ''),
(13,  'ACTIVE_LOCALE',  'English'),
(14,  'LOGO_SUB_TITLE', 'Dictionary of the Faulian Language');

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
(4, 'Een kat in de zak kopen.', 1,  '2017-04-17 10:12:53'),
(5, 'Ik heb daar wel een mannetje voor.', 0,  '2017-05-15 12:54:49'),
(6, 'de boot afhouden', 0,  '2017-05-31 18:26:42'),
(7, 'de boot is aan', 0,  '2017-05-31 18:26:50'),
(8, 'uit de boot vallen', 0,  '2017-05-31 18:26:59'),
(9, 'We varen in het weekend met onze boot.', 0,  '2017-05-31 18:27:25');

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
(4, 5,  17, 'mannetje'),
(5, 1,  17, 'man'),
(6, 1,  13, 'vrouw'),
(7, 3,  6,  'kat'),
(8, 4,  6,  'kat'),
(9, 6,  9,  'boot'),
(10,  7,  9,  'boot'),
(11,  9,  9,  'boot'),
(12,  8,  9,  'boot');

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
(8, 'Polish', 0,  'pl', 0,  'PL', ''),
(10,  'Swedish',  0,  'se', 1,  'SV', ''),
(11,  'Hungarian',  0,  'hu', 0,  'HU', '');

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
(23,  'diminutive', 'dim',  0,  2),
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
(17,  26, 5,  0),
(18,  23, 1,  0);

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
  `is_irregular` tinyint(4) NOT NULL,
  `is_stem` tinyint(4) NOT NULL,
  `irregular_form` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lemma_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `morphology` (`id`, `name`, `rule`, `is_irregular`, `is_stem`, `irregular_form`, `lemma_id`) VALUES
(19,  'Dimunitive suffix rule', '[]&ETJE?$m;&ETJE?$ng:;nkje?$ng;etje?$an;tje?!$m?!$n?!$an?$uin;tje?$VOW;je?&ELSE',  0,  0,  '', 0),
(20,  '-en meervoud', '[]&EN',  0,  0,  '', 0),
(21,  'het dimunitief', 'het []', 0,  0,  '', 0),
(22,  'de dimunitief',  'de []',  0,  0,  '', 0),
(23,  'een verkleinwoordje',  'een []', 0,  0,  '', 0),
(24,  'indef sing noun masc 17',  '', 1,  0,  'OVERRIDE!',  17);

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
(10,  19, 1),
(11,  20, 1),
(12,  21, 1),
(13,  22, 1),
(14,  23, 1),
(15,  24, 1);

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
(2, 19, 23),
(3, 20, 1),
(4, 20, 23),
(5, 21, 23),
(6, 22, 23),
(7, 23, 23),
(8, 24, 1);

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
(1, 20, 3),
(2, 21, 1),
(3, 22, 3),
(4, 23, 1),
(5, 24, 1);

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
(2, 19, 1),
(3, 19, 2),
(4, 20, 1),
(5, 20, 2),
(6, 21, 2),
(7, 22, 2),
(8, 23, 1),
(9, 24, 1);

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
(34,  'etje => pje if long vowel 2',  '#.VOW.#.CON.+_&ETJE_=>pje'),
(35,  'kofschip dental suffix t ',  '[x,k,f,s,c,h,p].+_&2DD_=>tt'),
(36,  'kofschip dental suffix d', '[^x,k,f,s,c,h,p].+_&2DD_=>dd');

DROP TABLE IF EXISTS `phonology_ipa_generation`;
CREATE TABLE `phonology_ipa_generation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phonology_ipa_generation` (`id`, `name`, `rule`) VALUES
(1, '_oo_', '_oo_=>oː');

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
(11,  1,  2,  0),
(18,  17, 12, 50),
(19,  17, 14, 25),
(20,  13, 12, 50),
(21,  9,  10, 75),
(22,  19, 9,  50);

DROP TABLE IF EXISTS `threads`;
CREATE TABLE `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linked_to` int(11) NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thread_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `threads` (`id`, `linked_to`, `section`, `thread_id`, `content`, `user_id`, `post_date`, `update_date`) VALUES
(1, 17, 'lemma',  0,  'Eerste post',  1,  '2017-06-05 15:24:00',  '2017-06-05 13:24:00'),
(2, 17, 'lemma',  1,  'Eerste reactie', 1,  '2017-06-05 15:30:02',  '2017-06-05 13:30:02'),
(3, 17, 'lemma',  2,  'Level 2',  1,  '2017-06-05 16:24:22',  '2017-06-05 14:24:22');

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
(374, 1,  'house',  '', '2017-05-25 16:57:51',  0),
(375, 1,  'bedroom',  'a place to sleep', '2017-05-25 17:06:55',  1),
(376, 10, 'sovrum', '', '2017-05-25 17:26:35',  0),
(378, 1,  'place to sleep', '', '2017-05-25 18:48:31',  1),
(379, 1,  'drool',  '', '2017-05-25 20:01:27',  1),
(380, 10, 'dregla', '', '2017-05-25 20:01:27',  1),
(381, 1,  'save', '', '2017-05-25 20:04:31',  1),
(382, 10, 'fräsla', '', '2017-05-25 20:04:31',  1),
(383, 1,  'get rid of', '', '2017-05-25 20:11:14',  1),
(384, 10, 'lösa', '', '2017-05-25 20:11:14',  1),
(385, 10, 'bli av med', '', '2017-05-25 20:11:14',  1),
(386, 1,  'cat',  '', '2017-05-25 20:33:29',  1),
(387, 10, 'katt', '', '2017-05-25 20:33:29',  1),
(388, 1,  'dog',  '', '2017-05-25 20:38:01',  1),
(389, 1,  'hound',  '', '2017-05-25 20:38:01',  1),
(390, 10, 'hund', '', '2017-05-25 20:38:01',  1),
(391, 10, 'vove', '', '2017-05-25 20:38:01',  1),
(392, 1,  'land', '', '2017-05-25 20:40:56',  1),
(393, 1,  'country',  '', '2017-05-25 20:40:56',  1),
(394, 10, 'land', '', '2017-05-25 20:40:56',  1),
(400, 1,  'husband',  '', '2017-05-25 21:24:57',  1),
(401, 10, 'make', '', '2017-05-25 21:24:57',  1),
(406, 1,  'child',  '', '2017-05-26 08:53:32',  1),
(407, 1,  'kid',  '', '2017-05-26 08:53:32',  1),
(408, 1,  'youngster',  '', '2017-05-26 08:53:32',  1),
(409, 10, 'barn', '', '2017-05-26 08:53:32',  1),
(410, 10, 'unge', '', '2017-05-26 08:53:32',  1),
(411, 1,  'horse',  '', '2017-05-26 10:49:16',  1),
(412, 10, 'häst', '', '2017-05-26 10:49:16',  1),
(413, 1,  'donkey', '', '2017-05-26 13:37:38',  1),
(424, 1,  'woman',  '', '2017-05-31 09:08:27',  1),
(425, 1,  'wife', '', '2017-05-31 09:08:27',  1),
(426, 10, 'kvinna', '', '2017-05-31 09:08:27',  1),
(427, 10, 'hustru', '', '2017-05-31 09:08:27',  1),
(428, 1,  'man',  '', '2017-05-31 12:45:55',  1),
(429, 10, 'man',  '', '2017-05-31 12:45:55',  1),
(434, 1,  'ship', '', '2017-05-31 18:30:28',  1),
(435, 1,  'boat', '', '2017-05-31 18:30:28',  1),
(436, 10, 'skep', '', '2017-05-31 18:30:28',  1),
(437, 10, 'båt',  '', '2017-05-31 18:30:28',  1),
(438, 1,  'dwelling', '', '2017-06-01 12:44:09',  1),
(439, 1,  'appartment', '', '2017-06-01 12:44:09',  1),
(440, 10, 'hus',  '', '2017-06-01 12:44:09',  1),
(441, 1,  'sail', '', '2017-06-05 15:51:18',  3),
(442, 10, 'segla',  '', '2017-06-05 15:51:18',  3);

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
  KEY `language_id` (`language_id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`),
  CONSTRAINT `translation_exceptions_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `translation_exceptions_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE
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
  CONSTRAINT `translation_words_ibfk_3` FOREIGN KEY (`translation_id`) REFERENCES `translations` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `translation_words_ibfk_4` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `translation_words` (`id`, `word_id`, `translation_id`, `specification`) VALUES
(1, 1,  374,  ''),
(4, 2,  375,  ''),
(5, 2,  376,  ''),
(6, 2,  378,  ''),
(19,  3,  381,  'religious'),
(20,  3,  382,  'religious'),
(25,  3,  383,  ''),
(26,  3,  384,  ''),
(27,  3,  385,  ''),
(28,  6,  386,  ''),
(29,  6,  387,  ''),
(34,  8,  392,  ''),
(35,  8,  393,  ''),
(36,  8,  394,  ''),
(51,  12, 400,  ''),
(52,  12, 401,  ''),
(55,  14, 406,  ''),
(56,  14, 407,  ''),
(57,  14, 408,  ''),
(58,  14, 409,  ''),
(59,  14, 410,  ''),
(60,  15, 411,  ''),
(61,  15, 412,  ''),
(62,  16, 413,  ''),
(81,  13, 424,  'female human being'),
(82,  13, 425,  ''),
(83,  13, 426,  ''),
(84,  13, 427,  ''),
(85,  18, 388,  ''),
(86,  18, 389,  ''),
(87,  18, 390,  ''),
(88,  17, 428,  ''),
(89,  17, 400,  ''),
(90,  17, 429,  ''),
(91,  17, 401,  ''),
(96,  10, 434,  ''),
(97,  10, 435,  ''),
(98,  10, 436,  ''),
(99,  10, 437,  ''),
(100, 9,  435,  ''),
(101, 9,  434,  ''),
(102, 9,  437,  ''),
(103, 9,  436,  ''),
(104, 1,  438,  ''),
(105, 1,  439,  ''),
(106, 1,  440,  ''),
(107, 19, 441,  ''),
(108, 19, 442,  '');

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
(2, 17, '2017-05-31 09:33:51',  '2017-05-31 09:33:51',  1,  'testtets'),
(3, 13, '2017-05-31 12:29:50',  '2017-05-31 12:29:50',  1,  ''),
(4, 12, '2017-05-31 12:32:37',  '2017-05-31 12:32:37',  1,  ''),
(5, 12, '2017-05-31 12:38:20',  '2017-05-31 12:38:20',  1,  ''),
(6, 6,  '2017-05-31 12:41:47',  '2017-05-31 12:41:47',  1,  ''),
(7, 6,  '2017-05-31 12:42:16',  '2017-05-31 12:42:16',  1,  ''),
(8, 6,  '2017-05-31 12:42:37',  '2017-05-31 12:42:37',  1,  ''),
(9, 17, '2017-05-31 12:45:55',  '2017-05-31 12:45:55',  1,  ''),
(10,  9,  '2017-05-31 18:28:14',  '2017-05-31 18:28:14',  1,  ''),
(11,  9,  '2017-05-31 18:28:45',  '2017-05-31 18:28:45',  1,  ''),
(12,  10, '2017-05-31 18:29:55',  '2017-05-31 18:29:55',  1,  ''),
(13,  10, '2017-05-31 18:30:28',  '2017-05-31 18:30:28',  1,  ''),
(14,  9,  '2017-05-31 18:30:59',  '2017-05-31 18:30:59',  1,  ''),
(15,  1,  '2017-06-01 12:44:09',  '2017-06-01 12:44:09',  1,  ''),
(16,  17, '2017-06-03 12:13:05',  '2017-06-03 12:13:05',  1,  ''),
(17,  19, '2017-06-05 15:51:18',  '2017-06-05 15:51:18',  3,  '');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `editor_lang` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editor_lang` (`editor_lang`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`editor_lang`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `longname`, `username`, `password`, `editor_lang`, `reg_date`, `role`, `avatar`) VALUES
(0, 'Guest',  'guest',  '', 0,  '2017-03-30 22:54:37',  4,  ''),
(1, 'Thomas de Roo',  'blekerfeld', '70674e943bcd2ce395ff619cff93c980f1cec914445cd69a30d612c7988e9966', 8,  '2017-06-05 01:07:32',  0,  'https://avatars3.githubusercontent.com/u/13293128?v=3&s=460'),
(2, '', 'Charlie',  'd88aad0fd193b6ac9c03db2edddf9d1402956df84e709932dd2c4b70e5dc7f1b', 1,  '2017-02-12 17:41:56',  0,  ''),
(3, 'Mr. Donut',  'donut',  'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', 1,  '2017-03-30 22:46:27',  0,  ''),
(4, 'John Sprinkle',  'sprinkle', 'e69fd784f93f82eb6bf5148f0a0e3f5282df5ac10427ab3d6704799adca95a07', 1,  '2017-04-03 19:27:40',  3,  '');

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
(1, 'huis', '', 'hœy;s',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-06-01 12:44:09',  '2017-06-01 12:44:09',  1,  NULL),
(2, 'slaapkamer', '', 'sla:pka:mər',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-05-25 19:41:38',  '2017-05-25 19:41:38',  1,  NULL),
(3, 'verlossen',  '', 'vər.loss.n', 0,  2,  5,  0,  0,  0,  0,  0,  0,  '2017-05-25 20:21:33',  '2017-05-25 20:21:33',  1,  NULL),
(6, 'kat',  '', 'kɑ:t', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-05-31 12:42:37',  '2017-05-31 12:42:37',  1,  NULL),
(8, 'land', '', 'lɑnt', 0,  1,  3,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-05-25 20:40:56',  1,  NULL),
(9, 'boot', '', 'bo:t', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-05-31 18:30:59',  '2017-05-31 18:30:59',  1,  NULL),
(10,  'schip',  '', 'sxɪp', 0,  1,  3,  0,  0,  0,  0,  0,  0,  '2017-05-31 18:30:28',  '2017-05-31 18:30:28',  1,  NULL),
(12,  'echtgenoot', '', 'extxɛno:t',  0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-05-31 12:38:20',  '2017-05-31 12:38:20',  1,  NULL),
(13,  'vrouw',  '', 'vrau', 0,  1,  2,  0,  0,  0,  0,  0,  0,  '2017-05-31 12:29:50',  '2017-05-31 12:29:50',  1,  NULL),
(14,  'kind', '', 'kɪnt', 0,  1,  3,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-05-26 08:53:32',  1,  NULL),
(15,  'paard',  '', 'pa:rt',  0,  1,  3,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-05-26 10:49:16',  1,  NULL),
(16,  'ezel', '', 'e:zel',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-05-26 13:37:38',  1,  NULL),
(17,  'man',  '', 'man',  0,  1,  1,  0,  0,  0,  0,  0,  0,  '2017-06-03 12:13:05',  '2017-06-03 12:13:05',  1,  NULL),
(18,  'hond', '', 'hont', 0,  1,  1,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-05-31 09:14:03',  1,  NULL),
(19,  'zeilen', '', 'zeilen', 0,  2,  5,  0,  0,  0,  0,  0,  0,  '0000-00-00 00:00:00',  '2017-06-05 15:51:17',  3,  NULL);

-- 2017-06-06 09:17:28
