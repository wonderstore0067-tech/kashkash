-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2019 at 11:09 AM
-- Server version: 5.7.27
-- PHP Version: 7.1.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etippers`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Roles_Permission`
--

CREATE TABLE IF NOT EXISTS `Admin_Roles_Permission` (
  `Id` int(11) NOT NULL,
  `Role_Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Permission` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admin_Roles_Permission`
--

INSERT INTO `Admin_Roles_Permission` (`Id`, `Role_Id`, `User_Id`, `Permission`) VALUES
(1, 1, 1, 'a:18:{s:9:"dashboard";i:1;s:11:"user_manage";i:1;s:15:"merchant_manage";i:1;s:8:"withdraw";i:1;s:7:"deposit";i:1;s:7:"request";i:1;s:11:"transaction";i:1;s:10:"send_money";i:1;s:17:"sharebill_request";i:1;s:6:"qrcode";i:1;s:8:"pay_bill";i:1;s:16:"manage_promocode";i:1;s:13:"biller_manage";i:1;s:8:"feedback";i:1;s:9:"trx_limit";i:1;s:7:"setting";i:1;s:7:"website";i:1;s:16:"admin_management";i:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `Charges_Type_Master`
--

CREATE TABLE IF NOT EXISTS `Charges_Type_Master` (
  `Id` int(11) NOT NULL,
  `Is_Withdrawal` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Deposit` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Tax` tinyint(1) NOT NULL DEFAULT '0',
  `Tax_Name` varchar(255) NOT NULL DEFAULT '',
  `Charge_Percentage` varchar(255) NOT NULL DEFAULT '0',
  `Charge_Fixed` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Min_Amt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Max_Amt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Is_Active` tinyint(1) NOT NULL DEFAULT '1',
  `Start_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `End_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Charges_Type_Master`
--

INSERT INTO `Charges_Type_Master` (`Id`, `Is_Withdrawal`, `Is_Deposit`, `Is_Tax`, `Tax_Name`, `Charge_Percentage`, `Charge_Fixed`, `Min_Amt`, `Max_Amt`, `Is_Active`, `Start_Date`, `End_Date`) VALUES
(1, 0, 0, 0, 'GST', '0', '0.00', '0.00', '0.00', 1, '2018-07-18 10:34:37', '2018-07-18 10:34:37'),
(2, 0, 0, 0, 'CGST', '0', '0.00', '0.00', '0.00', 1, '2018-07-18 10:34:37', '2018-07-18 10:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `Cities`
--

CREATE TABLE IF NOT EXISTS `Cities` (
  `Id` int(11) NOT NULL,
  `State_Id` int(11) NOT NULL DEFAULT '0',
  `City_Name` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Countries`
--

-- CREATE TABLE IF NOT EXISTS `Countries` (
--   `Id` int(11) NOT NULL,
--   `Country_Name` varchar(255) NOT NULL DEFAULT '',
--   `Country_Code` varchar(255) NOT NULL DEFAULT ''
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `code` int(5) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `iso`, `name`, `nicename`, `code`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 93),
(2, 'AL', 'ALBANIA', 'Albania', 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 376),
(6, 'AO', 'ANGOLA', 'Angola', 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', 672),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 54),
(11, 'AM', 'ARMENIA', 'Armenia', 374),
(12, 'AW', 'ARUBA', 'Aruba', 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 61),
(14, 'AT', 'AUSTRIA', 'Austria', 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 880),
(19, 'BB', 'BARBADOS', 'Barbados', 1246),
(20, 'BY', 'BELARUS', 'Belarus', 375),
(21, 'BE', 'BELGIUM', 'Belgium', 32),
(22, 'BZ', 'BELIZE', 'Belize', 501),
(23, 'BJ', 'BENIN', 'Benin', 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', 0),
(30, 'BR', 'BRAZIL', 'Brazil', 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 226),
(35, 'BI', 'BURUNDI', 'Burundi', 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 237),
(38, 'CA', 'CANADA', 'Canada', 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 236),
(42, 'TD', 'CHAD', 'Chad', 235),
(43, 'CL', 'CHILE', 'Chile', 56),
(44, 'CN', 'CHINA', 'China', 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 57),
(48, 'KM', 'COMOROS', 'Comoros', 269),
(49, 'CG', 'CONGO', 'Congo', 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 506),
(53, 'CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 225),
(54, 'HR', 'CROATIA', 'Croatia', 385),
(55, 'CU', 'CUBA', 'Cuba', 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 420),
(58, 'DK', 'DENMARK', 'Denmark', 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 253),
(60, 'DM', 'DOMINICA', 'Dominica', 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 593),
(63, 'EG', 'EGYPT', 'Egypt', 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 240),
(66, 'ER', 'ERITREA', 'Eritrea', 291),
(67, 'EE', 'ESTONIA', 'Estonia', 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 298),
(71, 'FJ', 'FIJI', 'Fiji', 679),
(72, 'FI', 'FINLAND', 'Finland', 358),
(73, 'FR', 'FRANCE', 'France', 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', 0),
(77, 'GA', 'GABON', 'Gabon', 241),
(78, 'GM', 'GAMBIA', 'Gambia', 220),
(79, 'GE', 'GEORGIA', 'Georgia', 995),
(80, 'DE', 'GERMANY', 'Germany', 49),
(81, 'GH', 'GHANA', 'Ghana', 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 350),
(83, 'GR', 'GREECE', 'Greece', 30),
(84, 'GL', 'GREENLAND', 'Greenland', 299),
(85, 'GD', 'GRENADA', 'Grenada', 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 590),
(87, 'GU', 'GUAM', 'Guam', 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 502),
(89, 'GN', 'GUINEA', 'Guinea', 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 245),
(91, 'GY', 'GUYANA', 'Guyana', 592),
(92, 'HT', 'HAITI', 'Haiti', 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 39),
(95, 'HN', 'HONDURAS', 'Honduras', 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 852),
(97, 'HU', 'HUNGARY', 'Hungary', 36),
(98, 'IS', 'ICELAND', 'Iceland', 354),
(99, 'IN', 'INDIA', 'India', 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 98),
(102, 'IQ', 'IRAQ', 'Iraq', 964),
(103, 'IE', 'IRELAND', 'Ireland', 353),
(104, 'IL', 'ISRAEL', 'Israel', 972),
(105, 'IT', 'ITALY', 'Italy', 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 1876),
(107, 'JP', 'JAPAN', 'Japan', 81),
(108, 'JO', 'JORDAN', 'Jordan', 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 7),
(110, 'KE', 'KENYA', 'Kenya', 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 996),
(116, 'LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', 856),
(117, 'LV', 'LATVIA', 'Latvia', 371),
(118, 'LB', 'LEBANON', 'Lebanon', 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 266),
(120, 'LR', 'LIBERIA', 'Liberia', 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 352),
(125, 'MO', 'MACAO', 'Macao', 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 261),
(128, 'MW', 'MALAWI', 'Malawi', 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 60),
(130, 'MV', 'MALDIVES', 'Maldives', 960),
(131, 'ML', 'MALI', 'Mali', 223),
(132, 'MT', 'MALTA', 'Malta', 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', 269),
(138, 'MX', 'MEXICO', 'Mexico', 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 373),
(141, 'MC', 'MONACO', 'Monaco', 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 264),
(148, 'NR', 'NAURU', 'Nauru', 674),
(149, 'NP', 'NEPAL', 'Nepal', 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 505),
(155, 'NE', 'NIGER', 'Niger', 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 234),
(157, 'NU', 'NIUE', 'Niue', 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 1670),
(160, 'NO', 'NORWAY', 'Norway', 47),
(161, 'OM', 'OMAN', 'Oman', 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 92),
(163, 'PW', 'PALAU', 'Palau', 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', 970),
(165, 'PA', 'PANAMA', 'Panama', 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 595),
(168, 'PE', 'PERU', 'Peru', 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 0),
(171, 'PL', 'POLAND', 'Poland', 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 1787),
(174, 'QA', 'QATAR', 'Qatar', 974),
(175, 'RE', 'REUNION', 'Reunion', 262),
(176, 'RO', 'ROMANIA', 'Romania', 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 70),
(178, 'RW', 'RWANDA', 'Rwanda', 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 1784),
(184, 'WS', 'SAMOA', 'Samoa', 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 966),
(188, 'SN', 'SENEGAL', 'Senegal', 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 677),
(196, 'SO', 'SOMALIA', 'Somalia', 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', 0),
(199, 'ES', 'SPAIN', 'Spain', 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 94),
(201, 'SD', 'SUDAN', 'Sudan', 249),
(202, 'SR', 'SURINAME', 'Suriname', 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 268),
(205, 'SE', 'SWEDEN', 'Sweden', 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 255),
(211, 'TH', 'THAILAND', 'Thailand', 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', 670),
(213, 'TG', 'TOGO', 'Togo', 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 690),
(215, 'TO', 'TONGA', 'Tonga', 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 216),
(218, 'TR', 'TURKEY', 'Turkey', 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 688),
(222, 'UG', 'UGANDA', 'Uganda', 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 44),
(226, 'US', 'UNITED STATES', 'United States', 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 212),
(237, 'YE', 'YEMEN', 'Yemen', 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 263);

-- --------------------------------------------------------

--
-- Table structure for table `Document_Type`
--

CREATE TABLE IF NOT EXISTS `Document_Type` (
  `Id` int(11) NOT NULL,
  `Document_Type_Name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Document_Type`
--

INSERT INTO `Document_Type` (`Id`, `Document_Type_Name`) VALUES
(1, 'krapin_details'),
(2, 'id_passport_details');

-- --------------------------------------------------------

--
-- Table structure for table `Fee`
--

CREATE TABLE IF NOT EXISTS `Fee` (
  `Id` int(11) NOT NULL,
  `Service_Name` varchar(255) DEFAULT '',
  `Review_Fee` varchar(5) DEFAULT '',
  `Fee` varchar(5) DEFAULT '' COMMENT '%',
  `Status` tinyint(1) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL COMMENT '1=>all_transaction,2=>point_earns',
  `Created_at` datetime DEFAULT NULL,
  `Created_By` int(11) DEFAULT NULL,
  `Modified_at` datetime DEFAULT NULL,
  `Modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Fee`
--

INSERT INTO `Fee` (`Id`, `Service_Name`, `Review_Fee`, `Fee`, `Status`, `Type`, `Created_at`, `Created_By`, `Modified_at`, `Modified_by`) VALUES
(1, 'All Money', '5', '1.5', 1, 'all', '2017-12-07 18:22:31', 1, '2019-03-06 11:27:12', 1),
(2, 'All Money', '0', '0.5', 1, 'point_earns', '2017-12-07 18:22:31', 1, '2018-10-16 14:10:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `General_Setting`
--

CREATE TABLE IF NOT EXISTS `General_Setting` (
  `Id` int(11) NOT NULL,
  `Sitetitle` varchar(255) DEFAULT NULL,
  `Reg` varchar(1) DEFAULT NULL,
  `Notimail` varchar(366) DEFAULT NULL,
  `emailtemp` blob,
  `smsapi` varchar(2555) DEFAULT NULL,
  `currency` varchar(11) DEFAULT NULL,
  `cur` varchar(11) DEFAULT NULL,
  `min_amount` varchar(10) NOT NULL,
  `colorcode` varchar(6) DEFAULT NULL,
  `ev` int(1) DEFAULT '0',
  `mv` int(1) DEFAULT '0',
  `deci` int(11) DEFAULT '2',
  `date_format` varchar(11) NOT NULL,
  `en` int(1) DEFAULT '1',
  `mn` int(1) DEFAULT '1',
  `Cemail` varchar(255) DEFAULT NULL,
  `Cmobile` varchar(255) DEFAULT NULL,
  `Caddress` varchar(255) DEFAULT NULL,
  `clocation` varchar(255) DEFAULT NULL,
  `clocation_text` text NOT NULL,
  `Clink` varchar(255) NOT NULL,
  `slogo` varchar(50) NOT NULL,
  `sfavicon` varchar(50) NOT NULL,
  `simage` varchar(50) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `mdescription` text NOT NULL,
  `ganalytics` text NOT NULL,
  `merchant_commission` varchar(20) NOT NULL,
  `modify_by` int(11) NOT NULL,
  `Contact_Image` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `General_Setting`
--

INSERT INTO `General_Setting` (`Id`, `Sitetitle`, `Reg`, `Notimail`, `emailtemp`, `smsapi`, `currency`, `cur`, `min_amount`, `colorcode`, `ev`, `mv`, `deci`, `date_format`, `en`, `mn`, `Cemail`, `Cmobile`, `Caddress`, `clocation`, `clocation_text`, `Clink`, `slogo`, `sfavicon`, `simage`, `mname`, `mdescription`, `ganalytics`, `merchant_commission`, `modify_by`, `Contact_Image`) VALUES
(1, 'eTIPPERS', '1', 'info@etippers.com', 0x3c703e266e6273703b3c2f703e0d0a3c64697620636c6173733d227772617070657222207374796c653d226261636b67726f756e642d636f6c6f723a20236632663266323b223e0d0a3c7461626c65207374796c653d22626f726465722d636f6c6c617073653a20636f6c6c617073653b207461626c652d6c61796f75743a2066697865643b20636f6c6f723a20236238623862383b20666f6e742d66616d696c793a205562756e74752c73616e732d73657269663b2220616c69676e3d2263656e746572223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420636c6173733d227072656865616465725f5f736e697070657422207374796c653d2270616464696e673a203130707820302035707820303b20766572746963616c2d616c69676e3a20746f703b2077696474683a2032383070783b223e266e6273703b3c2f74643e0d0a3c746420636c6173733d227072656865616465725f5f77656276657273696f6e22207374796c653d22746578742d616c69676e3a2072696768743b2070616464696e673a203130707820302035707820303b20766572746963616c2d616c69676e3a20746f703b2077696474683a2032383070783b223e266e6273703b3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c652069643d22656d622d656d61696c2d6865616465722d636f6e7461696e65722220636c6173733d2268656164657222207374796c653d22626f726465722d636f6c6c617073653a20636f6c6c617073653b207461626c652d6c61796f75743a2066697865643b206d617267696e2d6c6566743a206175746f3b206d617267696e2d72696768743a206175746f3b2220616c69676e3d2263656e746572223e0d0a3c74626f64793e0d0a3c74723e0d0a3c7464207374796c653d2270616464696e673a20303b2077696474683a2036303070783b223e0d0a3c64697620636c6173733d226865616465725f5f6c6f676f20656d622d6c6f676f2d6d617267696e2d626f7822207374796c653d22666f6e742d73697a653a20323670783b206c696e652d6865696768743a20333270783b20636f6c6f723a20236333636564393b20666f6e742d66616d696c793a20526f626f746f2c5461686f6d612c73616e732d73657269663b206d617267696e3a203670782032307078203230707820323070783b223e0d0a3c6469762069643d22656d622d656d61696c2d6865616465722220636c6173733d226c6f676f2d6c65667422207374796c653d22666f6e742d73697a653a203070782021696d706f7274616e743b206c696e652d6865696768743a20302021696d706f7274616e743b2220616c69676e3d226c656674223e3c696d67207374796c653d226865696768743a206175746f3b2077696474683a20313030253b20626f726465723a20303b206d61782d77696474683a2033313270783b22207372633d2268747470733a2f2f6c6f63616c686f73742f6d6f6e65795f7472616e736665725f6170702f6577616c6c65742f6173736574732f696d616765732f6c6f676f2e706e672220616c743d22222077696474683d2233313222206865696768743d2234342220616c69676e3d226e6f6e65223e3c2f6469763e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c7461626c6520636c6173733d226c61796f7574206c61796f75742d2d6e6f2d67757474657222207374796c653d22626f726465722d636f6c6c617073653a20636f6c6c617073653b207461626c652d6c61796f75743a2066697865643b206d617267696e2d6c6566743a206175746f3b206d617267696e2d72696768743a206175746f3b206f766572666c6f772d777261703a20627265616b2d776f72643b20776f72642d777261703a20627265616b2d776f72643b20776f72642d627265616b3a20627265616b2d776f72643b206261636b67726f756e642d636f6c6f723a20236666666666663b2220616c69676e3d2263656e746572223e0d0a3c74626f64793e0d0a3c74723e0d0a3c746420636c6173733d22636f6c756d6e22207374796c653d2270616464696e673a20303b20746578742d616c69676e3a206c6566743b20766572746963616c2d616c69676e3a20746f703b20636f6c6f723a20233630363636643b20666f6e742d73697a653a20313470783b206c696e652d6865696768743a20323170783b20666f6e742d66616d696c793a2073616e732d73657269663b2077696474683a2036303070783b223e0d0a3c646976207374796c653d226d617267696e2d6c6566743a20323070783b206d617267696e2d72696768743a20323070783b206d617267696e2d746f703a20323470783b223e0d0a3c646976207374796c653d226c696e652d6865696768743a20313070783b20666f6e742d73697a653a203170783b223e266e6273703b3c2f6469763e0d0a3c2f6469763e0d0a3c646976207374796c653d226d617267696e2d6c6566743a20323070783b206d617267696e2d72696768743a20323070783b223e0d0a3c68323e4869207b7b6e616d657d7d2c3c2f68323e0d0a3c703e3c7374726f6e673e7b7b6d6573736167657d7d3c2f7374726f6e673e0d0a0d0a0d0a0d0a090d0a090d0a090d0a090d0a093c7374796c6520747970653d22746578742f637373223e0d0a0909626f64792c6469762c7461626c652c74686561642c74626f64792c74666f6f742c74722c74682c74642c70207b20666f6e742d66616d696c793a22417269616c223b20666f6e742d73697a653a782d736d616c6c207d0d0a0909612e636f6d6d656e742d696e64696361746f723a686f766572202b20636f6d6d656e74207b206261636b67726f756e643a236666643b20706f736974696f6e3a6162736f6c7574653b20646973706c61793a626c6f636b3b20626f726465723a31707820736f6c696420626c61636b3b2070616464696e673a302e35656d3b20207d200d0a0909612e636f6d6d656e742d696e64696361746f72207b206261636b67726f756e643a7265643b20646973706c61793a696e6c696e652d626c6f636b3b20626f726465723a31707820736f6c696420626c61636b3b2077696474683a302e35656d3b206865696768743a302e35656d3b20207d200d0a0909636f6d6d656e74207b20646973706c61793a6e6f6e653b20207d200d0a093c2f7374796c653e3c2f703e0d0a3c2f6469763e0d0a3c646976207374796c653d226d617267696e2d6c6566743a20323070783b206d617267696e2d72696768743a20323070783b223e3c62723e3c2f6469763e0d0a3c646976207374796c653d226d617267696e2d6c6566743a20323070783b206d617267696e2d72696768743a20323070783b206d617267696e2d626f74746f6d3a20323470783b223e0d0a3c7020636c6173733d2273697a652d313422207374796c653d226d617267696e2d746f703a20303b206d617267696e2d626f74746f6d3a20303b20666f6e742d73697a653a20313470783b206c696e652d6865696768743a20323170783b223e5468616e6b732c3c62723e203c7374726f6e673e436f6e7361676f757320452d57616c6c6574205465616d3c2f7374726f6e673e3c2f703e0d0a3c2f6469763e0d0a3c2f74643e0d0a3c2f74723e0d0a3c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f6469763e, 'https://api.infobip.com/api/v3/sendsms/plain?user=****&password=*****&sender=Consagous-EWallet&SMSText={{message}}&GSM={{number}}&type=longSMS', 'K', 'K', '', '01A9DB', 0, 1, 2, 'DD/MM/YYYY', 1, 0, 'info@etippers.com', '+917240875215', ' 403, Gold Stone Building, 3/5 New Palasia, Near 56 Shops, Indore, Madhya Pradesh 452001', 'Kenya', '																				', 'https://www.etippers.com/', 'splash_logo@3x.png', 'fav_icn.png', 'services-bg.jpg', '', '', '', '1', 0, 'contact_info@3x.png');

-- --------------------------------------------------------

--
-- Table structure for table `Manage_Advertisement_Images`
--

CREATE TABLE IF NOT EXISTS `Manage_Advertisement_Images` (
  `Id` int(11) NOT NULL,
  `Advertisement_Title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Advertisement_Subtitle` varchar(255) NOT NULL,
  `Advertisement_Image` text NOT NULL,
  `Description` text NOT NULL,
  `Lat` varchar(50) NOT NULL DEFAULT '0',
  `Lang` varchar(50) NOT NULL DEFAULT '0',
  `Status` tinyint(1) NOT NULL COMMENT '1=>Acive,0=>deactive',
  `Address` varchar(255) NOT NULL,
  `Advertisement_Type` enum('location_wise','default') NOT NULL,
  `Created_By` int(11) NOT NULL,
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Manage_Advertisement_Images`
--

INSERT INTO `Manage_Advertisement_Images` (`Id`, `Advertisement_Title`, `Advertisement_Subtitle`, `Advertisement_Image`, `Description`, `Lat`, `Lang`, `Status`, `Address`, `Advertisement_Type`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 'Win Reffral Points', 'Add money', 'coupen@2x.png', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '0', '0', 1, '', 'default', 1, '2019-09-16 05:56:00', '2019-09-16 05:56:00', '2019-09-16 05:56:00'),
(2, 'Offers', 'Sell', '1571299749etippersadvertisement.jpg', '<p>This is dummytext</p>', '0', '0', 1, '', 'default', 1, '2019-10-15 08:18:07', '2019-10-15 13:48:07', '2019-10-15 13:48:07'),
(3, 'Food Offers', '50% off', '1571915178honda-city-front-angle-low-view-168154.jpg', '<p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p>', '22.7195687', '75.85772580000003', 1, 'Indore, Madhya Pradesh, India', 'location_wise', 1, '2019-10-17 02:37:24', '2019-10-17 08:07:24', '2019-10-17 08:07:24'),
(4, 'Test Text', 'Sub test text', '1574768460images4.jpeg', '<p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p> <p>This is dummy text.</p>', '22.7195687', '75.85772580000003', 1, 'Indore, Madhya Pradesh, India', 'location_wise', 1, '2019-10-17 22:20:02', '2019-10-18 03:50:02', '2019-10-18 03:50:02'),
(5, 'testing Kirtisagar', 'testing Kirtisagar sub title', '15747681312330752_2.jpg', '<p>its just for test kirtisagar</p>', '18.5204303', '73.85674369999992', 1, 'Pune, Maharashtra, India', 'default', 1, '2019-11-20 06:33:12', '2019-11-20 12:03:12', '2019-11-20 12:03:12'),
(6, 'test a', 'abhi test', '1574768040a15e98ea_z.jpg', '<p>aaaaaaaaaaaaaaaaaaaaaa aaaaaaaaaa a a aaaa aaa</p>', '22.7195687', '75.85772580000003', 1, 'Indore, Madhya Pradesh, India', 'location_wise', 1, '2019-11-26 06:04:00', '2019-11-26 11:34:00', '2019-11-26 11:34:00'),
(7, 'test ad ythjy utyutyju ty', 'new test ad tyjt tyj tjy ', '1574769472Waterfalls_USA_Crag_Trees_Hocking_Hills_State_Park_Ohio_Nature_10929x5553.jpg', '<p>ad for city wise tyjyt ytjtyj ty</p>', '18.52882319999999', '73.87448569999992', 1, 'Pune Railway Station, Agarkar Nagar, Pune, Maharashtra, India', 'location_wise', 1, '2019-11-26 06:27:54', '2019-11-26 11:57:54', '2019-11-26 11:57:54'),
(8, 'abc', 'ab', '1575026987jakob-owens-hlKw49XvyRU-unsplash.jpg', '<p>uygyu gyugyugyug</p>', '', '', 1, '', 'default', 1, '2019-11-29 05:59:47', '2019-11-29 11:29:47', '2019-11-29 11:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `manage_notification_mail`
--

CREATE TABLE IF NOT EXISTS `manage_notification_mail` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `send_by` varchar(200) NOT NULL,
  `status` enum('Active','Deactive') NOT NULL DEFAULT 'Active',
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manage_notification_mail`
--

INSERT INTO `manage_notification_mail` (`id`, `slug`, `subject`, `description`, `send_by`, `status`, `created_dt`, `updated_dt`) VALUES
(1, 'forget_password', 'Forgot Password', '<p>Hi <strong>{USERNAME}</strong>,</p> <p>Your OTP is: <strong>{OTP}</strong></p>', '1', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'sucessfully_registration_user', 'Welcome to eTIPPERS', '<p>{NAME}</pp>\r\n<p>Thank you for registration with eTIPPERS, <a href="{URL}"> Click Here </a> To Activate Your Account</p>', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'forgot_password_admin', 'Forgot Password', '<p>Hi {USERNAME},</p> <p>For Reset your password <a href="{URL}"> Click here </a></p>', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'send_otp_code', 'Registration', '<p>Your OTP is {OTP}</p>', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'resend_otp_code', 'Resend OTP Code', '<p> Your OTP is {OTP}</p>', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'profile_switch', 'Profile Switch', '<p>Hi <strong>{USERNAME} </strong>,</p> <p>You have successfully switched your profile to {PROFILE}.</p>', '', 'Active', '2019-10-01 11:33:34', '2019-10-01 11:33:34'),
(7, 'profile_receiver_new', 'New Profile for Receiver', '<p> Hi <strong>Admin </strong>,</p> <p> Their is a new request for switching the profile as receiver. Please verify the doucments of the following user  - </p>\n\n<p>Full name - {FULLNAME}</p>\n<p>Email - {EMAIL}</p>\n<p>Mobile - {MOBILE}</p>', '', 'Active', '2019-10-01 11:33:34', '2019-10-01 11:33:34'),
(8, 'document_status', 'Document Status', '<p>Hi <strong>{USERNAME}</strong>,</p> <p>Your Documents are - <strong>{STATUS}</strong></p>', '', 'Active', '2019-10-01 13:51:39', '2019-10-01 13:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `Notification_Templates`
--

CREATE TABLE IF NOT EXISTS `Notification_Templates` (
  `Id` int(11) NOT NULL,
  `Event_Code` varchar(255) NOT NULL DEFAULT '',
  `Display_Name` varchar(255) NOT NULL DEFAULT '',
  `Key_Words` varchar(255) NOT NULL DEFAULT '',
  `Subject_Text` text NOT NULL,
  `Notification_Text` text NOT NULL,
  `Is_For_Push` tinyint(1) NOT NULL DEFAULT '0',
  `Is_For_eMail` tinyint(1) NOT NULL DEFAULT '0',
  `Is_For_SMS` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Options`
--

CREATE TABLE IF NOT EXISTS `Options` (
  `Id` int(11) NOT NULL,
  `Option_Name` mediumtext NOT NULL,
  `Option_Value` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Options`
--

INSERT INTO `Options` (`Id`, `Option_Name`, `Option_Value`) VALUES
(1, 'terms_condition', '1539256718lorem-ipsum.pdf'),
(2, 'privacy_policy', '1539256725lorem-ipsum.pdf'),
(3, 'about_us', '1539256737lorem-ipsum.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `organisation_types`
--

CREATE TABLE IF NOT EXISTS `organisation_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organisation_types`
--

INSERT INTO `organisation_types` (`id`, `name`, `created_at`) VALUES
(1, 'Restaurant', '2019-10-01 06:19:00'),
(2, 'Bar', '2019-10-01 06:19:00'),
(3, 'Bakery', '2019-10-01 06:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `Organization_Details`
--

CREATE TABLE IF NOT EXISTS `Organization_Details` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Organization_Name` varchar(100) NOT NULL,
  `Organization_Type` int(50) NOT NULL,
  `Organization_Phone` varchar(15) NOT NULL,
  `Organization_Address` varchar(255) NOT NULL,
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Organization_Details`
--

INSERT INTO `Organization_Details` (`Id`, `User_Id`, `Organization_Name`, `Organization_Type`, `Organization_Phone`, `Organization_Address`, `Creation_Date_Time`, `is_deleted`) VALUES
(1, 95, 'Test Organization', 1, '1233212321', 'Test Address', '2019-11-14 14:28:02', 0),
(2, 95, 'Test Organization2', 2, '1112223336', 'Test Address2', '2019-11-14 14:28:34', 0),
(3, 98, 'Demo', 1, '99999999999', 'Demo Addresa', '2019-11-19 11:17:51', 0),
(4, 99, 'mc Donald''s', 1, '9736988498', '1970 Valencia', '2019-11-20 05:15:28', 0),
(5, 106, 'Test', 1, '69855850506', 'Indore', '2019-11-25 12:37:25', 0),
(6, 123, 'Leon''s Gin Bar', 2, '6462627000', '666 Park ave. New York', '2019-12-03 16:34:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Refferal_Points_Settings`
--

CREATE TABLE IF NOT EXISTS `Refferal_Points_Settings` (
  `Id` int(11) NOT NULL,
  `Give_Refferal_Point` int(11) NOT NULL COMMENT 'At the time of signup give points',
  `Refferal_Points` int(11) NOT NULL COMMENT 'ser refferal points for amount equal',
  `Refferal_Amount` float(10,2) NOT NULL COMMENT 'ser amount for equal to refferal points'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Refferal_Points_Settings`
--

INSERT INTO `Refferal_Points_Settings` (`Id`, `Give_Refferal_Point`, `Refferal_Points`, `Refferal_Amount`) VALUES
(1, 100, 10, 0.50);

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `Id` int(11) NOT NULL,
  `Role_Name` varchar(50) NOT NULL DEFAULT '',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Status` int(11) NOT NULL COMMENT '1=>active,0=>deactive',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`Id`, `Role_Name`, `Created_By`, `Status`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 'Admin', 1, 1, '2018-07-30 11:13:55', 1, '2018-10-05 08:06:46'),
(2, 'Sender', 1, 1, '2018-07-30 11:13:55', 1, '2018-10-16 08:16:35'),
(3, 'Receiver', 1, 1, '2018-07-30 11:14:14', 1, '2018-10-05 08:07:00'),
(4, 'Sub admin', 1, 1, '2019-02-27 05:29:52', 0, '2019-02-27 10:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `Scheduled_Notifications`
--

CREATE TABLE IF NOT EXISTS `Scheduled_Notifications` (
  `Id` int(11) NOT NULL,
  `Noti_Temp_Id` int(11) NOT NULL DEFAULT '0',
  `Subject_Text` text NOT NULL,
  `Notification_Text` text NOT NULL,
  `Scheduled_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Is_Sent` tinyint(1) NOT NULL DEFAULT '0',
  `Sent_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `To_User_Id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `States`
--

CREATE TABLE IF NOT EXISTS `States` (
  `Id` int(11) NOT NULL,
  `Country_Id` int(11) NOT NULL DEFAULT '0',
  `State_Name` varchar(255) NOT NULL DEFAULT '',
  `State_Code` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `static_page`
--

CREATE TABLE IF NOT EXISTS `static_page` (
  `id` int(11) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `discription` longtext NOT NULL,
  `meta_title` varchar(50) NOT NULL,
  `meta_keyword` varchar(50) NOT NULL,
  `meta_discription` longtext NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `static_page`
--

INSERT INTO `static_page` (`id`, `slug`, `title`, `discription`, `meta_title`, `meta_keyword`, `meta_discription`, `status`, `Creation_Date_Time`, `Last_Updated_Date_Time`) VALUES
(1, 'about-us', 'About Us', '<p>Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '                                    ', '                            ', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'term-and-condition', 'Term And Condition', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32', 'terms and condition', 'terms and condition', '', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'privacy_policy', 'Privacy And Policy', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 'privacy and policy', 'privacy and policy', '<p>Please read these Terms of Use,&nbsp;Privacy Policy,&nbsp;Cookies Policy&nbsp;and&nbsp;Community Guidelines, very carefully before using the Website and mobile application which is an online marketplace</p>\r\n', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'faq', 'FAQ’s', '<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'faq', 'faq', '<p>Please read these Terms of Use,&nbsp;Privacy Policy,&nbsp;Cookies Policy&nbsp;and&nbsp;Community Guidelines, very carefully before using the Website and mobile application which is an online marketplace</p>\r\n', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Transactions`
--

CREATE TABLE IF NOT EXISTS `Transactions` (
  `Id` int(11) NOT NULL,
  `Tran_Type_Id` int(11) NOT NULL DEFAULT '0',
  `To_User_Id` int(11) NOT NULL DEFAULT '0' COMMENT 'who',
  `From_User_Id` int(11) NOT NULL DEFAULT '0' COMMENT 'sender',
  `To_Payment_Method_Id` int(11) NOT NULL DEFAULT '0' COMMENT 'who',
  `From_Payment_Method_Id` int(11) NOT NULL DEFAULT '0' COMMENT 'sender',
  `Sig` varchar(1) DEFAULT NULL,
  `Amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Redeem_Referral_Point` int(11) NOT NULL DEFAULT '0',
  `Balance_Referral_Point` int(11) NOT NULL,
  `Is_Referral_Point` tinyint(1) NOT NULL,
  `Msg` text NOT NULL,
  `Tran_Status_Id` int(11) NOT NULL DEFAULT '0',
  `Amount_Received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Third_Party_Tran_Id` varchar(255) NOT NULL DEFAULT '' COMMENT 'Alpha numeric GUID for sending and receiving from third party i.e. bank, card, third party wallet. for internal we can use short value of 8 to 16 chars. ',
  `Image` varchar(255) NOT NULL DEFAULT '',
  `Refund_Reason` varchar(255) NOT NULL DEFAULT '',
  `Rejection_Reason` varchar(255) NOT NULL DEFAULT '',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tip_type` enum('fixed','percent') DEFAULT NULL,
  `bill_amount` varchar(10) NOT NULL DEFAULT '0',
  `organisation_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Transactions`
--

INSERT INTO `Transactions` (`Id`, `Tran_Type_Id`, `To_User_Id`, `From_User_Id`, `To_Payment_Method_Id`, `From_Payment_Method_Id`, `Sig`, `Amount`, `Charge`, `Redeem_Referral_Point`, `Balance_Referral_Point`, `Is_Referral_Point`, `Msg`, `Tran_Status_Id`, `Amount_Received`, `Third_Party_Tran_Id`, `Image`, `Refund_Reason`, `Rejection_Reason`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`, `tip_type`, `bill_amount`, `organisation_id`) VALUES
(1, 3, 94, 95, 0, 0, '-', '25.00', '0.00', 0, 0, 0, 'test', 6, '25.00', 'G5907A0W200S', '', '', '', 94, '2019-11-14 14:31:49', 94, '2019-11-14 14:31:49', 'percent', '100', 1),
(2, 4, 95, 94, 0, 0, '+', '25.00', '0.00', 0, 0, 0, '', 6, '25.00', 'G5907A0W200S', '', '', '', 94, '2019-11-14 14:31:49', 94, '2019-11-14 14:31:49', 'percent', '100', 1),
(3, 3, 96, 95, 0, 0, '-', '0.04', '0.00', 0, 0, 0, 'cgo', 6, '0.04', 'WC9A56FM796R', '', '', '', 96, '2019-11-15 09:54:20', 96, '2019-11-15 09:54:20', 'percent', '.8', 1),
(4, 4, 95, 96, 0, 0, '+', '0.04', '0.00', 0, 0, 0, '', 6, '0.04', 'WC9A56FM796R', '', '', '', 96, '2019-11-15 09:54:20', 96, '2019-11-15 09:54:20', 'percent', '.8', 1),
(5, 3, 96, 95, 0, 0, '-', '0.10', '0.00', 0, 0, 0, '', 6, '0.10', 'VBEFE8BB991T', '', '', '', 96, '2019-11-15 10:29:31', 96, '2019-11-15 10:29:31', 'percent', '2', 1),
(6, 4, 95, 96, 0, 0, '+', '0.10', '0.00', 0, 0, 0, '', 6, '0.10', 'VBEFE8BB991T', '', '', '', 96, '2019-11-15 10:29:32', 96, '2019-11-15 10:29:32', 'percent', '2', 1),
(7, 3, 94, 98, 0, 0, '-', '1000.00', '0.00', 0, 0, 0, '', 6, '1000.00', 'S4CE90CP180K', '', '', '', 94, '2019-11-19 11:19:00', 94, '2019-11-19 11:19:00', 'percent', '$1000', 3),
(8, 4, 98, 94, 0, 0, '+', '1000.00', '0.00', 0, 0, 0, '', 6, '1000.00', 'S4CE90CP180K', '', '', '', 94, '2019-11-19 11:19:00', 94, '2019-11-19 11:19:00', 'percent', '$1000', 3),
(9, 3, 98, 95, 0, 0, '-', '1.00', '0.00', 0, 0, 0, '', 6, '1.00', 'XFA96CFU476P', '', '', '', 98, '2019-11-20 05:05:35', 98, '2019-11-20 05:05:35', 'percent', '$10', 2),
(10, 4, 95, 98, 0, 0, '+', '1.00', '0.00', 0, 0, 0, '', 6, '1.00', 'XFA96CFU476P', '', '', '', 98, '2019-11-20 05:05:35', 98, '2019-11-20 05:05:35', 'percent', '$10', 2),
(11, 3, 98, 99, 0, 0, '-', '800.00', '0.00', 0, 0, 0, 'virtual money ', 6, '800.00', 'Z5E312AW763Z', '', '', '', 98, '2019-11-20 05:16:37', 98, '2019-11-20 05:16:37', 'percent', '$1000', 4),
(12, 4, 99, 98, 0, 0, '+', '800.00', '0.00', 0, 0, 0, '', 6, '800.00', 'Z5E312AW763Z', '', '', '', 98, '2019-11-20 05:16:38', 98, '2019-11-20 05:16:38', 'percent', '$1000', 4),
(13, 3, 96, 99, 0, 0, '-', '0.12', '0.00', 0, 0, 0, '', 6, '0.12', 'HB32CDAC242A', '', '', '', 96, '2019-11-20 05:16:43', 96, '2019-11-20 05:16:43', 'percent', '12', 4),
(14, 4, 99, 96, 0, 0, '+', '0.12', '0.00', 0, 0, 0, '', 6, '0.12', 'HB32CDAC242A', '', '', '', 96, '2019-11-20 05:16:43', 96, '2019-11-20 05:16:43', 'percent', '12', 4),
(15, 3, 99, 98, 0, 0, '-', '53.00', '0.00', 0, 0, 0, 'Thanks', 6, '53.00', 'N13D847B749O', '', '', '', 99, '2019-11-20 05:22:57', 99, '2019-11-20 05:22:57', 'percent', '$525.98', 3),
(16, 4, 98, 99, 0, 0, '+', '53.00', '0.00', 0, 0, 0, '', 6, '53.00', 'N13D847B749O', '', '', '', 99, '2019-11-20 05:22:57', 99, '2019-11-20 05:22:57', 'percent', '$525.98', 3),
(17, 3, 96, 99, 0, 0, '-', '50.00', '0.00', 0, 0, 0, '', 6, '50.00', 'F367573E716V', '', '', '', 96, '2019-11-20 05:30:43', 96, '2019-11-20 05:30:43', 'fixed', '', 4),
(18, 4, 99, 96, 0, 0, '+', '50.00', '0.00', 0, 0, 0, '', 6, '50.00', 'F367573E716V', '', '', '', 96, '2019-11-20 05:30:44', 96, '2019-11-20 05:30:44', 'fixed', '', 4),
(19, 3, 99, 95, 0, 0, '-', '11.00', '0.00', 0, 0, 0, 'thanks', 6, '11.00', 'A2CEF09H698N', '', '', '', 99, '2019-11-20 05:32:34', 99, '2019-11-20 05:32:34', 'percent', '$56.36', 1),
(20, 3, 98, 95, 0, 0, '-', '11.00', '0.00', 0, 0, 0, '', 6, '11.00', 'B2CF008G953A', '', '', '', 98, '2019-11-20 05:32:34', 98, '2019-11-20 05:32:34', 'percent', '$56.36', 1),
(21, 4, 95, 99, 0, 0, '+', '11.00', '0.00', 0, 0, 0, '', 6, '11.00', 'A2CEF09H698N', '', '', '', 99, '2019-11-20 05:32:35', 99, '2019-11-20 05:32:35', 'percent', '$56.36', 1),
(22, 4, 95, 98, 0, 0, '+', '11.00', '0.00', 0, 0, 0, '', 6, '11.00', 'B2CF008G953A', '', '', '', 98, '2019-11-20 05:32:35', 98, '2019-11-20 05:32:35', 'percent', '$56.36', 1),
(23, 3, 96, 95, 0, 0, '-', '2.20', '0.00', 0, 0, 0, '', 6, '2.20', 'K96D77EB710H', '', '', '', 96, '2019-11-29 12:41:13', 96, '2019-11-29 12:41:13', 'percent', '22', 2),
(24, 4, 95, 96, 0, 0, '+', '2.20', '0.00', 0, 0, 0, '', 6, '2.20', 'K96D77EB710H', '', '', '', 96, '2019-11-29 12:41:13', 96, '2019-11-29 12:41:13', 'percent', '22', 2),
(25, 3, 96, 95, 0, 0, '-', '1.50', '0.00', 0, 0, 0, '', 6, '1.50', 'S6CE39EK963B', '', '', '', 96, '2019-12-02 21:34:30', 96, '2019-12-02 21:34:30', 'percent', '10', 2),
(26, 4, 95, 96, 0, 0, '+', '1.50', '0.00', 0, 0, 0, '', 6, '1.50', 'S6CE39EK963B', '', '', '', 96, '2019-12-02 21:34:30', 96, '2019-12-02 21:34:30', 'percent', '10', 2),
(27, 3, 96, 123, 0, 0, '-', '20.00', '0.00', 0, 0, 0, 'good job!', 6, '20.00', 'B7B05C1Q209G', '', '', '', 96, '2019-12-03 16:36:55', 96, '2019-12-03 16:36:55', 'percent', '100', 6),
(28, 4, 123, 96, 0, 0, '+', '20.00', '0.00', 0, 0, 0, '', 6, '20.00', 'B7B05C1Q209G', '', '', '', 96, '2019-12-03 16:36:55', 96, '2019-12-03 16:36:55', 'percent', '100', 6);

-- --------------------------------------------------------

--
-- Table structure for table `Transactions_Limit`
--

CREATE TABLE IF NOT EXISTS `Transactions_Limit` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `tran_type_id` int(11) NOT NULL,
  `count_limit` int(11) NOT NULL,
  `daily_limit` int(11) NOT NULL,
  `monthly_trans_limit` int(11) NOT NULL,
  `monthly_limit` int(11) NOT NULL,
  `yearly_trans_limit` int(11) NOT NULL,
  `yearly_limit` int(11) NOT NULL,
  `created_on` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Transactions_Limit`
--

INSERT INTO `Transactions_Limit` (`id`, `name`, `tran_type_id`, `count_limit`, `daily_limit`, `monthly_trans_limit`, `monthly_limit`, `yearly_trans_limit`, `yearly_limit`, `created_on`) VALUES
(1, 'Add Money', 2, 100, 50000, 100, 800000, 0, 5000, 2019),
(2, 'Send Money', 3, 100, 20000, 100, 200000, 0, 0, 1540383493),
(3, 'Withdraw', 1, 100, 20000, 100, 100000, 0, 0, 2019),
(4, 'Cashout', 6, 100, 50000, 100, 200000, 0, 5000, 1540383493);

-- --------------------------------------------------------

--
-- Table structure for table `Tran_Charges`
--

CREATE TABLE IF NOT EXISTS `Tran_Charges` (
  `Id` int(11) NOT NULL,
  `Transaction_Id` int(11) NOT NULL DEFAULT '0',
  `Charge_Type_Id` int(11) NOT NULL DEFAULT '0' COMMENT 'IGST, CSGT, Withdrawal, etc.',
  `Charge_Amt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tran_Charges`
--

INSERT INTO `Tran_Charges` (`Id`, `Transaction_Id`, `Charge_Type_Id`, `Charge_Amt`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 1, 0, '0.00', 2, '2019-09-17 06:37:14', 2, '2019-09-17 06:37:14'),
(2, 2, 0, '0.00', 2, '2019-09-17 06:37:58', 2, '2019-09-17 06:37:58'),
(3, 3, 0, '0.00', 2, '2019-09-17 07:09:42', 2, '2019-09-17 07:09:42'),
(4, 4, 0, '0.00', 2, '2019-09-17 11:12:58', 2, '2019-09-17 11:12:58'),
(5, 5, 0, '0.00', 2, '2019-09-17 11:20:04', 2, '2019-09-17 11:20:04'),
(6, 66, 0, '0.00', 80, '2019-10-15 07:24:01', 80, '2019-10-15 07:24:01'),
(7, 101, 0, '0.00', 70, '2019-11-06 13:58:26', 70, '2019-11-06 13:58:26'),
(8, 102, 0, '0.00', 70, '2019-11-07 06:06:01', 70, '2019-11-07 06:06:01'),
(9, 105, 0, '0.00', 81, '2019-11-07 10:14:37', 81, '2019-11-07 10:14:37'),
(10, 106, 0, '0.00', 81, '2019-11-07 11:21:40', 81, '2019-11-07 11:21:40'),
(11, 107, 0, '0.00', 81, '2019-11-07 11:22:17', 81, '2019-11-07 11:22:17'),
(12, 108, 0, '0.00', 70, '2019-11-07 11:45:58', 70, '2019-11-07 11:45:58'),
(13, 109, 0, '0.00', 2, '2019-11-07 11:53:31', 2, '2019-11-07 11:53:31'),
(14, 110, 0, '0.00', 2, '2019-11-07 11:53:45', 2, '2019-11-07 11:53:45'),
(15, 111, 0, '0.00', 2, '2019-11-07 11:53:57', 2, '2019-11-07 11:53:57'),
(16, 112, 0, '0.00', 2, '2019-11-07 11:54:04', 2, '2019-11-07 11:54:04'),
(17, 113, 0, '0.00', 2, '2019-11-07 11:54:43', 2, '2019-11-07 11:54:43'),
(18, 114, 0, '0.00', 2, '2019-11-07 11:54:50', 2, '2019-11-07 11:54:50'),
(19, 115, 0, '0.00', 2, '2019-11-07 11:54:59', 2, '2019-11-07 11:54:59'),
(20, 116, 0, '0.00', 2, '2019-11-07 11:55:55', 2, '2019-11-07 11:55:55'),
(21, 117, 0, '0.00', 2, '2019-11-07 11:57:00', 2, '2019-11-07 11:57:00'),
(22, 118, 0, '0.00', 81, '2019-11-07 12:07:07', 81, '2019-11-07 12:07:07'),
(23, 119, 0, '0.00', 81, '2019-11-07 12:07:50', 81, '2019-11-07 12:07:50'),
(24, 120, 0, '0.00', 81, '2019-11-07 12:08:38', 81, '2019-11-07 12:08:38'),
(25, 121, 0, '0.00', 81, '2019-11-07 12:09:00', 81, '2019-11-07 12:09:00'),
(26, 122, 0, '0.00', 81, '2019-11-07 12:09:18', 81, '2019-11-07 12:09:18'),
(27, 123, 0, '0.00', 81, '2019-11-07 12:09:31', 81, '2019-11-07 12:09:31'),
(28, 124, 0, '0.00', 81, '2019-11-07 13:17:28', 81, '2019-11-07 13:17:28'),
(29, 125, 0, '0.00', 81, '2019-11-07 13:17:45', 81, '2019-11-07 13:17:45'),
(30, 126, 0, '0.00', 81, '2019-11-07 13:18:13', 81, '2019-11-07 13:18:13'),
(31, 127, 0, '0.00', 2, '2019-11-07 13:19:15', 2, '2019-11-07 13:19:15'),
(32, 132, 0, '0.00', 2, '2019-11-07 14:30:59', 2, '2019-11-07 14:30:59'),
(33, 133, 0, '0.00', 81, '2019-11-07 14:31:06', 81, '2019-11-07 14:31:06'),
(34, 166, 0, '0.00', 76, '2019-11-14 13:13:45', 76, '2019-11-14 13:13:45'),
(35, 167, 0, '0.00', 76, '2019-11-14 13:15:46', 76, '2019-11-14 13:15:46');

-- --------------------------------------------------------

--
-- Table structure for table `Tran_Status`
--

CREATE TABLE IF NOT EXISTS `Tran_Status` (
  `Id` int(11) NOT NULL,
  `Status_Name` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tran_Status`
--

INSERT INTO `Tran_Status` (`Id`, `Status_Name`) VALUES
(1, 'Pending'),
(2, 'Processed'),
(3, 'Hold'),
(4, 'Reject'),
(5, 'Refund'),
(6, 'Success'),
(7, 'Cancel');

-- --------------------------------------------------------

--
-- Table structure for table `Tran_Types`
--

CREATE TABLE IF NOT EXISTS `Tran_Types` (
  `Id` int(11) NOT NULL,
  `Tran_Name` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tran_Types`
--

INSERT INTO `Tran_Types` (`Id`, `Tran_Name`) VALUES
(1, 'Withdraw'),
(2, 'Deposit'),
(3, 'Send Money'),
(4, 'Receive Money');

-- --------------------------------------------------------

--
-- Table structure for table `unreadCount`
--

CREATE TABLE IF NOT EXISTS `unreadCount` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `chatroomId` int(11) NOT NULL,
  `unreadCount` int(11) NOT NULL,
  `unreadTime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL DEFAULT '',
  `FirstName` varchar(100) NOT NULL DEFAULT '',
  `LastName` varchar(100) NOT NULL DEFAULT '',
  `Password` varchar(255) NOT NULL DEFAULT '',
  `Transaction_Password` varchar(255) NOT NULL DEFAULT '',
  `Email` varchar(50) NOT NULL DEFAULT '',
  `Mobile_No` varchar(20) NOT NULL DEFAULT '',
  `Current_Wallet_Balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Total_Points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Session_Id` varchar(255) NOT NULL DEFAULT '',
  `Is_Mobile_Verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not verified , 1= verified',
  `Mobile_OTP` varchar(255) NOT NULL DEFAULT '',
  `Is_Email_Verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not verified , 1= verified',
  `Email_OTP` varchar(20) NOT NULL DEFAULT '',
  `Verification_EMail_Sent` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Allowed_Transaction` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 means pin not set , 1 means pin set',
  `Is_Profile_Complete` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Chat_Enabled` tinyint(1) NOT NULL DEFAULT '0',
  `Last_Login_TS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Profile_Pic` varchar(255) NOT NULL DEFAULT '',
  `Lat` varchar(20) NOT NULL,
  `Lang` varchar(20) NOT NULL,
  `Address` text NOT NULL,
  `Location` varchar(255) NOT NULL,
  `Gender` enum('M','F','O') DEFAULT 'M',
  `DOB` varchar(30) DEFAULT NULL,
  `Age` varchar(10) NOT NULL DEFAULT '0',
  `Is_LoggedIn` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Active` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Blocked` tinyint(1) NOT NULL DEFAULT '0',
  `Notification_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=on, 0=off ',
  `Fingerprint_Status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 = No ,  1= Yes',
  `Block_Reason` varchar(255) NOT NULL DEFAULT '',
  `Login_Token` varchar(255) NOT NULL DEFAULT '',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Referral_Code` varchar(50) NOT NULL,
  `Total_Referral_Points` int(11) NOT NULL DEFAULT '0',
  `Auth_Provider` int(11) NOT NULL COMMENT '0=>Normal Login,1=>Facbook,2=>Google',
  `social_auth_id` varchar(255) DEFAULT NULL,
  `etippers_id` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Id`, `FullName`, `FirstName`, `LastName`, `Password`, `Transaction_Password`, `Email`, `Mobile_No`, `Current_Wallet_Balance`, `Total_Points`, `Session_Id`, `Is_Mobile_Verified`, `Mobile_OTP`, `Is_Email_Verified`, `Email_OTP`, `Verification_EMail_Sent`, `Is_Allowed_Transaction`, `Is_Profile_Complete`, `Is_Chat_Enabled`, `Last_Login_TS`, `Profile_Pic`, `Lat`, `Lang`, `Address`, `Location`, `Gender`, `DOB`, `Age`, `Is_LoggedIn`, `Is_Active`, `Is_Blocked`, `Notification_Status`, `Fingerprint_Status`, `Block_Reason`, `Login_Token`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`, `Referral_Code`, `Total_Referral_Points`, `Auth_Provider`, `social_auth_id`, `etippers_id`) VALUES
(1, 'Alain  Charles', 'Alain ', 'Charles', '7567af59e14dfc8d792b499908e8c67dcf77c95e624594ec5d9915b24faf8e43878154f66bc0deb03f651add7269ab704ec526940f91f0cdd5f3f7b721bf0713', '', 'admin@gmail.com', '1234567899', '0.00', '0.00', '', 1, '1234', 1, '1234', 0, 0, 1, 0, '2019-09-05 11:47:52', '1571312873head-659651__340.png', '0', '0', '', '', 'M', NULL, '', 1, 1, 0, 0, '0', '', '', 0, '2019-09-05 11:47:52', 1, '2019-11-25 05:59:11', '', 0, 0, NULL, ''),
(93, 'Abhi Test', 'Abhi', 'Test', '393e86cbe95c6afd57cf363bb01510ee4ec18fb86627203632263263bc1be9ebac29e0f72e6f1a3f96a761a4b62241c0aab94ab4aa3acc941d51d0c068ce5899', '', 'abhi@mailinator.com', '9998887776', '10000.00', '1000.00', '', 1, '8732', 1, '8732', 0, 0, 1, 0, '2019-11-14 14:16:02', 'default.jpg', '22.72668018874629', '75.88425506226702', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '', 0, '2019-11-14 14:16:02', 93, '2019-11-18 01:38:03', 'thzfGfeKzT', 0, 0, NULL, 'ab60776@etippers'),
(94, 'Test 1', 'Test', '1', '393e86cbe95c6afd57cf363bb01510ee4ec18fb86627203632263263bc1be9ebac29e0f72e6f1a3f96a761a4b62241c0aab94ab4aa3acc941d51d0c068ce5899', '', 'test1@mailinator.com', '8889996665', '8975.00', '1000.00', '', 1, '5743', 1, '5743', 0, 1, 1, 0, '2019-11-14 14:18:18', 'default.jpg', '22.72668018874629', '75.88425506226702', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5dd3cf2283be7', 0, '2019-11-14 14:18:18', 94, '2019-11-19 05:46:50', 'MAUV9bgQRt', 0, 0, NULL, 'te59665@etippers'),
(95, 'Test 2', 'Test', '2', '393e86cbe95c6afd57cf363bb01510ee4ec18fb86627203632263263bc1be9ebac29e0f72e6f1a3f96a761a4b62241c0aab94ab4aa3acc941d51d0c068ce5899', '', 'test2@mailinator.com', '7778889996', '10040.84', '1000.00', '', 1, '0145', 1, '0145', 0, 0, 1, 0, '2019-11-14 14:19:53', 'default.jpg', '22.72668018874629', '75.88425506226702', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5dd7e4e07187f', 0, '2019-11-14 14:19:53', 95, '2019-11-22 08:08:40', '9eITVTq9D6', 200, 0, NULL, 'te90996@etippers'),
(96, 'Reginald Nelson', 'Reginald', 'Nelson', '28724364801325eed90eaf130e6e18503e6b5cb667262d0beee05563d5a109c637fa7b1f41a6483a65c91ba3d9a0752f1bad3c554421e5b45932a2905fcfbc67', '', 'rnelson@etippers.com', '9177713738', '9926.04', '10000.00', '', 1, '6283', 0, '6283', 0, 0, 1, 0, '2019-11-14 17:29:39', 'default.jpg', '40.63898279619489', '-74.01447223532703', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5de7f41759b80', 0, '2019-11-14 17:29:39', 96, '2019-12-04 12:29:51', 'RG0hY26Kqj', 0, 0, NULL, 're10738@etippers'),
(97, 'Anil Kumar', 'Anil', 'Kumar', 'eee4465dff0852bbedb1f9407e2f690cf9d77eba742edb5af7d817ed01561e538f4c11baa88318088a3749963af02160d50a0fb5762f6a97cb9d376cdad91f0e', '', 'anil@mailinator.com', '9800078000', '0.00', '957.00', '', 1, '4526', 1, '4526', 0, 0, 1, 0, '2019-11-15 09:43:51', 'default.jpg', '0', '0', 'D', '', 'M', NULL, '14', 0, 1, 0, 1, '0', '', '', 0, '2019-11-15 09:43:51', 97, '2019-11-15 04:21:35', 'QMONfUtA6P', 0, 0, NULL, 'an37000@etippers'),
(98, 'Deepak Sharma', 'Deepak', 'Sharma', '7952ce0d8ed16edc3afabe8ce23e738b09a3a2b7f66d0e42a7754ba3f0d8b4c4f6b35f50fe2b167cbfa06ddd8ab108fb13a28fd2365ebc483a561ab606e23d5c', '', 'deepak.sharma@consagous.com', '9399982216', '241.00', '513.00', '', 1, '4712', 1, '4712', 0, 0, 1, 0, '2019-11-19 11:11:47', 'default.jpg', '22.7266651', '75.8841306', '', '', 'M', NULL, '0', 1, 1, 0, 1, '1', '', '5dd3cf386036a', 0, '2019-11-19 11:11:47', 98, '2019-11-19 05:47:12', 'ZuWk10PlYD', 0, 0, NULL, 'de29216@etippers'),
(99, 'Alain Charles', 'Alain', 'Charles', '70b5073550e4151b768461617f5a823d6eb806e8c1fd344e9cf532627197ff9617e49b1955010944ff221af99d93c996dab68986186f8daf5f458e19792ee15a', '', 'acharles@etippers.com', '9736988498', '786.12', '751.00', '', 1, '2749', 1, '2749', 0, 1, 1, 0, '2019-11-20 05:05:00', 'default.jpg', '32.1348933', '-111.0083959', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5ddee87520e23', 0, '2019-11-20 05:05:00', 99, '2019-11-27 15:49:49', 'NMXT5F5Yx7', 0, 0, NULL, 'al52498@etippers'),
(100, 'Abhishek Pachauri123', 'Abhishek', 'Pachauri', '', '', 'abhishek.pachauri7889@consagous.com', '8387442021', '0.00', '0.00', '', 1, '2739', 1, '2739', 0, 1, 1, 0, '2019-11-22 13:26:19', 'default.jpg', '22.7266815', '75.8841342', '', '', 'M', NULL, '0', 0, 1, 0, 1, '0', '', '', 0, '2019-11-22 13:26:19', 100, '2019-11-25 06:33:05', 'oII6o9TI1e', 0, 2, '10809178969786789', 'ab821@etippers'),
(101, 'Consagous MobileTeam', 'Consagous', 'MobileTeam', '', '', 'consagous.mobileteam@gmail.com', '8982000051', '0.00', '0.00', '', 1, '7813', 1, '7813', 0, 0, 1, 0, '2019-11-22 13:44:39', 'default.jpg', '22.7266911', '75.8833777', '', '', 'M', NULL, '0', 1, 1, 0, 1, '1', '', '5dedfb59b533f', 0, '2019-11-22 13:44:39', 101, '2019-12-09 02:14:25', 'adMaYhU9dr', 0, 1, '548490952671290', 'co13051@etippers'),
(102, 'Deepak Sharma', 'Deepak Sharma', '', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-22 13:52:53', '', '22.7050848', '75.909381', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5dd7e84d55a6a', 0, '2019-11-22 13:52:53', 102, '2019-11-22 08:23:17', '', 0, 2, '102446300860429263715', ''),
(103, 'Abhishek Pachauri56', 'Abhishek', 'Pachauri', '', '', 'abhishek.pachauri877@gmail.com', '8494647653', '0.00', '0.00', '', 1, '5209', 1, '5209', 0, 1, 1, 0, '2019-11-22 13:55:05', '1574684203cropped1471260410.jpg', '22.7266778', '75.8841235', 'Indore', '', 'M', NULL, '28', 0, 1, 0, 1, '0', '', '', 0, '2019-11-22 13:55:05', 103, '2019-11-25 06:44:22', 'XKJruEdbhQ', 0, 1, '', 'ab56794@etippers'),
(104, 'vipul.goyal@consagous.com', 'vipul.goyal@consagous.com', '', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-25 11:34:22', '', '22.7267341', '75.8840846', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5ddbbd4579737', 0, '2019-11-25 11:34:22', 104, '2019-11-25 06:08:45', '', 0, 2, '104620261570951300872', ''),
(105, 'Abhishek pachauri', 'Abhishek pachauri', '', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-25 11:57:14', '', '22.7266834', '75.8841197', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5ddbc1e4120dc', 0, '2019-11-25 11:57:14', 105, '2019-11-25 06:28:28', '', 0, 2, '103231807817643487276', ''),
(106, 'Abhishek Pachauri', 'Abhishek', 'Pachauri', '', '', 'abhishek.pachauri52@gmail.com', '8305442021', '0.00', '0.00', '', 1, '6854', 1, '6854', 0, 0, 1, 0, '2019-11-25 12:32:41', 'default.jpg', '22.7266875', '75.884122', '', '', 'M', NULL, '0', 1, 1, 0, 1, '1', '', '5ddd36998af45', 0, '2019-11-25 12:32:41', 106, '2019-11-26 08:58:41', 'eJt0igwWJv', 0, 1, '2640433009328804', 'ab25021@etippers'),
(107, 'Abhishek Pachauri', 'Abhishek', 'Pachauri', '', '', 'abhishek.pachauri@consagous.com', '7974795493', '0.00', '0.00', '', 1, '1034', 1, '1034', 0, 1, 1, 0, '2019-11-25 13:24:05', 'default.jpg', '22.7266948', '75.884098', '', '', 'M', NULL, '0', 0, 1, 0, 1, '1', '', '', 0, '2019-11-25 13:24:05', 107, '2019-11-26 06:13:18', 'nbvTVgMPuw', 0, 2, '108091913596807042002', 'ab63493@etippers'),
(108, 'QA QA', 'QA', 'QA', '', '', 'user.qa7771@gmail.com', '8988965000', '0.00', '0.00', '', 0, '7450', 0, '7450', 0, 0, 0, 0, '2019-11-26 05:49:13', 'default.jpg', '22.7266732', '75.8841665', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5de615940718a', 0, '2019-11-26 05:49:13', 108, '2019-12-03 02:28:12', 'efbCF9aJNv', 0, 2, '5025726927', 'q88000@etippers'),
(109, 'Alain Charles', 'Alain Charles', '', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-26 18:07:37', '', '33.5215881', '-117.1606647', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5ddd69e90f697', 0, '2019-11-26 18:07:37', 109, '2019-11-26 12:37:37', '', 0, 2, '117053500976986872721', ''),
(110, 'R J', 'R', 'J', 'd8b425dc9c170dbb7f49e55489b1a52a0e2c3a39b3186b3cf3a04a1277fa4f46bd1ffc021319d366ae358043c5d672fc559f19b2468e2c941459d142dff68902', '', 'rj@yopmail.com', '1234321234', '0.00', '0.00', '', 1, '8017', 1, '8017', 0, 0, 1, 0, '2019-11-27 10:02:17', 'default.jpg', '22.7196', '75.85769999999999', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5dde49de910c6', 0, '2019-11-27 10:02:17', 110, '2019-11-27 04:33:10', 'Ocasfh2bky', 0, 0, NULL, 'r63234@etippers'),
(111, 'Bri Charles', 'Bri', 'Charles', '', '', 'briana.a.charles@gmail.com', '9515266772', '0.00', '0.00', '', 1, '0759', 1, '0759', 0, 1, 1, 0, '2019-11-27 21:04:06', 'default.jpg', '33.4793945', '-117.0690753', '', '', 'F', NULL, '0', 1, 1, 0, 1, '0', '', '5ddee4c693dc7', 0, '2019-11-27 21:04:06', 111, '2019-11-27 15:34:06', 'JjTAOWC9ff', 0, 1, '1033584530310912', 'br39772@etippers'),
(112, 'Mahesh Dhakad Mahesh Dhakad', 'Mahesh Dhakad', 'Mahesh Dhakad', '', '', 'mdhakad89@gmail.com', '9977386130', '0.00', '0.00', '', 1, '4105', 1, '4105', 0, 0, 1, 0, '2019-11-28 09:27:23', 'default.jpg', '22.72658735981465', '75.88456878445659', '', '', 'M', NULL, '0', 0, 1, 0, 1, '1', '', '', 0, '2019-11-28 09:27:23', 112, '2019-12-02 05:59:16', 'hENZY5onPT', 0, 1, '2659949214043825', 'ma20130@etippers'),
(113, 'M D', 'M', 'D', '', '', 'md@mailinator.com', '9632587410', '0.00', '0.00', '', 1, '9264', 1, '9264', 0, 0, 1, 0, '2019-11-28 12:56:27', 'default.jpg', '22.726684', '75.8841295', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '', 0, '2019-11-28 12:56:27', 113, '2019-11-28 07:42:33', 'UejME5DcW7', 0, 2, '115386390175205028686', 'm69410@etippers'),
(114, 'M k', 'M', 'k', '7d6a0a8bc3381964ad38283a33cc05bff1775b8ec440c51a6e3fd5dee3b176d000a754cd3807898dc49bc9a22e9b4bfa261dc0348d9fa53056a0e729ad134bae', '', 'mk@mailinator.com', '9874563210', '0.00', '0.00', '', 1, '2479', 1, '2479', 0, 0, 1, 0, '2019-11-28 13:13:32', 'default.jpg', '22.7266777', '75.8841566', '', '', 'M', NULL, '0', 0, 1, 0, 1, '0', '', '', 0, '2019-11-28 13:13:32', 114, '2019-11-28 07:45:47', 'tYpRSND0C5', 0, 0, NULL, 'm31210@etippers'),
(115, 'Aman ', 'Aman', 'QA', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-28 13:55:57', '', '22.7266837', '75.8841225', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5de614b7564dd', 0, '2019-11-28 13:55:57', 115, '2019-12-03 02:24:31', '', 0, 1, '489888908540111', ''),
(116, 'Armen Pashkam Armen Pashkam', 'Armen Pashkam', 'Armen Pashkam', '', '', 'abc789@mailinator.com', '8972415564', '0.00', '0.00', '', 1, '3971', 1, '3971', 0, 0, 1, 0, '2019-11-28 14:01:27', 'default.jpg', '22.72674764775329', '75.88415952250621', '', '', 'M', NULL, '0', 0, 1, 0, 1, '1', '', '', 0, '2019-11-28 14:01:27', 116, '2019-11-29 05:20:08', 'XjYjCut6L1', 0, 0, '1312489798789', 'ab35564@etippers'),
(117, 'QA Activity ', 'QA Activity', 'QA Activity', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-11-29 10:35:28', '', '22.72667700426907', '75.88422420441721', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5de9270560b16', 0, '2019-11-29 10:35:28', 117, '2019-12-05 10:19:25', '', 0, 0, '582483765773-haopuvkbmuebtqqn8pi2hq5s55lc5ep9.apps.googleusercontent.com', ''),
(118, 'Armen Pashkam', 'Armen', 'Pashkam', '', '', 'abc@mailinator.com', '9852147852', '0.00', '0.00', '', 1, '1439', 1, '1439', 0, 1, 1, 0, '2019-11-29 11:21:33', '1575026663oscar-keys-ojVMh1QTVGY-unsplash.jpg', '22.72670247870139', '75.88414844205748', '', '', 'M', NULL, '0', 1, 1, 0, 1, '1', '', '5de523f7dd08c', 0, '2019-11-29 11:21:33', 118, '2019-12-02 09:17:19', 'paaSKwMZ5l', 0, 0, '131245901630371', 'ar63852@etippers'),
(119, 'Reginald Nelson Reginald Nelson', 'Reginald Nelson', 'Reginald Nelson', '', '', 'regitennis@yahoo.vom', '3475764043', '0.00', '0.00', '', 0, '9321', 0, '9321', 0, 0, 0, 0, '2019-11-29 12:12:11', 'default.jpg', '40.66796174509011', '-73.99754707232974', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5de64a030eaf9', 0, '2019-11-29 12:12:11', 119, '2019-12-03 06:11:55', 'g3KbyxKBnM', 0, 0, '147448383329174', 're91043@etippers'),
(120, 'abhishek pachauri ', 'abhishek pachauri', 'abhishek', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-12-02 13:17:25', '', '22.7266944', '75.8841333', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5de50ee5e4cec', 0, '2019-12-02 13:17:25', 120, '2019-12-02 07:47:25', '', 0, 2, '109609835512020609712', ''),
(121, 'Nikhat Shaikh', 'Nikhat Shaikh', '', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-12-03 05:59:29', '', '22.7195983', '75.8576983', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5de5fe68e4114', 0, '2019-12-03 05:59:29', 121, '2019-12-03 00:49:20', '', 0, 2, '110157207332973217380', ''),
(122, 'QA Activity ', 'QA Activity', 'Activity', '', '', '', '', '0.00', '0.00', '', 0, '', 0, '', 0, 0, 0, 0, '2019-12-03 08:11:58', '', '22.7267067', '75.8841304', '', '', 'M', NULL, '0', 1, 1, 0, 0, '0', '', '5de634e10b1c8', 0, '2019-12-03 08:11:58', 122, '2019-12-03 04:41:45', '', 0, 2, '101835653565025726927', ''),
(123, 'Leon charles', 'Leon', 'charles', 'e268e37a3ab8456002976fa81746ac13ea3613d2c65fac6700ec91ea2439baa0673f48c61f0284f821a4b6e62d2bf3082610f232b5d9a63124db8fce9f97ec99', '', 'gowireless4g@gmail.com', '6462627000', '20.00', '0.00', '', 1, '3804', 1, '3804', 0, 1, 1, 0, '2019-12-03 14:49:15', 'default.jpg', '40.8869553', '-74.0148031', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5de6860b72acf', 0, '2019-12-03 14:49:15', 123, '2019-12-03 10:28:03', 'JtmQZ4GVwZ', 0, 0, NULL, 'le24000@etippers'),
(124, 'Deepak Sharma', 'Deepak', 'Sharma', '', '', 'deeps.mist@gmail.com', '9399982214', '0.00', '0.00', '', 1, '4187', 1, '4187', 0, 0, 1, 0, '2019-12-04 11:28:28', 'default.jpg', '22.7266863', '75.8841189', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5de799511805d', 0, '2019-12-04 11:28:28', 124, '2019-12-04 06:02:33', '9YrmaXOPE6', 0, 2, '101633419662520006489', 'de72214@etippers'),
(125, 'Ritesh test', 'Ritesh', 'test', '393e86cbe95c6afd57cf363bb01510ee4ec18fb86627203632263263bc1be9ebac29e0f72e6f1a3f96a761a4b62241c0aab94ab4aa3acc941d51d0c068ce5899', '', 'riteshtest@mailinator.com', '9996633221', '0.00', '0.00', '', 0, '8365', 0, '8365', 0, 0, 0, 0, '2019-12-09 10:08:37', 'default.jpg', '22.7267027', '75.8840866', '', '', 'M', NULL, '0', 0, 0, 0, 1, '0', '', '', 0, '2019-12-09 10:08:37', 0, '2019-12-09 10:08:37', 'vx2aQLVEN9', 0, 0, NULL, 'ri96221@etippers'),
(126, 'T 1', 'T', '1', '393e86cbe95c6afd57cf363bb01510ee4ec18fb86627203632263263bc1be9ebac29e0f72e6f1a3f96a761a4b62241c0aab94ab4aa3acc941d51d0c068ce5899', '', 't1@mailinator.com', '1212124545', '0.00', '0.00', '', 1, '6847', 1, '6847', 0, 1, 1, 0, '2019-12-09 10:11:26', 'default.jpg', '22.7267116', '75.8841193', '', '', 'M', NULL, '0', 1, 1, 0, 1, '0', '', '5dee1fe6f0d3c', 0, '2019-12-09 10:11:26', 126, '2019-12-09 04:50:22', 'p6H0JustTa', 0, 0, NULL, 't94545@etippers');

-- --------------------------------------------------------

--
-- Table structure for table `Users_Documents`
--

CREATE TABLE IF NOT EXISTS `Users_Documents` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `Document_Type_Id` int(11) NOT NULL DEFAULT '0',
  `Pin_Number` varchar(255) NOT NULL DEFAULT '',
  `Document_Image_Name` varchar(255) NOT NULL DEFAULT '',
  `Varification_End_Image` varchar(255) NOT NULL DEFAULT '',
  `Is_Verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not verified , 1= verified',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users_Documents`
--

INSERT INTO `Users_Documents` (`Id`, `User_Id`, `Document_Type_Id`, `Pin_Number`, `Document_Image_Name`, `Varification_End_Image`, `Is_Verified`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 93, 2, '', '1573740962varification_image1573740962664_7239.png', '', 0, 93, '2019-11-14 14:16:03', 0, '2019-11-14 14:16:03'),
(2, 94, 2, '', '1573741098varification_image1573741098078_089.png', '', 1, 94, '2019-11-14 14:18:18', 0, '2019-11-14 14:18:18'),
(3, 95, 2, '', '1573741193varification_image1573741193340_77.png', '', 1, 95, '2019-11-14 14:19:53', 0, '2019-11-14 14:19:53'),
(4, 96, 2, '', '1573752579varification_image1573752576231_012.png', '', 1, 96, '2019-11-14 17:29:39', 0, '2019-11-14 17:29:39'),
(5, 97, 2, '', '1573811031varification_image1573811031053_166.png', '', 0, 97, '2019-11-15 09:43:51', 0, '2019-11-15 09:43:51'),
(6, 98, 2, '', '1574161903cropped7033329515726621313.jpg', '', 1, 98, '2019-11-19 11:11:47', 0, '2019-11-19 11:11:47'),
(7, 99, 2, '', '1574226299cropped8767085252940961058.jpg', '', 1, 99, '2019-11-20 05:05:00', 0, '2019-11-20 05:05:00'),
(8, 100, 2, '', '1574430095cropped825451035.jpg', '', 1, 100, '2019-11-22 13:41:36', 0, '2019-11-22 13:41:36'),
(9, 103, 2, '', '1574430970cropped484929145.jpg', '', 1, 103, '2019-11-22 13:56:10', 0, '2019-11-22 13:56:10'),
(10, 101, 2, '', '1574681635cropped1151791406171568592.jpg', '', 1, 101, '2019-11-25 11:33:55', 0, '2019-11-25 11:33:55'),
(11, 106, 2, '', '1574685189cropped406224781.jpg', '', 1, 106, '2019-11-25 12:33:09', 0, '2019-11-25 12:33:09'),
(12, 107, 2, '', '1574688747cropped1606075081.jpg', '', 1, 107, '2019-11-25 13:32:27', 0, '2019-11-25 13:32:27'),
(13, 108, 2, '', '1574747390cropped6367546861344162616.jpg', '', 0, 108, '2019-11-26 05:49:51', 0, '2019-11-26 05:49:51'),
(14, 110, 2, '', '1574848936varification_image1574848936751_009.png', '', 0, 110, '2019-11-27 10:02:17', 0, '2019-11-27 10:02:17'),
(15, 111, 2, '', '1574888694cropped7961574456770862058.jpg', '', 0, 111, '2019-11-27 21:04:54', 0, '2019-11-27 21:04:54'),
(16, 112, 2, '', '1574946180varification_image1574946178954_566.png', '', 1, 112, '2019-11-28 13:03:00', 0, '2019-11-28 13:03:00'),
(17, 113, 2, '', '1574946638cropped198289296.jpg', '', 0, 113, '2019-11-28 13:10:38', 0, '2019-11-28 13:10:38'),
(18, 114, 2, '', '1574946812cropped322782309.jpg', '', 1, 114, '2019-11-28 13:13:32', 0, '2019-11-28 13:13:32'),
(19, 116, 2, '', '1575022528varification_image1575022527027_2231.png', '', 1, 116, '2019-11-29 10:15:28', 0, '2019-11-29 10:15:28'),
(20, 118, 2, '', '1575026574varification_image1575026568817_951.png', '', 1, 118, '2019-11-29 11:22:54', 0, '2019-11-29 11:22:54'),
(21, 119, 2, '', '1575322671varification_image1575322668608_0088.png', '', 0, 119, '2019-12-02 21:37:52', 0, '2019-12-02 21:37:52'),
(22, 123, 2, '', '1575384555cropped39357562233857932.jpg', '', 1, 123, '2019-12-03 14:49:15', 0, '2019-12-03 14:49:15'),
(23, 124, 2, '', '1575458964cropped2201628702130576069.jpg', '', 1, 124, '2019-12-04 11:29:25', 0, '2019-12-04 11:29:25'),
(24, 125, 2, '', '1575886117cropped28309932.jpg', '', 0, 125, '2019-12-09 10:08:37', 0, '2019-12-09 10:08:37'),
(25, 126, 2, '', '1575886286cropped1628178912.jpg', '', 1, 126, '2019-12-09 10:11:26', 0, '2019-12-09 10:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `Users_Referrals`
--

CREATE TABLE IF NOT EXISTS `Users_Referrals` (
  `Id` int(11) NOT NULL,
  `Referral_From` int(11) NOT NULL,
  `Referral_To` int(11) NOT NULL,
  `Referral_Code` varchar(20) NOT NULL,
  `Referral_Points` int(11) NOT NULL DEFAULT '0',
  `Balance_Referral_Point` int(11) NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=>success,0=>pending',
  `Ref_Num` varchar(50) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users_Referrals`
--

INSERT INTO `Users_Referrals` (`Id`, `Referral_From`, `Referral_To`, `Referral_Code`, `Referral_Points`, `Balance_Referral_Point`, `Status`, `Ref_Num`, `Creation_Date_Time`) VALUES
(1, 95, 100, '9eITVTq9D6', 100, 100, 1, 'Y016A23N562F', '2019-11-22 13:41:36'),
(2, 95, 103, '9eITVTq9D6', 100, 200, 1, 'IACCD6CH892S', '2019-11-22 13:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `User_Billers`
--

CREATE TABLE IF NOT EXISTS `User_Billers` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `Biller_Name_Id` int(11) NOT NULL DEFAULT '0',
  `Account_Number` varchar(255) NOT NULL DEFAULT '',
  `Account_Number_Label` varchar(255) NOT NULL DEFAULT '',
  `Bill_Generation_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Bill_Payment_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Bill_Due_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Paid_Amt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Biller_Transaction_Id` varchar(255) NOT NULL DEFAULT '',
  `Late_Fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Is_Recurring` tinyint(1) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `User_Feedback`
--

CREATE TABLE IF NOT EXISTS `User_Feedback` (
  `Id` int(11) NOT NULL,
  `Faq_Id` int(11) NOT NULL DEFAULT '0',
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `First_Name` varchar(255) NOT NULL DEFAULT '',
  `Last_Name` varchar(255) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  `Subject` varchar(255) NOT NULL DEFAULT '',
  `Message` text NOT NULL,
  `Type` enum('Faq','Other') NOT NULL,
  `Creation_Date_Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `User_In_Roles`
--

CREATE TABLE IF NOT EXISTS `User_In_Roles` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `Role_Id` int(11) NOT NULL DEFAULT '0',
  `Parent_Role_Id` int(11) NOT NULL DEFAULT '0',
  `Device_Id` text NOT NULL,
  `Device_Type` varchar(10) NOT NULL DEFAULT '',
  `Device_OS_Version` varchar(255) NOT NULL DEFAULT '',
  `Current_APP_Version` varchar(255) NOT NULL DEFAULT '',
  `Is_Push_Enabled` tinyint(1) NOT NULL DEFAULT '1',
  `Device_Model_Name` varchar(255) NOT NULL DEFAULT '',
  `QR_Code` varchar(50) NOT NULL DEFAULT '',
  `QR_Status` int(11) NOT NULL,
  `QR_Code_Img_Path` varchar(255) NOT NULL,
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_In_Roles`
--

INSERT INTO `User_In_Roles` (`Id`, `User_Id`, `Role_Id`, `Parent_Role_Id`, `Device_Id`, `Device_Type`, `Device_OS_Version`, `Current_APP_Version`, `Is_Push_Enabled`, `Device_Model_Name`, `QR_Code`, `QR_Status`, `QR_Code_Img_Path`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 1, 1, 0, '111', 'ios', '', '', 1, '', '0', 0, '.png', 0, '2019-09-05 11:47:52', 0, '2019-09-05 11:47:52'),
(86, 93, 2, 0, '', '', '', '', 1, '', '99tESyLxqiGf', 0, '99tESyLxqiGf.png', 0, '2019-11-14 14:16:03', 0, '2019-11-14 14:16:03'),
(87, 94, 2, 0, '', 'android', '', '', 1, '', '88U5o4zC6bw8', 0, '88U5o4zC6bw8.png', 0, '2019-11-14 14:18:19', 94, '2019-11-19 05:46:59'),
(88, 95, 3, 0, '', 'ios', '', '', 1, '', '77XyPsUZGxQb', 0, '77XyPsUZGxQb.png', 0, '2019-11-14 14:19:54', 95, '2019-11-15 04:57:01'),
(89, 96, 2, 0, '', 'ios', '', '', 1, '', '91TZh7WlEuuQ', 0, '91TZh7WlEuuQ.png', 0, '2019-11-14 17:29:39', 96, '2019-12-03 11:00:22'),
(90, 97, 2, 0, '', '', '', '', 1, '', '98fFsdbprNkM', 0, '98fFsdbprNkM.png', 0, '2019-11-15 09:43:57', 0, '2019-11-15 09:43:57'),
(91, 98, 2, 0, '', 'android', '', '', 1, '', '93lHr0FIOYS2', 0, '93lHr0FIOYS2.png', 0, '2019-11-19 11:11:48', 98, '2019-11-19 23:54:16'),
(92, 99, 2, 0, '', 'android', '', '', 1, '', '97WKqnKG9iyZ', 0, '97WKqnKG9iyZ.png', 0, '2019-11-20 05:05:01', 99, '2019-11-27 15:50:34'),
(93, 100, 3, 0, '', '', '', '', 1, '', '833DQb9EGuoG', 0, '833DQb9EGuoG.png', 0, '2019-11-22 13:41:37', 0, '2019-11-22 13:41:37'),
(94, 103, 3, 0, '', '', '', '', 1, '', '84h7LBfje5CP', 0, '84h7LBfje5CP.png', 0, '2019-11-22 13:56:16', 103, '2019-11-25 06:47:06'),
(95, 101, 3, 0, '', 'android', '', '', 1, '', '89Os1nVDnt1a', 0, '89Os1nVDnt1a.png', 0, '2019-11-25 11:33:57', 101, '2019-11-26 00:45:43'),
(96, 106, 2, 0, '', 'android', '', '', 1, '', '837M2gIait9L', 0, '837M2gIait9L.png', 0, '2019-11-25 12:33:15', 106, '2019-11-26 08:59:06'),
(97, 107, 2, 0, '', '', '', '', 1, '', '79O2vZDtAJJA', 0, '79O2vZDtAJJA.png', 0, '2019-11-25 13:32:28', 107, '2019-11-25 08:04:40'),
(98, 108, 2, 0, '', 'android', '', '', 1, '', '89hFRDoEsiKE', 0, '89hFRDoEsiKE.png', 0, '2019-11-26 05:49:51', 0, '2019-11-26 05:49:51'),
(99, 110, 2, 0, '', 'ios', '', '', 1, '', '12KqXaWphecn', 0, '12KqXaWphecn.png', 0, '2019-11-27 10:02:18', 0, '2019-11-27 10:02:18'),
(100, 111, 2, 0, '', 'android', '', '', 1, '', '95o1ASoTZQkm', 0, '95o1ASoTZQkm.png', 0, '2019-11-27 21:04:55', 0, '2019-11-27 21:04:55'),
(101, 112, 2, 0, '', '', '', '', 1, '', '99SJ1I0OpCzx', 0, '99SJ1I0OpCzx.png', 0, '2019-11-28 13:03:01', 0, '2019-11-28 13:03:01'),
(102, 113, 2, 0, '', '', '', '', 1, '', '967p8FlMAMzM', 0, '967p8FlMAMzM.png', 0, '2019-11-28 13:10:38', 0, '2019-11-28 13:10:38'),
(103, 114, 3, 0, '', '', '', '', 1, '', '988qogInseNe', 0, '988qogInseNe.png', 0, '2019-11-28 13:13:33', 0, '2019-11-28 13:13:33'),
(104, 116, 3, 0, '', '', '', '', 1, '', '98qH01UpsdId', 0, '98qH01UpsdId.png', 0, '2019-11-29 10:15:29', 116, '2019-11-29 05:20:38'),
(105, 118, 2, 0, '', 'ios', '', '', 1, '', '98gjr3VjdLmx', 0, '98gjr3VjdLmx.png', 0, '2019-11-29 11:22:55', 118, '2019-11-29 06:00:24'),
(106, 119, 2, 0, '', 'ios', '', '', 1, '', '34n012XHMN6l', 0, '34n012XHMN6l.png', 0, '2019-12-02 21:37:53', 0, '2019-12-02 21:37:53'),
(107, 123, 3, 0, '', 'android', '', '', 1, '', '64y3AANPvpWZ', 0, '64y3AANPvpWZ.png', 0, '2019-12-03 14:49:16', 0, '2019-12-03 14:49:16'),
(108, 124, 3, 0, '', 'android', '', '', 1, '', '93rsZq2iQKiT', 0, '93rsZq2iQKiT.png', 0, '2019-12-04 11:29:26', 0, '2019-12-04 11:29:26'),
(109, 125, 3, 0, '', 'android', '', '', 1, '', '99OUYueSc4Q2', 0, '99OUYueSc4Q2.png', 0, '2019-12-09 10:08:38', 0, '2019-12-09 10:08:38'),
(110, 126, 3, 0, '', 'android', '', '', 1, '', '12DfoiMlwzHE', 0, '12DfoiMlwzHE.png', 0, '2019-12-09 10:11:27', 0, '2019-12-09 10:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `User_Logins`
--

CREATE TABLE IF NOT EXISTS `User_Logins` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `Ip_Address` varchar(255) NOT NULL DEFAULT '',
  `Location` varchar(255) NOT NULL DEFAULT '',
  `User_Os_Platform` varchar(255) NOT NULL DEFAULT '',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=312 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Logins`
--

INSERT INTO `User_Logins` (`Id`, `User_Id`, `Ip_Address`, `Location`, `User_Os_Platform`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(1, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-14 14:11:14', 0, '2019-11-14 14:11:14'),
(2, 93, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 14:17:13', 0, '2019-11-14 14:17:13'),
(3, 93, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 14:21:05', 0, '2019-11-14 14:21:05'),
(4, 94, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 14:25:03', 0, '2019-11-14 14:25:03'),
(5, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-14 14:27:07', 0, '2019-11-14 14:27:07'),
(6, 95, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 14:27:26', 0, '2019-11-14 14:27:26'),
(7, 94, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 14:30:41', 0, '2019-11-14 14:30:41'),
(8, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-14 17:36:05', 0, '2019-11-14 17:36:05'),
(9, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-15 06:54:16', 0, '2019-11-15 06:54:16'),
(10, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-15 07:00:49', 0, '2019-11-15 07:00:49'),
(11, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-15 07:03:20', 0, '2019-11-15 07:03:20'),
(12, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-15 09:07:14', 0, '2019-11-15 09:07:14'),
(13, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-15 09:07:20', 0, '2019-11-15 09:07:20'),
(14, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:40:42', 0, '2019-11-15 09:40:42'),
(15, 97, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:44:54', 0, '2019-11-15 09:44:54'),
(16, 97, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:45:07', 0, '2019-11-15 09:45:07'),
(17, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Firefox on Unknown OS Platform', 0, '2019-11-15 09:46:48', 0, '2019-11-15 09:46:48'),
(18, 97, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:51:36', 0, '2019-11-15 09:51:36'),
(19, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:51:47', 0, '2019-11-15 09:51:47'),
(20, 94, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:52:56', 0, '2019-11-15 09:52:56'),
(21, 94, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 09:58:14', 0, '2019-11-15 09:58:14'),
(22, 95, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 10:00:03', 0, '2019-11-15 10:00:03'),
(23, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-15 10:26:02', 0, '2019-11-15 10:26:02'),
(24, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-19 11:13:11', 0, '2019-11-19 11:13:11'),
(25, 98, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-19 11:13:42', 0, '2019-11-19 11:13:42'),
(26, 94, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-19 11:16:51', 0, '2019-11-19 11:16:51'),
(27, 98, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-19 11:17:18', 0, '2019-11-19 11:17:18'),
(28, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-20 04:49:04', 0, '2019-11-20 04:49:04'),
(29, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-20 04:59:30', 0, '2019-11-20 04:59:30'),
(30, 99, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-20 05:06:12', 0, '2019-11-20 05:06:12'),
(31, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-20 11:05:13', 0, '2019-11-20 11:05:13'),
(32, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-20 11:10:43', 0, '2019-11-20 11:10:43'),
(33, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-20 11:37:14', 0, '2019-11-20 11:37:14'),
(34, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-21 06:32:39', 0, '2019-11-21 06:32:39'),
(35, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-21 14:02:32', 0, '2019-11-21 14:02:32'),
(36, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-22 05:52:04', 0, '2019-11-22 05:52:04'),
(37, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:26:25', 0, '2019-11-22 13:26:25'),
(38, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-22 13:35:43', 0, '2019-11-22 13:35:43'),
(39, 95, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:38:46', 0, '2019-11-22 13:38:46'),
(40, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:44:46', 0, '2019-11-22 13:44:46'),
(41, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:45:00', 0, '2019-11-22 13:45:00'),
(42, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:45:47', 0, '2019-11-22 13:45:47'),
(43, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:46:43', 0, '2019-11-22 13:46:43'),
(44, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:47:39', 0, '2019-11-22 13:47:39'),
(45, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:48:22', 0, '2019-11-22 13:48:22'),
(46, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:49:04', 0, '2019-11-22 13:49:04'),
(47, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:49:27', 0, '2019-11-22 13:49:27'),
(48, 102, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:52:59', 0, '2019-11-22 13:52:59'),
(49, 102, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:53:08', 0, '2019-11-22 13:53:08'),
(50, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:53:11', 0, '2019-11-22 13:53:11'),
(51, 102, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:53:23', 0, '2019-11-22 13:53:23'),
(52, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:55:11', 0, '2019-11-22 13:55:11'),
(53, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:56:38', 0, '2019-11-22 13:56:38'),
(54, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:57:42', 0, '2019-11-22 13:57:42'),
(55, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 13:59:50', 0, '2019-11-22 13:59:50'),
(56, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 14:11:11', 0, '2019-11-22 14:11:11'),
(57, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-22 14:19:18', 0, '2019-11-22 14:19:18'),
(58, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-22 14:30:35', 0, '2019-11-22 14:30:35'),
(59, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:33:28', 0, '2019-11-25 11:33:28'),
(60, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:34:08', 0, '2019-11-25 11:34:08'),
(61, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:34:16', 0, '2019-11-25 11:34:16'),
(62, 104, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:34:23', 0, '2019-11-25 11:34:23'),
(63, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:36:39', 0, '2019-11-25 11:36:39'),
(64, 104, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:36:44', 0, '2019-11-25 11:36:44'),
(65, 104, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:38:12', 0, '2019-11-25 11:38:12'),
(66, 104, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:38:51', 0, '2019-11-25 11:38:51'),
(67, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:38:59', 0, '2019-11-25 11:38:59'),
(68, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:46:44', 0, '2019-11-25 11:46:44'),
(69, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:48:43', 0, '2019-11-25 11:48:43'),
(70, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-25 11:48:52', 0, '2019-11-25 11:48:52'),
(71, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:50:02', 0, '2019-11-25 11:50:02'),
(72, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:54:25', 0, '2019-11-25 11:54:25'),
(73, 105, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:57:15', 0, '2019-11-25 11:57:15'),
(74, 105, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:58:34', 0, '2019-11-25 11:58:34'),
(75, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 11:59:48', 0, '2019-11-25 11:59:48'),
(76, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-25 12:00:03', 0, '2019-11-25 12:00:03'),
(77, 100, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:03:06', 0, '2019-11-25 12:03:06'),
(78, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:03:09', 0, '2019-11-25 12:03:09'),
(79, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:03:49', 0, '2019-11-25 12:03:49'),
(80, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:04:11', 0, '2019-11-25 12:04:11'),
(81, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-25 12:06:04', 0, '2019-11-25 12:06:04'),
(82, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:06:50', 0, '2019-11-25 12:06:50'),
(83, 103, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:14:28', 0, '2019-11-25 12:14:28'),
(84, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:32:42', 0, '2019-11-25 12:32:42'),
(85, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:33:36', 0, '2019-11-25 12:33:36'),
(86, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:34:40', 0, '2019-11-25 12:34:40'),
(87, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 12:36:38', 0, '2019-11-25 12:36:38'),
(88, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:12:17', 0, '2019-11-25 13:12:17'),
(89, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:13:52', 0, '2019-11-25 13:13:52'),
(90, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:24:07', 0, '2019-11-25 13:24:07'),
(91, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:33:08', 0, '2019-11-25 13:33:08'),
(92, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:33:36', 0, '2019-11-25 13:33:36'),
(93, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:34:02', 0, '2019-11-25 13:34:02'),
(94, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:37:48', 0, '2019-11-25 13:37:48'),
(95, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:45:20', 0, '2019-11-25 13:45:20'),
(96, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:45:41', 0, '2019-11-25 13:45:41'),
(97, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:48:29', 0, '2019-11-25 13:48:29'),
(98, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:53:16', 0, '2019-11-25 13:53:16'),
(99, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 13:55:59', 0, '2019-11-25 13:55:59'),
(100, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 14:02:06', 0, '2019-11-25 14:02:06'),
(101, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 14:03:04', 0, '2019-11-25 14:03:04'),
(102, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 14:04:04', 0, '2019-11-25 14:04:04'),
(103, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-25 14:23:35', 0, '2019-11-25 14:23:35'),
(104, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 05:49:19', 0, '2019-11-26 05:49:19'),
(105, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 05:50:05', 0, '2019-11-26 05:50:05'),
(106, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 05:50:24', 0, '2019-11-26 05:50:24'),
(107, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 05:51:11', 0, '2019-11-26 05:51:11'),
(108, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:05:15', 0, '2019-11-26 06:05:15'),
(109, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:05:57', 0, '2019-11-26 06:05:57'),
(110, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:06:32', 0, '2019-11-26 06:06:32'),
(111, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:06:55', 0, '2019-11-26 06:06:55'),
(112, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:09:29', 0, '2019-11-26 06:09:29'),
(113, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:10:01', 0, '2019-11-26 06:10:01'),
(114, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:12:55', 0, '2019-11-26 06:12:55'),
(115, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:15:16', 0, '2019-11-26 06:15:16'),
(116, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:38:39', 0, '2019-11-26 06:38:39'),
(117, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 06:39:48', 0, '2019-11-26 06:39:48'),
(118, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-26 06:51:53', 0, '2019-11-26 06:51:53'),
(119, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-26 07:03:07', 0, '2019-11-26 07:03:07'),
(120, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-26 11:31:45', 0, '2019-11-26 11:31:45'),
(121, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-26 11:32:16', 0, '2019-11-26 11:32:16'),
(122, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:42:49', 0, '2019-11-26 11:42:49'),
(123, 107, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:43:24', 0, '2019-11-26 11:43:24'),
(124, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:43:44', 0, '2019-11-26 11:43:44'),
(125, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:44:10', 0, '2019-11-26 11:44:10'),
(126, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:47:02', 0, '2019-11-26 11:47:02'),
(127, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:47:14', 0, '2019-11-26 11:47:14'),
(128, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:47:55', 0, '2019-11-26 11:47:55'),
(129, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:48:20', 0, '2019-11-26 11:48:20'),
(130, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 11:48:48', 0, '2019-11-26 11:48:48'),
(131, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 12:34:40', 0, '2019-11-26 12:34:40'),
(132, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 12:38:07', 0, '2019-11-26 12:38:07'),
(133, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 12:42:34', 0, '2019-11-26 12:42:34'),
(134, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 12:51:19', 0, '2019-11-26 12:51:19'),
(135, 106, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 14:28:42', 0, '2019-11-26 14:28:42'),
(136, 109, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 18:07:43', 0, '2019-11-26 18:07:43'),
(137, 99, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-26 18:11:20', 0, '2019-11-26 18:11:20'),
(138, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-27 04:49:40', 0, '2019-11-27 04:49:40'),
(139, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-27 06:13:10', 0, '2019-11-27 06:13:10'),
(140, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-27 07:15:39', 0, '2019-11-27 07:15:39'),
(141, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-27 07:15:47', 0, '2019-11-27 07:15:47'),
(142, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-27 08:11:20', 0, '2019-11-27 08:11:20'),
(143, 110, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-27 10:03:11', 0, '2019-11-27 10:03:11'),
(144, 111, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-27 21:04:12', 0, '2019-11-27 21:04:12'),
(145, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Handheld Browser on Unknown OS Platform', 0, '2019-11-27 21:10:39', 0, '2019-11-27 21:10:39'),
(146, 99, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-27 21:19:50', 0, '2019-11-27 21:19:50'),
(147, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 09:23:35', 0, '2019-11-28 09:23:35'),
(148, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 09:27:31', 0, '2019-11-28 09:27:31'),
(149, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 09:28:06', 0, '2019-11-28 09:28:06'),
(150, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 10:18:03', 0, '2019-11-28 10:18:03'),
(151, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 10:35:46', 0, '2019-11-28 10:35:46'),
(152, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:00:19', 0, '2019-11-28 12:00:19'),
(153, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:00:34', 0, '2019-11-28 12:00:34'),
(154, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:00:53', 0, '2019-11-28 12:00:53'),
(155, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:04:32', 0, '2019-11-28 12:04:32'),
(156, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:48:14', 0, '2019-11-28 12:48:14'),
(157, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:52:45', 0, '2019-11-28 12:52:45'),
(158, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-28 12:53:22', 0, '2019-11-28 12:53:22'),
(159, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:54:35', 0, '2019-11-28 12:54:35'),
(160, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:54:51', 0, '2019-11-28 12:54:51'),
(161, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:54:56', 0, '2019-11-28 12:54:56'),
(162, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:55:22', 0, '2019-11-28 12:55:22'),
(163, 113, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:56:33', 0, '2019-11-28 12:56:33'),
(164, 113, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 12:56:58', 0, '2019-11-28 12:56:58'),
(165, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:00:04', 0, '2019-11-28 13:00:04'),
(166, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:03:55', 0, '2019-11-28 13:03:55'),
(167, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:05:50', 0, '2019-11-28 13:05:50'),
(168, 113, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:11:54', 0, '2019-11-28 13:11:54'),
(169, 114, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:14:30', 0, '2019-11-28 13:14:30'),
(170, 114, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:15:31', 0, '2019-11-28 13:15:31'),
(171, 114, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:15:48', 0, '2019-11-28 13:15:48'),
(172, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:16:07', 0, '2019-11-28 13:16:07'),
(173, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:16:18', 0, '2019-11-28 13:16:18'),
(174, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 13:55:58', 0, '2019-11-28 13:55:58'),
(175, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 14:01:33', 0, '2019-11-28 14:01:33'),
(176, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 14:04:28', 0, '2019-11-28 14:04:28'),
(177, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 14:09:30', 0, '2019-11-28 14:09:30'),
(178, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-28 14:10:12', 0, '2019-11-28 14:10:12'),
(179, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 06:45:24', 0, '2019-11-29 06:45:24'),
(180, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-29 06:56:18', 0, '2019-11-29 06:56:18'),
(181, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:14:15', 0, '2019-11-29 10:14:15'),
(182, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:20:39', 0, '2019-11-29 10:20:39'),
(183, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-11-29 10:20:52', 0, '2019-11-29 10:20:52'),
(184, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:22:08', 0, '2019-11-29 10:22:08'),
(185, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:33:23', 0, '2019-11-29 10:33:23'),
(186, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:33:39', 0, '2019-11-29 10:33:39'),
(187, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:34:46', 0, '2019-11-29 10:34:46'),
(188, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:35:34', 0, '2019-11-29 10:35:34'),
(189, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:36:30', 0, '2019-11-29 10:36:30'),
(190, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:36:49', 0, '2019-11-29 10:36:49'),
(191, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:36:59', 0, '2019-11-29 10:36:59'),
(192, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:37:07', 0, '2019-11-29 10:37:07'),
(193, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:37:17', 0, '2019-11-29 10:37:17'),
(194, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:39:59', 0, '2019-11-29 10:39:59'),
(195, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:42:50', 0, '2019-11-29 10:42:50'),
(196, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:49:27', 0, '2019-11-29 10:49:27'),
(197, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:49:50', 0, '2019-11-29 10:49:50'),
(198, 116, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 10:50:14', 0, '2019-11-29 10:50:14'),
(199, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 11:21:34', 0, '2019-11-29 11:21:34'),
(200, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 11:23:50', 0, '2019-11-29 11:23:50'),
(201, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 11:24:38', 0, '2019-11-29 11:24:38'),
(202, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 11:25:28', 0, '2019-11-29 11:25:28'),
(203, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 12:12:17', 0, '2019-11-29 12:12:17'),
(204, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 12:16:36', 0, '2019-11-29 12:16:36'),
(205, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 12:40:11', 0, '2019-11-29 12:40:11'),
(206, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 15:01:00', 0, '2019-11-29 15:01:00'),
(207, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 15:30:36', 0, '2019-11-29 15:30:36'),
(208, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-29 16:28:06', 0, '2019-11-29 16:28:06'),
(209, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-11-30 19:19:25', 0, '2019-11-30 19:19:25'),
(210, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-01 13:50:13', 0, '2019-12-01 13:50:13'),
(211, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 08:10:45', 0, '2019-12-02 08:10:45'),
(212, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 08:45:30', 0, '2019-12-02 08:45:30'),
(213, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 08:47:05', 0, '2019-12-02 08:47:05'),
(214, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 09:31:03', 0, '2019-12-02 09:31:03'),
(215, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 09:31:15', 0, '2019-12-02 09:31:15'),
(216, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 09:31:58', 0, '2019-12-02 09:31:58'),
(217, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 11:27:18', 0, '2019-12-02 11:27:18'),
(218, 112, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 11:29:17', 0, '2019-12-02 11:29:17'),
(219, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:11:33', 0, '2019-12-02 12:11:33'),
(220, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:13:06', 0, '2019-12-02 12:13:06'),
(221, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:13:33', 0, '2019-12-02 12:13:33'),
(222, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:24:27', 0, '2019-12-02 12:24:27'),
(223, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:35:16', 0, '2019-12-02 12:35:16'),
(224, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 12:36:10', 0, '2019-12-02 12:36:10'),
(225, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:06:42', 0, '2019-12-02 13:06:42'),
(226, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:07:12', 0, '2019-12-02 13:07:12'),
(227, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:07:34', 0, '2019-12-02 13:07:34'),
(228, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:07:40', 0, '2019-12-02 13:07:40'),
(229, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:13:28', 0, '2019-12-02 13:13:28'),
(230, 120, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:17:27', 0, '2019-12-02 13:17:27'),
(231, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:22:45', 0, '2019-12-02 13:22:45'),
(232, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:30:19', 0, '2019-12-02 13:30:19'),
(233, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 13:51:57', 0, '2019-12-02 13:51:57'),
(234, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:02:26', 0, '2019-12-02 14:02:26'),
(235, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:22:57', 0, '2019-12-02 14:22:57'),
(236, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:23:34', 0, '2019-12-02 14:23:34'),
(237, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:24:23', 0, '2019-12-02 14:24:23'),
(238, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:25:12', 0, '2019-12-02 14:25:12'),
(239, 118, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 14:47:21', 0, '2019-12-02 14:47:21'),
(240, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 21:33:51', 0, '2019-12-02 21:33:51'),
(241, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-02 21:34:57', 0, '2019-12-02 21:34:57'),
(242, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 05:59:30', 0, '2019-12-03 05:59:30'),
(243, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 05:59:44', 0, '2019-12-03 05:59:44'),
(244, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:00:57', 0, '2019-12-03 06:00:57'),
(245, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:02:09', 0, '2019-12-03 06:02:09'),
(246, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:02:33', 0, '2019-12-03 06:02:33'),
(247, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:19:06', 0, '2019-12-03 06:19:06'),
(248, 121, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:19:26', 0, '2019-12-03 06:19:26'),
(249, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:23:55', 0, '2019-12-03 06:23:55'),
(250, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:25:03', 0, '2019-12-03 06:25:03'),
(251, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:25:54', 0, '2019-12-03 06:25:54'),
(252, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:26:01', 0, '2019-12-03 06:26:01'),
(253, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:26:24', 0, '2019-12-03 06:26:24'),
(254, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:26:48', 0, '2019-12-03 06:26:48'),
(255, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:28:20', 0, '2019-12-03 06:28:20'),
(256, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:29:35', 0, '2019-12-03 06:29:35'),
(257, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:29:54', 0, '2019-12-03 06:29:54'),
(258, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:30:16', 0, '2019-12-03 06:30:16'),
(259, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:42:39', 0, '2019-12-03 06:42:39'),
(260, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:45:20', 0, '2019-12-03 06:45:20'),
(261, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:48:31', 0, '2019-12-03 06:48:31'),
(262, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:50:58', 0, '2019-12-03 06:50:58'),
(263, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:53:12', 0, '2019-12-03 06:53:12'),
(264, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 06:53:36', 0, '2019-12-03 06:53:36'),
(265, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 07:48:12', 0, '2019-12-03 07:48:12'),
(266, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 07:48:19', 0, '2019-12-03 07:48:19'),
(267, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 07:48:27', 0, '2019-12-03 07:48:27'),
(268, 115, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 07:54:32', 0, '2019-12-03 07:54:32'),
(269, 108, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 07:58:18', 0, '2019-12-03 07:58:18'),
(270, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 08:11:59', 0, '2019-12-03 08:11:59'),
(271, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 08:13:13', 0, '2019-12-03 08:13:13'),
(272, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 08:16:05', 0, '2019-12-03 08:16:05'),
(273, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 08:24:42', 0, '2019-12-03 08:24:42'),
(274, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:12:40', 0, '2019-12-03 09:12:40'),
(275, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:13:29', 0, '2019-12-03 09:13:29'),
(276, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:16:24', 0, '2019-12-03 09:16:24'),
(277, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:21:11', 0, '2019-12-03 09:21:11'),
(278, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:22:34', 0, '2019-12-03 09:22:34'),
(279, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:24:02', 0, '2019-12-03 09:24:02'),
(280, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:27:05', 0, '2019-12-03 09:27:05'),
(281, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:27:06', 0, '2019-12-03 09:27:06'),
(282, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:32:34', 0, '2019-12-03 09:32:34'),
(283, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:34:23', 0, '2019-12-03 09:34:23'),
(284, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:34:42', 0, '2019-12-03 09:34:42'),
(285, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:44:24', 0, '2019-12-03 09:44:24'),
(286, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:45:36', 0, '2019-12-03 09:45:36'),
(287, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:45:52', 0, '2019-12-03 09:45:52'),
(288, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 09:55:50', 0, '2019-12-03 09:55:50'),
(289, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 10:01:02', 0, '2019-12-03 10:01:02'),
(290, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 10:07:44', 0, '2019-12-03 10:07:44'),
(291, 122, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 10:11:46', 0, '2019-12-03 10:11:46'),
(292, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 11:41:40', 0, '2019-12-03 11:41:40'),
(293, 119, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 11:42:01', 0, '2019-12-03 11:42:01'),
(294, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 11:42:23', 0, '2019-12-03 11:42:23'),
(295, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Handheld Browser on Unknown OS Platform', 0, '2019-12-03 13:06:49', 0, '2019-12-03 13:06:49');
INSERT INTO `User_Logins` (`Id`, `User_Id`, `Ip_Address`, `Location`, `User_Os_Platform`, `Created_By`, `Creation_Date_Time`, `Last_Updated_By`, `Last_Updated_Date_Time`) VALUES
(296, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 15:57:26', 0, '2019-12-03 15:57:26'),
(297, 123, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 15:58:04', 0, '2019-12-03 15:58:04'),
(298, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 16:16:51', 0, '2019-12-03 16:16:51'),
(299, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-03 16:31:00', 0, '2019-12-03 16:31:00'),
(300, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-12-04 09:13:59', 0, '2019-12-04 09:13:59'),
(301, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Handheld Browser on Unknown OS Platform', 0, '2019-12-04 10:13:36', 0, '2019-12-04 10:13:36'),
(302, 124, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-04 11:28:29', 0, '2019-12-04 11:28:29'),
(303, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-12-04 11:29:33', 0, '2019-12-04 11:29:33'),
(304, 124, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-04 11:29:58', 0, '2019-12-04 11:29:58'),
(305, 124, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-04 11:30:39', 0, '2019-12-04 11:30:39'),
(306, 124, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-04 11:32:34', 0, '2019-12-04 11:32:34'),
(307, 96, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-04 17:59:52', 0, '2019-12-04 17:59:52'),
(308, 117, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-05 15:49:31', 0, '2019-12-05 15:49:31'),
(309, 101, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-09 07:44:26', 0, '2019-12-09 07:44:26'),
(310, 1, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Chrome on Unknown OS Platform', 0, '2019-12-09 10:12:56', 0, '2019-12-09 10:12:56'),
(311, 126, 'etippers.consagous.co.in', 'Indore, Madhya Pradesh, India (IN)', 'Unknown Browser on Unknown OS Platform', 0, '2019-12-09 10:20:29', 0, '2019-12-09 10:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `User_Notifications`
--

CREATE TABLE IF NOT EXISTS `User_Notifications` (
  `Id` int(11) NOT NULL,
  `Req_Id` int(11) NOT NULL DEFAULT '0',
  `Recepient_Id` int(11) NOT NULL DEFAULT '0',
  `Notification_Text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Tran_Type_Id` int(11) NOT NULL DEFAULT '0',
  `Is_Read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0  =  not read , 1 =  read',
  `Is_Deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not deleted , 1= deleted',
  `Read_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Notifications`
--

INSERT INTO `User_Notifications` (`Id`, `Req_Id`, `Recepient_Id`, `Notification_Text`, `Tran_Type_Id`, `Is_Read`, `Is_Deleted`, `Read_Date_Time`) VALUES
(1, 0, 94, 'Dear Test 1, You have successfully sent $25.00 to Test 2 7778889996 on 14/11/2019  at 14:31 PM Ref.No: G5907A0W200S Transaction cost $0.00', 3, 1, 0, '2019-11-14 14:31:49'),
(2, 0, 95, 'Dear Test 2, You have successfully received $25.00 from Test 1 8889996665 on 14-11-2019  at 14:31 PM Ref.No: G5907A0W200S Your new balance is $10025.00', 4, 0, 0, '2019-11-14 14:31:49'),
(3, 0, 96, 'Dear Reginald Nelson, You have successfully sent $0.04 to Test 2 7778889996 on 15/11/2019  at 09:54 AM Ref.No: WC9A56FM796R Transaction cost $0.00', 3, 1, 1, '2019-11-15 09:54:20'),
(4, 0, 95, 'Dear Test 2, You have successfully received $0.04 from Reginald nelson 9177713738 on 15-11-2019  at 09:54 AM Ref.No: WC9A56FM796R Your new balance is $10025.04', 4, 0, 0, '2019-11-15 09:54:20'),
(5, 0, 96, 'Dear Reginald Nelson, You have successfully sent $0.10 to Test 2 7778889996 on 15/11/2019  at 10:29 AM Ref.No: VBEFE8BB991T Transaction cost $0.00', 3, 0, 0, '2019-11-15 10:29:32'),
(6, 0, 95, 'Dear Test 2, You have successfully received $0.10 from Reginald nelson 9177713738 on 15-11-2019  at 10:29 AM Ref.No: VBEFE8BB991T Your new balance is $10025.14', 4, 0, 0, '2019-11-15 10:29:32'),
(7, 0, 94, 'Dear Test 1, You have successfully sent $1,000.00 to Deepak sharma 9399982216 on 19/11/2019  at 11:19 AM Ref.No: S4CE90CP180K Transaction cost $0.00', 3, 1, 0, '2019-11-19 11:19:01'),
(8, 0, 98, 'Dear Deepak sharma, You have successfully received $1,000.00 from Test 1 8889996665 on 19-11-2019  at 11:19 AM Ref.No: S4CE90CP180K Your new balance is $1000.00', 4, 1, 0, '2019-11-19 11:19:01'),
(9, 0, 98, 'Dear Deepak Sharma, You have successfully sent $1.00 to Test 2 7778889996 on 20/11/2019  at 05:05 AM Ref.No: XFA96CFU476P Transaction cost $0.00', 3, 1, 0, '2019-11-20 05:05:36'),
(10, 0, 95, 'Dear Test 2, You have successfully received $1.00 from Deepak sharma 9399982216 on 20-11-2019  at 05:05 AM Ref.No: XFA96CFU476P Your new balance is $10026.14', 4, 0, 0, '2019-11-20 05:05:36'),
(11, 0, 98, 'Dear Deepak Sharma, You have successfully sent $800.00 to Alain charles 9736988498 on 20/11/2019  at 05:16 AM Ref.No: Z5E312AW763Z Transaction cost $0.00', 3, 1, 0, '2019-11-20 05:16:38'),
(12, 0, 99, 'Dear Alain charles, You have successfully received $800.00 from Deepak sharma 9399982216 on 20-11-2019  at 05:16 AM Ref.No: Z5E312AW763Z Your new balance is $800.00', 4, 1, 0, '2019-11-20 05:16:38'),
(13, 0, 96, 'Dear Reginald Nelson, You have successfully sent $0.12 to Alain charles 9736988498 on 20/11/2019  at 05:16 AM Ref.No: HB32CDAC242A Transaction cost $0.00', 3, 0, 0, '2019-11-20 05:16:43'),
(14, 0, 99, 'Dear Alain charles, You have successfully received $0.12 from Reginald nelson 9177713738 on 20-11-2019  at 05:16 AM Ref.No: HB32CDAC242A Your new balance is $800.12', 4, 1, 0, '2019-11-20 05:16:43'),
(15, 0, 99, 'Dear Alain Charles, You have successfully sent $53.00 to Deepak sharma 9399982216 on 20/11/2019  at 05:22 AM Ref.No: N13D847B749O Transaction cost $0.00', 3, 1, 0, '2019-11-20 05:22:58'),
(16, 0, 98, 'Dear Deepak sharma, You have successfully received $53.00 from Alain charles 9736988498 on 20-11-2019  at 05:22 AM Ref.No: N13D847B749O Your new balance is $252.00', 4, 0, 0, '2019-11-20 05:22:58'),
(17, 0, 96, 'Dear Reginald Nelson, You have successfully sent $50.00 to Alain charles 9736988498 on 20/11/2019  at 05:30 AM Ref.No: F367573E716V Transaction cost $0.00', 3, 0, 0, '2019-11-20 05:30:44'),
(18, 0, 99, 'Dear Alain charles, You have successfully received $50.00 from Reginald nelson 9177713738 on 20-11-2019  at 05:30 AM Ref.No: F367573E716V Your new balance is $797.12', 4, 1, 0, '2019-11-20 05:30:45'),
(19, 0, 99, 'Dear Alain Charles, You have successfully sent $11.00 to Test 2 7778889996 on 20/11/2019  at 05:32 AM Ref.No: A2CEF09H698N Transaction cost $0.00', 3, 1, 0, '2019-11-20 05:32:36'),
(20, 0, 98, 'Dear Deepak Sharma, You have successfully sent $11.00 to Test 2 7778889996 on 20/11/2019  at 05:32 AM Ref.No: B2CF008G953A Transaction cost $0.00', 3, 0, 0, '2019-11-20 05:32:36'),
(21, 0, 95, 'Dear Test 2, You have successfully received $11.00 from Alain charles 9736988498 on 20-11-2019  at 05:32 AM Ref.No: A2CEF09H698N Your new balance is $10037.14', 4, 0, 0, '2019-11-20 05:32:36'),
(22, 0, 95, 'Dear Test 2, You have successfully received $11.00 from Deepak sharma 9399982216 on 20-11-2019  at 05:32 AM Ref.No: B2CF008G953A Your new balance is $10037.14', 4, 0, 0, '2019-11-20 05:32:37'),
(23, 0, 96, 'Dear Reginald Nelson, You have successfully sent $2.20 to Test 2 7778889996 on 29/11/2019  at 12:41 PM Ref.No: K96D77EB710H Transaction cost $0.00', 3, 0, 0, '2019-11-29 12:41:13'),
(24, 0, 95, 'Dear Test 2, You have successfully received $2.20 from Reginald nelson 9177713738 on 29-11-2019  at 12:41 PM Ref.No: K96D77EB710H Your new balance is $10039.34', 4, 0, 0, '2019-11-29 12:41:13'),
(25, 0, 96, 'Dear Reginald Nelson, You have successfully sent $1.50 to Test 2 7778889996 on 02/12/2019  at 21:34 PM Ref.No: S6CE39EK963B Transaction cost $0.00', 3, 0, 0, '2019-12-02 21:34:30'),
(26, 0, 95, 'Dear Test 2, You have successfully received $1.50 from Reginald nelson 9177713738 on 02-12-2019  at 21:34 PM Ref.No: S6CE39EK963B Your new balance is $10040.84', 4, 0, 0, '2019-12-02 21:34:30'),
(27, 0, 96, 'Dear Reginald Nelson, You have successfully sent $20.00 to Leon charles 6462627000 on 03/12/2019  at 16:36 PM Ref.No: B7B05C1Q209G Transaction cost $0.00', 3, 0, 0, '2019-12-03 16:36:55'),
(28, 0, 123, 'Dear Leon charles, You have successfully received $20.00 from Reginald nelson 9177713738 on 03-12-2019  at 16:36 PM Ref.No: B7B05C1Q209G Your new balance is $20.00', 4, 0, 0, '2019-12-03 16:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `User_Otp`
--

CREATE TABLE IF NOT EXISTS `User_Otp` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) DEFAULT '0',
  `Send_Otp` varchar(255) DEFAULT '',
  `Otp_For` varchar(100) DEFAULT '',
  `Send_Date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `User_Payment_Methods`
--

CREATE TABLE IF NOT EXISTS `User_Payment_Methods` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL DEFAULT '0',
  `Card_Bank_No` varchar(255) NOT NULL DEFAULT '',
  `Token_No` varchar(255) NOT NULL DEFAULT '',
  `Expiry_Month_Year` varchar(255) NOT NULL DEFAULT '00-00',
  `Bank_Name` varchar(100) NOT NULL DEFAULT '',
  `Acc_Holder_Name` varchar(150) NOT NULL DEFAULT '',
  `Branch_Name` varchar(255) NOT NULL DEFAULT '',
  `Account_No` varchar(255) NOT NULL DEFAULT '',
  `OB_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Is_Active` tinyint(1) NOT NULL DEFAULT '1',
  `Is_Blocked` tinyint(1) NOT NULL DEFAULT '0',
  `Blocked_Reason` varchar(255) NOT NULL DEFAULT '',
  `card_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=visa,2=master',
  `Is_Bank` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Debit_Card` tinyint(1) NOT NULL DEFAULT '0',
  `Is_Credit_Card` tinyint(1) NOT NULL DEFAULT '0',
  `Logo_Image_Name` varchar(255) NOT NULL DEFAULT '',
  `Is_Deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = NO ,1= YES',
  `Created_By` int(11) NOT NULL DEFAULT '0',
  `Creation_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated_By` int(11) NOT NULL DEFAULT '0',
  `Last_Updated_Date_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin_Roles_Permission`
--
ALTER TABLE `Admin_Roles_Permission`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Charges_Type_Master`
--
ALTER TABLE `Charges_Type_Master`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Cities`
--
ALTER TABLE `Cities`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Countries`
--
-- ALTER TABLE `Countries`
--   ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Document_Type`
--
ALTER TABLE `Document_Type`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Fee`
--
ALTER TABLE `Fee`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `General_Setting`
--
ALTER TABLE `General_Setting`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Manage_Advertisement_Images`
--
ALTER TABLE `Manage_Advertisement_Images`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `manage_notification_mail`
--
ALTER TABLE `manage_notification_mail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Notification_Templates`
--
ALTER TABLE `Notification_Templates`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Options`
--
ALTER TABLE `Options`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `organisation_types`
--
ALTER TABLE `organisation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Organization_Details`
--
ALTER TABLE `Organization_Details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Refferal_Points_Settings`
--
ALTER TABLE `Refferal_Points_Settings`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Scheduled_Notifications`
--
ALTER TABLE `Scheduled_Notifications`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `States`
--
ALTER TABLE `States`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `static_page`
--
ALTER TABLE `static_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Transactions`
--
ALTER TABLE `Transactions`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Transactions_Limit`
--
ALTER TABLE `Transactions_Limit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Tran_Charges`
--
ALTER TABLE `Tran_Charges`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Tran_Status`
--
ALTER TABLE `Tran_Status`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Tran_Types`
--
ALTER TABLE `Tran_Types`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `unreadCount`
--
ALTER TABLE `unreadCount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Users_Documents`
--
ALTER TABLE `Users_Documents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Users_Referrals`
--
ALTER TABLE `Users_Referrals`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_Billers`
--
ALTER TABLE `User_Billers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_Feedback`
--
ALTER TABLE `User_Feedback`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_In_Roles`
--
ALTER TABLE `User_In_Roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_Logins`
--
ALTER TABLE `User_Logins`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_Notifications`
--
ALTER TABLE `User_Notifications`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `User_Otp`
--
ALTER TABLE `User_Otp`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `User_Payment_Methods`
--
ALTER TABLE `User_Payment_Methods`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin_Roles_Permission`
--
ALTER TABLE `Admin_Roles_Permission`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Charges_Type_Master`
--
ALTER TABLE `Charges_Type_Master`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Cities`
--
ALTER TABLE `Cities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Countries`
--
-- ALTER TABLE `Countries`
--   MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT for table `Document_Type`
--
ALTER TABLE `Document_Type`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Fee`
--
ALTER TABLE `Fee`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `General_Setting`
--
ALTER TABLE `General_Setting`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Manage_Advertisement_Images`
--
ALTER TABLE `Manage_Advertisement_Images`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `manage_notification_mail`
--
ALTER TABLE `manage_notification_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `Notification_Templates`
--
ALTER TABLE `Notification_Templates`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Options`
--
ALTER TABLE `Options`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `organisation_types`
--
ALTER TABLE `organisation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Organization_Details`
--
ALTER TABLE `Organization_Details`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Refferal_Points_Settings`
--
ALTER TABLE `Refferal_Points_Settings`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Scheduled_Notifications`
--
ALTER TABLE `Scheduled_Notifications`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `States`
--
ALTER TABLE `States`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `static_page`
--
ALTER TABLE `static_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Transactions`
--
ALTER TABLE `Transactions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `Transactions_Limit`
--
ALTER TABLE `Transactions_Limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Tran_Charges`
--
ALTER TABLE `Tran_Charges`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `Tran_Status`
--
ALTER TABLE `Tran_Status`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `Tran_Types`
--
ALTER TABLE `Tran_Types`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `unreadCount`
--
ALTER TABLE `unreadCount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `Users_Documents`
--
ALTER TABLE `Users_Documents`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `Users_Referrals`
--
ALTER TABLE `Users_Referrals`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `User_Billers`
--
ALTER TABLE `User_Billers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `User_Feedback`
--
ALTER TABLE `User_Feedback`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `User_In_Roles`
--
ALTER TABLE `User_In_Roles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `User_Logins`
--
ALTER TABLE `User_Logins`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=312;
--
-- AUTO_INCREMENT for table `User_Notifications`
--
ALTER TABLE `User_Notifications`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `User_Otp`
--
ALTER TABLE `User_Otp`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `User_Payment_Methods`
--
ALTER TABLE `User_Payment_Methods`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
