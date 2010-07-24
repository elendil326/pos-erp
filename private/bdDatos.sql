-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-07-2010 a las 05:23:04
-- Versión del servidor: 5.1.37
-- Versión de PHP: 5.3.0

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del cliente',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del cliente si es que tiene',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del cliente',
  `direccion` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'domicilio del cliente calle, no, colonia',
  `telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefono del cliete',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'dias de credito para que pague el cliente',
  `limite_credito` float NOT NULL DEFAULT '0' COMMENT 'Limite de credito otorgado al cliente',
  `descuento` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Taza porcentual de descuento de 0 a 100',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=201 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `limite_credito`, `descuento`) VALUES
(1, 'AFJ19INT3JI', 'Clementine Manning', '992-3353 Euismod Street', '(777) 586-8657', 'adipiscing.elit.Curabitur@elitelit.edu', 25563, 0),
(2, 'EEN95XGS4ZA', 'September Whitaker', 'Ap #147-755 Montes, Rd.', '(569) 226-0218', 'gravida.non@lacusEtiambibendum.edu', 10101, 26),
(3, 'SFV46VKN7TB', 'Kimberly Rocha', '154-2822 Non, St.', '(772) 180-4981', 'mollis@vitaerisusDuis.com', 17321, 9),
(4, 'DKP61IRR1UT', 'MacKenzie Shelton', 'Ap #375-3459 Urna. Street', '(538) 429-4572', 'amet.ornare.lectus@egetodio.org', 2454, 34),
(5, 'WKK55EHC0IL', 'Madeson Moon', 'P.O. Box 659, 4605 Etiam St.', '(369) 832-8679', 'a@augueidante.edu', 3219, 33),
(6, 'THA83JHY9CZ', 'Alana Parker', '454-1969 Faucibus Road', '(660) 339-5167', 'tortor.dictum@Integermollis.edu', 15257, 42),
(7, 'KWF38HIW0TF', 'Germane Chan', '4262 Aliquet. Avenue', '(313) 820-5680', 'Nunc.ac.sem@pretiumaliquet.com', 12906, 38),
(8, 'QHD91DTJ4XT', 'Irma Odom', '5688 Purus. Rd.', '(406) 164-0336', 'quis@iaculis.com', 10734, 44),
(9, 'INC08OYP9QU', 'Maggy House', 'Ap #971-755 Ac St.', '(241) 687-5584', 'faucibus.orci.luctus@ornareFusce.org', 15693, 13),
(10, 'PCG10XZR3HS', 'Shelly Estrada', 'Ap #934-6679 Dolor St.', '(572) 156-0812', 'malesuada.vel@aenim.org', 23593, 15),
(11, 'URH40PEW8YY', 'Madonna Stephenson', 'Ap #869-2606 Tincidunt St.', '(252) 165-5207', 'mauris.ipsum.porta@placerat.org', 10578, 10),
(12, 'NZK38IHL2XP', 'Roanna Ramirez', '209-9933 Blandit. Avenue', '(965) 362-2430', 'lacus.Etiam.bibendum@lorem.org', 18810, 40),
(13, 'MZE91BNZ8KY', 'Beverly Lambert', 'P.O. Box 554, 2937 Dui. St.', '(875) 305-3778', 'malesuada.augue@Pellentesqueutipsum.com', 25364, 34),
(14, 'NXH57RLV1KW', 'Guinevere Valencia', 'Ap #700-4494 Ut St.', '(727) 595-8326', 'mollis.Integer.tincidunt@aptenttaciti.ca', 19935, 35),
(15, 'RIF45QJW5NZ', 'Daria Christensen', '265-4464 Enim Avenue', '(472) 798-4027', 'Vivamus@Vestibulumante.com', 20749, 10),
(16, 'VIP68CRC2CB', 'Martina Estrada', 'P.O. Box 782, 6958 Sed Avenue', '(961) 198-0837', 'ullamcorper@gravida.com', 18118, 29),
(17, 'FRH29JJP6WO', 'Mikayla Mcmillan', '449-6881 Cras Rd.', '(227) 155-2552', 'nec.malesuada.ut@CraspellentesqueSed.ca', 3661, 18),
(18, 'VMT41PYU2FL', 'Kaitlin Mcdonald', '8968 Vitae Ave', '(734) 990-1820', 'nibh.Quisque.nonummy@Nam.ca', 19368, 6),
(19, 'IUT25YMB4BE', 'Belle Talley', '580-8290 Arcu. Av.', '(350) 270-1814', 'aliquam.iaculis.lacus@pellentesqueSed.org', 11219, 17),
(20, 'FIW27XLW8NS', 'Barbara Bishop', 'Ap #584-4614 Montes, Road', '(455) 370-1662', 'dapibus@sodalesnisi.com', 13632, 33),
(21, 'DJF91TRH8FP', 'Jescie Puckett', '661-1909 Pellentesque Av.', '(162) 735-9414', 'Integer.id.magna@dui.ca', 10619, 43),
(22, 'CCQ38VZI5BF', 'Tanya Noel', '712-7158 Est. Avenue', '(716) 220-9829', 'facilisis.magna.tellus@atliberoMorbi.org', 14095, 12),
(23, 'PHG98IDU9NU', 'Haley Contreras', '240-1962 Felis. St.', '(721) 540-3082', 'ac.risus.Morbi@felisullamcorper.org', 22455, 12),
(24, 'NBK30XCJ1OL', 'Kaye Pierce', '7616 Eget St.', '(747) 156-2306', 'Aliquam.nisl.Nulla@vitaealiquet.edu', 15248, 17),
(25, 'NOH69KEJ8XZ', 'Anjolie Strickland', '596-7633 Nec Ave', '(929) 326-9919', 'malesuada@Ut.edu', 17355, 14),
(26, 'DZM48KMY2GR', 'Martina Roman', 'P.O. Box 475, 7793 Bibendum. Rd.', '(307) 326-4891', 'sit@rhoncusid.com', 28787, 29),
(27, 'JJD26JYH1KV', 'Charlotte Sargent', '3203 Magna. Rd.', '(719) 298-3937', 'metus@congueelitsed.edu', 2663, 40),
(28, 'NTG40JWV5RL', 'Alika Mccray', 'Ap #727-9108 Lacinia. Ave', '(652) 699-7864', 'libero@idmagna.org', 17688, 0),
(29, 'OZW35OVO1OI', 'Shellie Edwards', '4376 Urna Rd.', '(284) 861-3687', 'sed@Nunc.ca', 8646, 7),
(30, 'JVS13WTR7AX', 'Diana Snider', 'Ap #772-2725 Ligula Road', '(386) 173-2165', 'a@dapibusidblandit.ca', 13048, 32),
(31, 'EIL80MTH3CR', 'Yvette Sherman', 'Ap #620-2912 Dolor, Street', '(595) 660-7572', 'luctus.et@penatibusetmagnis.org', 13119, 16),
(32, 'PXD82FOF0NM', 'Quemby Boyle', 'P.O. Box 388, 8261 Etiam St.', '(645) 228-5480', 'tellus.Nunc.lectus@nonenim.edu', 17520, 21),
(33, 'LUA68NDP6XV', 'Mia Dean', 'Ap #311-5229 At, Street', '(810) 391-2268', 'amet.diam@Nunc.edu', 9122, 26),
(34, 'XVS54QID8YL', 'Keelie Conley', '136-6146 Dolor, Road', '(328) 686-0190', 'ut@accumsansed.edu', 10076, 25),
(35, 'PZW78VDI0CQ', 'Hayfa Burnett', '211-2435 Taciti Rd.', '(383) 473-5095', 'sollicitudin@tincidunt.com', 754, 20),
(36, 'CWO49RBG7IX', 'Buffy Hubbard', '513-7323 Lorem Road', '(182) 188-2199', 'accumsan.interdum@Cumsociis.ca', 29506, 42),
(37, 'TQA04IXO7AX', 'Amber Kent', '906-1645 Natoque St.', '(273) 373-6671', 'egestas.lacinia@montes.com', 3796, 15),
(38, 'ULG32HGH7XJ', 'Blythe Russell', 'Ap #909-5031 Sit Street', '(321) 903-4748', 'aliquet@feugiat.edu', 12716, 13),
(39, 'ISO54MYP2LV', 'Roanna Gordon', 'Ap #559-9642 Pharetra. St.', '(684) 432-5944', 'nibh@eleifendvitaeerat.org', 9940, 44),
(40, 'CUX60JNW7WX', 'Ginger Guzman', 'P.O. Box 391, 3312 Et, Avenue', '(668) 603-6910', 'orci.in@Duiscursusdiam.edu', 26689, 14),
(41, 'HGV19SBG0YF', 'Gisela Dunn', '3941 Sagittis Avenue', '(597) 288-2322', 'felis@diam.ca', 10773, 6),
(42, 'YIV05XYU2FI', 'Cathleen Booth', 'P.O. Box 488, 9081 Nunc Ave', '(821) 353-2045', 'tempor.erat.neque@laciniaSedcongue.edu', 17226, 35),
(43, 'TMT57YWW0OP', 'Anastasia Wynn', 'P.O. Box 666, 2056 In Avenue', '(794) 740-5372', 'nulla.Integer@facilisi.ca', 24068, 34),
(44, 'OTP47DAW1NU', 'India Cannon', 'P.O. Box 504, 9108 Nec, St.', '(654) 937-4686', 'Phasellus.elit@egestasa.com', 29056, 25),
(45, 'EDO02CUR2UV', 'Hilary Day', 'Ap #260-9257 In Avenue', '(193) 779-2333', 'arcu.eu.odio@interdumNuncsollicitudin.com', 15596, 44),
(46, 'SPU38OEE7XE', 'Kirsten Boone', 'P.O. Box 497, 3246 Nonummy Rd.', '(746) 279-9198', 'Duis.elementum@tellusNunclectus.edu', 17829, 31),
(47, 'XDW09UGL8IA', 'Summer Robertson', 'P.O. Box 944, 8072 Ac Rd.', '(197) 285-7340', 'posuere.enim@maurisMorbinon.edu', 17612, 16),
(48, 'BJP52YNC7WT', 'Vielka Roy', 'Ap #359-884 Arcu. Avenue', '(688) 928-3256', 'sagittis.semper@elementumduiquis.com', 17251, 44),
(49, 'SNM82JPE1ED', 'Sybil Hudson', '103-4285 Lobortis Rd.', '(687) 833-2537', 'sem@pedeblandit.com', 4596, 26),
(50, 'RNE86IZN7EA', 'Bree Clements', 'P.O. Box 279, 5417 Nulla. Ave', '(457) 984-9716', 'ornare@enim.ca', 11978, 33),
(51, 'LTS95WAQ0MI', 'Ariel Nieves', 'P.O. Box 685, 3731 Aliquam Rd.', '(212) 929-1510', 'augue.malesuada@maurisipsumporta.com', 13257, 37),
(52, 'VIV43IUL9RX', 'Cynthia Gardner', '601-2414 Tempus Street', '(743) 363-6205', 'iaculis.nec@et.ca', 5091, 36),
(53, 'GMP64HPR5WA', 'Angelica Hoffman', '5950 Mollis. Av.', '(159) 770-2255', 'odio.a@ProinvelitSed.org', 7507, 45),
(54, 'JSG93WWK9HQ', 'Ocean Conrad', '1770 Sed Av.', '(652) 888-4422', 'mi.Aliquam.gravida@laciniamattis.com', 5112, 3),
(55, 'IOY19WLO5QU', 'Ignacia Henson', '1885 Sem Avenue', '(879) 312-4928', 'magna.Praesent.interdum@lorem.ca', 18424, 35),
(56, 'COQ51FFY5PX', 'Lacota Macdonald', '157-5491 Fames Street', '(539) 458-1190', 'nisl@liberoestcongue.edu', 13402, 32),
(57, 'FRX12NBU0CC', 'Ila Clark', 'Ap #665-2797 Etiam Av.', '(144) 335-3934', 'sagittis@dolor.com', 3628, 18),
(58, 'RQL47OOC7FY', 'Debra Smith', '709-5601 Eu, Street', '(718) 643-6259', 'pellentesque.massa@vel.com', 22347, 36),
(59, 'BHJ10PCG4HO', 'Regina Whitley', 'P.O. Box 996, 1852 Dolor. Rd.', '(638) 897-5608', 'fermentum.risus.at@ullamcorperDuisat.edu', 1019, 7),
(60, 'DQG30NYV4BJ', 'Maya Bell', '449-1625 Ante, Street', '(416) 925-9029', 'sapien@velit.ca', 10637, 28),
(61, 'ZWO83QEA6SH', 'Beverly Browning', '225-4423 Odio Rd.', '(573) 593-6248', 'risus@sitamet.org', 1232, 43),
(62, 'BRD03VJL9UX', 'Cleo Ward', 'Ap #748-1590 Consectetuer Rd.', '(607) 856-0301', 'Donec@Integer.ca', 6656, 7),
(63, 'DBN50INF2VW', 'Velma Manning', 'Ap #803-4380 Erat. Rd.', '(244) 546-4675', 'dui.Fusce@euenimEtiam.edu', 6791, 8),
(64, 'AOR28NCT0CH', 'Nyssa Terry', '278-3066 Cubilia Av.', '(887) 453-8504', 'luctus.ut@nullaante.ca', 10336, 35),
(65, 'QWW39ZRR8KN', 'Chanda Mcfarland', '627-4051 Velit Avenue', '(992) 597-0135', 'dui.Suspendisse.ac@Nullam.org', 5151, 44),
(66, 'PDQ20AUE6PD', 'Alisa Church', 'P.O. Box 841, 5000 Sed St.', '(155) 290-1737', 'dictum@Curabiturconsequatlectus.org', 10311, 7),
(67, 'SJY89NAA6PA', 'Quynn Hebert', '210 Risus. Ave', '(990) 264-8995', 'penatibus.et.magnis@etarcuimperdiet.com', 8993, 43),
(68, 'FHL22DVH8GU', 'Minerva Dale', 'P.O. Box 884, 7324 Risus, Rd.', '(826) 721-6916', 'consequat@maurisSuspendisse.org', 81, 34),
(69, 'ZDP20VBR0WH', 'Colleen Ray', 'P.O. Box 676, 7483 Semper Avenue', '(383) 399-4459', 'mauris@estmollisnon.org', 8700, 17),
(70, 'SDL31ISJ0TJ', 'Candace Gutierrez', 'P.O. Box 527, 6908 Dolor Rd.', '(241) 691-7588', 'sit@nulla.edu', 19750, 12),
(71, 'LZY80SEF0HR', 'Eugenia Riley', 'P.O. Box 592, 655 Dignissim. Rd.', '(267) 755-8677', 'auctor.odio.a@mi.com', 135, 5),
(72, 'NSH77PLA3VM', 'Lunea Harding', 'P.O. Box 391, 5071 Nec Av.', '(694) 271-1124', 'fermentum.arcu@atsem.ca', 25058, 42),
(73, 'RLK77FFJ6TE', 'Naida Boyd', 'P.O. Box 154, 4881 Nec Street', '(725) 940-5656', 'Sed.malesuada@tinciduntadipiscing.ca', 386, 0),
(74, 'PGF98ZDO0UU', 'Autumn Soto', 'P.O. Box 892, 7125 Tincidunt Av.', '(692) 349-8851', 'malesuada.malesuada.Integer@ornare.ca', 10531, 12),
(75, 'RIK24FAM0HH', 'Cheyenne Cherry', 'P.O. Box 324, 9778 At Ave', '(518) 479-0203', 'massa@Suspendisseac.ca', 2489, 43),
(76, 'EHY63GZO4ZI', 'Karina Dennis', '201-4175 Iaculis St.', '(204) 418-4552', 'pretium@vehicula.ca', 3737, 7),
(77, 'IJD87DFG3NG', 'Regina Mendez', 'Ap #255-3268 Magnis Avenue', '(808) 450-7122', 'consectetuer@Curabiturut.org', 15844, 15),
(78, 'MRO70BZV6RS', 'Halla Sims', '8569 Et St.', '(548) 361-4748', 'vitae@enimcommodo.org', 205, 5),
(79, 'JBE38VAP1WQ', 'Genevieve Roberts', '441-685 Molestie Av.', '(944) 793-9165', 'nec.ante.blandit@necenim.org', 24360, 21),
(80, 'OQM17JTC1GY', 'Ivy Mills', 'P.O. Box 538, 882 Eget Road', '(170) 245-9120', 'mollis.Phasellus.libero@nisiMauris.org', 28990, 23),
(81, 'MJH55NNY1NH', 'Evangeline Yates', '4061 Ac Road', '(529) 432-4128', 'libero.Proin@euaccumsansed.com', 6240, 15),
(82, 'ASW56ABX2BG', 'Martina Shields', 'P.O. Box 688, 5631 Placerat, St.', '(589) 618-1172', 'nonummy.ac@tellusjusto.org', 12348, 6),
(83, 'HLF27GMJ0DM', 'Cheryl Rush', 'Ap #127-2548 Nunc Rd.', '(894) 966-5511', 'tincidunt@Integer.edu', 15399, 35),
(84, 'TTG08KOB2QR', 'Tamara Morton', 'Ap #399-7199 Tincidunt, Ave', '(292) 814-0776', 'ut.pellentesque.eget@actellus.edu', 19526, 26),
(85, 'WBD92UPE4NX', 'Yolanda Snow', '8912 Erat, St.', '(993) 170-6430', 'sed.pede.Cum@blanditmattisCras.edu', 22636, 32),
(86, 'DAM71XGC8CS', 'Anjolie Wall', '9174 Est St.', '(169) 702-7064', 'risus.at@adipiscing.ca', 7480, 36),
(87, 'RLS95OBF6ZV', 'Moana Strickland', '745-4828 Egestas Ave', '(692) 667-2495', 'Aliquam.adipiscing@convallisdolor.org', 2039, 44),
(88, 'EQA32ZFY8YR', 'Heidi Norton', 'Ap #475-5783 Sem Avenue', '(318) 703-0270', 'aliquam.iaculis.lacus@orci.com', 874, 22),
(89, 'TAS68RJF3NE', 'Adele Webster', 'P.O. Box 606, 9686 Vel Street', '(290) 570-4515', 'semper@risusDuisa.edu', 20753, 22),
(90, 'AJW26KKR3DZ', 'Jessamine Parker', 'Ap #947-1402 Mi Rd.', '(405) 910-0208', 'tellus.id@orci.edu', 4686, 29),
(91, 'UOB49FLJ8JZ', 'Leila Frank', '708-9260 Nam Avenue', '(579) 787-0505', 'ligula.Aenean.euismod@sagittis.ca', 6148, 43),
(92, 'RKJ02TZW0CV', 'Ima Roman', 'Ap #712-5336 Congue. Av.', '(446) 941-6721', 'Ut.sagittis@Donec.ca', 13619, 6),
(93, 'EFD02GVQ0PM', 'Lillian Duran', 'Ap #667-8834 Ut Avenue', '(275) 806-3574', 'at.sem@idmagna.com', 22104, 27),
(94, 'QZL20ATP2UN', 'Xaviera Hayes', 'P.O. Box 937, 489 Eget St.', '(253) 728-0446', 'molestie.in.tempus@amet.edu', 19775, 33),
(95, 'FRM79GIX6TB', 'Samantha Nelson', 'P.O. Box 535, 6996 Non St.', '(537) 984-7062', 'tellus@Phasellusdolorelit.ca', 13456, 9),
(96, 'XSN83GWF9ZF', 'Cathleen Howe', '6768 Accumsan Ave', '(660) 467-6805', 'non@tempor.ca', 22711, 1),
(97, 'TDH63FPN3AL', 'Ariel Sellers', '272-1345 Enim. Ave', '(197) 993-2947', 'sem.molestie.sodales@semmagnanec.com', 961, 13),
(98, 'QGW16XPS7RL', 'Cheryl Stone', '3239 Sed Street', '(276) 470-1728', 'Proin.sed@fringillaornareplacerat.edu', 5084, 24),
(99, 'IVL95EPY3MN', 'McKenzie Ford', 'P.O. Box 553, 2931 Pharetra, Av.', '(854) 683-9326', 'Maecenas.malesuada@ornare.edu', 17875, 38),
(100, 'TUL61YFU2AQ', 'Gwendolyn Leon', 'P.O. Box 425, 7578 Mi St.', '(966) 482-9248', 'egestas.a@infaucibusorci.ca', 4351, 42),
(101, 'ZCD73DKR6JW', 'Roanna Pearson', '375-1370 Non Ave', '(360) 609-0062', 'nec.tempus.mauris@consequat.ca', 9761, 30),
(102, 'EMC81LSY1IS', 'Lacy Mendoza', 'P.O. Box 925, 3801 Egestas. Av.', '(384) 447-2191', 'ipsum@egetlacusMauris.org', 28580, 2),
(103, 'WKT85BOC6KX', 'Teegan Franks', 'P.O. Box 729, 5653 Risus. St.', '(648) 830-8590', 'sem@nisi.com', 25748, 41),
(104, 'UYL86VUN1BS', 'Fay Riddle', 'P.O. Box 221, 6584 Morbi Av.', '(514) 905-2963', 'aliquam@Lorem.com', 19873, 40),
(105, 'AMR56THY5OU', 'Neve Oneil', '9321 Odio St.', '(294) 480-7059', 'ornare@indolor.ca', 2639, 30),
(106, 'MSK76YIL6AX', 'Roanna Stark', '2963 Quam. Avenue', '(473) 188-9975', 'congue.In.scelerisque@maurisblanditmattis.edu', 12033, 44),
(107, 'ZBX35OIL3EH', 'Glenna Aguilar', 'Ap #915-7442 Et Ave', '(577) 453-3398', 'Mauris.eu@NullamenimSed.com', 23112, 39),
(108, 'ZIE36JRA3FR', 'Athena Calderon', '1687 Laoreet Rd.', '(144) 474-2380', 'volutpat.ornare.facilisis@faucibusorciluctus.com', 5677, 41),
(109, 'RWG38POO6SZ', 'Eve Mclean', 'P.O. Box 666, 6891 Elementum Street', '(819) 390-1288', 'turpis.egestas@Proinnislsem.ca', 28958, 26),
(110, 'VOD32VJN4NV', 'Lacey Castaneda', '497-6470 At, Road', '(347) 535-0433', 'per.inceptos@cursusNuncmauris.ca', 1424, 26),
(111, 'BXY53MKW6XH', 'Sylvia Gonzales', '6843 Porttitor St.', '(612) 755-0989', 'tristique@fringillaornareplacerat.org', 26078, 28),
(112, 'HFC60SOJ6YH', 'Olympia Porter', '2515 Lorem Road', '(148) 136-0420', 'nisl@scelerisquescelerisquedui.com', 2319, 38),
(113, 'YEO55DLV8EV', 'Danielle Beard', 'Ap #875-3737 Vitae, Street', '(843) 373-4721', 'rhoncus@imperdiet.com', 17003, 22),
(114, 'ASY85CRU1SC', 'Melodie Mccarthy', 'P.O. Box 852, 8476 Luctus Rd.', '(823) 645-6856', 'Donec@lacus.ca', 1966, 9),
(115, 'KEW13PGZ1SM', 'Deanna Lynn', '6347 Magnis St.', '(721) 416-0471', 'nisi.dictum.augue@dolor.ca', 11664, 38),
(116, 'DSK46PDD8RM', 'Tallulah Houston', 'Ap #314-9748 Non, Rd.', '(889) 361-0230', 'odio@nonsapienmolestie.com', 8807, 31),
(117, 'DWU26MTO9WE', 'McKenzie Brown', 'P.O. Box 231, 7261 Ipsum Rd.', '(637) 867-0462', 'a.arcu.Sed@tinciduntaliquamarcu.ca', 23321, 16),
(118, 'NGD01ZGV1KH', 'Breanna Marquez', '7692 Interdum Ave', '(655) 602-8928', 'Fusce@eleifendCras.org', 26971, 25),
(119, 'QBO98QIE7XD', 'Kiona Richardson', '537-7442 Dapibus St.', '(476) 395-3917', 'vitae.orci@vulputate.edu', 25587, 4),
(120, 'ANL12IIU1GX', 'Jamalia Phillips', '359-911 Nascetur Ave', '(484) 492-8050', 'aliquet@turpis.ca', 16733, 14),
(121, 'DXQ99WVK6LE', 'Angelica Randolph', 'Ap #735-7644 Imperdiet Road', '(596) 546-6778', 'ridiculus.mus@aliquet.edu', 23922, 31),
(122, 'FRM62YVI7CT', 'Dorothy Rosario', 'Ap #234-2978 Sed Ave', '(279) 957-5993', 'nunc@libero.com', 14776, 44),
(123, 'SLU01WVK6JE', 'Idola Klein', '479-3975 Odio Rd.', '(829) 812-9922', 'dui.nec@netus.edu', 29973, 5),
(124, 'JWY75HXX8JK', 'Briar Romero', 'P.O. Box 764, 7736 Lacinia Avenue', '(296) 129-4015', 'odio@Craseget.edu', 17901, 14),
(125, 'OXF47PWV3RV', 'India Herrera', '347-6807 Est Rd.', '(711) 736-8434', 'accumsan.laoreet@ullamcorperDuisat.ca', 29052, 22),
(126, 'NOJ39AEI4UX', 'Signe Bean', 'P.O. Box 742, 2187 Nunc Rd.', '(630) 165-4525', 'taciti.sociosqu@temporloremeget.ca', 10401, 10),
(127, 'TJK08FZR2OF', 'Kelly Finley', 'Ap #577-1129 Dictum St.', '(305) 931-2601', 'odio.vel@utmolestie.ca', 24031, 13),
(128, 'QZH32WOT1ZV', 'Rosalyn Larsen', 'Ap #572-306 Mi, St.', '(962) 674-0182', 'faucibus.leo.in@dolortempus.org', 24978, 44),
(129, 'WSN68NLQ0JU', 'Megan Strong', 'Ap #171-2107 Integer Rd.', '(352) 434-3900', 'amet.metus@iaculisaliquetdiam.org', 16073, 44),
(130, 'KBK01TVT6QJ', 'Candice Peterson', 'P.O. Box 832, 7842 Eros Road', '(531) 431-9213', 'primis.in.faucibus@Phasellus.ca', 19989, 11),
(131, 'XKC60TAF4PP', 'Naomi Hendricks', 'Ap #576-6427 Ullamcorper St.', '(175) 398-0612', 'sem.Pellentesque@euodio.edu', 18362, 45),
(132, 'HJA48PBM0KS', 'Pascale Weaver', 'Ap #929-869 Semper Av.', '(681) 394-6799', 'arcu@arcuMorbisit.ca', 276, 36),
(133, 'SQW16HXJ2GE', 'Tamekah Castillo', '4005 Dictum Rd.', '(797) 467-3723', 'non@tellussem.ca', 29666, 29),
(134, 'AQX90DDE0HL', 'Kiayada Dyer', 'P.O. Box 662, 5966 Mauris St.', '(839) 653-5763', 'quam.Curabitur@turpis.com', 27754, 21),
(135, 'IBP40XYP1TV', 'Hanae Hall', 'Ap #433-7903 Massa. St.', '(363) 137-5071', 'tortor.Nunc@viverraDonec.org', 5100, 7),
(136, 'VBC42VGO2RU', 'Venus Avila', 'Ap #941-2651 Mauris Street', '(168) 249-4151', 'vel.lectus.Cum@euismod.org', 11521, 6),
(137, 'ZFJ54AIJ0WP', 'Raven Salazar', '703-9924 Malesuada Av.', '(964) 116-9796', 'nec.diam@purusactellus.org', 4570, 1),
(138, 'YEI32FXA0TW', 'Kimberly Hodges', 'P.O. Box 408, 5179 Et, Rd.', '(294) 980-4289', 'malesuada.id.erat@lobortisultricesVivamus.edu', 9982, 0),
(139, 'LOX45RIC1FO', 'Calista Lowe', 'Ap #554-2098 Aliquam Street', '(456) 646-3460', 'felis.adipiscing.fringilla@id.com', 29261, 27),
(140, 'BQY17DRX6TE', 'Clio Downs', '296 Iaculis Rd.', '(336) 682-6416', 'tristique.neque@consequatnec.org', 9335, 8),
(141, 'WBI59AHC2OM', 'Scarlett Hays', 'Ap #210-6395 Tempor, St.', '(532) 159-6169', 'gravida.Aliquam.tincidunt@Nullam.ca', 12474, 14),
(142, 'YLQ06ENM5AO', 'Fiona Burton', '495-8316 Phasellus Av.', '(134) 293-0948', 'libero.lacus.varius@sagittis.com', 23077, 40),
(143, 'ZYK45KAU5EP', 'Abigail Melendez', 'P.O. Box 934, 2048 Vel St.', '(132) 971-8626', 'dolor@sitamet.ca', 28386, 20),
(144, 'MIL28QXW2IW', 'Desiree Elliott', 'Ap #410-1791 Et Street', '(440) 176-0720', 'aliquet.vel.vulputate@diameu.org', 28659, 24),
(145, 'JPK28TCB0EI', 'Maia Lambert', '4892 Suspendisse Avenue', '(171) 143-1256', 'magna.a@purusaccumsan.com', 6115, 19),
(146, 'RAE70FYK8MW', 'Rhoda House', 'P.O. Box 571, 9343 Quis, Av.', '(690) 847-3533', 'ligula.eu.enim@ornareplaceratorci.org', 28106, 20),
(147, 'SDK19WZT3JO', 'Karen Stone', '5990 Tellus. St.', '(923) 377-6734', 'Suspendisse.dui@laoreetipsum.org', 15087, 14),
(148, 'TLE71NCT9ZG', 'Bethany Riggs', 'P.O. Box 287, 4222 Netus St.', '(890) 625-0289', 'dapibus.ligula.Aliquam@sed.ca', 12919, 18),
(149, 'AYN79MAA2WC', 'Fiona Boone', 'P.O. Box 645, 7129 Nascetur St.', '(317) 697-4391', 'Maecenas@euelit.com', 1359, 45),
(150, 'GOA28XJC9WF', 'Aileen Juarez', 'Ap #706-9084 Nunc Road', '(633) 934-1703', 'neque@Sedmolestie.org', 10975, 23),
(151, 'KGW52TSG2QP', 'Scarlett Frost', 'Ap #876-3867 Ac Road', '(135) 738-7429', 'aliquet@lectusrutrum.ca', 10956, 0),
(152, 'CCT33JYE6LP', 'Illana Finley', 'Ap #552-1224 Gravida Road', '(148) 568-5230', 'arcu@eget.ca', 24365, 27),
(153, 'EEN38YXG2WR', 'Sylvia Hardin', '6289 Donec Avenue', '(444) 978-0851', 'Nullam@arcuvelquam.org', 3837, 11),
(154, 'VAF72LQY3AS', 'Ella Salinas', '821-6709 Montes, Ave', '(967) 818-9576', 'Curabitur@metusAliquamerat.ca', 3330, 28),
(155, 'FOG19HVE0EV', 'Hayfa Whitaker', 'Ap #307-1524 Dapibus Street', '(700) 387-2761', 'Nulla.semper.tellus@nullaIntegervulputate.com', 1634, 0),
(156, 'FIV34ZGU9TC', 'Lana William', 'P.O. Box 394, 5483 Rhoncus. Street', '(908) 779-3472', 'non.quam.Pellentesque@tincidunt.edu', 2315, 19),
(157, 'QBQ40KMO3TW', 'Ivy Bentley', 'P.O. Box 307, 9927 Aliquet. Street', '(823) 405-1881', 'consectetuer@ultriciessem.ca', 7280, 3),
(158, 'AQO50HLK3VT', 'Kelsie Bauer', '173-7068 Curae; Road', '(368) 240-2468', 'volutpat.nunc@variusNamporttitor.org', 16004, 44),
(159, 'NVJ92FRS2KB', 'Lacey Johns', 'P.O. Box 666, 178 Tortor, Av.', '(662) 121-4108', 'convallis.erat@consequatpurusMaecenas.com', 4807, 4),
(160, 'RJT36DLD6FZ', 'Rebecca Smith', 'Ap #305-4753 Dui Road', '(839) 947-6047', 'venenatis.lacus@eueleifendnec.edu', 19243, 25),
(161, 'QUA74GUF0UQ', 'Maia Albert', '991-3791 Nec Road', '(879) 827-3865', 'volutpat.ornare.facilisis@at.edu', 3661, 13),
(162, 'NJC74WJB8LM', 'Daryl Holland', 'P.O. Box 850, 8758 Lorem St.', '(462) 227-0696', 'Ut.tincidunt.vehicula@diamluctuslobortis.com', 29647, 45),
(163, 'VWI87UJY2EZ', 'Hilda Ashley', 'Ap #178-9163 Neque Ave', '(980) 676-2855', 'rutrum@sedorci.com', 26302, 43),
(164, 'BRH08MZG4VY', 'Camilla Tyler', '8675 Cursus St.', '(654) 949-4640', 'pede.Cras.vulputate@ullamcorperDuiscursus.ca', 16353, 41),
(165, 'XLJ86WSQ0PM', 'Bianca Hays', '8167 At, Ave', '(747) 202-1700', 'mauris@etultrices.ca', 283, 28),
(166, 'SRM42NAU6LD', 'Helen Higgins', '7258 Mi St.', '(289) 450-3809', 'nunc@blanditcongueIn.org', 19411, 10),
(167, 'AYT07IMV6XT', 'Pamela Phelps', 'Ap #520-2602 Tellus. Avenue', '(256) 155-1459', 'Aenean@Cras.edu', 26374, 3),
(168, 'WFO69LLQ1YO', 'Quemby Hatfield', '4543 Pellentesque Avenue', '(940) 286-0882', 'a.arcu.Sed@enim.ca', 9321, 24),
(169, 'NFZ98EXL9HG', 'Sage Craft', 'Ap #779-9246 Arcu Av.', '(639) 511-3123', 'est.ac.facilisis@loremipsumsodales.ca', 27263, 19),
(170, 'SBI19PKU8DO', 'Karyn Talley', '535-5187 Luctus St.', '(469) 692-5520', 'pellentesque.Sed@Craspellentesque.edu', 22480, 12),
(171, 'DJR91VNQ7AR', 'Xyla Kent', 'P.O. Box 193, 9643 In Road', '(234) 755-1692', 'Donec.dignissim.magna@massarutrum.edu', 5722, 44),
(172, 'EAM87MNS9SC', 'Celeste Saunders', 'P.O. Box 603, 473 Senectus Street', '(801) 593-3683', 'Aliquam.ultrices.iaculis@Morbiaccumsan.edu', 25026, 33),
(173, 'VIG53YQM7SU', 'Yeo Edwards', '995-2585 Elementum, Ave', '(336) 929-7336', 'sem.Nulla@sit.org', 16871, 38),
(174, 'VNM32ECG4YA', 'Rachel Waller', '893-937 Ut St.', '(687) 924-4477', 'enim.sit.amet@sempertellus.ca', 937, 3),
(175, 'CFE36DJK0QN', 'Iliana Knox', '444-9444 Et, Avenue', '(673) 150-9867', 'Cum.sociis@Nuncquisarcu.ca', 29721, 2),
(176, 'NCK91BLO5BN', 'Willa Hinton', 'P.O. Box 855, 6264 Lorem Rd.', '(796) 553-1218', 'cursus@necquamCurabitur.com', 10845, 28),
(177, 'LKC99DNX5ZM', 'Quynn Sanders', 'Ap #748-533 Sed Rd.', '(302) 621-7303', 'lectus.a@accumsan.ca', 2626, 1),
(178, 'UFN77NFN3IA', 'Angelica Ballard', 'P.O. Box 355, 9357 Malesuada Road', '(336) 740-3803', 'ut.erat@arcuCurabitur.com', 28096, 6),
(179, 'CLI67QQU6SE', 'Remedios Wolfe', '773-7983 Dictum. St.', '(871) 674-2897', 'sagittis@acarcuNunc.com', 6362, 40),
(180, 'AWN85FAI2DL', 'Lisandra Stevenson', 'Ap #414-301 Posuere Road', '(483) 370-4597', 'Aenean.massa.Integer@consequatlectus.ca', 5717, 33),
(181, 'OKT86WIF7OP', 'India Tate', 'Ap #928-1115 Tellus Av.', '(595) 183-9008', 'primis@elitpretium.org', 10174, 20),
(182, 'GFT49IBJ2RE', 'Wendy Mccarty', 'Ap #886-5189 Risus. Road', '(524) 523-5580', 'sit@pellentesquea.com', 9147, 41),
(183, 'LQY78PZT1GF', 'Petra Burris', '258-6675 Quis St.', '(641) 232-3677', 'ipsum@nibhenim.org', 17306, 14),
(184, 'NEH23NMD6FI', 'Alana Hardin', '1562 Morbi Rd.', '(823) 281-8924', 'enim.nisl@a.org', 7731, 2),
(185, 'VQP37GNC2XB', 'Ursa Rogers', 'P.O. Box 643, 8775 Mi Ave', '(977) 902-3031', 'magna.et@non.edu', 23731, 34),
(186, 'ZAH02ECF9HY', 'Alana Trevino', 'Ap #608-8401 Egestas. Avenue', '(872) 813-4658', 'ligula.consectetuer.rhoncus@elita.com', 26624, 40),
(187, 'QBZ80HUB1QA', 'Susan Collins', '584 Morbi Rd.', '(503) 979-8624', 'venenatis.lacus@nuncsed.com', 12410, 13),
(188, 'ELO95TPN6UJ', 'Florence Riddle', '8183 A, Av.', '(767) 620-0674', 'Ut@lectuspedeultrices.edu', 8249, 40),
(189, 'DUQ73GNR5HC', 'Holly Carlson', '6929 Mattis St.', '(797) 864-2740', 'dui@pede.ca', 2998, 23),
(190, 'MKU01IHF7LD', 'Shelby Cooley', 'P.O. Box 618, 4137 Pede Rd.', '(806) 646-5094', 'quis.diam.Pellentesque@sodales.com', 23651, 16),
(191, 'PDQ88CYS2UH', 'Kay Hahn', '392-9693 Orci, Road', '(895) 990-7001', 'habitant.morbi@egestasFusce.ca', 4267, 40),
(192, 'VGV55QVA0NK', 'Iona Simpson', '9303 Iaculis Street', '(279) 970-1286', 'Proin@velturpisAliquam.org', 29380, 1),
(193, 'FNR05ROX0KH', 'Hayley Dean', '1916 Turpis. Rd.', '(572) 601-2399', 'magna.nec@ligulaelitpretium.ca', 14025, 11),
(194, 'XZY48BWE3CJ', 'Venus Brooks', '7730 Luctus. Rd.', '(188) 328-3752', 'non.bibendum@enimEtiamimperdiet.com', 21741, 24),
(195, 'VQP77ZVD8XR', 'Haviva Rollins', 'Ap #407-7531 Duis Road', '(920) 673-9105', 'fringilla.mi@montes.com', 21110, 1),
(196, 'FRV35TAX5MI', 'Daryl Schmidt', 'P.O. Box 233, 7870 Metus. St.', '(747) 953-4347', 'mauris.sit.amet@liberoProinmi.ca', 19381, 13),
(197, 'LKI39VRE6TI', 'Mia Valenzuela', '893-3621 Nunc Road', '(927) 375-0930', 'aliquam.adipiscing.lacus@Curae;Phasellus.com', 20409, 26),
(198, 'JMG51AXU9ZF', 'Haley Stanley', 'P.O. Box 418, 6043 Magna Street', '(896) 827-5208', 'Sed.nec.metus@Aliquamultrices.com', 19731, 29),
(199, 'JRN16NJY3XG', 'Kessie Estrada', 'P.O. Box 575, 1628 Pellentesque Road', '(527) 735-4195', 'non.lacinia@egestas.com', 21817, 41),
(200, 'YGG86EDD4ZN', 'McKenzie Knight', '4182 Tempus, St.', '(819) 329-8560', 'lectus.ante@nislsemconsequat.ca', 672, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la compra',
  `id_proveedor` int(11) NOT NULL COMMENT 'PROVEEDOR AL QUE SE LE COMPRO',
  `tipo_compra` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de compra, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de compra',
  `subtotal` float NOT NULL COMMENT 'subtotal de compra',
  `iva` float NOT NULL COMMENT 'iva de la compra',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en que se compro',
  `id_usuario` int(11) NOT NULL COMMENT 'quien realizo la compra',
  PRIMARY KEY (`id_compra`),
  KEY `compras_proveedor` (`id_proveedor`),
  KEY `compras_sucursal` (`id_sucursal`),
  KEY `compras_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `compras`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corte`
--

CREATE TABLE IF NOT EXISTS `corte` (
  `num_corte` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de corte',
  `anio` year(4) NOT NULL COMMENT 'año del corte',
  `inicio` date NOT NULL COMMENT 'año del corte',
  `fin` date NOT NULL COMMENT 'fecha de fin del corte',
  `ventas` float NOT NULL COMMENT 'ventas al contado en ese periodo',
  `abonosVentas` float NOT NULL COMMENT 'pagos de abonos en este periodo',
  `compras` float NOT NULL COMMENT 'compras realizadas en ese periodo',
  `AbonosCompra` float NOT NULL COMMENT 'pagos realizados en ese periodo',
  `gastos` float NOT NULL COMMENT 'gastos echos en ese periodo',
  `ingresos` float NOT NULL COMMENT 'ingresos obtenidos en ese periodo',
  `gananciasNetas` float NOT NULL COMMENT 'ganancias netas dentro del periodo',
  PRIMARY KEY (`num_corte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `corte`
