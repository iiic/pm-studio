-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Pondělí 21. února 2011, 14:56
-- Verze MySQL: 5.1.41
-- Verze PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `pm`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `author` varchar(80) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `comments`
--


-- --------------------------------------------------------

--
-- Struktura tabulky `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `email` varchar(250) NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `contact`
--

INSERT INTO `contact` (`title`, `content`, `email`, `user_id`, `date`) VALUES
('nadpis', 'obsah a *text*', 'ic.czech@gmail.com', 1, 1292088636);

-- --------------------------------------------------------

--
-- Struktura tabulky `introduction`
--

CREATE TABLE IF NOT EXISTS `introduction` (
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='informace na úvodu stránek';

--
-- Vypisuji data pro tabulku `introduction`
--

INSERT INTO `introduction` (`title`, `content`, `user_id`, `date`) VALUES
('nadpis', 'modifikováno pomocí texy\n\n***texynost***', 0, 1291323299);

-- --------------------------------------------------------

--
-- Struktura tabulky `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `p_type` int(2) NOT NULL,
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='položky menu' AUTO_INCREMENT=13 ;

--
-- Vypisuji data pro tabulku `menu`
--

INSERT INTO `menu` (`id`, `p_type`, `title`, `content`) VALUES
(1, 1, '', ''),
(2, 2, 'Naše nové projekty', ''),
(3, 0, 'grafika • loga', ''),
(4, 0, 'samolepky • auta • výlohy', ''),
(5, 1, '', ''),
(6, 1, '', ''),
(7, 1, '', ''),
(8, 1, '', ''),
(9, 1, '', ''),
(10, 1, '', ''),
(11, 5, '', ''),
(12, 3, 'Kontakt', '');

-- --------------------------------------------------------

--
-- Struktura tabulky `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `date`) VALUES
(1, 'Narození štěňat', 'Dne 20.2.2007 se nám narodily 2 fenky ze spojení Grin z Radotínských strání + Heli od Hubertovy skály.', 1292093362),
(2, 'NOVÁ NAROZENÁ ŠTĚŇÁTKA', 'Dne 25.6.2007 se narodila 4 štěnátka; Sendy, Sam, Sir a Swen. Matka: Kali z Radotínských strání a Otec: Grin z Radotínských strání. Přibližný odběr je koncem srpna.', 1292093555),
(3, 'ŠTĚŇÁTKA JSOU ZAMLUVENÁ', 'Naše dvě fenečky si již našli nové majitele :)', 1292093957),
(4, 'Sendy a Swen jsou...', 'Sendy a Swen jsou už dávno u svých nových pánečků.', 1292094009),
(5, 'Výstava', 'Dne 28.10 2007 Klubová výstava KCHJ ČR, Praha 9, Letňany Jedeme s fenkou Feli z Radotínských strání', 1292094136),
(6, 'Feli získala...', 'Naše Feli získala na klubové výstavě v Praze Res. CAC a byla výborná. Blahopřejeme.', 1292096192),
(7, 'V nejbližších týdnech. . .', 'V nejbližších týdnech oživíme naši smečku novými holkami. :) Nelou z Radotínských strání a Dianou Judring. Celá smečka a rodina už se nemůže dočkat. Fotky budou jen co holky dovezeme domů. :)', 1292096260),
(8, 'Klubová výstava SLUŠOVICE 1.8.2010', 'Naše mladé feny, Diana Judring a Nela-B z Radotínských strání se zúčastnily klubové výstavy jezevčíků. DIANKA se známkou V1. NELA-B získala CAC a CCWUT. Dianka byla zároveň uchovněna a Nela získala Kandidát chovu.', 1292096316),
(9, 'Zkoušky vloh Domaželice 4.9.2010', 'S Nelou-B z Radotínských strání jsme úspěšně splnili zkoušky vloh v 1C s počtem bodů 188, jako druhá z 15 psů. Počasí nám přálo a zvěře bylo až moc. Tím to se stává chovnou fenou.', 1292096383),
(10, 'Národní výstava psů BRNO 25.9.2010', '[* 57964910.jpeg .(velký úspěch) <] Po úspěchu na klubové výstavě ve Slušovicích jsme se rozhodli zúčastnit Národní výstavy v Brně, s Nelou-B z Radotínských strání a Onyxem z Radotínských strání. Nela nám udělala radost získala V1, CAC, NÁRODNÍ VÍTĚZ. Onyx VD1 a získal bonitaci Chovný pes.', 1295460725);

-- --------------------------------------------------------

--
-- Struktura tabulky `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `set_id` int(8) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(80) NOT NULL,
  `description` varchar(250) NOT NULL,
  `uri` varchar(250) NOT NULL,
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `photos`
--

