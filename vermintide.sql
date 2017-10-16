-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Okt 2017 um 18:27
-- Server-Version: 10.1.13-MariaDB
-- PHP-Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `vermintide`
--
CREATE DATABASE IF NOT EXISTS `vermintide` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `vermintide`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_difficulty`
--

CREATE TABLE `tbl_difficulty` (
  `id_difficulty` int(11) NOT NULL,
  `dif_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_difficulty`
--

INSERT INTO `tbl_difficulty` (`id_difficulty`, `dif_name`) VALUES
(1, 'Easy'),
(2, 'Medium'),
(3, 'Hard'),
(4, 'Nightmare'),
(5, 'Cataclysm');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_dlc`
--

CREATE TABLE `tbl_dlc` (
  `id_dlc` int(11) NOT NULL,
  `dlc_name` varchar(100) NOT NULL,
  `dlc_maps` int(11) NOT NULL,
  `dlc_release_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_dlc`
--

INSERT INTO `tbl_dlc` (`id_dlc`, `dlc_name`, `dlc_maps`, `dlc_release_date`) VALUES
(1, 'Vermintide', 13, '2015-10-23'),
(2, 'Drachenfels', 3, '2016-05-26'),
(3, 'Karak Azgaraz', 3, '2016-12-15'),
(4, 'Stromdorf', 2, '2017-05-04'),
(5, 'Death on the Reik', 2, '2017-10-17');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_map`
--

CREATE TABLE `tbl_map` (
  `id_map` int(11) NOT NULL,
  `map_dlc_id` int(11) NOT NULL,
  `map_name` varchar(100) NOT NULL,
  `map_name_intern` varchar(100) NOT NULL,
  `map_grimoires` int(11) NOT NULL,
  `map_tomes` int(11) NOT NULL,
  `map_chests` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_map`
--

INSERT INTO `tbl_map` (`id_map`, `map_dlc_id`, `map_name`, `map_name_intern`, `map_grimoires`, `map_tomes`, `map_chests`) VALUES
(1, 1, 'Horn of Magnus', 'magnus', 2, 3, 26),
(2, 1, 'Supply and Demand', 'merchant', 2, 3, 23),
(3, 1, 'Smuggler''s Run', 'sewers_short', 0, 0, 5),
(4, 1, 'Wizard''s Tower', 'wizard', 2, 3, 21),
(5, 1, 'Black Powder', 'bridge', 0, 0, 15),
(6, 1, 'Engines of War', 'forest_ambush', 2, 3, 5),
(7, 1, 'Man the Ramparts', 'city_wall', 0, 0, 5),
(8, 1, 'Garden of Morr', 'cemetery', 2, 3, 6),
(9, 1, 'Wheat and Chaff', 'farm', 0, 0, 7),
(10, 1, 'Enemy Below', 'tunnels', 2, 3, 8),
(11, 1, 'Well Watch', 'courtyard_level', 0, 0, 3),
(12, 1, 'Waterfront', 'docks_short_level', 0, 0, 16),
(13, 1, 'The White Rat', 'end_boss', 1, 0, 9),
(14, 2, 'Castle Drachenfels', 'dlc_castle', 2, 3, 8),
(15, 2, 'The Dungeons', 'dlc_castle_dungeon', 2, 2, 17),
(16, 2, 'Summoner''s Peak', 'dlc_portals', 1, 1, 7),
(17, 3, 'Khazid Kro', 'dlc_dwarf_interior', 2, 3, 6),
(18, 3, 'The Cursed Rune', 'dlc_dwarf_exterior', 2, 3, 8),
(19, 3, 'Chain of Fire', 'dlc_dwarf_beacons', 1, 2, 2),
(20, 4, 'The Courier', 'dlc_stromdorf_hills', 1, 2, 10),
(21, 4, 'Reaching Out', 'dlc_stromdorf_town', 1, 3, 17);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_mod`
--

CREATE TABLE `tbl_mod` (
  `id_mod` int(11) NOT NULL,
  `mod_name` varchar(50) NOT NULL,
  `mod_description` varchar(200) NOT NULL,
  `mod_extra_dice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_mod`
--

INSERT INTO `tbl_mod` (`id_mod`, `mod_name`, `mod_description`, `mod_extra_dice`) VALUES
(1, 'deathwish', 'Deathwish Difficulty', 2),
(2, 'onslaught', 'Onslaught Mode', 1),
(3, 'stormmutation', 'Stormvermin Mutation', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_parameter`
--

CREATE TABLE `tbl_parameter` (
  `id_parameter` int(11) NOT NULL,
  `par_name` varchar(100) NOT NULL,
  `par_value` varchar(100) NOT NULL,
  `par_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_parameter`
--

INSERT INTO `tbl_parameter` (`id_parameter`, `par_name`, `par_value`, `par_description`) VALUES
(1, 'cata_red_prob_on_6', '25', 'Chance to get a red item with a 6 on Cataclysm');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_probability`
--

CREATE TABLE `tbl_probability` (
  `id_probability` int(11) NOT NULL,
  `pro_grimoire_dice` int(11) NOT NULL,
  `pro_tome_dice` int(11) NOT NULL,
  `pro_extra_dice` int(11) NOT NULL,
  `pro_normal_dice` int(11) NOT NULL,
  `pro_dice_string` varchar(8) NOT NULL,
  `pro_probability_zero` double(13,11) NOT NULL,
  `pro_probability_one` double(13,11) NOT NULL,
  `pro_probability_two` double(13,11) NOT NULL,
  `pro_probability_three` double(13,11) NOT NULL,
  `pro_probability_four` double(13,11) NOT NULL,
  `pro_probability_five` double(13,11) NOT NULL,
  `pro_probability_six` double(13,11) NOT NULL,
  `pro_probability_seven` double(13,11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_probability`
--

INSERT INTO `tbl_probability` (`id_probability`, `pro_grimoire_dice`, `pro_tome_dice`, `pro_extra_dice`, `pro_normal_dice`, `pro_dice_string`, `pro_probability_zero`, `pro_probability_one`, `pro_probability_two`, `pro_probability_three`, `pro_probability_four`, `pro_probability_five`, `pro_probability_six`, `pro_probability_seven`) VALUES
(1, 0, 0, 0, 7, '0g0t0e7n', 5.85276634700, 20.48468221000, 30.72702332000, 25.60585277000, 12.80292638000, 3.84087791500, 0.64014631900, 0.04572473700),
(2, 0, 0, 1, 6, '0g0t1e6n', 4.38957476000, 17.55829904000, 29.62962963000, 27.43484225000, 15.08916324000, 4.93827160500, 0.89163237300, 0.06858710600),
(3, 0, 0, 2, 5, '0g0t2e5n', 3.29218107000, 14.81481481000, 27.98353909000, 28.80658436000, 17.48971193000, 6.27572016500, 1.23456790100, 0.10288065800),
(4, 0, 1, 0, 6, '0g1t0e6n', 2.92638317300, 14.63191587000, 28.53223594000, 29.26383173000, 17.37540009000, 6.03566529500, 1.14311842700, 0.09144947400),
(5, 0, 1, 1, 5, '0g1t1e5n', 2.19478738000, 12.07133059000, 26.33744856000, 30.17832647000, 19.89026063000, 7.61316872400, 1.57750342900, 0.13717421100),
(6, 0, 1, 2, 4, '0g1t2e4n', 1.64609053500, 9.87654321000, 23.86831276000, 30.45267490000, 22.32510288000, 9.46502057600, 2.16049382700, 0.20576131700),
(7, 0, 2, 0, 5, '0g2t0e5n', 1.46319158700, 9.51074531300, 24.14266118000, 31.09282122000, 22.40512117000, 9.19067215400, 2.01188843200, 0.18289894800),
(8, 0, 2, 1, 4, '0g2t1e4n', 1.09739369000, 7.68175583000, 21.39917695000, 30.72702332000, 24.75994513000, 11.31687243000, 2.74348422500, 0.27434842200),
(9, 0, 2, 2, 3, '0g2t2e3n', 0.82304526700, 6.17283950600, 18.72427984000, 29.73251029000, 26.74897119000, 13.68312757000, 3.70370370400, 0.41152263400),
(10, 0, 3, 0, 4, '0g3t0e4n', 0.73159579300, 5.85276634700, 18.65569273000, 30.36122542000, 27.11476909000, 13.44307270000, 3.47508001800, 0.36579789700),
(11, 0, 3, 1, 3, '0g3t1e3n', 0.54869684500, 4.66392318200, 16.04938272000, 28.73799726000, 28.73799726000, 16.04938272000, 4.66392318200, 0.54869684500),
(12, 0, 3, 2, 2, '0g3t2e2n', 0.41152263400, 3.70370370400, 13.68312757000, 26.74897119000, 29.73251029000, 18.72427984000, 6.17283950600, 0.82304526700),
(13, 1, 0, 0, 6, '1g0t0e6n', 0.00000000000, 8.77914952000, 26.33744856000, 32.92181070000, 21.94787380000, 8.23045267500, 1.64609053500, 0.13717421100),
(14, 1, 0, 1, 5, '1g0t1e5n', 0.00000000000, 6.58436214000, 23.04526749000, 32.92181070000, 24.69135802000, 10.28806584000, 2.26337448600, 0.20576131700),
(15, 1, 0, 2, 4, '1g0t2e4n', 0.00000000000, 4.93827160500, 19.75308642000, 32.09876543000, 27.16049383000, 12.65432099000, 3.08641975300, 0.30864197500),
(16, 1, 1, 0, 5, '1g1t0e5n', 0.00000000000, 4.38957476000, 19.75308642000, 32.92181070000, 27.43484225000, 12.34567901000, 2.88065843600, 0.27434842200),
(17, 1, 1, 1, 4, '1g1t1e4n', 0.00000000000, 3.29218107000, 16.46090535000, 31.27572016000, 29.62962963000, 15.02057613000, 3.90946502100, 0.41152263400),
(18, 1, 1, 2, 3, '1g1t2e3n', 0.00000000000, 2.46913580200, 13.58024691000, 29.01234568000, 31.17283951000, 17.90123457000, 5.24691358000, 0.61728395100),
(19, 1, 2, 0, 4, '1g2t0e4n', 0.00000000000, 2.19478738000, 13.16872428000, 29.62962963000, 31.82441701000, 17.69547325000, 4.93827160500, 0.54869684500),
(20, 1, 2, 1, 3, '1g2t1e3n', 0.00000000000, 1.64609053500, 10.69958848000, 26.74897119000, 32.71604938000, 20.78189300000, 6.58436214000, 0.82304526700),
(21, 1, 2, 2, 2, '1g2t2e2n', 0.00000000000, 1.23456790100, 8.64197530900, 23.76543210000, 32.71604938000, 23.76543210000, 8.64197530900, 1.23456790100),
(22, 1, 3, 0, 3, '1g3t0e3n', 0.00000000000, 1.09739369000, 8.23045267500, 23.86831276000, 33.60768176000, 23.86831276000, 8.23045267500, 1.09739369000),
(23, 1, 3, 1, 2, '1g3t1e2n', 0.00000000000, 0.82304526700, 6.58436214000, 20.78189300000, 32.71604938000, 26.74897119000, 10.69958848000, 1.64609053500),
(24, 1, 3, 2, 1, '1g3t2e1n', 0.00000000000, 0.61728395100, 5.24691358000, 17.90123457000, 31.17283951000, 29.01234568000, 13.58024691000, 2.46913580200),
(25, 2, 0, 0, 5, '2g0t0e5n', 0.00000000000, 0.00000000000, 13.16872428000, 32.92181070000, 32.92181070000, 16.46090535000, 4.11522633700, 0.41152263400),
(26, 2, 0, 1, 4, '2g0t1e4n', 0.00000000000, 0.00000000000, 9.87654321000, 29.62962963000, 34.56790123000, 19.75308642000, 5.55555555600, 0.61728395100),
(27, 2, 0, 2, 3, '2g0t2e3n', 0.00000000000, 0.00000000000, 7.40740740700, 25.92592593000, 35.18518519000, 23.14814815000, 7.40740740700, 0.92592592600),
(28, 2, 1, 0, 4, '2g1t0e4n', 0.00000000000, 0.00000000000, 6.58436214000, 26.33744856000, 36.21399177000, 23.04526749000, 6.99588477400, 0.82304526700),
(29, 2, 1, 1, 3, '2g1t1e3n', 0.00000000000, 0.00000000000, 4.93827160500, 22.22222222000, 35.80246914000, 26.54320988000, 9.25925925900, 1.23456790100),
(30, 2, 1, 2, 2, '2g1t2e2n', 0.00000000000, 0.00000000000, 3.70370370400, 18.51851852000, 34.25925926000, 29.62962963000, 12.03703704000, 1.85185185200),
(31, 2, 2, 0, 3, '2g2t0e3n', 0.00000000000, 0.00000000000, 3.29218107000, 18.10699588000, 35.39094650000, 30.04115226000, 11.52263374000, 1.64609053500),
(32, 2, 2, 1, 2, '2g2t1e2n', 0.00000000000, 0.00000000000, 2.46913580200, 14.81481481000, 32.71604938000, 32.71604938000, 14.81481481000, 2.46913580200),
(33, 2, 2, 2, 1, '2g2t2e1n', 0.00000000000, 0.00000000000, 1.85185185200, 12.03703704000, 29.62962963000, 34.25925926000, 18.51851852000, 3.70370370400),
(34, 2, 3, 0, 2, '2g3t0e2n', 0.00000000000, 0.00000000000, 1.64609053500, 11.52263374000, 30.04115226000, 35.39094650000, 18.10699588000, 3.29218107000),
(35, 2, 3, 1, 1, '2g3t1e1n', 0.00000000000, 0.00000000000, 1.23456790100, 9.25925925900, 26.54320988000, 35.80246914000, 22.22222222000, 4.93827160500),
(36, 2, 3, 2, 0, '2g3t2e0n', 0.00000000000, 0.00000000000, 0.92592592600, 7.40740740700, 23.14814815000, 35.18518519000, 25.92592593000, 7.40740740700),
(37, 3, 0, 0, 4, '3g0t0e4n', 0.00000000000, 0.00000000000, 0.00000000000, 19.75308642000, 39.50617284000, 29.62962963000, 9.87654321000, 1.23456790100),
(38, 3, 0, 1, 3, '3g0t1e3n', 0.00000000000, 0.00000000000, 0.00000000000, 14.81481481000, 37.03703704000, 33.33333333000, 12.96296296000, 1.85185185200),
(39, 3, 0, 2, 2, '3g0t2e2n', 0.00000000000, 0.00000000000, 0.00000000000, 11.11111111000, 33.33333333000, 36.11111111000, 16.66666667000, 2.77777777800),
(40, 3, 1, 0, 3, '3g1t0e3n', 0.00000000000, 0.00000000000, 0.00000000000, 9.87654321000, 34.56790123000, 37.03703704000, 16.04938272000, 2.46913580200),
(41, 3, 1, 1, 2, '3g1t1e2n', 0.00000000000, 0.00000000000, 0.00000000000, 7.40740740700, 29.62962963000, 38.88888889000, 20.37037037000, 3.70370370400),
(42, 3, 1, 2, 1, '3g1t2e1n', 0.00000000000, 0.00000000000, 0.00000000000, 5.55555555600, 25.00000000000, 38.88888889000, 25.00000000000, 5.55555555600),
(43, 3, 2, 0, 2, '3g2t0e2n', 0.00000000000, 0.00000000000, 0.00000000000, 4.93827160500, 24.69135802000, 40.74074074000, 24.69135802000, 4.93827160500),
(44, 3, 2, 1, 1, '3g2t1e1n', 0.00000000000, 0.00000000000, 0.00000000000, 3.70370370400, 20.37037037000, 38.88888889000, 29.62962963000, 7.40740740700),
(45, 3, 2, 2, 0, '3g2t2e0n', 0.00000000000, 0.00000000000, 0.00000000000, 2.77777777800, 16.66666667000, 36.11111111000, 33.33333333000, 11.11111111000),
(46, 3, 3, 0, 1, '3g3t0e1n', 0.00000000000, 0.00000000000, 0.00000000000, 2.46913580200, 16.04938272000, 37.03703704000, 34.56790123000, 9.87654321000),
(47, 3, 3, 1, 0, '3g3t1e0n', 0.00000000000, 0.00000000000, 0.00000000000, 1.85185185200, 12.96296296000, 33.33333333000, 37.03703704000, 14.81481481000),
(48, 4, 0, 0, 3, '4g0t0e3n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 29.62962963000, 44.44444444000, 22.22222222000, 3.70370370400),
(49, 4, 0, 1, 2, '4g0t1e2n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 22.22222222000, 44.44444444000, 27.77777778000, 5.55555555600),
(50, 4, 0, 2, 1, '4g0t2e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 16.66666667000, 41.66666667000, 33.33333333000, 8.33333333300),
(51, 4, 1, 0, 2, '4g1t0e2n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 14.81481481000, 44.44444444000, 33.33333333000, 7.40740740700),
(52, 4, 1, 1, 1, '4g1t1e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 11.11111111000, 38.88888889000, 38.88888889000, 11.11111111000),
(53, 4, 1, 2, 0, '4g1t2e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 8.33333333300, 33.33333333000, 41.66666667000, 16.66666667000),
(54, 4, 2, 0, 1, '4g2t0e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 7.40740740700, 33.33333333000, 44.44444444000, 14.81481481000),
(55, 4, 2, 1, 0, '4g2t1e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 5.55555555600, 27.77777778000, 44.44444444000, 22.22222222000),
(56, 4, 3, 0, 0, '4g3t0e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 3.70370370400, 22.22222222000, 44.44444444000, 29.62962963000),
(57, 5, 0, 0, 2, '5g0t0e2n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 44.44444444000, 44.44444444000, 11.11111111000),
(58, 5, 0, 1, 1, '5g0t1e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 33.33333333000, 50.00000000000, 16.66666667000),
(59, 5, 0, 2, 0, '5g0t2e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 25.00000000000, 50.00000000000, 25.00000000000),
(60, 5, 1, 0, 1, '5g1t0e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 22.22222222000, 55.55555556000, 22.22222222000),
(61, 5, 1, 1, 0, '5g1t1e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 16.66666667000, 50.00000000000, 33.33333333000),
(62, 5, 2, 0, 0, '5g2t0e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 11.11111111000, 44.44444444000, 44.44444444000),
(63, 6, 0, 0, 1, '6g0t0e1n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 66.66666666670, 33.33333333330),
(64, 6, 0, 1, 0, '6g0t1e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 50.00000000000, 50.00000000000),
(65, 6, 1, 0, 0, '6g1t0e0n', 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 0.00000000000, 33.33333333330, 66.66666666670);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_run`
--

CREATE TABLE `tbl_run` (
  `id_run` int(11) NOT NULL,
  `run_map_id` int(11) NOT NULL,
  `run_difficulty_id` int(11) NOT NULL,
  `run_probability_id` int(11) NOT NULL,
  `run_duration` int(11) DEFAULT NULL,
  `run_probability_red` double(4,2) NOT NULL DEFAULT '0.00',
  `run_xRed` int(11) NOT NULL DEFAULT '0',
  `run_notes` text,
  `run_createDtTi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_run`
--

INSERT INTO `tbl_run` (`id_run`, `run_map_id`, `run_difficulty_id`, `run_probability_id`, `run_duration`, `run_probability_red`, `run_xRed`, `run_notes`, `run_createDtTi`) VALUES
(1, 1, 5, 36, 21, 0.00, 0, '', '2017-10-12 12:13:59');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_run_mod`
--

CREATE TABLE `tbl_run_mod` (
  `id_run_mod` int(11) NOT NULL,
  `rm_run_id` int(11) NOT NULL,
  `rm_mod_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_run_mod`
--

INSERT INTO `tbl_run_mod` (`id_run_mod`, `rm_run_id`, `rm_mod_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `vw_run`
--
CREATE TABLE `vw_run` (
);

-- --------------------------------------------------------

--
-- Struktur des Views `vw_run`
--
DROP TABLE IF EXISTS `vw_run`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_run`  AS  select `run`.`id_run` AS `id_run`,`dif`.`dif_name` AS `dif_name`,`map`.`map_name` AS `map_name`,`dlc`.`dlc_name` AS `dlc_name`,`run`.`run_length` AS `run_length`,`pro`.`pro_dice_string` AS `pro_dice_string`,`run`.`run_probability_red` AS `run_probability_red`,`run`.`run_createDtTi` AS `run_createDtTi` from ((((`tbl_run` `run` join `tbl_map` `map`) join `tbl_dlc` `dlc`) join `tbl_difficulty` `dif`) join `tbl_probability` `pro`) where ((`run`.`run_map_id` = `map`.`id_map`) and (`map`.`map_dlc_id` = `dlc`.`id_dlc`) and (`run`.`run_difficulty_id` = `dif`.`id_difficulty`) and (`run`.`run_probability_id` = `pro`.`id_probability`)) ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tbl_difficulty`
--
ALTER TABLE `tbl_difficulty`
  ADD PRIMARY KEY (`id_difficulty`);

--
-- Indizes für die Tabelle `tbl_dlc`
--
ALTER TABLE `tbl_dlc`
  ADD PRIMARY KEY (`id_dlc`);

--
-- Indizes für die Tabelle `tbl_map`
--
ALTER TABLE `tbl_map`
  ADD PRIMARY KEY (`id_map`),
  ADD KEY `map_dlc_id` (`map_dlc_id`) USING BTREE;

--
-- Indizes für die Tabelle `tbl_mod`
--
ALTER TABLE `tbl_mod`
  ADD PRIMARY KEY (`id_mod`);

--
-- Indizes für die Tabelle `tbl_parameter`
--
ALTER TABLE `tbl_parameter`
  ADD PRIMARY KEY (`id_parameter`);

--
-- Indizes für die Tabelle `tbl_probability`
--
ALTER TABLE `tbl_probability`
  ADD PRIMARY KEY (`id_probability`);

--
-- Indizes für die Tabelle `tbl_run`
--
ALTER TABLE `tbl_run`
  ADD PRIMARY KEY (`id_run`),
  ADD KEY `run_probability_id` (`run_probability_id`) USING BTREE,
  ADD KEY `run_map_id` (`run_map_id`) USING BTREE,
  ADD KEY `run_difficulty_id` (`run_difficulty_id`) USING BTREE;

--
-- Indizes für die Tabelle `tbl_run_mod`
--
ALTER TABLE `tbl_run_mod`
  ADD PRIMARY KEY (`id_run_mod`),
  ADD UNIQUE KEY `run_mod_unique` (`rm_run_id`,`rm_mod_id`),
  ADD KEY `rm_run_id` (`rm_run_id`),
  ADD KEY `rm_mod_id` (`rm_mod_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tbl_difficulty`
--
ALTER TABLE `tbl_difficulty`
  MODIFY `id_difficulty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `tbl_dlc`
--
ALTER TABLE `tbl_dlc`
  MODIFY `id_dlc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `tbl_map`
--
ALTER TABLE `tbl_map`
  MODIFY `id_map` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT für Tabelle `tbl_mod`
--
ALTER TABLE `tbl_mod`
  MODIFY `id_mod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `tbl_parameter`
--
ALTER TABLE `tbl_parameter`
  MODIFY `id_parameter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `tbl_probability`
--
ALTER TABLE `tbl_probability`
  MODIFY `id_probability` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT für Tabelle `tbl_run`
--
ALTER TABLE `tbl_run`
  MODIFY `id_run` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `tbl_run_mod`
--
ALTER TABLE `tbl_run_mod`
  MODIFY `id_run_mod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tbl_map`
--
ALTER TABLE `tbl_map`
  ADD CONSTRAINT `tbl_map_ibfk_1` FOREIGN KEY (`map_dlc_id`) REFERENCES `tbl_dlc` (`id_dlc`);

--
-- Constraints der Tabelle `tbl_run`
--
ALTER TABLE `tbl_run`
  ADD CONSTRAINT `tbl_run_ibfk_1` FOREIGN KEY (`run_map_id`) REFERENCES `tbl_map` (`id_map`),
  ADD CONSTRAINT `tbl_run_ibfk_2` FOREIGN KEY (`run_difficulty_id`) REFERENCES `tbl_difficulty` (`id_difficulty`),
  ADD CONSTRAINT `tbl_run_ibfk_3` FOREIGN KEY (`run_probability_id`) REFERENCES `tbl_probability` (`id_probability`);

--
-- Constraints der Tabelle `tbl_run_mod`
--
ALTER TABLE `tbl_run_mod`
  ADD CONSTRAINT `tbl_run_mod_ibfk_1` FOREIGN KEY (`rm_run_id`) REFERENCES `tbl_run` (`id_run`),
  ADD CONSTRAINT `tbl_run_mod_ibfk_2` FOREIGN KEY (`rm_mod_id`) REFERENCES `tbl_mod` (`id_mod`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