--

INSERT INTO `corte` (`num_corte`, `anio`, `inicio`, `fin`, `ventas`, `abonosVentas`, `compras`, `AbonosCompra`, `gastos`, `ingresos`, `gananciasNetas`) VALUES
(1, 2011, '2010-05-02', '2011-06-01', 46364, 1000, 4483, 7173, 7184, 8911, 46401),
(2, 2010, '2010-04-16', '2011-02-26', 49346, 1000, 2815, 9191, 9356, 7840, 42454),
(3, 2009, '2009-07-24', '2010-08-11', 44787, 1000, 8994, 1819, 9450, 2866, 46378),
(4, 2010, '2010-06-01', '2010-12-02', 83376, 1000, 2203, 9898, 6535, 9027, 79173),
(5, 2009, '2009-11-21', '2010-09-20', 77295, 1000, 7344, 4913, 2736, 9242, 87232),
(6, 2010, '2009-08-05', '2010-10-31', 42213, 1000, 5861, 3576, 8225, 8345, 45618),
(7, 2009, '2009-10-29', '2011-06-07', 57566, 1000, 3522, 5206, 5552, 1055, 52385),
(8, 2009, '2010-03-21', '2011-02-25', 15751, 1000, 3787, 7110, 8900, 3130, 7658),
(9, 2010, '2009-08-02', '2011-05-10', 59563, 1000, 1542, 7693, 3867, 9112, 59657),
(10, 2011, '2009-12-10', '2011-06-11', 94966, 1000, 9665, 7721, 7570, 4357, 94697);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE IF NOT EXISTS `cotizacion` (
  `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la cotizacion',
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `fecha` date NOT NULL COMMENT 'fecha de cotizacion',
  `subtotal` float NOT NULL COMMENT 'subtotal de la cotizacion',
  `iva` float NOT NULL COMMENT 'iva sobre el subtotal',
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_cotizacion`),
  KEY `cotizacion_cliente` (`id_cliente`),
  KEY `fk_cotizacion_1` (`id_sucursal`),
  KEY `fk_cotizacion_2` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cotizacion`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_compra` int(11) NOT NULL COMMENT 'id de la compra',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad comprada',
  `precio` float NOT NULL COMMENT 'costo de compra',
  PRIMARY KEY (`id_compra`,`id_producto`),
  KEY `detalle_compra_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_corte`