INSERT INTO `photos` (`id`, `set_id`, `display`, `title`, `description`, `uri`, `width`, `height`, `user_id`, `date`) VALUES
(1, 1, 1, '', '', '11096307.jpeg', 600, 450, 1, 1290257465),
(2, 1, 1, '', '', '11138273.jpeg', 2048, 1536, 1, 1290257673),
(3, 2, 1, '', '', '12435222.jpeg', 1280, 960, 1, 1290258008),
(4, 2, 1, '', '', '124352221.jpeg', 1290, 960, 1, 1290258089);

-- --------------------------------------------------------

--
-- Struktura tabulky `photos_albums`
--

CREATE TABLE IF NOT EXISTS `photos_albums` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(250) NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `photos_albums`
--

INSERT INTO `photos_albums` (`id`, `display`, `title`, `user_id`, `date`) VALUES
(1, 1, 'Feli z Radotínských strání', 1, 1234567890),
(2, 1, 'Grin z Radotínských strání', 1, 1234567890);

-- --------------------------------------------------------

--
-- Struktura tabulky `photos_sets`
--

CREATE TABLE IF NOT EXISTS `photos_sets` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `album_id` int(8) NOT NULL,
  `display` tinyint(1) NOT NULL,
  `title` varchar(250) NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `photos_sets`
--

INSERT INTO `photos_sets` (`id`, `album_id`, `display`, `title`, `user_id`, `date`) VALUES
(1, 1, 1, 'za malda', 1, 1234567890),
(2, 1, 1, 'rok a půl', 1, 1234567890),
(3, 2, 1, 'Grin', 1, 1234567890),
(4, 2, 1, 'Něco hledáme', 1, 1234567890);

-- --------------------------------------------------------

--
-- Struktura tabulky `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `title` varchar(80) NOT NULL COMMENT 'nadpis stránek (do title)',
  `description` varchar(250) NOT NULL,
  `keywords` varchar(250) NOT NULL,
  `important_title` varchar(80) NOT NULL COMMENT 'nadpis důležité zprávy',
  `important` varchar(250) NOT NULL,
  `important_efect` tinyint(1) NOT NULL DEFAULT '0',
  `style` int(3) NOT NULL,
  `robots` varchar(250) NOT NULL,
  `admins` varchar(250) NOT NULL,
  `user_id` int(3) NOT NULL,
  `date` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='globální nastavení web';

--
-- Vypisuji data pro tabulku `settings`
--

INSERT INTO `settings` (`title`, `description`, `keywords`, `important_title`, `important`, `important_efect`, `style`, `robots`, `admins`, `user_id`, `date`) VALUES
('Nadpis stránek', 'popisek webíku zde', 'slovo, další, slovíčko', 'Nepřehlédněte !', 'tohle je velice důležité', 0, 1, '', '1', 1, 1295199375);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `validate_email` varchar(5) NOT NULL DEFAULT '0',
  `password` varchar(250) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='uživatelé' AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `validate_email`, `password`, `date`) VALUES
(1, 'demo', 'demo@icweb.eu', '1', 'BhRZJA0GCgJRFTo9ARQMFg==', 1296995113);

-- --------------------------------------------------------

--
-- Struktura tabulky `users_openid`
--

CREATE TABLE IF NOT EXISTS `users_openid` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `openid` varchar(250) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='open id účty zdejších uživatelů' AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `users_openid`
--


--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `news` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
