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


DROP TABLE IF EXISTS `classification_apply`;
CREATE TABLE `classification_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SETTING_NAME` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `SETTING_VALUE` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `derivations`;
CREATE TABLE `derivations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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


DROP TABLE IF EXISTS `graphemes_groups`;
CREATE TABLE `graphemes_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grapheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupstring` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grapheme` (`grapheme`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
(65,  'katten', '3_1_1',  6),
(66,  'de kat', '1_2_1',  6),
(67,  'de katten',  '3_2_1',  6),
(68,  'een katje',  '1_1_23', 6),
(69,  'katjes', '3_1_23', 6),
(70,  'het katje',  '1_2_23', 6),
(71,  'de katjes',  '3_2_23', 6),
(72,  'bootten',  '3_1_1',  9),
(73,  'de boot',  '1_2_1',  9),
(74,  'de bootten', '3_2_1',  9),
(75,  'een bootje', '1_1_23', 9),
(76,  'bootjes',  '3_1_23', 9),
(77,  'het bootje', '1_2_23', 9),
(78,  'de bootjes', '3_2_23', 9);

DROP TABLE IF EXISTS `modes`;
CREATE TABLE `modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `hidden_native_entry` int(11) NOT NULL,
  `mode_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


DROP TABLE IF EXISTS `mode_types`;
CREATE TABLE `mode_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


DROP TABLE IF EXISTS `phonology_contexts`;
CREATE TABLE `phonology_contexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


DROP TABLE IF EXISTS `subclassifications`;
CREATE TABLE `subclassifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `native_hidden_entry` int(11) NOT NULL,
  `native_hidden_entry_short` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


DROP TABLE IF EXISTS `submode_groups`;
CREATE TABLE `submode_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `inflect_classifications` int(11) NOT NULL,
  `inflect_not` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
  `editor_lang` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editor_lang` (`editor_lang`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`editor_lang`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


-- 2017-06-07 13:47:25