--

CREATE TABLE IF NOT EXISTS `detalle_corte` (
  `num_corte` int(11) NOT NULL COMMENT 'id del corte al que hace referencia',
  `nombre` varchar(100) NOT NULL COMMENT 'nombre del encargado de sucursal al momento del corte',
  `total` float NOT NULL COMMENT 'total que le corresponde al encargado al momento del corte',
  `deben` float NOT NULL COMMENT 'lo que deben en la sucursal del encargado al momento del corte',
  PRIMARY KEY (`num_corte`,`nombre`),
  KEY `corte_detalleCorte` (`num_corte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `detalle_corte`
--

INSERT INTO `detalle_corte` (`num_corte`, `nombre`, `total`, `deben`) VALUES
(1, 'Acton', 23200.5, 3446),
(1, 'Alvin', 23200.5, 3358),
(2, 'Vance', 21227, 3414),
(2, 'Wing', 21227, 2313),
(3, 'Blaze', 11594.5, 2034),
(3, 'Chancellor', 11594.5, 1663),
(3, 'Jasper', 11594.5, 2701),
(3, 'Ryan', 11594.5, 3093),
(4, 'Bevis', 19793.2, 3101),
(4, 'Henry', 19793.2, 1709),
(4, 'Igor', 19793.2, 2494),
(4, 'Kennan', 19793.2, 1220),
(5, 'Baxter', 43616, 3238),
(5, 'Joseph', 43616, 1734),
(6, 'Basil', 45618, 1093),
(7, 'Graham', 13096.2, 2348),
(7, 'Kennan', 13096.2, 2191),
(7, 'Rigel', 13096.2, 3914),
(7, 'Silas', 13096.2, 3630),
(8, 'Asher', 1276.33, 2371),
(8, 'Brendan', 1276.33, 2849),
(8, 'Cody', 1276.33, 3055),
(8, 'Cooper', 1276.33, 3386),
(8, 'Darius', 1276.33, 2987),
(8, 'Rahim', 1276.33, 2199),
(9, 'Colton', 29828.5, 1195),
(9, 'Conan', 29828.5, 3191),
(10, 'Galvin', 47348.5, 1275),
(10, 'Hedley', 47348.5, 3098);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cotizacion`
--

CREATE TABLE IF NOT EXISTS `detalle_cotizacion` (
  `id_cotizacion` int(11) NOT NULL COMMENT 'id de la cotizacion',
  `id_producto` int(11) NOT NULL COMMENT 'id del producto',
  `cantidad` float NOT NULL COMMENT 'cantidad cotizado',
  `precio` float NOT NULL COMMENT 'precio al que cotizo el producto',
  PRIMARY KEY (`id_cotizacion`,`id_producto`),
  KEY `detalle_cotizacion_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_cotizacion`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_inventario` (
  `id_producto` int(11) NOT NULL COMMENT 'id del producto al que se refiere',
  `id_sucursal` int(11) NOT NULL COMMENT 'id de la sucursal',
  `precio_venta` float NOT NULL COMMENT 'precio al que se vendera al publico',
  `min` float NOT NULL DEFAULT '0' COMMENT 'cantidad minima que debe de haber del producto en almacen de esta sucursal',
  `existencias` float NOT NULL DEFAULT '0' COMMENT 'cantidad de producto que hay actualmente en almacen de esta sucursal',
  PRIMARY KEY (`id_producto`,`id_sucursal`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `detalle_inventario`
--

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES
(1, 9, 486, 72, 208),
(1, 27, 137, 88, 195),
(2, 21, 29, 60, 358),
(2, 28, 272, 61, 92),
(2, 43, 493, 66, 174),
(2, 47, 206, 71, 391),
(3, 9, 85, 58, 362),
(3, 23, 4, 61, 130),
(3, 39, 139, 69, 323),
(4, 4, 129, 62, 77),
(4, 16, 273, 81, 153),
(4, 19, 275, 50, 143),
(4, 23, 474, 63, 277),
(4, 45, 458, 70, 18),
(6, 18, 374, 84, 91),
(6, 25, 209, 86, 310),
(6, 27, 309, 64, 173),
(6, 31, 107, 64, 388),
(7, 11, 307, 67, 307),
(7, 28, 402, 54, 8),
(7, 37, 32, 81, 160),
(7, 43, 10, 73, 352),
(8, 17, 158, 68, 256),
(8, 30, 91, 75, 17),
(8, 31, 348, 53, 7),
(8, 32, 248, 56, 170),
(8, 38, 308, 69, 345),
(8, 40, 333, 81, 60),
(8, 42, 49, 73, 116),
(9, 5, 203, 79, 196),
(9, 12, 150, 79, 225),
(9, 23, 102, 80, 339),
(9, 24, 189, 56, 95),
(9, 29, 431, 77, 96),
(9, 38, 165, 72, 186),
(9, 40, 267, 79, 87),
(10, 14, 233, 86, 282),
(10, 47, 103, 86, 249),
(11, 16, 250, 55, 393),
(11, 18, 117, 74, 173),
(11, 46, 363, 87, 146),
(12, 13, 461, 64, 127),
(12, 16, 136, 61, 246),
(12, 27, 2, 82, 350),
(12, 37, 399, 63, 33),
(12, 41, 107, 57, 333),
(12, 48, 488, 69, 13),
(13, 6, 243, 83, 142),
(13, 8, 216, 68, 305),
(13, 14, 361, 88, 242),
(13, 16, 238, 87, 63),
(13, 18, 247, 73, 294),
(13, 21, 467, 75, 62),
(13, 25, 485, 64, 336),
(13, 34, 90, 70, 192),
(13, 41, 276, 84, 264),
(13, 46, 281, 54, 102),
(14, 26, 161, 80, 172),
(14, 32, 417, 55, 384),
(14, 37, 353, 64, 209),
(15, 39, 498, 62, 183),
(15, 41, 198, 87, 12),
(16, 8, 299, 74, 355),
(16, 10, 364, 86, 91),
(16, 31, 1, 67, 373),
(16, 43, 359, 69, 272),
(16, 52, 465, 68, 207),
(17, 17, 84, 70, 87),
(17, 27, 176, 58, 60),
(18, 13, 395, 72, 367),
(18, 15, 202, 50, 18),
(18, 22, 462, 69, 394),
(18, 27, 98, 65, 110),
(19, 15, 116, 74, 208),
(19, 16, 270, 85, 245),
(19, 33, 281, 85, 50),
(19, 42, 233, 80, 396),
(20, 33, 36, 71, 327),
(20, 51, 275, 71, 131),
(21, 8, 370, 53, 98),
(21, 15, 27, 53, 310),
(21, 25, 44, 63, 168),
(21, 32, 402, 57, 179),
(21, 37, 337, 50, 111),
(21, 47, 184, 63, 333),
(21, 50, 288, 87, 14),
(22, 23, 488, 86, 240),
(22, 30, 112, 51, 205),
(22, 33, 83, 79, 286),
(22, 40, 168, 61, 54),
(22, 43, 51, 90, 133),
(23, 14, 130, 50, 215),
(23, 19, 186, 76, 62),
(23, 43, 346, 83, 32),
(24, 18, 336, 53, 125),
(24, 44, 82, 72, 91),
(25, 15, 272, 52, 343),
(25, 27, 29, 81, 283),
(25, 33, 424, 54, 104),
(25, 41, 320, 52, 9),
(25, 42, 404, 82, 379),
(26, 4, 125, 89, 10),
(26, 27, 444, 86, 313),
(26, 29, 63, 79, 121),
(27, 13, 142, 71, 146),
(27, 28, 260, 81, 125),
(28, 15, 259, 65, 41),
(28, 16, 96, 85, 39),
(28, 36, 198, 58, 130),
(28, 46, 152, 66, 203),
(28, 49, 165, 68, 179),
(29, 12, 259, 89, 80),
(29, 22, 473, 50, 106),
(29, 28, 350, 50, 296),
(29, 30, 353, 89, 247),
(29, 32, 23, 56, 325),
(29, 45, 147, 89, 130),
(30, 6, 148, 76, 254),
(30, 9, 13, 88, 13),
(30, 19, 80, 65, 280),
(30, 25, 247, 84, 12),
(30, 33, 307, 73, 36),
(31, 47, 195, 81, 352),
(32, 34, 399, 64, 330),
(33, 7, 116, 76, 102),
(33, 24, 365, 60, 338),
(33, 29, 59, 85, 213),
(33, 31, 494, 54, 212),
(33, 38, 300, 90, 51),
(33, 40, 383, 71, 132),
(34, 4, 169, 69, 6),
(34, 7, 289, 89, 379),
(34, 11, 211, 65, 249),
(34, 32, 323, 55, 271),
(34, 36, 272, 84, 263),
(34, 50, 23, 65, 93),
(35, 16, 68, 61, 246),
(35, 41, 222, 65, 351),
(36, 31, 252, 67, 22),
(36, 34, 325, 69, 288),
(37, 18, 323, 56, 216),
(37, 19, 308, 52, 41),
(37, 45, 229, 68, 97),
(38, 5, 343, 85, 31),
(38, 8, 81, 50, 320),
(38, 10, 485, 65, 19),
(38, 21, 48, 90, 90),
(38, 23, 314, 60, 267),
(38, 33, 236, 53, 248),
(38, 38, 262, 58, 173),
(39, 17, 77, 55, 101),
(39, 24, 327, 78, 121),
(39, 45, 368, 61, 303),
(40, 4, 383, 83, 2),
(40, 21, 469, 53, 337),
(40, 34, 285, 67, 391),
(40, 39, 188, 89, 22),
(41, 7, 203, 81, 48),
(41, 13, 204, 70, 384),
(41, 22, 153, 51, 314),
(41, 25, 298, 84, 227),
(41, 43, 400, 69, 108),
(41, 45, 99, 50, 90),
(42, 45, 155, 66, 122),
(43, 21, 311, 53, 20),
(43, 30, 463, 50, 97),
(43, 37, 25, 63, 68),
(43, 38, 339, 87, 9),
(43, 43, 139, 79, 22),
(43, 46, 162, 51, 162),
(44, 28, 44, 82, 224),
(44, 35, 322, 66, 196),
(44, 49, 390, 66, 111),
(45, 6, 429, 50, 389),
(45, 17, 9, 50, 27),
(45, 35, 329, 78, 371),
(46, 24, 476, 86, 227),
(46, 49, 346, 74, 379),
(47, 43, 130, 50, 118),
(47, 45, 438, 64, 221),
(48, 15, 257, 57, 400),
(48, 28, 30, 61, 47),
(48, 43, 214, 56, 277),
(48, 51, 137, 54, 90),
(49, 7, 201, 66, 357),
(49, 10, 48, 78, 24),
(49, 20, 488, 58, 209),
(49, 35, 62, 60, 227),
(50, 31, 320, 53, 234),
(50, 35, 380, 69, 81);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `id_venta` int(11) NOT NULL COMMENT 'venta a que se referencia',
  `id_producto` int(11) NOT NULL COMMENT 'producto de la venta',
  `cantidad` float NOT NULL COMMENT 'cantidad que se vendio',
  `precio` float NOT NULL COMMENT 'precio al que se vendio',
  PRIMARY KEY (`id_venta`,`id_producto`),
  KEY `detalle_venta_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `precio`) VALUES
(1, 44, 77, 322),
(1, 45, 131, 329),
(1, 49, 58, 62),
(1, 50, 25, 380),
(2, 18, 309, 395),
(2, 27, 127, 142),
(3, 18, 74, 395),
(3, 41, 59, 204),
(4, 9, 224, 102),
(4, 22, 164, 488),
(4, 38, 39, 314),
(5, 33, 92, 116),
(5, 34, 339, 289),
(6, 7, 161, 10),
(6, 43, 7, 139),
(6, 48, 16, 214),
(7, 4, 130, 474),
(7, 9, 81, 102),
(7, 22, 164, 488),
(7, 38, 257, 314),
(8, 4, 59, 474),
(8, 9, 127, 102),
(8, 22, 141, 488),
(8, 38, 85, 314),
(9, 9, 193, 203),
(10, 13, 124, 243),
(10, 45, 64, 429),
(11, 11, 12, 250),
(11, 12, 122, 136),
(11, 13, 44, 238),
(11, 28, 38, 96),
(12, 14, 130, 417),
(12, 29, 229, 23),
(12, 34, 137, 323),
(13, 3, 24, 4),
(13, 4, 18, 474),
(13, 9, 311, 102),
(13, 22, 238, 488),
(13, 38, 162, 314),
(14, 14, 90, 161),
(15, 18, 15, 202),
(15, 21, 9, 27),
(15, 25, 113, 272),
(15, 28, 40, 259),
(15, 48, 330, 257),
(16, 1, 59, 486),
(16, 3, 34, 85),
(16, 30, 5, 13),
(17, 44, 90, 322),
(17, 49, 226, 62),
(17, 50, 79, 380),
(18, 2, 326, 206),
(18, 21, 133, 184),
(18, 31, 63, 195),
(19, 33, 39, 116),
(19, 34, 242, 289),
(19, 41, 39, 203),
(19, 49, 351, 201),
(20, 19, 27, 116),
(20, 21, 275, 27),
(20, 25, 25, 272),
(20, 28, 29, 259),
(21, 43, 37, 162),
(22, 13, 67, 243),
(22, 30, 17, 148),
(22, 45, 10, 429),
(23, 11, 36, 117),
(23, 13, 283, 247),
(23, 24, 112, 336),
(24, 14, 159, 161),
(25, 3, 125, 4),
(25, 22, 195, 488),
(25, 38, 21, 314),
(26, 13, 150, 90),
(26, 32, 184, 399),
(26, 36, 73, 325),
(26, 40, 299, 285),
(27, 8, 85, 248),
(27, 14, 114, 417),
(27, 29, 192, 23),
(27, 34, 60, 323),
(28, 9, 65, 431),
(28, 26, 88, 63),
(28, 33, 26, 59),
(29, 2, 59, 272),
(29, 27, 38, 260),
(29, 48, 10, 30),
(30, 23, 14, 346),
(30, 43, 9, 139),
(31, 8, 16, 91),
(31, 22, 106, 112),
(32, 2, 32, 272),
(32, 7, 3, 402),
(32, 27, 115, 260),
(32, 29, 46, 350),
(32, 44, 3, 44),
(32, 48, 36, 30),
(33, 13, 90, 243),
(33, 30, 249, 148),
(33, 45, 222, 429),
(34, 13, 79, 243),
(34, 30, 187, 148),
(34, 45, 258, 429),
(35, 8, 14, 91),
(35, 22, 9, 112),
(36, 23, 19, 346),
(36, 43, 2, 139),
(37, 8, 140, 248),
(37, 21, 6, 402),
(37, 34, 42, 323),
(38, 6, 78, 374),
(38, 11, 89, 117),
(38, 13, 18, 247),
(38, 24, 50, 336),
(38, 37, 34, 323),
(39, 3, 49, 4),
(39, 4, 204, 474),
(39, 9, 119, 102),
(39, 22, 144, 488),
(39, 38, 214, 314),
(40, 9, 50, 203),
(40, 38, 1, 343),
(41, 14, 103, 161),
(42, 18, 9, 202),
(42, 21, 6, 27),
(43, 11, 312, 250),
(43, 12, 133, 136),
(43, 13, 18, 238),
(43, 19, 142, 270),
(43, 28, 5, 96),
(44, 13, 139, 243),
(44, 30, 58, 148),
(44, 45, 221, 429),
(45, 23, 21, 346),
(45, 48, 84, 214),
(46, 1, 194, 486),
(46, 3, 145, 85),
(46, 30, 5, 13),
(47, 2, 95, 206),
(47, 10, 2, 103),
(47, 21, 241, 184),
(47, 31, 55, 195),
(48, 18, 9, 202),
(48, 21, 306, 27),
(48, 28, 25, 259),
(49, 13, 100, 243),
(49, 30, 253, 148),
(49, 45, 224, 429),
(50, 13, 56, 90),
(50, 32, 61, 399),
(50, 36, 26, 325),
(50, 40, 149, 285),
(51, 14, 295, 417),
(51, 21, 174, 402),
(51, 29, 269, 23),
(51, 34, 149, 323),
(52, 13, 22, 243),
(52, 30, 129, 148),
(52, 45, 47, 429),
(53, 11, 94, 363),
(53, 13, 79, 281),
(53, 28, 159, 152),
(53, 43, 96, 162),
(54, 18, 13, 202),
(54, 48, 36, 257);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargado`
--

CREATE TABLE IF NOT EXISTS `encargado` (
  `id_usuario` int(11) NOT NULL COMMENT 'Este id es el del usuario encargado de su sucursal',
  `porciento` float NOT NULL COMMENT 'este es el porciento de las ventas que le tocan al encargado',
  PRIMARY KEY (`id_usuario`),
  KEY `fk_encargado_1` (`id_usuario`),
  KEY `usuario_encargado` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `encargado`
--

INSERT INTO `encargado` (`id_usuario`, `porciento`) VALUES
(43, 5.88235),
(53, 5.88235),
(38, 5.88235),
(6, 5.88235),
(16, 5.88235),
(34, 5.88235),
(19, 5.88235),
(36, 5.88235),
(3, 5.88235),
(8, 5.88235),
(9, 5.88235),
(30, 5.88235),
(7, 5.88235),
(24, 5.88235),
(49, 5.88235),
(42, 5.88235),
(39, 5.88235);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_compra`
--

CREATE TABLE IF NOT EXISTS `factura_compra` (
  `folio` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'COMPRA A LA QUE CORRESPONDE LA FACTURA',
  PRIMARY KEY (`folio`),
  KEY `factura_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `factura_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

CREATE TABLE IF NOT EXISTS `factura_venta` (
  `folio` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'folio que tiene la factura',
  `id_venta` int(11) NOT NULL COMMENT 'venta a la cual corresponde la factura',
  PRIMARY KEY (`folio`),
  KEY `factura_venta_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `factura_venta`
--

INSERT INTO `factura_venta` (`folio`, `id_venta`) VALUES
('ALGUNFOLIO74296', 4),
('ALGUNFOLIO75640', 8),
('ALGUNFOLIO73694', 9),
('ALGUNFOLIO79049', 13),
('ALGUNFOLIO78355', 24),
('ALGUNFOLIO71861', 25),
('ALGUNFOLIO71586', 27),
('ALGUNFOLIO76085', 35),
('ALGUNFOLIO78268', 40),
('ALGUNFOLIO72701', 49),
('ALGUNFOLIO75463', 53);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `id_gasto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el gasto',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se gasto',
  `monto` float NOT NULL COMMENT 'lo que costo este gasto',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del gasto',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el gasto',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el gasto',
  PRIMARY KEY (`id_gasto`),
  KEY `fk_gastos_1` (`id_usuario`),
  KEY `usuario_gasto` (`id_usuario`),
  KEY `sucursal_gasto` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

--
-- Volcar la base de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `concepto`, `monto`, `fecha`, `id_sucursal`, `id_usuario`) VALUES
(1, 'ut cursus luctus, ipsum', 1747, '0000-00-00 00:00:00', 41, 18),
(2, 'eu, odio. Phasellus at', 5380, '0000-00-00 00:00:00', 19, 47),
(3, 'sodales at, velit. Pellentesque', 4764, '0000-00-00 00:00:00', 46, 8),
(4, 'sagittis semper. Nam tempor', 6327, '0000-00-00 00:00:00', 23, 34),
(5, 'auctor, velit eget laoreet', 8526, '0000-00-00 00:00:00', 22, 18),
(6, 'vel, faucibus id, libero.', 4383, '0000-00-00 00:00:00', 10, 21),
(7, 'semper auctor. Mauris vel', 5135, '0000-00-00 00:00:00', 8, 28),
(8, 'consequat dolor vitae dolor.', 113, '0000-00-00 00:00:00', 49, 16),
(9, 'malesuada ut, sem. Nulla', 7524, '2013-07-11 14:19:00', 31, 20),
(10, 'Aliquam ornare, libero at', 9087, '0000-00-00 00:00:00', 17, 33),
(11, 'placerat velit. Quisque varius.', 1810, '0000-00-00 00:00:00', 38, 28),
(12, 'purus mauris a nunc.', 1380, '0000-00-00 00:00:00', 12, 41),
(13, 'sit amet, consectetuer adipiscing', 5589, '0000-00-00 00:00:00', 9, 33),
(14, 'enim nisl elementum purus,', 2518, '0000-00-00 00:00:00', 50, 13),
(15, 'id nunc interdum feugiat.', 830, '0000-00-00 00:00:00', 37, 45),
(16, 'Quisque nonummy ipsum non', 6840, '0000-00-00 00:00:00', 11, 17),
(17, 'in consequat enim diam', 7275, '0000-00-00 00:00:00', 33, 38),
(18, 'Cras dolor dolor, tempus', 9120, '0000-00-00 00:00:00', 20, 4),
(19, 'nibh. Quisque nonummy ipsum', 867, '0000-00-00 00:00:00', 29, 46),
(20, 'fermentum fermentum arcu. Vestibulum', 7084, '0000-00-00 00:00:00', 37, 13),
(21, 'per inceptos hymenaeos. Mauris', 8329, '0000-00-00 00:00:00', 43, 16),
(22, 'odio. Nam interdum enim', 8272, '0000-00-00 00:00:00', 25, 43),
(23, 'nunc id enim. Curabitur', 1778, '0000-00-00 00:00:00', 38, 4),
(24, 'egestas blandit. Nam nulla', 8822, '0000-00-00 00:00:00', 17, 17),
(25, 'Donec non justo. Proin', 9134, '0000-00-00 00:00:00', 5, 15),
(26, 'pharetra, felis eget varius', 3839, '0000-00-00 00:00:00', 16, 47),
(27, 'Fusce aliquam, enim nec', 8010, '0000-00-00 00:00:00', 42, 36),
(28, 'risus. Donec nibh enim,', 5329, '0000-00-00 00:00:00', 46, 40),
(29, 'diam lorem, auctor quis,', 7368, '0000-00-00 00:00:00', 5, 25),
(30, 'cursus vestibulum. Mauris magna.', 4952, '0000-00-00 00:00:00', 17, 26),
(31, 'ultricies sem magna nec', 291, '0000-00-00 00:00:00', 26, 29),
(32, 'id enim. Curabitur massa.', 7531, '0000-00-00 00:00:00', 42, 28),
(33, 'fringilla ornare placerat, orci', 8508, '0000-00-00 00:00:00', 39, 27),
(34, 'ornare. Fusce mollis. Duis', 307, '0000-00-00 00:00:00', 23, 37),
(35, 'elit, a feugiat tellus', 2315, '0000-00-00 00:00:00', 22, 19),
(36, 'tellus id nunc interdum', 3006, '0000-00-00 00:00:00', 9, 10),
(37, 'semper rutrum. Fusce dolor', 1536, '0000-00-00 00:00:00', 31, 32),
(38, 'dis parturient montes, nascetur', 8739, '0000-00-00 00:00:00', 7, 19),
(39, 'sit amet luctus vulputate,', 5477, '0000-00-00 00:00:00', 44, 42),
(40, 'arcu. Morbi sit amet', 6809, '0000-00-00 00:00:00', 19, 49),
(41, 'arcu. Sed eu nibh', 969, '0000-00-00 00:00:00', 25, 39),
(42, 'sit amet ante. Vivamus', 8665, '0000-00-00 00:00:00', 26, 48),
(43, 'amet risus. Donec egestas.', 8636, '0000-00-00 00:00:00', 17, 44),
(44, 'at sem molestie sodales.', 4112, '0000-00-00 00:00:00', 50, 13),
(45, 'cursus vestibulum. Mauris magna.', 5663, '0000-00-00 00:00:00', 41, 13),
(46, 'Curabitur ut odio vel', 8728, '0000-00-00 00:00:00', 28, 20),
(47, 'dolor elit, pellentesque a,', 3826, '0000-00-00 00:00:00', 11, 37),
(48, 'morbi tristique senectus et', 4857, '0000-00-00 00:00:00', 36, 31),
(49, 'id, ante. Nunc mauris', 1447, '0000-00-00 00:00:00', 20, 37),
(50, 'urna et arcu imperdiet', 8274, '0000-00-00 00:00:00', 35, 9),
(51, 'Aliquam nisl. Nulla eu', 9595, '0000-00-00 00:00:00', 49, 44),
(52, 'erat. Vivamus nisi. Mauris', 9564, '0000-00-00 00:00:00', 6, 47),
(53, 'montes, nascetur ridiculus mus.', 8537, '0000-00-00 00:00:00', 10, 49),
(54, 'Vestibulum ante ipsum primis', 6750, '0000-00-00 00:00:00', 18, 10),
(55, 'facilisis vitae, orci. Phasellus', 4574, '0000-00-00 00:00:00', 44, 9),
(56, 'sollicitudin commodo ipsum. Suspendisse', 5526, '0000-00-00 00:00:00', 37, 28),
(57, 'Vivamus rhoncus. Donec est.', 731, '0000-00-00 00:00:00', 14, 37),
(58, 'ipsum. Curabitur consequat, lectus', 8940, '0000-00-00 00:00:00', 42, 31),
(59, 'ac turpis egestas. Aliquam', 7815, '0000-00-00 00:00:00', 24, 14),
(60, 'vel pede blandit congue.', 4981, '0000-00-00 00:00:00', 11, 16),
(61, 'Aliquam rutrum lorem ac', 3017, '0000-00-00 00:00:00', 14, 7),
(62, 'vitae, aliquet nec, imperdiet', 5252, '0000-00-00 00:00:00', 14, 5),
(63, 'imperdiet non, vestibulum nec,', 899, '0000-00-00 00:00:00', 11, 47),
(64, 'pede. Praesent eu dui.', 8651, '0000-00-00 00:00:00', 25, 19),
(65, 'porttitor vulputate, posuere vulputate,', 6777, '0000-00-00 00:00:00', 6, 43),
(66, 'enim commodo hendrerit. Donec', 8571, '0000-00-00 00:00:00', 24, 23),
(67, 'non, lacinia at, iaculis', 8706, '0000-00-00 00:00:00', 34, 21),
(68, 'ac, eleifend vitae, erat.', 8047, '0000-00-00 00:00:00', 33, 37),
(69, 'mollis non, cursus non,', 8570, '0000-00-00 00:00:00', 41, 7),
(70, 'egestas ligula. Nullam feugiat', 202, '0000-00-00 00:00:00', 9, 21),
(71, 'egestas a, dui. Cras', 2662, '0000-00-00 00:00:00', 17, 39),
(72, 'sagittis semper. Nam tempor', 7343, '0000-00-00 00:00:00', 8, 7),
(73, 'Suspendisse eleifend. Cras sed', 1307, '0000-00-00 00:00:00', 46, 50),
(74, 'feugiat. Lorem ipsum dolor', 3313, '0000-00-00 00:00:00', 23, 33),
(75, 'Aliquam adipiscing lobortis risus.', 559, '0000-00-00 00:00:00', 4, 30),
(76, 'sed sem egestas blandit.', 6365, '0000-00-00 00:00:00', 17, 49),
(77, 'mattis semper, dui lectus', 853, '0000-00-00 00:00:00', 6, 45),
(78, 'Sed nunc est, mollis', 1607, '0000-00-00 00:00:00', 15, 13),
(79, 'est. Nunc laoreet lectus', 5253, '0000-00-00 00:00:00', 49, 30),
(80, 'luctus lobortis. Class aptent', 488, '0000-00-00 00:00:00', 33, 35),
(81, 'ante dictum cursus. Nunc', 5280, '0000-00-00 00:00:00', 34, 11),
(82, 'scelerisque neque sed sem', 611, '0000-00-00 00:00:00', 41, 6),
(83, 'vulputate ullamcorper magna. Sed', 7905, '0000-00-00 00:00:00', 33, 49),
(84, 'laoreet lectus quis massa.', 9166, '0000-00-00 00:00:00', 48, 47),
(85, 'sit amet, consectetuer adipiscing', 9858, '0000-00-00 00:00:00', 41, 8),
(86, 'aliquet, sem ut cursus', 326, '0000-00-00 00:00:00', 18, 30),
(87, 'nisi dictum augue malesuada', 2787, '0000-00-00 00:00:00', 9, 42),
(88, 'sociis natoque penatibus et', 7505, '0000-00-00 00:00:00', 26, 27),
(89, 'Donec egestas. Duis ac', 1394, '0000-00-00 00:00:00', 35, 10),
(90, 'auctor. Mauris vel turpis.', 4585, '0000-00-00 00:00:00', 13, 18),
(91, 'lorem lorem, luctus ut,', 8236, '0000-00-00 00:00:00', 17, 35),
(92, 'In scelerisque scelerisque dui.', 2288, '0000-00-00 00:00:00', 31, 38),
(93, 'facilisi. Sed neque. Sed', 3883, '0000-00-00 00:00:00', 43, 20),
(94, 'Phasellus dolor elit, pellentesque', 1628, '0000-00-00 00:00:00', 47, 49),
(95, 'ut odio vel est', 581, '0000-00-00 00:00:00', 41, 39),
(96, 'ultricies ornare, elit elit', 2775, '0000-00-00 00:00:00', 16, 8),
(97, 'vehicula aliquet libero. Integer', 9379, '0000-00-00 00:00:00', 14, 32),
(98, 'aliquet. Proin velit. Sed', 4166, '0000-00-00 00:00:00', 47, 34),
(99, 'dolor egestas rhoncus. Proin', 2173, '0000-00-00 00:00:00', 18, 50),
(100, 'felis orci, adipiscing non,', 9125, '0000-00-00 00:00:00', 37, 34),
(101, 'arcu. Morbi sit amet', 5659, '0000-00-00 00:00:00', 4, 37),
(102, 'ipsum primis in faucibus', 9482, '0000-00-00 00:00:00', 36, 11),
(103, 'tortor. Integer aliquam adipiscing', 4871, '0000-00-00 00:00:00', 47, 42),
(104, 'tellus id nunc interdum', 2035, '0000-00-00 00:00:00', 46, 12),
(105, 'rhoncus id, mollis nec,', 1198, '0000-00-00 00:00:00', 41, 29),
(106, 'sagittis placerat. Cras dictum', 7213, '0000-00-00 00:00:00', 12, 40),
(107, 'vulputate, posuere vulputate, lacus.', 4993, '0000-00-00 00:00:00', 39, 21),
(108, 'massa. Suspendisse eleifend. Cras', 4742, '0000-00-00 00:00:00', 5, 37),
(109, 'quam, elementum at, egestas', 590, '0000-00-00 00:00:00', 24, 40),
(110, 'Integer aliquam adipiscing lacus.', 1268, '0000-00-00 00:00:00', 47, 4),
(111, 'massa non ante bibendum', 8675, '0000-00-00 00:00:00', 42, 21),
(112, 'Fusce aliquet magna a', 7225, '0000-00-00 00:00:00', 30, 13),
(113, 'pede sagittis augue, eu', 3482, '2013-11-06 07:59:00', 39, 21),
(114, 'turpis egestas. Fusce aliquet', 9274, '0000-00-00 00:00:00', 11, 4),
(115, 'laoreet lectus quis massa.', 9042, '0000-00-00 00:00:00', 22, 43),
(116, 'erat volutpat. Nulla dignissim.', 4292, '0000-00-00 00:00:00', 29, 23),
(117, 'hendrerit neque. In ornare', 9792, '0000-00-00 00:00:00', 12, 27),
(118, 'est, mollis non, cursus', 1184, '0000-00-00 00:00:00', 27, 37),
(119, 'in, dolor. Fusce feugiat.', 3294, '2013-03-16 13:18:00', 8, 6),
(120, 'scelerisque mollis. Phasellus libero', 6486, '0000-00-00 00:00:00', 16, 45),
(121, 'posuere, enim nisl elementum', 136, '0000-00-00 00:00:00', 16, 7),
(122, 'lacus. Cras interdum. Nunc', 4190, '0000-00-00 00:00:00', 7, 21),
(123, 'gravida nunc sed pede.', 4591, '0000-00-00 00:00:00', 13, 36),
(124, 'eget mollis lectus pede', 7521, '0000-00-00 00:00:00', 9, 21),
(125, 'et ultrices posuere cubilia', 3014, '0000-00-00 00:00:00', 44, 43),
(126, 'magna. Phasellus dolor elit,', 3719, '0000-00-00 00:00:00', 30, 22),
(127, 'velit in aliquet lobortis,', 6475, '0000-00-00 00:00:00', 25, 39),
(128, 'enim, sit amet ornare', 7241, '0000-00-00 00:00:00', 34, 17),
(129, 'vitae erat vel pede', 9110, '0000-00-00 00:00:00', 20, 35),
(130, 'parturient montes, nascetur ridiculus', 4265, '0000-00-00 00:00:00', 5, 34),
(131, 'enim mi tempor lorem,', 2465, '0000-00-00 00:00:00', 20, 40),
(132, 'elementum purus, accumsan interdum', 4698, '0000-00-00 00:00:00', 10, 38),
(133, 'tristique aliquet. Phasellus fermentum', 133, '0000-00-00 00:00:00', 30, 12),
(134, 'feugiat placerat velit. Quisque', 1190, '0000-00-00 00:00:00', 31, 7),
(135, 'interdum feugiat. Sed nec', 507, '0000-00-00 00:00:00', 43, 6),
(136, 'Donec luctus aliquet odio.', 4780, '0000-00-00 00:00:00', 20, 27),
(137, 'scelerisque neque. Nullam nisl.', 7077, '0000-00-00 00:00:00', 12, 37),
(138, 'diam dictum sapien. Aenean', 2298, '0000-00-00 00:00:00', 21, 4),
(139, 'purus, in molestie tortor', 7217, '0000-00-00 00:00:00', 41, 26),
(140, 'Nulla tempor augue ac', 9352, '0000-00-00 00:00:00', 13, 49),
(141, 'dolor, nonummy ac, feugiat', 7657, '0000-00-00 00:00:00', 11, 19),
(142, 'ultricies adipiscing, enim mi', 7410, '0000-00-00 00:00:00', 23, 34),
(143, 'tristique pellentesque, tellus sem', 2502, '0000-00-00 00:00:00', 37, 30),
(144, 'ipsum primis in faucibus', 625, '0000-00-00 00:00:00', 7, 9),
(145, 'lorem tristique aliquet. Phasellus', 2616, '0000-00-00 00:00:00', 43, 46),
(146, 'felis. Donec tempor, est', 2031, '0000-00-00 00:00:00', 47, 49),
(147, 'Aliquam tincidunt, nunc ac', 7100, '0000-00-00 00:00:00', 24, 9),
(148, 'sociis natoque penatibus et', 6385, '0000-00-00 00:00:00', 7, 31),
(149, 'semper egestas, urna justo', 2249, '0000-00-00 00:00:00', 47, 17),
(150, 'hendrerit a, arcu. Sed', 3390, '0000-00-00 00:00:00', 47, 39),
(151, 'et, magna. Praesent interdum', 6463, '0000-00-00 00:00:00', 19, 18),
(152, 'arcu ac orci. Ut', 3945, '0000-00-00 00:00:00', 24, 26),
(153, 'neque venenatis lacus. Etiam', 2637, '0000-00-00 00:00:00', 21, 10),
(154, 'vitae sodales nisi magna', 2998, '0000-00-00 00:00:00', 9, 22),
(155, 'Ut nec urna et', 5471, '0000-00-00 00:00:00', 27, 34),
(156, 'ut, sem. Nulla interdum.', 5789, '0000-00-00 00:00:00', 15, 31),
(157, 'Integer eu lacus. Quisque', 1701, '0000-00-00 00:00:00', 45, 40),
(158, 'sit amet, risus. Donec', 8118, '0000-00-00 00:00:00', 28, 8),
(159, 'a purus. Duis elementum,', 8426, '0000-00-00 00:00:00', 23, 11),
(160, 'sit amet diam eu', 8406, '0000-00-00 00:00:00', 34, 45),
(161, 'Sed nunc est, mollis', 214, '0000-00-00 00:00:00', 5, 30),
(162, 'Donec sollicitudin adipiscing ligula.', 1462, '0000-00-00 00:00:00', 49, 19),
(163, 'Nulla eu neque pellentesque', 4615, '0000-00-00 00:00:00', 50, 45),
(164, 'elementum at, egestas a,', 2284, '0000-00-00 00:00:00', 25, 43),
(165, 'Proin velit. Sed malesuada', 3412, '0000-00-00 00:00:00', 32, 46),
(166, 'aliquam eros turpis non', 1334, '0000-00-00 00:00:00', 4, 32),
(167, 'auctor vitae, aliquet nec,', 4111, '0000-00-00 00:00:00', 40, 13),
(168, 'pellentesque eget, dictum placerat,', 4167, '0000-00-00 00:00:00', 8, 20),
(169, 'enim diam vel arcu.', 1392, '0000-00-00 00:00:00', 47, 39),
(170, 'sapien molestie orci tincidunt', 8805, '0000-00-00 00:00:00', 7, 49),
(171, 'rhoncus. Nullam velit dui,', 673, '0000-00-00 00:00:00', 44, 21),
(172, 'arcu iaculis enim, sit', 3184, '0000-00-00 00:00:00', 14, 36),
(173, 'fermentum metus. Aenean sed', 8329, '0000-00-00 00:00:00', 31, 42),
(174, 'a, arcu. Sed et', 5963, '0000-00-00 00:00:00', 23, 48),
(175, 'Integer aliquam adipiscing lacus.', 1953, '0000-00-00 00:00:00', 11, 16),
(176, 'justo. Proin non massa', 2548, '0000-00-00 00:00:00', 32, 6),
(177, 'fringilla est. Mauris eu', 5347, '0000-00-00 00:00:00', 41, 35),
(178, 'Cras vehicula aliquet libero.', 8881, '2013-04-26 19:43:00', 34, 39),
(179, 'diam nunc, ullamcorper eu,', 5050, '0000-00-00 00:00:00', 10, 15),
(180, 'ac mi eleifend egestas.', 3604, '0000-00-00 00:00:00', 48, 26),
(181, 'ac ipsum. Phasellus vitae', 5357, '0000-00-00 00:00:00', 17, 11),
(182, 'semper. Nam tempor diam', 7111, '0000-00-00 00:00:00', 8, 10),
(183, 'sem semper erat, in', 2503, '0000-00-00 00:00:00', 22, 28),
(184, 'lorem, vehicula et, rutrum', 71, '0000-00-00 00:00:00', 30, 46),
(185, 'nulla ante, iaculis nec,', 3674, '0000-00-00 00:00:00', 30, 48),
(186, 'molestie. Sed id risus', 2249, '0000-00-00 00:00:00', 37, 13),
(187, 'in faucibus orci luctus', 8479, '0000-00-00 00:00:00', 25, 29),
(188, 'in, tempus eu, ligula.', 3976, '0000-00-00 00:00:00', 5, 22),
(189, 'justo nec ante. Maecenas', 3228, '0000-00-00 00:00:00', 30, 6),
(190, 'Nulla eget metus eu', 6561, '0000-00-00 00:00:00', 13, 48),
(191, 'sem semper erat, in', 5959, '0000-00-00 00:00:00', 25, 23),
(192, 'magna tellus faucibus leo,', 185, '0000-00-00 00:00:00', 18, 10),
(193, 'placerat velit. Quisque varius.', 8776, '0000-00-00 00:00:00', 8, 34),
(194, 'Nunc ut erat. Sed', 8082, '0000-00-00 00:00:00', 43, 43),
(195, 'venenatis lacus. Etiam bibendum', 3064, '0000-00-00 00:00:00', 21, 44),
(196, 'nisi nibh lacinia orci,', 4130, '0000-00-00 00:00:00', 16, 33),
(197, 'Donec tincidunt. Donec vitae', 8210, '0000-00-00 00:00:00', 36, 40),
(198, 'tortor nibh sit amet', 8991, '0000-00-00 00:00:00', 37, 21),
(199, 'dui lectus rutrum urna,', 1608, '0000-00-00 00:00:00', 41, 8),
(200, 'ante dictum cursus. Nunc', 970, '0000-00-00 00:00:00', 15, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL COMMENT 'Nombre del Grupo',
  `descripcion` varchar(256) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Lorem ipsum dolor sit amet, consectetuer adip'),
(2, 'Gerente', 'Lorem ipsum dolor sit amet'),
(3, 'Cajero', 'Lorem ipsum dolor sit amet, consectetuer adip');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_permisos`
--

CREATE TABLE IF NOT EXISTS `grupos_permisos` (
  `id_grupo` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  PRIMARY KEY (`id_grupo`,`id_permiso`),
  KEY `fk_grupos_permisos_1` (`id_permiso`),
  KEY `fk_grupos_permisos_2` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `grupos_permisos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_usuarios`
--

CREATE TABLE IF NOT EXISTS `grupos_usuarios` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_grupo`,`id_usuario`),
  KEY `fk_grupos_usuarios_1` (`id_grupo`),
  KEY `fk_grupos_usuarios_2` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `grupos_usuarios`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE IF NOT EXISTS `impuesto` (
  `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `valor` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  PRIMARY KEY (`id_impuesto`),
  KEY `fk_impuesto_1` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `impuesto`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `id_ingreso` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id para identificar el ingreso',
  `concepto` varchar(100) NOT NULL COMMENT 'concepto en lo que se ingreso',
  `monto` float NOT NULL COMMENT 'lo que costo este ingreso',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha del ingreso',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal en la que se hizo el ingreso',
  `id_usuario` int(11) NOT NULL COMMENT 'usuario que registro el ingreso',
  PRIMARY KEY (`id_ingreso`),
  KEY `fk_ingresos_1` (`id_usuario`),
  KEY `usuario_ingreso` (`id_usuario`),
  KEY `sucursal_ingreso` (`id_sucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

--
-- Volcar la base de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `concepto`, `monto`, `fecha`, `id_sucursal`, `id_usuario`) VALUES
(1, 'lorem ut aliquam iaculis,', 5261, '0000-00-00 00:00:00', 16, 40),
(2, 'vestibulum massa rutrum magna.', 8347, '0000-00-00 00:00:00', 8, 29),
(3, 'odio. Etiam ligula tortor,', 4132, '0000-00-00 00:00:00', 43, 47),
(4, 'aliquam iaculis, lacus pede', 5230, '0000-00-00 00:00:00', 48, 39),
(5, 'velit. Pellentesque ultricies dignissim', 2711, '0000-00-00 00:00:00', 23, 28),
(6, 'In faucibus. Morbi vehicula.', 4160, '0000-00-00 00:00:00', 14, 8),
(7, 'egestas. Sed pharetra, felis', 4207, '0000-00-00 00:00:00', 12, 13),
(8, 'Class aptent taciti sociosqu', 4663, '0000-00-00 00:00:00', 43, 8),
(9, 'adipiscing elit. Aliquam auctor,', 1056, '0000-00-00 00:00:00', 44, 4),
(10, 'vulputate, lacus. Cras interdum.', 9761, '0000-00-00 00:00:00', 29, 37),
(11, 'scelerisque neque. Nullam nisl.', 3970, '0000-00-00 00:00:00', 33, 14),
(12, 'Nunc ac sem ut', 5834, '0000-00-00 00:00:00', 50, 16),
(13, 'ac turpis egestas. Aliquam', 4058, '0000-00-00 00:00:00', 40, 9),
(14, 'ut ipsum ac mi', 127, '0000-00-00 00:00:00', 14, 34),
(15, 'dictum eleifend, nunc risus', 4129, '0000-00-00 00:00:00', 43, 36),
(16, 'tincidunt nibh. Phasellus nulla.', 6940, '0000-00-00 00:00:00', 41, 44),
(17, 'Duis a mi fringilla', 6604, '0000-00-00 00:00:00', 45, 38),
(18, 'dolor dolor, tempus non,', 4301, '0000-00-00 00:00:00', 25, 5),
(19, 'felis eget varius ultrices,', 883, '0000-00-00 00:00:00', 15, 39),
(20, 'sit amet ultricies sem', 2331, '0000-00-00 00:00:00', 4, 28),
(21, 'tempus non, lacinia at,', 7930, '0000-00-00 00:00:00', 34, 25),
(22, 'Quisque imperdiet, erat nonummy', 8743, '0000-00-00 00:00:00', 8, 15),
(23, 'adipiscing. Mauris molestie pharetra', 9510, '0000-00-00 00:00:00', 47, 11),
(24, 'sociis natoque penatibus et', 7174, '0000-00-00 00:00:00', 5, 24),
(25, 'Aliquam erat volutpat. Nulla', 9170, '0000-00-00 00:00:00', 12, 4),
(26, 'sodales at, velit. Pellentesque', 6335, '0000-00-00 00:00:00', 5, 12),
(27, 'tincidunt congue turpis. In', 2906, '0000-00-00 00:00:00', 47, 22),
(28, 'nascetur ridiculus mus. Proin', 9293, '0000-00-00 00:00:00', 46, 11),
(29, 'turpis. In condimentum. Donec', 5650, '0000-00-00 00:00:00', 33, 28),
(30, 'arcu. Curabitur ut odio', 7214, '0000-00-00 00:00:00', 39, 38),
(31, 'ultricies sem magna nec', 6914, '0000-00-00 00:00:00', 45, 18),
(32, 'sollicitudin orci sem eget', 9237, '0000-00-00 00:00:00', 39, 40),
(33, 'lacus pede sagittis augue,', 9403, '0000-00-00 00:00:00', 41, 12),
(34, 'Donec porttitor tellus non', 8364, '0000-00-00 00:00:00', 20, 17),
(35, 'amet ante. Vivamus non', 2776, '0000-00-00 00:00:00', 36, 34),
(36, 'ut, molestie in, tempus', 5997, '0000-00-00 00:00:00', 41, 47),
(37, 'sem molestie sodales. Mauris', 4271, '0000-00-00 00:00:00', 25, 32),
(38, 'cursus luctus, ipsum leo', 2184, '0000-00-00 00:00:00', 19, 42),
(39, 'ipsum nunc id enim.', 2182, '0000-00-00 00:00:00', 10, 18),
(40, 'id, mollis nec, cursus', 8959, '0000-00-00 00:00:00', 8, 50),
(41, 'lorem. Donec elementum, lorem', 9007, '0000-00-00 00:00:00', 11, 23),
(42, 'erat vel pede blandit', 5283, '0000-00-00 00:00:00', 37, 44),
(43, 'Quisque imperdiet, erat nonummy', 3883, '0000-00-00 00:00:00', 28, 4),
(44, 'feugiat. Lorem ipsum dolor', 3204, '0000-00-00 00:00:00', 48, 11),
(45, 'egestas lacinia. Sed congue,', 3942, '0000-00-00 00:00:00', 39, 46),
(46, 'Proin non massa non', 821, '0000-00-00 00:00:00', 37, 28),
(47, 'justo eu arcu. Morbi', 8498, '0000-00-00 00:00:00', 43, 24),
(48, 'velit egestas lacinia. Sed', 9393, '0000-00-00 00:00:00', 23, 31),
(49, 'iaculis, lacus pede sagittis', 5901, '0000-00-00 00:00:00', 50, 31),
(50, 'eu eros. Nam consequat', 6939, '0000-00-00 00:00:00', 24, 47),
(51, 'arcu. Aliquam ultrices iaculis', 9510, '0000-00-00 00:00:00', 47, 7),
(52, 'sapien imperdiet ornare. In', 8722, '0000-00-00 00:00:00', 14, 4),
(53, 'nostra, per inceptos hymenaeos.', 9727, '0000-00-00 00:00:00', 46, 45),
(54, 'aliquam eu, accumsan sed,', 6244, '0000-00-00 00:00:00', 22, 20),
(55, 'ipsum sodales purus, in', 2348, '0000-00-00 00:00:00', 41, 14),
(56, 'vulputate eu, odio. Phasellus', 2143, '0000-00-00 00:00:00', 41, 4),
(57, 'eu lacus. Quisque imperdiet,', 7912, '0000-00-00 00:00:00', 31, 47),
(58, 'tellus. Aenean egestas hendrerit', 301, '0000-00-00 00:00:00', 43, 30),
(59, 'neque tellus, imperdiet non,', 7763, '0000-00-00 00:00:00', 33, 15),
(60, 'dis parturient montes, nascetur', 8533, '0000-00-00 00:00:00', 16, 46),
(61, 'felis eget varius ultrices,', 1788, '0000-00-00 00:00:00', 41, 45),
(62, 'mattis. Integer eu lacus.', 1887, '0000-00-00 00:00:00', 14, 16),
(63, 'a, facilisis non, bibendum', 255, '0000-00-00 00:00:00', 27, 31),
(64, 'diam eu dolor egestas', 2957, '0000-00-00 00:00:00', 32, 5),
(65, 'non nisi. Aenean eget', 1987, '0000-00-00 00:00:00', 49, 30),
(66, 'primis in faucibus orci', 8054, '0000-00-00 00:00:00', 10, 13),
(67, 'id, ante. Nunc mauris', 7704, '0000-00-00 00:00:00', 25, 14),
(68, 'dui augue eu tellus.', 7254, '0000-00-00 00:00:00', 10, 25),
(69, 'amet luctus vulputate, nisi', 9340, '0000-00-00 00:00:00', 19, 37),
(70, 'dolor. Donec fringilla. Donec', 5615, '0000-00-00 00:00:00', 50, 29),
(71, 'hendrerit consectetuer, cursus et,', 4759, '2013-09-23 05:45:00', 10, 42),
(72, 'aliquet. Phasellus fermentum convallis', 7475, '0000-00-00 00:00:00', 44, 44),
(73, 'leo. Vivamus nibh dolor,', 8275, '0000-00-00 00:00:00', 24, 12),
(74, 'Donec consectetuer mauris id', 5636, '0000-00-00 00:00:00', 21, 14),
(75, 'vel arcu eu odio', 8374, '0000-00-00 00:00:00', 24, 48),
(76, 'ipsum cursus vestibulum. Mauris', 5864, '0000-00-00 00:00:00', 23, 25),
(77, 'magnis dis parturient montes,', 7348, '2013-03-30 21:22:00', 12, 34),
(78, 'enim, sit amet ornare', 1522, '0000-00-00 00:00:00', 12, 45),
(79, 'elit pede, malesuada vel,', 3248, '0000-00-00 00:00:00', 37, 22),
(80, 'magna a neque. Nullam', 5705, '0000-00-00 00:00:00', 15, 4),
(81, 'velit. Sed malesuada augue', 6810, '0000-00-00 00:00:00', 12, 5),
(82, 'nec, mollis vitae, posuere', 5689, '2013-11-03 10:54:00', 15, 31),
(83, 'justo faucibus lectus, a', 6866, '0000-00-00 00:00:00', 29, 49),
(84, 'ornare. In faucibus. Morbi', 9397, '0000-00-00 00:00:00', 24, 41),
(85, 'quam. Curabitur vel lectus.', 6046, '2013-09-24 20:39:00', 24, 49),
(86, 'erat eget ipsum. Suspendisse', 6193, '0000-00-00 00:00:00', 43, 21),
(87, 'commodo ipsum. Suspendisse non', 5563, '0000-00-00 00:00:00', 40, 4),
(88, 'sit amet ante. Vivamus', 343, '0000-00-00 00:00:00', 5, 28),
(89, 'quis arcu vel quam', 2209, '0000-00-00 00:00:00', 29, 49),
(90, 'laoreet, libero et tristique', 7966, '0000-00-00 00:00:00', 29, 13),
(91, 'Suspendisse aliquet molestie tellus.', 697, '0000-00-00 00:00:00', 11, 39),
(92, 'lacus. Ut nec urna', 5962, '0000-00-00 00:00:00', 29, 36),
(93, 'vel turpis. Aliquam adipiscing', 9809, '0000-00-00 00:00:00', 34, 48),
(94, 'ac facilisis facilisis, magna', 5035, '0000-00-00 00:00:00', 19, 29),
(95, 'dapibus quam quis diam.', 1182, '0000-00-00 00:00:00', 30, 12),
(96, 'sem, vitae aliquam eros', 5891, '0000-00-00 00:00:00', 37, 44),
(97, 'elit elit fermentum risus,', 2617, '0000-00-00 00:00:00', 42, 32),
(98, 'vitae, orci. Phasellus dapibus', 3583, '0000-00-00 00:00:00', 41, 29),
(99, 'laoreet lectus quis massa.', 9540, '0000-00-00 00:00:00', 17, 24),
(100, 'sed libero. Proin sed', 8425, '0000-00-00 00:00:00', 9, 39),
(101, 'eleifend egestas. Sed pharetra,', 7697, '0000-00-00 00:00:00', 37, 23),
(102, 'In faucibus. Morbi vehicula.', 437, '0000-00-00 00:00:00', 49, 19),
(103, 'gravida. Praesent eu nulla', 5239, '0000-00-00 00:00:00', 28, 18),
(104, 'dictum. Proin eget odio.', 2247, '0000-00-00 00:00:00', 12, 47),
(105, 'Aenean egestas hendrerit neque.', 9983, '0000-00-00 00:00:00', 28, 29),
(106, 'euismod urna. Nullam lobortis', 3106, '0000-00-00 00:00:00', 7, 40),
(107, 'torquent per conubia nostra,', 3782, '0000-00-00 00:00:00', 14, 42),
(108, 'nec urna suscipit nonummy.', 3351, '0000-00-00 00:00:00', 30, 6),
(109, 'nunc est, mollis non,', 2688, '0000-00-00 00:00:00', 25, 21),
(110, 'feugiat nec, diam. Duis', 4323, '2013-10-20 22:04:00', 37, 26),
(111, 'nunc, ullamcorper eu, euismod', 2245, '0000-00-00 00:00:00', 40, 7),
(112, 'sit amet, faucibus ut,', 9578, '0000-00-00 00:00:00', 50, 47),
(113, 'ullamcorper eu, euismod ac,', 5069, '0000-00-00 00:00:00', 26, 18),
(114, 'pede. Cras vulputate velit', 5578, '0000-00-00 00:00:00', 8, 7),
(115, 'felis, adipiscing fringilla, porttitor', 3122, '0000-00-00 00:00:00', 45, 11),
(116, 'penatibus et magnis dis', 4572, '0000-00-00 00:00:00', 13, 39),
(117, 'at arcu. Vestibulum ante', 6639, '0000-00-00 00:00:00', 9, 5),
(118, 'arcu. Vivamus sit amet', 8343, '0000-00-00 00:00:00', 27, 6),
(119, 'orci. Phasellus dapibus quam', 2896, '0000-00-00 00:00:00', 9, 27),
(120, 'velit. Cras lorem lorem,', 1065, '0000-00-00 00:00:00', 24, 13),
(121, 'massa non ante bibendum', 9132, '0000-00-00 00:00:00', 28, 24),
(122, 'enim nec tempus scelerisque,', 6094, '0000-00-00 00:00:00', 27, 23),
(123, 'sem ut dolor dapibus', 4026, '0000-00-00 00:00:00', 30, 30),
(124, 'neque sed sem egestas', 7581, '0000-00-00 00:00:00', 18, 31),
(125, 'pellentesque a, facilisis non,', 4435, '0000-00-00 00:00:00', 33, 28),
(126, 'risus. Donec egestas. Duis', 1269, '0000-00-00 00:00:00', 30, 19),
(127, 'Cras vehicula aliquet libero.', 6849, '0000-00-00 00:00:00', 43, 35),
(128, 'et tristique pellentesque, tellus', 2790, '0000-00-00 00:00:00', 44, 22),
(129, 'tempor augue ac ipsum.', 3796, '0000-00-00 00:00:00', 43, 35),
(130, 'ipsum dolor sit amet,', 3396, '0000-00-00 00:00:00', 7, 46),
(131, 'scelerisque scelerisque dui. Suspendisse', 6276, '0000-00-00 00:00:00', 26, 32),
(132, 'velit. Quisque varius. Nam', 7919, '0000-00-00 00:00:00', 12, 41),
(133, 'Fusce mollis. Duis sit', 8025, '0000-00-00 00:00:00', 19, 28),
(134, 'ante dictum mi, ac', 8902, '0000-00-00 00:00:00', 43, 20),
(135, 'non magna. Nam ligula', 6869, '0000-00-00 00:00:00', 4, 21),
(136, 'hendrerit a, arcu. Sed', 8694, '0000-00-00 00:00:00', 40, 11),
(137, 'lorem lorem, luctus ut,', 5978, '0000-00-00 00:00:00', 42, 15),
(138, 'fringilla, porttitor vulputate, posuere', 8853, '0000-00-00 00:00:00', 10, 35),
(139, 'sit amet, dapibus id,', 6203, '0000-00-00 00:00:00', 17, 46),
(140, 'Suspendisse eleifend. Cras sed', 4608, '0000-00-00 00:00:00', 37, 34),
(141, 'ad litora torquent per', 277, '0000-00-00 00:00:00', 11, 12),
(142, 'tortor at risus. Nunc', 49, '0000-00-00 00:00:00', 29, 27),
(143, 'Aliquam nisl. Nulla eu', 5395, '2013-01-26 16:45:00', 44, 34),
(144, 'sollicitudin orci sem eget', 6234, '0000-00-00 00:00:00', 42, 34),
(145, 'urna suscipit nonummy. Fusce', 6301, '0000-00-00 00:00:00', 45, 11),
(146, 'blandit at, nisi. Cum', 386, '0000-00-00 00:00:00', 43, 5),
(147, 'bibendum fermentum metus. Aenean', 1123, '0000-00-00 00:00:00', 47, 7),
(148, 'commodo hendrerit. Donec porttitor', 6287, '0000-00-00 00:00:00', 37, 43),
(149, 'neque venenatis lacus. Etiam', 8731, '0000-00-00 00:00:00', 5, 30),
(150, 'Suspendisse commodo tincidunt nibh.', 5597, '0000-00-00 00:00:00', 6, 30),
(151, 'Donec egestas. Aliquam nec', 9194, '0000-00-00 00:00:00', 14, 27),
(152, 'iaculis nec, eleifend non,', 356, '0000-00-00 00:00:00', 11, 39),
(153, 'tempus, lorem fringilla ornare', 570, '0000-00-00 00:00:00', 47, 12),
(154, 'ipsum non arcu. Vivamus', 7541, '0000-00-00 00:00:00', 13, 39),
(155, 'convallis in, cursus et,', 1319, '0000-00-00 00:00:00', 42, 24),
(156, 'vitae diam. Proin dolor.', 5364, '0000-00-00 00:00:00', 16, 23),
(157, 'orci sem eget massa.', 2971, '0000-00-00 00:00:00', 50, 17),
(158, 'fringilla cursus purus. Nullam', 503, '0000-00-00 00:00:00', 44, 48),
(159, 'Proin mi. Aliquam gravida', 772, '0000-00-00 00:00:00', 25, 28),
(160, 'pellentesque. Sed dictum. Proin', 6243, '0000-00-00 00:00:00', 16, 40),
(161, 'magnis dis parturient montes,', 1939, '0000-00-00 00:00:00', 48, 40),
(162, 'nibh dolor, nonummy ac,', 1622, '0000-00-00 00:00:00', 28, 42),
(163, 'ullamcorper, nisl arcu iaculis', 3466, '0000-00-00 00:00:00', 15, 6),
(164, 'risus a ultricies adipiscing,', 5209, '0000-00-00 00:00:00', 25, 17),
(165, 'Cras vulputate velit eu', 4530, '0000-00-00 00:00:00', 31, 35),
(166, 'dolor. Fusce mi lorem,', 4305, '0000-00-00 00:00:00', 33, 26),
(167, 'eu, ultrices sit amet,', 881, '0000-00-00 00:00:00', 50, 33),
(168, 'neque. Morbi quis urna.', 2558, '0000-00-00 00:00:00', 22, 22),
(169, 'amet, faucibus ut, nulla.', 3482, '0000-00-00 00:00:00', 11, 22),
(170, 'risus. Donec nibh enim,', 6907, '0000-00-00 00:00:00', 13, 41),
(171, 'semper rutrum. Fusce dolor', 4571, '0000-00-00 00:00:00', 44, 47),
(172, 'nec, imperdiet nec, leo.', 3202, '0000-00-00 00:00:00', 13, 41),
(173, 'fermentum convallis ligula. Donec', 7860, '0000-00-00 00:00:00', 25, 16),
(174, 'Sed eget lacus. Mauris', 937, '0000-00-00 00:00:00', 38, 37),
(175, 'Nunc mauris elit, dictum', 7263, '0000-00-00 00:00:00', 19, 45),
(176, 'tellus, imperdiet non, vestibulum', 7323, '0000-00-00 00:00:00', 17, 24),
(177, 'tincidunt aliquam arcu. Aliquam', 4478, '0000-00-00 00:00:00', 42, 31),
(178, 'nibh. Donec est mauris,', 329, '0000-00-00 00:00:00', 22, 12),
(179, 'ornare sagittis felis. Donec', 2437, '0000-00-00 00:00:00', 8, 25),
(180, 'ipsum cursus vestibulum. Mauris', 3020, '0000-00-00 00:00:00', 16, 19),
(181, 'nisi. Aenean eget metus.', 7258, '0000-00-00 00:00:00', 32, 18),
(182, 'velit eu sem. Pellentesque', 3536, '0000-00-00 00:00:00', 5, 50),
(183, 'Integer tincidunt aliquam arcu.', 3647, '0000-00-00 00:00:00', 45, 6),
(184, 'Vivamus sit amet risus.', 1725, '0000-00-00 00:00:00', 27, 12),
(185, 'aliquet, metus urna convallis', 3147, '0000-00-00 00:00:00', 39, 39),
(186, 'vitae, sodales at, velit.', 1545, '0000-00-00 00:00:00', 47, 43),
(187, 'Proin velit. Sed malesuada', 236, '0000-00-00 00:00:00', 18, 49),
(188, 'sed pede. Cum sociis', 5595, '0000-00-00 00:00:00', 17, 23),
(189, 'molestie orci tincidunt adipiscing.', 9024, '0000-00-00 00:00:00', 37, 8),
(190, 'Sed id risus quis', 7392, '0000-00-00 00:00:00', 8, 19),
(191, 'erat. Sed nunc est,', 9907, '0000-00-00 00:00:00', 22, 13),
(192, 'nulla at sem molestie', 8839, '0000-00-00 00:00:00', 21, 40),
(193, 'semper auctor. Mauris vel', 1395, '0000-00-00 00:00:00', 29, 29),
(194, 'ullamcorper, nisl arcu iaculis', 4688, '0000-00-00 00:00:00', 21, 36),
(195, 'tempor, est ac mattis', 6783, '0000-00-00 00:00:00', 34, 42),
(196, 'amet diam eu dolor', 9423, '0000-00-00 00:00:00', 14, 28),
(197, 'neque non quam. Pellentesque', 9404, '0000-00-00 00:00:00', 33, 8),
(198, 'et magnis dis parturient', 7260, '0000-00-00 00:00:00', 24, 40),
(199, 'quam dignissim pharetra. Nam', 8283, '0000-00-00 00:00:00', 50, 45),
(200, 'Morbi quis urna. Nunc', 3569, '0000-00-00 00:00:00', 35, 46);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id del producto',
  `nombre` varchar(90) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=201 ;

--
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `nombre`, `denominacion`) VALUES
(1, 'enim commodo hendrerit.', 'Quisque tincidunt pede ac urna'),
(2, 'libero et', 'arcu. Curabitur ut odio vel'),
(3, 'eu nibh', 'et, lacinia vitae, sodales at,'),
(4, 'et, lacinia vitae,', 'dui. Cum sociis natoque penati'),
(6, 'gravida.', 'Suspendisse eleifend. Cras sed'),
(7, 'risus.', 'est ac mattis semper, dui lect'),
(8, 'Donec luctus', 'neque. In ornare sagittis feli'),
(9, 'Vivamus sit', 'malesuada fames ac turpis eges'),
(10, 'interdum ligula eu', 'libero at auctor ullamcorper, '),
(11, 'vitae', 'eget metus. In nec orci. Donec'),
(12, 'feugiat tellus', 'sollicitudin commodo ipsum. Su'),
(13, 'Mauris non', 'orci. Ut semper pretium neque.'),
(14, 'Suspendisse', 'velit. Quisque varius. Nam por'),
(15, 'nisl arcu iaculis', 'at auctor ullamcorper, nisl ar'),
(16, 'magna', 'orci sem eget massa. Suspendis'),
(17, 'justo eu arcu.', 'mus. Aenean eget magna. Suspen'),
(18, 'dictum augue malesuada', 'nec urna et arcu imperdiet ull'),
(19, 'non, hendrerit id,', 'eu, odio. Phasellus at augue'),
(20, 'a feugiat', 'mattis velit justo nec ante.'),
(21, 'tincidunt', 'sem. Nulla interdum. Curabitur'),
(22, 'metus facilisis lorem', 'iaculis nec, eleifend non, dap'),
(23, 'ut,', 'amet ante. Vivamus non lorem v'),
(24, 'aliquet,', 'mi tempor lorem, eget mollis l'),
(25, 'lacinia mattis. Integer', 'mattis. Integer eu lacus. Quis'),
(26, 'aliquet molestie', 'mollis. Phasellus libero mauri'),
(27, 'Vestibulum', 'scelerisque, lorem ipsum sodal'),
(28, 'sodales at, velit.', 'adipiscing elit. Etiam laoreet'),
(29, 'et pede.', 'cursus a, enim. Suspendisse al'),
(30, 'rutrum, justo. Praesent', 'nunc sit amet metus. Aliquam e'),
(31, 'ridiculus mus. Proin', 'Aliquam vulputate ullamcorper '),
(32, 'ligula.', 'vitae, orci. Phasellus dapibus'),
(33, 'eleifend non,', 'pellentesque massa lobortis ul'),
(34, 'vel', 'Aenean eget metus. In nec orci'),
(35, 'vitae, aliquet', 'sociis natoque penatibus et ma'),
(36, 'a,', 'massa lobortis ultrices. Vivam'),
(37, 'metus.', 'ut ipsum ac mi eleifend egesta'),
(38, 'nisl. Quisque', 'Phasellus elit pede, malesuada'),
(39, 'ullamcorper,', 'dictum. Phasellus in felis. Nu'),
(40, 'egestas', 'Donec porttitor tellus non mag'),
(41, 'non,', 'diam. Duis mi enim, condimentu'),
(42, 'sollicitudin a, malesuada', 'auctor quis, tristique ac, ele'),
(43, 'sem elit, pharetra', 'diam. Duis mi enim, condimentu'),
(44, 'elit elit fermentum', 'ante. Maecenas mi felis, adipi'),
(45, 'quam. Pellentesque habitant', 'enim. Etiam gravida molestie a'),
(46, 'auctor odio', 'magna. Phasellus dolor elit, p'),
(47, 'sit', 'scelerisque neque. Nullam nisl'),
(48, 'Aliquam fringilla', 'dictum mi, ac mattis velit jus'),
(49, 'ornare. Fusce mollis.', 'Suspendisse ac metus vitae vel'),
(50, 'penatibus', 'vitae, posuere at, velit. Cras'),
(51, 'imperdiet, erat', 'fringilla euismod enim. Etiam '),
(52, 'dignissim tempor', 'amet, risus. Donec nibh enim, '),
(53, 'urna suscipit', 'a, magna. Lorem ipsum dolor'),
(54, 'semper auctor. Mauris', 'mauris ipsum porta elit, a feu'),
(55, 'fermentum', 'dui. Suspendisse ac metus vita'),
(56, 'eleifend nec,', 'eget tincidunt dui augue eu te'),
(57, 'ut dolor', 'placerat, orci lacus vestibulu'),
(58, 'amet orci.', 'Maecenas libero est, congue a,'),
(59, 'lorem', 'lacus, varius et, euismod et, '),
(60, 'Praesent', 'sit amet, faucibus ut, nulla. '),
(61, 'felis orci,', 'vitae, orci. Phasellus dapibus'),
(62, 'eleifend, nunc', 'vitae erat vel pede blandit co'),
(63, 'non,', 'consequat, lectus sit amet luc'),
(64, 'purus', 'nibh lacinia orci, consectetue'),
(65, 'commodo', 'iaculis quis, pede. Praesent e'),
(66, 'pharetra nibh.', 'magna. Suspendisse tristique n'),
(67, 'risus. Donec nibh', 'sodales elit erat vitae risus.'),
(68, 'nunc, ullamcorper eu,', 'augue scelerisque mollis. Phas'),
(69, 'nibh sit', 'elementum at, egestas a, scele'),
(70, 'eget laoreet posuere,', 'luctus, ipsum leo elementum se'),
(71, 'sociosqu', 'neque. Nullam nisl. Maecenas m'),
(72, 'et, rutrum', 'rutrum urna, nec luctus felis'),
(73, 'aliquam, enim', 'erat neque non quam. Pellentes'),
(74, 'ac, feugiat non,', 'at, nisi. Cum sociis natoque p'),
(75, 'augue scelerisque', 'amet ante. Vivamus non lorem v'),
(76, 'id', 'neque vitae semper egestas, ur'),
(77, 'molestie', 'commodo ipsum. Suspendisse non'),
(78, 'fermentum', 'Aliquam erat volutpat. Nulla d'),
(79, 'Suspendisse aliquet,', 'ipsum. Curabitur consequat, le'),
(80, 'Nam nulla magna,', 'vitae mauris sit amet lorem se'),
(81, 'Cras', 'nisi. Aenean eget metus. In'),
(82, 'nulla ante,', 'posuere vulputate, lacus. Cras'),
(83, 'risus. Nunc', 'Ut tincidunt orci quis lectus.'),
(84, 'nec, mollis', 'enim, gravida sit amet, dapibu'),
(85, 'est tempor', 'Integer eu lacus. Quisque impe'),
(86, 'sed turpis', 'cursus luctus, ipsum leo eleme'),
(87, 'feugiat non, lobortis', 'in magna. Phasellus dolor elit'),
(88, 'amet, faucibus ut,', 'rhoncus id, mollis nec, cursus'),
(89, 'Suspendisse tristique neque', 'nisi magna sed dui. Fusce aliq'),
(90, 'lorem', 'magna. Lorem ipsum dolor sit a'),
(91, 'purus, in molestie', 'Vestibulum ante ipsum primis i'),
(92, 'Aliquam erat volutpat.', 'mollis vitae, posuere at, veli'),
(93, 'sit amet lorem', 'mauris. Suspendisse aliquet mo'),
(94, 'posuere cubilia Curae;', 'vel est tempor bibendum. Donec'),
(95, 'parturient', 'Lorem ipsum dolor sit amet, co'),
(96, 'sodales', 'Sed dictum. Proin eget odio.'),
(97, 'felis', 'Donec sollicitudin adipiscing '),
(98, 'dolor', 'vulputate, lacus. Cras interdu'),
(99, 'aliquet molestie', 'cursus. Nunc mauris elit, dict'),
(100, 'vulputate, nisi sem', 'Cum sociis natoque penatibus e'),
(101, 'sodales', 'non quam. Pellentesque habitan'),
(102, 'pellentesque, tellus sem', 'adipiscing, enim mi tempor lor'),
(103, 'ipsum leo', 'quis lectus. Nullam suscipit, '),
(104, 'lacus. Ut', 'egestas lacinia. Sed congue, e'),
(105, 'ornare, libero', 'augue malesuada malesuada. Int'),
(106, 'vel,', 'Donec vitae erat vel pede blan'),
(107, 'viverra. Donec', 'sit amet, dapibus id, blandit '),
(108, 'rhoncus. Nullam', 'tempus, lorem fringilla ornare'),
(109, 'ac risus. Morbi', 'sociis natoque penatibus et ma'),
(110, 'lacinia', 'non ante bibendum ullamcorper.'),
(111, 'ac arcu. Nunc', 'ut eros non enim commodo hendr'),
(112, 'ac', 'tristique neque venenatis lacu'),
(113, 'porttitor', 'sagittis felis. Donec tempor, '),
(114, 'Morbi quis', 'Nulla facilisi. Sed neque. Sed'),
(115, 'a, enim.', 'fringilla, porttitor vulputate'),
(116, 'ut dolor', 'ornare lectus justo eu arcu. M'),
(117, 'magna.', 'leo. Cras vehicula aliquet lib'),
(118, 'urna suscipit nonummy.', 'malesuada vel, convallis in, c'),
(119, 'nec, mollis', 'consectetuer adipiscing elit. '),
(120, 'tempus', 'dignissim. Maecenas ornare ege'),
(121, 'Etiam', 'ultrices. Vivamus rhoncus. Don'),
(122, 'amet risus.', 'vel pede blandit congue. In sc'),
(123, 'quis,', 'In condimentum. Donec at arcu.'),
(124, 'orci tincidunt', 'a feugiat tellus lorem eu'),
(125, 'lacus pede', 'facilisis eget, ipsum. Donec s'),
(126, 'Donec', 'malesuada malesuada. Integer i'),
(127, 'at pretium', 'a tortor. Nunc commodo auctor '),
(128, 'tristique pellentesque,', 'sit amet, risus. Donec nibh'),
(129, 'lacus. Cras', 'risus, at fringilla purus maur'),
(130, 'eros.', 'tincidunt tempus risus. Donec '),
(131, 'posuere cubilia Curae;', 'ornare, facilisis eget, ipsum.'),
(132, 'magna. Cras', 'ornare tortor at risus. Nunc'),
(133, 'aliquet. Proin velit.', 'dolor dapibus gravida. Aliquam'),
(134, 'Integer in magna.', 'ipsum. Phasellus vitae mauris '),
(135, 'elit,', 'lacus. Nulla tincidunt, neque '),
(136, 'lobortis quis,', 'morbi tristique senectus et ne'),
(137, 'Sed eu eros.', 'sem ut dolor dapibus gravida. '),
(138, 'laoreet', 'elit. Aliquam auctor, velit eg'),
(139, 'adipiscing lobortis', 'vel, vulputate eu, odio. Phase'),
(140, 'Duis', 'metus. In nec orci. Donec'),
(141, 'Cum sociis natoque', 'Quisque fringilla euismod enim'),
(142, 'semper, dui lectus', 'convallis ligula. Donec luctus'),
(143, 'tellus', 'posuere vulputate, lacus. Cras'),
(144, 'eget metus.', 'Curabitur ut odio vel est'),
(145, 'tincidunt', 'ut odio vel est tempor bibendu'),
(146, 'Mauris', 'euismod ac, fermentum vel, mau'),
(147, 'metus vitae velit', 'sit amet, consectetuer adipisc'),
(148, 'Fusce', 'eu, accumsan sed, facilisis vi'),
(149, 'nec orci.', 'pharetra nibh. Aliquam ornare,'),
(150, 'massa. Quisque', 'luctus vulputate, nisi sem sem'),
(151, 'nisl sem, consequat', 'augue porttitor interdum. Sed '),
(152, 'tristique', 'nibh vulputate mauris sagittis'),
(153, 'diam luctus lobortis.', 'convallis dolor. Quisque tinci'),
(154, 'sem eget massa.', 'sapien, cursus in, hendrerit c'),
(155, 'Sed', 'ac risus. Morbi metus. Vivamus'),
(156, 'venenatis lacus.', 'enim mi tempor lorem, eget mol'),
(157, 'adipiscing elit. Aliquam', 'natoque penatibus et magnis di'),
(158, 'rhoncus.', 'sit amet lorem semper auctor. '),
(159, 'a odio', 'elit fermentum risus, at fring'),
(160, 'Praesent luctus. Curabitur', 'sociis natoque penatibus et ma'),
(161, 'gravida nunc sed', 'dui augue eu tellus. Phasellus'),
(162, 'bibendum fermentum metus.', 'ultricies adipiscing, enim mi '),
(163, 'elit. Etiam laoreet,', 'eget odio. Aliquam vulputate u'),
(164, 'dis parturient', 'sem. Nulla interdum. Curabitur'),
(165, 'at augue', 'lectus ante dictum mi, ac matt'),
(166, 'vitae velit', 'libero. Morbi accumsan laoreet'),
(167, 'dictum eu, placerat', 'blandit congue. In scelerisque'),
(168, 'sed,', 'non lorem vitae odio sagittis '),
(169, 'Phasellus dapibus', 'dui. Cras pellentesque. Sed di'),
(170, 'hendrerit a, arcu.', 'neque. In ornare sagittis feli'),
(171, 'cubilia Curae; Phasellus', 'ac ipsum. Phasellus vitae maur'),
(172, 'faucibus leo, in', 'blandit at, nisi. Cum sociis n'),
(173, 'libero lacus, varius', 'Phasellus vitae mauris sit ame'),
(174, 'Donec porttitor', 'porttitor interdum. Sed auctor'),
(175, 'lacinia mattis.', 'magna nec quam. Curabitur vel '),
(176, 'Nunc mauris sapien,', 'Phasellus ornare. Fusce mollis'),
(177, 'eget nisi', 'metus. Vivamus euismod urna. N'),
(178, 'libero nec', 'sapien imperdiet ornare. In fa'),
(179, 'sit amet,', 'amet, faucibus ut, nulla. Cras'),
(180, 'non', 'Sed pharetra, felis eget variu'),
(181, 'massa. Suspendisse eleifend.', 'erat. Vivamus nisi. Mauris nul'),
(182, 'odio. Aliquam vulputate', 'ridiculus mus. Proin vel arcu '),
(183, 'id, erat. Etiam', 'tristique aliquet. Phasellus f'),
(184, 'fermentum risus,', 'consequat nec, mollis vitae, p'),
(185, 'ultricies dignissim', 'risus a ultricies adipiscing, '),
(186, 'bibendum. Donec felis', 'risus quis diam luctus loborti'),
(187, 'erat, eget tincidunt', 'porttitor vulputate, posuere v'),
(188, 'ultricies sem', 'Mauris vel turpis. Aliquam adi'),
(189, 'tellus', 'et netus et malesuada fames ac'),
(190, 'Nulla aliquet. Proin', 'Cras vulputate velit eu sem. P'),
(191, 'urna justo', 'sollicitudin a, malesuada id, '),
(192, 'tincidunt', 'elit, pretium et, rutrum non, '),
(193, 'egestas blandit. Nam', 'vel, venenatis vel, faucibus i'),
(194, 'urna', 'ipsum. Phasellus vitae mauris '),
(195, 'dictum', 'orci, consectetuer euismod est'),
(196, 'vulputate,', 'semper et, lacinia vitae, soda'),
(197, 'in molestie tortor', 'tincidunt orci quis lectus. Nu'),
(198, 'elit elit fermentum', 'tincidunt adipiscing. Mauris m'),
(199, 'sed', 'est. Nunc laoreet lectus quis '),
(200, 'arcu. Vestibulum', 'ante, iaculis nec, eleifend no');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_compra`
--

CREATE TABLE IF NOT EXISTS `pagos_compra` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del pago',
  `id_compra` int(11) NOT NULL COMMENT 'identificador de la compra a la que pagamos',
  `fecha` date NOT NULL COMMENT 'fecha en que se abono',
  `monto` float NOT NULL COMMENT 'monto que se abono',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_compra_compra` (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pagos_compra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_venta`
--

CREATE TABLE IF NOT EXISTS `pagos_venta` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de pago del cliente',
  `id_venta` int(11) NOT NULL COMMENT 'id de la venta a la que se esta pagando',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha en que se registro el pago',
  `monto` float NOT NULL COMMENT 'total de credito del cliente',
  PRIMARY KEY (`id_pago`),
  KEY `pagos_venta_venta` (`id_venta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=162 ;

--
-- Volcar la base de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `fecha`, `monto`) VALUES
(1, 1, '2010-07-24 07:16:10', 385),
(2, 1, '2010-07-24 07:16:10', 206),
(3, 1, '2010-07-24 07:16:10', 63),
(4, 1, '2010-07-24 07:16:10', 219),
(5, 1, '2010-07-24 07:16:10', 151),
(6, 1, '2010-07-24 07:16:10', 116),
(7, 1, '2010-07-24 07:16:10', 44),
(8, 1, '2010-07-24 07:16:10', 33),
(9, 1, '2010-07-24 07:16:10', 34),
(10, 1, '2010-07-24 07:16:10', 59),
(11, 1, '2010-07-24 07:16:10', 10),
(12, 2, '2010-07-24 07:16:17', 45),
(13, 2, '2010-07-24 07:16:17', 103),
(14, 2, '2010-07-24 07:16:17', 1),
(15, 2, '2010-07-24 07:16:17', 42),
(16, 5, '2010-07-24 07:16:22', 23),
(17, 5, '2010-07-24 07:16:22', 20),
(18, 10, '2010-07-24 07:16:28', 5),
(19, 10, '2010-07-24 07:16:28', 198),
(20, 10, '2010-07-24 07:16:28', 161),
(21, 10, '2010-07-24 07:16:28', 74),
(22, 10, '2010-07-24 07:16:28', 182),
(23, 10, '2010-07-24 07:16:28', 24),
(24, 10, '2010-07-24 07:16:28', 114),
(25, 10, '2010-07-24 07:16:28', 64),
(26, 10, '2010-07-24 07:16:28', 41),
(27, 10, '2010-07-24 07:16:28', 61),
(28, 10, '2010-07-24 07:16:28', 2),
(29, 10, '2010-07-24 07:16:28', 26),
(30, 10, '2010-07-24 07:16:28', 4),
(31, 10, '2010-07-24 07:16:28', 45),
(32, 12, '2010-07-24 07:16:32', 16),
(33, 12, '2010-07-24 07:16:32', 143),
(34, 12, '2010-07-24 07:16:32', 395),
(35, 12, '2010-07-24 07:16:32', 154),
(36, 12, '2010-07-24 07:16:32', 11),
(37, 12, '2010-07-24 07:16:32', 102),
(38, 12, '2010-07-24 07:16:32', 44),
(39, 12, '2010-07-24 07:16:32', 215),
(40, 12, '2010-07-24 07:16:32', 88),
(41, 14, '2010-07-24 07:16:34', 53),
(42, 14, '2010-07-24 07:16:34', 7),
(43, 14, '2010-07-24 07:16:34', 18),
(44, 14, '2010-07-24 07:16:34', 35),
(45, 14, '2010-07-24 07:16:34', 25),
(46, 14, '2010-07-24 07:16:34', 4),
(47, 14, '2010-07-24 07:16:34', 30),
(48, 14, '2010-07-24 07:16:34', 13),
(49, 16, '2010-07-24 07:16:36', 195),
(50, 16, '2010-07-24 07:16:36', 115),
(51, 16, '2010-07-24 07:16:36', 13),
(52, 16, '2010-07-24 07:16:36', 9),
(53, 16, '2010-07-24 07:16:36', 202),
(54, 16, '2010-07-24 07:16:36', 119),
(55, 16, '2010-07-24 07:16:36', 91),
(56, 16, '2010-07-24 07:16:36', 95),
(57, 16, '2010-07-24 07:16:36', 70),
(58, 16, '2010-07-24 07:16:36', 60),
(59, 16, '2010-07-24 07:16:36', 31),
(60, 16, '2010-07-24 07:16:36', 12),
(61, 18, '2010-07-24 07:16:37', 25),
(62, 18, '2010-07-24 07:16:37', 55),
(63, 18, '2010-07-24 07:16:37', 34),
(64, 18, '2010-07-24 07:16:37', 71),
(65, 18, '2010-07-24 07:16:37', 26),
(66, 18, '2010-07-24 07:16:37', 47),
(67, 18, '2010-07-24 07:16:37', 15),
(68, 18, '2010-07-24 07:16:37', 7),
(69, 18, '2010-07-24 07:16:37', 32),
(70, 18, '2010-07-24 07:16:37', 18),
(71, 18, '2010-07-24 07:16:37', 2),
(72, 18, '2010-07-24 07:16:37', 6),
(73, 18, '2010-07-24 07:16:37', 9),
(74, 18, '2010-07-24 07:16:37', 12),
(75, 18, '2010-07-24 07:16:37', 10),
(76, 19, '2010-07-24 07:16:38', 315),
(77, 19, '2010-07-24 07:16:38', 45),
(78, 19, '2010-07-24 07:16:38', 137),
(79, 19, '2010-07-24 07:16:38', 17),
(80, 19, '2010-07-24 07:16:38', 47),
(81, 19, '2010-07-24 07:16:38', 37),
(82, 20, '2010-07-24 07:16:39', 102),
(83, 20, '2010-07-24 07:16:39', 175),
(84, 20, '2010-07-24 07:16:39', 87),
(85, 20, '2010-07-24 07:16:39', 150),
(86, 20, '2010-07-24 07:16:39', 174),
(87, 20, '2010-07-24 07:16:39', 117),
(88, 20, '2010-07-24 07:16:39', 40),
(89, 20, '2010-07-24 07:16:39', 91),
(90, 21, '2010-07-24 07:16:40', 36),
(91, 21, '2010-07-24 07:16:40', 53),
(92, 21, '2010-07-24 07:16:40', 4),
(93, 21, '2010-07-24 07:16:40', 6),
(94, 21, '2010-07-24 07:16:40', 28),
(95, 21, '2010-07-24 07:16:40', 16),
(96, 21, '2010-07-24 07:16:40', 11),
(97, 21, '2010-07-24 07:16:40', 22),
(98, 21, '2010-07-24 07:16:40', 23),
(99, 21, '2010-07-24 07:16:40', 9),
(100, 21, '2010-07-24 07:16:40', 16),
(101, 21, '2010-07-24 07:16:40', 4),
(102, 21, '2010-07-24 07:16:40', 6),
(103, 21, '2010-07-24 07:16:40', 8),
(104, 30, '2010-07-24 07:16:50', 117),
(105, 30, '2010-07-24 07:16:50', 73),
(106, 30, '2010-07-24 07:16:50', 69),
(107, 30, '2010-07-24 07:16:50', 46),
(108, 30, '2010-07-24 07:16:50', 27),
(109, 30, '2010-07-24 07:16:50', 18),
(110, 30, '2010-07-24 07:16:50', 47),
(111, 30, '2010-07-24 07:16:50', 38),
(112, 30, '2010-07-24 07:16:50', 2),
(113, 30, '2010-07-24 07:16:50', 9),
(114, 30, '2010-07-24 07:16:50', 6),
(115, 30, '2010-07-24 07:16:50', 20),
(116, 34, '2010-07-24 07:16:57', 295),
(117, 34, '2010-07-24 07:16:57', 114),
(118, 34, '2010-07-24 07:16:57', 9),
(119, 34, '2010-07-24 07:16:57', 24),
(120, 34, '2010-07-24 07:16:57', 135),
(121, 34, '2010-07-24 07:16:57', 17),
(122, 34, '2010-07-24 07:16:57', 195),
(123, 34, '2010-07-24 07:16:57', 27),
(124, 34, '2010-07-24 07:16:57', 90),
(125, 34, '2010-07-24 07:16:57', 60),
(126, 34, '2010-07-24 07:16:57', 103),
(127, 34, '2010-07-24 07:16:57', 76),
(128, 34, '2010-07-24 07:16:57', 35),
(129, 34, '2010-07-24 07:16:57', 35),
(130, 34, '2010-07-24 07:16:57', 66),
(131, 36, '2010-07-24 07:17:01', 30),
(132, 37, '2010-07-24 07:17:03', 242),
(133, 37, '2010-07-24 07:17:03', 309),
(134, 41, '2010-07-24 07:17:08', 201),
(135, 41, '2010-07-24 07:17:08', 177),
(136, 42, '2010-07-24 07:17:09', 42),
(137, 43, '2010-07-24 07:17:11', 411),
(138, 43, '2010-07-24 07:17:11', 140),
(139, 43, '2010-07-24 07:17:11', 96),
(140, 43, '2010-07-24 07:17:11', 18),
(141, 43, '2010-07-24 07:17:11', 229),
(142, 43, '2010-07-24 07:17:11', 89),
(143, 45, '2010-07-24 07:17:14', 40),
(144, 45, '2010-07-24 07:17:14', 40),
(145, 45, '2010-07-24 07:17:14', 61),
(146, 45, '2010-07-24 07:17:14', 35),
(147, 45, '2010-07-24 07:17:14', 36),
(148, 45, '2010-07-24 07:17:14', 27),
(149, 45, '2010-07-24 07:17:14', 40),
(150, 45, '2010-07-24 07:17:14', 38),
(151, 45, '2010-07-24 07:17:14', 23),
(152, 45, '2010-07-24 07:17:14', 17),
(153, 45, '2010-07-24 07:17:14', 25),
(154, 45, '2010-07-24 07:17:14', 3),
(155, 45, '2010-07-24 07:17:14', 1),
(156, 45, '2010-07-24 07:17:14', 15),
(157, 48, '2010-07-24 07:17:18', 99),
(158, 48, '2010-07-24 07:17:18', 83),
(159, 48, '2010-07-24 07:17:18', 32),
(160, 48, '2010-07-24 07:17:18', 40),
(161, 52, '2010-07-24 07:17:25', 61);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `permisos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_proveedor`
--

CREATE TABLE IF NOT EXISTS `productos_proveedor` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del producto',
  `clave_producto` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'clave de producto para el proveedor',
  `id_proveedor` int(11) NOT NULL COMMENT 'clave del proveedor',
  `id_inventario` int(11) NOT NULL COMMENT 'clave con la que entra a nuestro inventario',
  `descripcion` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripcion del producto que nos vende el proveedor',
  `precio` int(11) NOT NULL COMMENT 'precio al que se compra el producto (sin descuento)',
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `clave_producto` (`clave_producto`,`id_proveedor`),
  UNIQUE KEY `id_proveedor` (`id_proveedor`,`id_inventario`),
  KEY `productos_proveedor_proveedor` (`id_proveedor`),
  KEY `productos_proveedor_producto` (`id_inventario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Volcar la base de datos para la tabla `productos_proveedor`
--

INSERT INTO `productos_proveedor` (`id_producto`, `clave_producto`, `id_proveedor`, `id_inventario`, `descripcion`, `precio`) VALUES
(1, '218', 47, 171, 'descripcion Proveedor', 17),
(2, '99', 36, 63, 'descripcion Proveedor', 6),
(3, '110', 19, 91, 'descripcion Proveedor', 18),
(4, '40', 14, 26, 'descripcion Proveedor', 7),
(5, '158', 36, 122, 'descripcion Proveedor', 20),
(6, '177', 26, 151, 'descripcion Proveedor', 18),
(7, '187', 19, 168, 'descripcion Proveedor', 10),
(8, '212', 29, 183, 'descripcion Proveedor', 3),
(9, '61', 15, 46, 'descripcion Proveedor', 16),
(10, '107', 22, 85, 'descripcion Proveedor', 11),
(11, '118', 19, 99, 'descripcion Proveedor', 4),
(12, '202', 28, 174, 'descripcion Proveedor', 4),
(13, '202', 40, 162, 'descripcion Proveedor', 13),
(14, '194', 20, 174, 'descripcion Proveedor', 19),
(15, '172', 13, 159, 'descripcion Proveedor', 19),
(16, '112', 18, 94, 'descripcion Proveedor', 8),
(17, '98', 46, 52, 'descripcion Proveedor', 15),
(18, '130', 8, 122, 'descripcion Proveedor', 10),
(19, '34', 13, 21, 'descripcion Proveedor', 17),
(20, '93', 44, 49, 'descripcion Proveedor', 6),
(21, '58', 49, 9, 'descripcion Proveedor', 11),
(22, '79', 25, 54, 'descripcion Proveedor', 11),
(23, '197', 40, 157, 'descripcion Proveedor', 9),
(24, '137', 2, 135, 'descripcion Proveedor', 10),
(25, '175', 25, 150, 'descripcion Proveedor', 2),
(26, '66', 24, 42, 'descripcion Proveedor', 17),
(27, '169', 30, 139, 'descripcion Proveedor', 1),
(28, '133', 6, 127, 'descripcion Proveedor', 18),
(29, '89', 2, 87, 'descripcion Proveedor', 6),
(30, '103', 7, 96, 'descripcion Proveedor', 13),
(31, '48', 31, 17, 'descripcion Proveedor', 15),
(32, '46', 17, 29, 'descripcion Proveedor', 13),
(33, '183', 32, 151, 'descripcion Proveedor', 12),
(34, '165', 9, 156, 'descripcion Proveedor', 11),
(35, '18', 2, 16, 'descripcion Proveedor', 18),
(36, '127', 41, 86, 'descripcion Proveedor', 1),
(37, '59', 50, 9, 'descripcion Proveedor', 2),
(38, '36', 27, 9, 'descripcion Proveedor', 18),
(39, '195', 37, 158, 'descripcion Proveedor', 20),
(40, '133', 43, 90, 'descripcion Proveedor', 5),
(41, '113', 33, 80, 'descripcion Proveedor', 12),
(42, '70', 5, 65, 'descripcion Proveedor', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del proveedor',
  `rfc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'rfc del proveedor',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del proveedor',
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'direccion del proveedor',
  `telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'telefono',
  `e_mail` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email del provedor',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=51 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`) VALUES
(1, 'VLQ10BUR1AF', 'Macey N. Schroeder', '380-6370 Erat Av.', '962-548-4136', 'lobortis.risus@eueuismod.ca'),
(2, 'QAX47ALX6HL', 'Kane T. Cherry', 'Ap #547-258 Pharetra, Av.', '298-701-3537', 'eu.dolor@vulputatemauris.edu'),
(3, 'VGE21UPP3TI', 'Buffy B. Lyons', 'P.O. Box 593, 4503 Dolor Rd.', '856-210-9623', 'malesuada@Maecenas.org'),
(4, 'MOJ01RUM8JN', 'Iris S. Vang', 'Ap #604-9897 Amet, Road', '860-722-0462', 'vitae.mauris.sit@quistristiqueac.org'),
(5, 'UNF59AYM4WF', 'Jack V. Dale', 'Ap #715-2168 Arcu St.', '494-531-7224', 'et.netus@Aliquam.edu'),
(6, 'VBC75OQU0WQ', 'Ahmed G. Bell', 'Ap #697-7305 Ullamcorper, Street', '208-821-3895', 'sapien.Nunc.pulvinar@aliquetnec.edu'),
(7, 'FNR83RRY7TF', 'Herman W. Andrews', '1256 Tristique Ave', '683-712-6717', 'orci@nequevitaesemper.org'),
(8, 'OGY29WYR0IS', 'Timothy L. Hatfield', 'P.O. Box 756, 8899 Lectus Rd.', '790-462-1839', 'ornare@gravidasit.ca'),
(9, 'ULS71UBV6LG', 'Leila H. Weber', 'P.O. Box 297, 9366 Eget, Av.', '481-574-5030', 'Curae;.Phasellus@tellusimperdietnon.ca'),
(10, 'OCT69LBF0ZM', 'Genevieve M. Shepherd', '4616 Arcu. Ave', '942-492-7079', 'dui.nec.tempus@tempusscelerisquelorem.ca'),
(11, 'RBU11ZEC4NY', 'Wendy S. Blake', 'Ap #359-7476 Tristique Rd.', '129-737-7256', 'Sed.diam.lorem@dolorsit.edu'),
(12, 'MXD60PEZ5GS', 'Hilary J. Richmond', '961-4995 Nulla Rd.', '222-857-5218', 'faucibus@nuncQuisque.com'),
(13, 'AYA18OKP9EY', 'Melyssa I. Owens', '916-4437 Eu St.', '341-863-7651', 'Lorem.ipsum@amet.edu'),
(14, 'IKP70OXZ2QH', 'Sierra T. Ryan', 'P.O. Box 272, 4432 Feugiat. Street', '987-753-8039', 'metus.Vivamus@vestibulum.ca'),
(15, 'AAN90TOH6JA', 'Gwendolyn C. Mcmahon', 'P.O. Box 468, 7968 Cursus St.', '791-504-4687', 'fermentum.convallis@VivamusrhoncusDonec.com'),
(16, 'SSA29QQA0FD', 'Lani D. England', '9099 In, St.', '239-685-4522', 'mi.ac@auguescelerisque.org'),
(17, 'PHP29QGD7UK', 'Teegan M. Rodriquez', 'Ap #231-6337 Metus Ave', '492-854-0936', 'feugiat.Lorem.ipsum@arcu.org'),
(18, 'KFF63ZLU8GG', 'Martha X. Walsh', '807-7718 In Rd.', '132-235-7259', 'aliquet.sem@ultriciesornare.com'),
(19, 'BEJ59GTF8RD', 'Michael J. Duran', '845-2387 Elementum, St.', '617-443-3047', 'malesuada.vel.convallis@purusaccumsaninterdum.edu'),
(20, 'IYL01IUG9AQ', 'Jaime C. Benson', 'Ap #479-974 Tincidunt Street', '184-489-2770', 'dolor.sit.amet@natoquepenatibus.com'),
(21, 'LUU20UGR2IT', 'Karina L. Melton', 'Ap #811-1443 Posuere Ave', '960-238-2085', 'iaculis.enim.sit@Fusce.edu'),
(22, 'GOV42BVY5LK', 'Kato X. Cameron', '9811 Condimentum Street', '303-224-0713', 'Suspendisse.tristique.neque@sedorci.edu'),
(23, 'FOK33JXU7KY', 'Maxwell F. Phillips', 'Ap #596-697 Dis Ave', '774-756-0324', 'ornare.sagittis@Loremipsumdolor.ca'),
(24, 'ZJG81ZHC6ZH', 'Carol V. Parker', 'Ap #549-2611 Dignissim Ave', '308-341-7964', 'Duis.dignissim.tempor@pellentesqueeget.ca'),
(25, 'GKU54LNT6FP', 'Finn D. Wynn', '910-1511 Ut Street', '733-520-9153', 'placerat.velit@aliquamiaculis.edu'),
(26, 'CIQ60FWR5WU', 'Griffin F. Brennan', '6423 Inceptos Street', '639-621-1555', 'scelerisque@perconubianostra.org'),
(27, 'JXU57MDP2MS', 'Jana Y. Reed', 'Ap #781-3261 Mauris Road', '104-369-8835', 'semper@maurisutmi.ca'),
(28, 'MSI53PCA0VY', 'Irene M. Day', 'Ap #319-9790 Sit Rd.', '889-673-1740', 'risus.Donec.egestas@ipsumsodales.org'),
(29, 'DUF28HBU7GG', 'Urielle V. Santana', 'Ap #153-7578 Maecenas Av.', '370-554-5371', 'scelerisque.neque.Nullam@eros.org'),
(30, 'LJX41QTK5OG', 'Odysseus X. Graves', 'P.O. Box 591, 2084 Dolor St.', '706-334-3186', 'sed.dui.Fusce@vitae.com'),
(31, 'NHZ26OVY2FV', 'Ann A. Franklin', 'Ap #727-2124 In Rd.', '906-425-9990', 'lorem@augueut.com'),
(32, 'UCX60EVV2DW', 'Shay P. Murphy', 'Ap #673-5383 Curabitur Av.', '980-980-9044', 'tellus.sem@Fusce.org'),
(33, 'XNX73DXF9OV', 'Kenyon K. Hendrix', '166-157 Dictum Avenue', '390-294-6328', 'Aliquam.auctor.velit@egetmassaSuspendisse.ca'),
(34, 'NUA49AZV5JF', 'Patience O. Fulton', '7867 Eu Street', '497-851-1401', 'augue@dolor.edu'),
(35, 'CFF11ARO2KW', 'Raven F. Delgado', 'Ap #589-7848 Nunc St.', '519-659-8848', 'tristique.neque@tellusloremeu.ca'),
(36, 'BAX75DDL9JQ', 'Juliet F. Head', 'Ap #257-1294 Sed St.', '370-265-0219', 'mauris@Integer.ca'),
(37, 'TQC80AHR5RX', 'Kyra P. Solomon', '5134 Scelerisque Avenue', '101-603-7048', 'dis@mauriserateget.edu'),
(38, 'XQF99XKP7ZL', 'Haley L. Peterson', 'Ap #402-3606 Molestie Street', '137-941-7465', 'ut@atpede.edu'),
(39, 'ISY73TTT1LE', 'Hop R. Oneill', '1485 Elit St.', '765-949-6914', 'quis.pede@posuerecubilia.org'),
(40, 'LMZ22TIR9TJ', 'Levi K. Keller', 'P.O. Box 162, 1806 Feugiat Rd.', '141-742-1826', 'euismod.et@arcu.ca'),
(41, 'HHQ69NJI1VT', 'Kalia X. Jennings', '877 Hendrerit Rd.', '865-184-2401', 'Vivamus.sit@dolor.com'),
(42, 'RKJ17SIP4VB', 'Garrison S. Heath', '112-401 Vel Rd.', '584-824-9750', 'dui.Fusce@porttitorvulputate.com'),
(43, 'WRA12LZI7KB', 'Brody O. Stevens', 'P.O. Box 971, 3574 Suspendisse Avenue', '828-893-8535', 'at.iaculis@aenim.org'),
(44, 'NKC57XXU0UI', 'Chase L. Oconnor', '442-8447 Vitae St.', '232-185-5318', 'ipsum.porta@Quisqueornare.ca'),
(45, 'YRW84XPV0HG', 'Darius O. Fuentes', 'Ap #989-3780 Tellus. St.', '789-320-1795', 'non.quam@diamDuis.org'),
(46, 'JUP96RGW9OK', 'Kiona D. Petersen', '4235 Sed St.', '916-133-9908', 'mus.Proin@anunc.edu'),
(47, 'CUS70PSO9HH', 'Brennan K. Santana', '244-8835 Sit Street', '542-442-2867', 'posuere.cubilia.Curae;@inmolestietortor.com'),
(48, 'RMA30ARJ1MT', 'Cairo D. Perry', 'Ap #824-8636 Lectus Rd.', '121-317-0848', 'Vivamus@mifringilla.ca'),
(49, 'YWA62DDZ2VD', 'Lewis S. Hoover', 'Ap #673-4506 Nulla Street', '172-156-9964', 'Proin.non.massa@hendreritDonec.org'),
(50, 'CUT27BBX6MQ', 'Amber J. Rocha', '502-4346 Montes, Street', '401-397-8632', 'auctor@Uttincidunt.org');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de cada sucursal',
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre o descripcion de sucursal',
  `direccion` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'direccion de la sucursal',
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- Volcar la base de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `descripcion`, `direccion`) VALUES
(3, 'Descripcion de la sucural 3', '2127 A Street'),
(4, 'Descripcion de la sucural 4', '673-8005 Non Road'),
(5, 'Descripcion de la sucural 5', 'P.O. Box 211, 4360 Sem Street'),
(6, 'Descripcion de la sucural 6', 'Ap #141-3279 Amet, St.'),
(7, 'Descripcion de la sucural 7', 'P.O. Box 434, 401 Adipiscing. St.'),
(8, 'Descripcion de la sucural 8', 'P.O. Box 777, 3956 Ridiculus Road'),
(9, 'Descripcion de la sucural 9', '4582 Nulla Rd.'),
(10, 'Descripcion de la sucural 10', 'P.O. Box 820, 3880 Magna. St.'),
(11, 'Descripcion de la sucural 11', '947-7151 Lorem Rd.'),
(12, 'Descripcion de la sucural 12', 'Ap #575-9024 Sodales Road'),
(13, 'Descripcion de la sucural 13', 'P.O. Box 892, 9492 Congue, Av.'),
(14, 'Descripcion de la sucural 14', 'Ap #910-9538 Integer Avenue'),
(15, 'Descripcion de la sucural 15', 'P.O. Box 905, 963 Aliquam Street'),
(16, 'Descripcion de la sucural 16', 'P.O. Box 661, 1219 Nullam Street'),
(17, 'Descripcion de la sucural 17', 'Ap #830-2370 Tempus Street'),
(18, 'Descripcion de la sucural 18', 'Ap #212-8071 Est. Street'),
(19, 'Descripcion de la sucural 19', 'Ap #721-7537 Lorem, Av.'),
(20, 'Descripcion de la sucural 20', 'Ap #281-1584 Lobortis Street'),
(21, 'Descripcion de la sucural 21', '783-4525 Diam Rd.'),
(22, 'Descripcion de la sucural 22', '6202 Enim. Road'),
(23, 'Descripcion de la sucural 23', 'P.O. Box 340, 5890 Imperdiet Av.'),
(24, 'Descripcion de la sucural 24', 'P.O. Box 257, 1071 Curabitur Rd.'),
(25, 'Descripcion de la sucural 25', 'Ap #104-6388 Sapien. Avenue'),
(26, 'Descripcion de la sucural 26', 'P.O. Box 586, 4244 Auctor Ave'),
(27, 'Descripcion de la sucural 27', 'P.O. Box 993, 1394 Vel Ave'),
(28, 'Descripcion de la sucural 28', 'Ap #234-2072 Sodales Road'),
(29, 'Descripcion de la sucural 29', 'P.O. Box 425, 4026 Pellentesque St.'),
(30, 'Descripcion de la sucural 30', '6696 Fermentum Street'),
(31, 'Descripcion de la sucural 31', '4215 Consequat Avenue'),
(32, 'Descripcion de la sucural 32', 'P.O. Box 895, 5667 Dictum Av.'),
(33, 'Descripcion de la sucural 33', '7331 Ornare, Road'),
(34, 'Descripcion de la sucural 34', 'Ap #320-8103 Aliquam St.'),
(35, 'Descripcion de la sucural 35', '3680 At Avenue'),
(36, 'Descripcion de la sucural 36', '586-4893 Cursus. St.'),
(37, 'Descripcion de la sucural 37', '781-2443 Orci. Av.'),
(38, 'Descripcion de la sucural 38', 'Ap #964-3699 Eleifend Rd.'),
(39, 'Descripcion de la sucural 39', '9932 Orci, Av.'),
(40, 'Descripcion de la sucural 40', 'Ap #445-9226 Quam Avenue'),
(41, 'Descripcion de la sucural 41', 'Ap #648-5372 Sit Rd.'),
(42, 'Descripcion de la sucural 42', 'Ap #798-4104 Velit. Avenue'),
(43, 'Descripcion de la sucural 43', '147-6410 Dictum Street'),
(44, 'Descripcion de la sucural 44', 'P.O. Box 829, 9426 Placerat, Street'),
(45, 'Descripcion de la sucural 45', 'Ap #830-1087 Proin St.'),
(46, 'Descripcion de la sucural 46', 'P.O. Box 425, 4690 At, St.'),
(47, 'Descripcion de la sucural 47', 'P.O. Box 692, 4235 Mattis Av.'),
(48, 'Descripcion de la sucural 48', '402-1322 Elit Road'),
(49, 'Descripcion de la sucural 49', '359-6446 In Rd.'),
(50, 'Descripcion de la sucural 50', '367 Erat. Rd.'),
(51, 'Descripcion de la sucural 51', '5295 Rutrum Av.'),
(52, 'Descripcion de la sucural 52', 'Ap #240-8862 Penatibus Av.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del usuario',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del empleado',
  `usuario` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrasena` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL COMMENT 'Id de la sucursal a que pertenece',
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_1` (`id_sucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `usuario`, `contrasena`, `id_sucursal`) VALUES
(1, 'Paul Wilkinson', 'Alec', '123', 28),
(2, 'Ferris Delgado', 'Lacota', '123', 6),
(3, 'Barry Stevenson', 'Wade', '123', 5),
(4, 'Buckminster Cantrell', 'Hoyt', '123', 46),
(5, 'Kasimir Alexander', 'Xanthus', '123', 29),
(6, 'Hayden Hendricks', 'Madison', '123', 47),
(7, 'Theodore Christian', 'Jane', '123', 16),
(8, 'Merrill Barton', 'Sybil', '123', 26),
(9, 'Ahmed Townsend', 'Valentine', '123', 23),
(10, 'Brody Kennedy', 'Imelda', '123', 15),
(11, 'Fuller Guy', 'Davis', '123', 30),
(12, 'Jasper Neal', 'Garrison', '123', 7),
(13, 'Marshall Frazier', 'Lacota', '123', 9),
(14, 'Nigel Colon', 'Wynter', '123', 35),
(15, 'Flynn Hodge', 'Simon', '123', 6),
(16, 'Hyatt Preston', 'Jocelyn', '123', 29),
(17, 'Lucian Herrera', 'Sonya', '123', 43),
(18, 'Todd Flowers', 'Adena', '123', 18),
(19, 'Marshall Dunn', 'Remedios', '123', 13),
(20, 'Tyler Campbell', 'Demetria', '123', 34),
(21, 'Hector Schmidt', 'Neville', '123', 12),
(22, 'Adam Nichols', 'Raja', '123', 23),
(23, 'Baxter Mooney', 'Ifeoma', '123', 32),
(24, 'Gage Trevino', 'Aphrodite', '123', 5),
(25, 'Elijah Velazquez', 'Rigel', '123', 15),
(26, 'Colby Santana', 'Kalia', '123', 49);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de venta',
  `id_cliente` int(11) NOT NULL COMMENT 'cliente al que se le vendio',
  `tipo_venta` enum('credito','contado') COLLATE utf8_unicode_ci NOT NULL COMMENT 'tipo de venta, contado o credito',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de venta',
  `subtotal` float DEFAULT NULL COMMENT 'subtotal de la venta, puede ser nulo',
  `iva` float DEFAULT NULL COMMENT 'iva agregado por la venta, depende de cada sucursal',
  `id_sucursal` int(11) NOT NULL COMMENT 'sucursal de la venta',
  `id_usuario` int(11) NOT NULL COMMENT 'empleado que lo vendio',
  PRIMARY KEY (`id_venta`),
  KEY `ventas_cliente` (`id_cliente`),
  KEY `ventas_sucursal` (`id_sucursal`),
  KEY `ventas_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Volcar la base de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `id_sucursal`, `id_usuario`) VALUES
(1, 65, 'credito', '2010-07-24 07:16:10', 2826, 0, 35, 14),
(2, 74, 'credito', '2010-07-24 07:16:17', 537, 0, 13, 19),
(3, 77, 'credito', '2010-07-24 07:16:19', NULL, NULL, 13, 19),
(4, 112, 'contado', '2010-07-24 07:16:20', 904, 0, 23, 9),
(5, 25, 'credito', '2010-07-24 07:16:22', 405, 0, 7, 12),
(6, 44, 'contado', '2010-07-24 07:16:23', 363, 0, 43, 17),
(7, 68, 'credito', '2010-07-24 07:16:24', 2180, 0, 23, 9),
(8, 20, 'contado', '2010-07-24 07:16:26', 3878, 0, 23, 9),
(9, 32, 'contado', '2010-07-24 07:16:27', 1015, 0, 5, 3),
(10, 9, 'credito', '2010-07-24 07:16:28', 1344, 0, 6, 15),
(11, 56, 'contado', '2010-07-24 07:16:30', 958, 0, 16, 7),
(12, 190, 'credito', '2010-07-24 07:16:32', 2337, 0, 32, 23),
(13, 163, 'contado', '2010-07-24 07:16:33', 3350, 0, 23, 9),
(14, 17, 'credito', '2010-07-24 07:16:34', 322, 0, 26, 8),
(15, 116, 'contado', '2010-07-24 07:16:35', 1562, 0, 15, 25),
(16, 142, 'credito', '2010-07-24 07:16:36', 1922, 0, 9, 13),
(17, 46, 'contado', '2010-07-24 07:16:36', 1466, 0, 35, 14),
(18, 8, 'credito', '2010-07-24 07:16:37', 769, 0, 47, 6),
(19, 96, 'credito', '2010-07-24 07:16:38', 1330, 0, 7, 12),
(20, 103, 'credito', '2010-07-24 07:16:39', 2062, 0, 15, 10),
(21, 147, 'credito', '2010-07-24 07:16:40', 324, 0, 46, 4),
(22, 146, 'contado', '2010-07-24 07:16:41', 2084, 0, 6, 2),
(23, 73, 'credito', '2010-07-24 07:16:42', 1311, 0, 18, 18),
(24, 151, 'contado', '2010-07-24 07:16:42', 322, 0, 26, 8),
(25, 129, 'contado', '2010-07-24 07:16:44', 1790, 0, 23, 22),
(26, 62, 'contado', '2010-07-24 07:16:45', 3099, 0, 34, 20),
(27, 100, 'contado', '2010-07-24 07:16:46', 1845, 0, 32, 23),
(28, 39, 'contado', '2010-07-24 07:16:47', 1224, 0, 29, 5),
(29, 173, 'contado', '2010-07-24 07:16:48', 954, 0, 28, 1),
(30, 166, 'credito', '2010-07-24 07:16:50', 624, 0, 43, 17),
(31, 141, 'contado', '2010-07-24 07:16:53', 406, 0, 30, 11),
(32, 32, 'credito', '2010-07-24 07:16:55', 1704, 0, 28, 1),
(33, 191, 'contado', '2010-07-24 07:16:55', 1249, 0, 6, 2),
(34, 32, 'credito', '2010-07-24 07:16:57', 1936, 0, 6, 15),
(35, 114, 'contado', '2010-07-24 07:17:00', 609, 0, 30, 11),
(36, 148, 'credito', '2010-07-24 07:17:01', 624, 0, 43, 17),
(37, 183, 'credito', '2010-07-24 07:17:03', 2904, 0, 32, 23),
(38, 155, 'contado', '2010-07-24 07:17:04', 2097, 0, 18, 18),
(39, 68, 'credito', '2010-07-24 07:17:05', 2162, 0, 23, 22),
(40, 153, 'contado', '2010-07-24 07:17:07', 3073, 0, 5, 3),
(41, 76, 'credito', '2010-07-24 07:17:08', 1127, 0, 26, 8),
(42, 69, 'credito', '2010-07-24 07:17:09', 229, 0, 15, 10),
(43, 69, 'credito', '2010-07-24 07:17:11', 2040, 0, 16, 7),
(44, 64, 'contado', '2010-07-24 07:17:13', 1655, 0, 6, 15),
(45, 99, 'credito', '2010-07-24 07:17:14', 774, 0, 43, 17),
(46, 78, 'contado', '2010-07-24 07:17:16', 2541, 0, 9, 13),
(47, 118, 'contado', '2010-07-24 07:17:17', 975, 0, 47, 6),
(48, 95, 'credito', '2010-07-24 07:17:18', 488, 0, 15, 25),
(49, 158, 'contado', '2010-07-24 07:17:20', 2270, 0, 6, 2),
(50, 92, 'contado', '2010-07-24 07:17:22', 1994, 0, 34, 20),
(51, 143, 'contado', '2010-07-24 07:17:23', 3464, 0, 32, 23),
(52, 58, 'credito', '2010-07-24 07:17:25', 820, 0, 6, 2),
(53, 15, 'contado', '2010-07-24 07:17:26', 2480, 0, 46, 4),
(54, 109, 'contado', '2010-07-24 07:17:27', 459, 0, 15, 10);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_compras`
--
CREATE TABLE IF NOT EXISTS `view_compras` (
`id_compra` int(11)
,`proveedor` varchar(30)
,`id_proveedor` int(11)
,`tipo_compra` enum('credito','contado')
,`fecha` timestamp
,`subtotal` float
,`iva` float
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
,`id_usuario` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_detalle_compra`
--
CREATE TABLE IF NOT EXISTS `view_detalle_compra` (
`id_compra` int(11)
,`id_producto` int(11)
,`denominacion` varchar(30)
,`cantidad` float
,`precio` float
,`fecha` timestamp
,`tipo_compra` enum('credito','contado')
,`id_sucursal` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_detalle_venta`
--
CREATE TABLE IF NOT EXISTS `view_detalle_venta` (
`id_venta` int(11)
,`id_producto` int(11)
,`denominacion` varchar(30)
,`cantidad` float
,`precio` float
,`fecha` timestamp
,`tipo_venta` enum('credito','contado')
,`id_sucursal` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_gastos`
--
CREATE TABLE IF NOT EXISTS `view_gastos` (
`id_gasto` int(11)
,`monto` float
,`fecha` timestamp
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_ingresos`
--
CREATE TABLE IF NOT EXISTS `view_ingresos` (
`id_ingreso` int(11)
,`monto` float
,`fecha` timestamp
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_ventas`
--
CREATE TABLE IF NOT EXISTS `view_ventas` (
`id_venta` int(11)
,`cliente` varchar(100)
,`id_cliente` int(11)
,`tipo_venta` enum('credito','contado')
,`fecha` timestamp
,`subtotal` float
,`iva` float
,`sucursal` varchar(100)
,`id_sucursal` int(11)
,`usuario` varchar(100)
,`id_usuario` int(11)
);
-- --------------------------------------------------------

--
-- Estructura para la vista `view_compras`
--
DROP TABLE IF EXISTS `view_compras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_compras` AS select `c`.`id_compra` AS `id_compra`,`p`.`nombre` AS `proveedor`,`p`.`id_proveedor` AS `id_proveedor`,`c`.`tipo_compra` AS `tipo_compra`,`c`.`fecha` AS `fecha`,`c`.`subtotal` AS `subtotal`,`c`.`iva` AS `iva`,`s`.`descripcion` AS `sucursal`,`c`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario`,`c`.`id_usuario` AS `id_usuario` from (`compras` `c` join ((`proveedor` `p` join `sucursal` `s`) join `usuario` `u`) on(((`c`.`id_proveedor` = `p`.`id_proveedor`) and (`c`.`id_sucursal` = `s`.`id_sucursal`) and (`c`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `view_detalle_compra`
--
DROP TABLE IF EXISTS `view_detalle_compra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_detalle_compra` AS select `d`.`id_compra` AS `id_compra`,`d`.`id_producto` AS `id_producto`,`i`.`denominacion` AS `denominacion`,`d`.`cantidad` AS `cantidad`,`d`.`precio` AS `precio`,`c`.`fecha` AS `fecha`,`c`.`tipo_compra` AS `tipo_compra`,`c`.`id_sucursal` AS `id_sucursal` from (`detalle_compra` `d` join (`inventario` `i` join `compras` `c`) on(((`d`.`id_compra` = `c`.`id_compra`) and (`d`.`id_producto` = `i`.`id_producto`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `view_detalle_venta`
--
DROP TABLE IF EXISTS `view_detalle_venta`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_detalle_venta` AS select `d`.`id_venta` AS `id_venta`,`d`.`id_producto` AS `id_producto`,`i`.`denominacion` AS `denominacion`,`d`.`cantidad` AS `cantidad`,`d`.`precio` AS `precio`,`v`.`fecha` AS `fecha`,`v`.`tipo_venta` AS `tipo_venta`,`v`.`id_sucursal` AS `id_sucursal` from (`detalle_venta` `d` join (`inventario` `i` join `ventas` `v`) on(((`d`.`id_venta` = `v`.`id_venta`) and (`d`.`id_producto` = `i`.`id_producto`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `view_gastos`
--
DROP TABLE IF EXISTS `view_gastos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_gastos` AS select `g`.`id_gasto` AS `id_gasto`,`g`.`monto` AS `monto`,`g`.`fecha` AS `fecha`,`s`.`descripcion` AS `sucursal`,`g`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario` from (`gastos` `g` join (`sucursal` `s` join `usuario` `u`) on(((`g`.`id_sucursal` = `s`.`id_sucursal`) and (`g`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `view_ingresos`
--
DROP TABLE IF EXISTS `view_ingresos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ingresos` AS select `i`.`id_ingreso` AS `id_ingreso`,`i`.`monto` AS `monto`,`i`.`fecha` AS `fecha`,`s`.`descripcion` AS `sucursal`,`i`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario` from (`ingresos` `i` join (`sucursal` `s` join `usuario` `u`) on(((`i`.`id_sucursal` = `s`.`id_sucursal`) and (`i`.`id_usuario` = `u`.`id_usuario`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `view_ventas`
--
DROP TABLE IF EXISTS `view_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_ventas` AS select `v`.`id_venta` AS `id_venta`,`c`.`nombre` AS `cliente`,`v`.`id_cliente` AS `id_cliente`,`v`.`tipo_venta` AS `tipo_venta`,`v`.`fecha` AS `fecha`,`v`.`subtotal` AS `subtotal`,`v`.`iva` AS `iva`,`s`.`descripcion` AS `sucursal`,`v`.`id_sucursal` AS `id_sucursal`,`u`.`nombre` AS `usuario`,`v`.`id_usuario` AS `id_usuario` from (`ventas` `v` join ((`cliente` `c` join `sucursal` `s`) join `usuario` `u`) on(((`v`.`id_cliente` = `c`.`id_cliente`) and (`v`.`id_sucursal` = `s`.`id_sucursal`) and (`v`.`id_usuario` = `u`.`id_usuario`))));

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_corte`
--
ALTER TABLE `detalle_corte`
  ADD CONSTRAINT `corte_detalleCorte` FOREIGN KEY (`num_corte`) REFERENCES `corte` (`num_corte`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_cotizacion`
--
ALTER TABLE `detalle_cotizacion`
  ADD CONSTRAINT `detalle_cotizacion_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_cotizacion_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_inventario`
--
ALTER TABLE `detalle_inventario`
  ADD CONSTRAINT `detalle_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_inventario_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_compra`
--
ALTER TABLE `factura_compra`
  ADD CONSTRAINT `factura_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD CONSTRAINT `factura_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupos_permisos`
--
ALTER TABLE `grupos_permisos`
  ADD CONSTRAINT `grupos_permisos_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_permisos_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupos_usuarios`
--
ALTER TABLE `grupos_usuarios`
  ADD CONSTRAINT `grupos_usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD CONSTRAINT `impuesto_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_compra`
--
ALTER TABLE `pagos_compra`
  ADD CONSTRAINT `pagos_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_proveedor`
--
ALTER TABLE `productos_proveedor`
  ADD CONSTRAINT `productos_proveedor_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_proveedor_ibfk_2` FOREIGN KEY (`id_inventario`) REFERENCES `inventario` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;

COMMIT;
