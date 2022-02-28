-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2020 at 10:25 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addItemInCart` (IN `amount1` INT, IN `itemid` INT, IN `userid` INT, IN `size` INT)  NO SQL
IF (
	SELECT COUNT(*) 
	FROM carts
	WHERE idUser=userid  AND idItem=itemid AND idBill is NULL AND idSize=size)=1 THEN
		IF 
		(SELECT amountAvailable 
		FROM belonging_sizes_items 
		WHERE idItem=itemid AND idSize=size)
		<
			(select amount+amount1 
			FROM carts 
			where idUser=userid AND idItem=itemid AND idBill is NULL  AND idSize=size)
		THEN
			UPDATE carts 
			SET amount =( SELECT amountAvailable 
			FROM belonging_sizes_items 
			WHERE idItem=itemid AND idSize=size)
			WHERE idUser=userid AND idItem=itemid AND idBill is NULL  AND idSize=size;
		ELSE
			UPDATE carts
			SET amount=amount+amount1
			WHERE idUser=userid AND idItem=itemid AND idBill is NULL  AND idSize=size;
		END IF;
ELSE 
	INSERT INTO carts VALUES (NULL, userid, itemid, NULL, amount1, size);
END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addNewUser` (IN `fname` VARCHAR(30), IN `lname` VARCHAR(30), IN `addr` VARCHAR(30), IN `number` VARCHAR(15), IN `user` VARCHAR(30), IN `pass` VARCHAR(30), IN `mail` VARCHAR(50))  NO SQL
IF (SELECT COUNT(idUser) FROM users WHERE username=user COLLATE utf16_bin)=0 THEN
	INSERT INTO users
    VALUES (NULL, fname, lname, addr, number, 'user', user, pass, mail,1);
    select 'Success' as message;
ELSE
	select 'User with this usernam already exists' as message;
END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addSize` (IN `itemid` INT, IN `sizeid` INT, IN `amount` INT)  NO SQL
IF (SELECT COUNT(*) FROM belonging_sizes_items WHERE idItem=itemid AND idSize=sizeid)>0 THEN
	UPDATE belonging_sizes_items
    SET amountAvailable=amountAvailable+amount
	WHERE idItem=itemid AND idSize=sizeid;
ELSE 
	INSERT INTO
    belonging_sizes_items
    values (itemid,sizeid,amount);

END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_changeAddress` (IN `userid` INT, IN `addr` VARCHAR(30))  NO SQL
UPDATE users
SET address=addr
WHERE idUser=userid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleteItemFromCart` (IN `userid` INT, IN `itemid` INT, IN `size` INT)  NO SQL
DELETE 
FROM carts
WHERE idUser=userid AND idItem=itemid AND idBill is NULL AND idSize=size$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_logIn` (IN `name` VARCHAR(30), IN `pass` VARCHAR(30))  NO SQL
SELECT *
FROM users
WHERE username = name COLLATE utf8_bin
AND password=pass COLLATE utf8_bin AND active=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_purchase` (IN `userid` INT)  BEGIN
	DECLARE sumCost FLOAT;
	DECLARE dt DATETIME;
	set dt=now();
	SET sumCost=(
	SELECT SUM((price-price*discount/100)*amount) 
	FROM carts, items 
	WHERE carts.idItem=items.idItem 
	and idUser=userid and idBill is NULL);
    
	UPDATE belonging_sizes_items b JOIN  carts c on b.idItem=c.idItem
	SET amountAvailable=amountAvailable-c.amount
	WHERE idBill is NULL and b.idSize=c.idSize; 

	INSERT INTO bills ( billDate, billTime, totalPrice) VALUES ( dt,dt,sumCost);
	
    
	UPDATE carts
	SET idBill= 
	(select idBill from bills where billDate=cast(dt as date) and billTime=cast(dt as time) and
	totalPrice=sumCost)
	WHERE idUser=userid AND idBill is NULL;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showCartItems` (IN `o` INT)  NO SQL
SELECT i.idItem, nameItem, price, discount, amount, (select urlPicture from pictures where pictures.idPicture = (select min(p.idPicture) from pictures p where p.idItem = i.idItem)) as urlPicture, (select s.nameSize from sizes s where s.idSize=c.idSize) as size, c.idSize,
(select amountAvailable from belonging_sizes_items b where b.idItem=c.idItem and b.idSize=c.idSize) as maximum
FROM items i, carts c, users
WHERE i.idItem=c.idItem AND c.idUser=users.idUser AND 
c.idUser=o AND idBill is NULL$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showItem` (IN `itemid` INT)  NO SQL
SELECT nameItem, price, discount, idBrand, (select pictureBrand FROM brands b WHERE b.idBrand=i.idBrand) as pictureBrand, (
		SELECT urlPicture 
		FROM pictures 
		WHERE pictures.idPicture =(
			SELECT min(p.idPicture) 	   
			FROM pictures p 
			WHERE p.idItem = i.idItem
			)
		) as urlPicture
FROM items i
WHERE i.idItem=itemid AND active=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showItemBill` (IN `itemid` INT, IN `billid` INT)  NO SQL
SELECT nameItem, (
		SELECT price 
		FROM items_log 
		WHERE (
			(
				dateFrom< (
					SELECT billDate 
					FROM bills 
					WHERE idBill=billid
				)
			)
		OR	(
				dateFrom= (
					SELECT billDate 
					FROM bills 
					WHERE idBill=billid
				)
				AND timeFrom<=(
					SELECT billTime 
					FROM bills 
					WHERE idBill=billid
					)
			) 
		)
		AND( 
			(
				(
					dateTo> (
						SELECT billDate 
						FROM bills 
						WHERE idBill=billid
					)
				)
				OR	(
					dateTo= (
						SELECT billDate 
						FROM bills 
						WHERE idBill=billid
					)
					AND timeTo>=(
						SELECT billTime 
						FROM bills 
						WHERE idBill=billid
						)
				)
			) 
			OR 
			(dateTo is NULL AND timeTo is  NULL) 
		) 
        AND idItem=itemid
	) as price, (
		SELECT discount 
		FROM items_log 
		WHERE 
			(
			(
				dateFrom< (
					SELECT billDate 
					FROM bills 
					WHERE idBill=billid
				)
			)
		OR	(
				dateFrom= (
					SELECT billDate 
					FROM bills 
					WHERE idBill=billid
				)
				AND timeFrom<=(
					SELECT billTime 
					FROM bills 
					WHERE idBill=billid
					)
			) 
		)
		AND( 
			(
				(
					dateTo> (
						SELECT billDate 
						FROM bills 
						WHERE idBill=billid
					)
				)
				OR	(
					dateTo= (
						SELECT billDate 
						FROM bills 
						WHERE idBill=billid
					)
					AND timeTo>=(
						SELECT billTime 
						FROM bills 
						WHERE idBill=billid
						)
				)
			) 
			OR 
			(dateTo is NULL AND timeTo is  NULL) 
		) 
        AND idItem=itemid
		) as discount, idBrand, (
		SELECT urlPicture 
		FROM pictures 
		WHERE pictures.idPicture =(
			SELECT min(p.idPicture) 	   
			FROM pictures p 
			WHERE p.idItem = i.idItem
			)
		) as urlPicture
    FROM items i
    WHERE i.idItem=itemid AND active=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showItems` (IN `idCat` INT, IN `idSubcat` INT)  NO SQL
IF (idCat=-1 AND idSubcat=-1) THEN
    SELECT idItem, nameItem, price, discount, (
		SELECT urlPicture 
		FROM pictures 
		WHERE pictures.idPicture =(
			SELECT min(p.idPicture) 	   
			FROM pictures p 
			WHERE p.idItem = i.idItem
			)
		) as urlPicture, idBrand, (select pictureBrand FROM brands b WHERE b.idBrand=i.idBrand) as pictureBrand, gender
     FROM items i
	 WHERE (SELECT SUM(amountAvailable) FROM belonging_sizes_items WHERE idItem=i.idItem)>0 AND active=1;
ELSEIF (idCat!=-1 AND idSubcat=-1) THEN
    SELECT idItem, nameItem, price, discount, (
		SELECT urlPicture 		
		FROM pictures 
		WHERE pictures.idPicture = (
			SELECT min(p.idPicture) 	  
			FROM pictures p 
			WHERE p.idItem = i.idItem
			)
		) as urlPicture, idBrand, (select pictureBrand FROM brands b WHERE b.idBrand=i.idBrand) as pictureBrand, gender
    FROM items i
    WHERE i.idItem IN(
		SELECT idItem 
		FROM belonging_items_subcategories b
		WHERE
		b.idSubcategory IN (
		SELECT idSubcategory 
		FROM subcategories 		
		WHERE idCategory=idCat
	)) AND(SELECT SUM(amountAvailable) FROM belonging_sizes_items WHERE idItem=i.idItem)>0 AND active=1;
ELSE
    SELECT idItem, nameItem, price, discount, (
		SELECT urlPicture 		
		FROM pictures 
		WHERE pictures.idPicture = (
			SELECT min(p.idPicture) 	  
			FROM pictures p 
			WHERE p.idItem = i.idItem
			)
		) as urlPicture, idBrand, (select pictureBrand FROM brands b WHERE b.idBrand=i.idBrand) as pictureBrand, gender
    FROM items i
    WHERE i.idItem IN(
		SELECT idItem 
		FROM belonging_items_subcategories b
		WHERE
		b.idSubcategory=idSubcat
	) AND(SELECT SUM(amountAvailable) FROM belonging_sizes_items WHERE idItem=i.idItem)>0 AND active=1;
END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showPictures` (IN `id` INT)  NO SQL
SELECT urlPicture
FROM pictures
WHERE idItem=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showSizes` (IN `itemid` INT)  NO SQL
SELECT sizes.idSize, nameSize, amountAvailable
FROM sizes, belonging_sizes_items
WHERE sizes.idSize=belonging_sizes_items.idSize AND
itemid=idItem$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_showSubcategory` (IN `catid` INT)  NO SQL
SELECT s.idSubcategory, s.nameSubcategory, (
	select count(0) 
	from items i 
	where idItem in (
		SELECT idItem 
		FROM belonging_items_subcategories b
		WHERE 
		b.idSubcategory =s.idSubcategory and 
		(
			select sum(amountAvailable) 
			from belonging_sizes_items
			where idItem = i.idItem
		) > 0
	)
) as amount
FROM subcategories s
WHERE s.idCategory=catid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCartAmount` (IN `userid` INT, IN `itemid` INT, IN `amount1` INT, IN `size` INT)  NO SQL
UPDATE carts
SET carts.amount=amount1
WHERE carts.idUser=userid AND carts.idItem=itemid and idSize=size$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `belonging_items_subcategories`
--

CREATE TABLE `belonging_items_subcategories` (
  `idItem` int(10) UNSIGNED NOT NULL,
  `idSubcategory` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `belonging_items_subcategories`
--

INSERT INTO `belonging_items_subcategories` (`idItem`, `idSubcategory`) VALUES
(1, 9),
(1, 10),
(2, 10),
(3, 10),
(4, 9),
(4, 10),
(5, 10),
(6, 11),
(7, 11),
(8, 1),
(9, 1),
(10, 4),
(11, 4),
(12, 6),
(13, 6),
(14, 8),
(15, 9),
(16, 9),
(17, 9),
(18, 1),
(19, 3),
(20, 2),
(21, 9),
(22, 10),
(23, 9),
(24, 9),
(25, 9),
(26, 10),
(27, 10),
(28, 10),
(29, 9),
(30, 9);

-- --------------------------------------------------------

--
-- Table structure for table `belonging_sizes_items`
--

CREATE TABLE `belonging_sizes_items` (
  `idItem` int(10) UNSIGNED NOT NULL,
  `idSize` int(10) UNSIGNED NOT NULL,
  `amountAvailable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `belonging_sizes_items`
--

INSERT INTO `belonging_sizes_items` (`idItem`, `idSize`, `amountAvailable`) VALUES
(1, 35, 15),
(1, 36, 1),
(1, 37, 5),
(1, 39, 5),
(1, 40, 23),
(1, 41, 6),
(2, 35, 37),
(2, 36, 23),
(2, 38, 44),
(2, 39, 23),
(3, 31, 17),
(3, 32, 19),
(3, 33, 17),
(3, 35, 12),
(4, 31, 13),
(4, 33, 15),
(4, 34, 24),
(5, 22, 15),
(5, 25, 19),
(5, 26, 18),
(5, 27, 24),
(5, 29, 13),
(6, 1, 23),
(6, 2, 26),
(6, 3, 21),
(6, 4, 19),
(7, 1, 17),
(7, 2, 12),
(7, 4, 12),
(8, 1, 21),
(8, 2, 20),
(8, 4, 18),
(8, 5, 14),
(9, 7, 15),
(9, 8, 14),
(9, 9, 18),
(9, 10, 15),
(10, 2, 16),
(10, 3, 17),
(10, 4, 24),
(10, 5, 14),
(11, 2, 14),
(11, 3, 26),
(11, 5, 24),
(12, 31, 26),
(12, 33, 15),
(13, 31, 34),
(13, 34, 16),
(13, 36, 19),
(14, 31, 29),
(14, 33, 23),
(14, 34, 4),
(15, 22, 5),
(15, 23, 5),
(15, 26, 8),
(16, 31, 9),
(16, 33, 23),
(16, 34, 15),
(17, 31, 4),
(17, 32, 0),
(17, 33, 2),
(18, 1, 2),
(18, 2, 3),
(18, 3, 4),
(18, 4, 5),
(18, 5, 6),
(18, 6, 7),
(19, 3, 22),
(19, 4, 0),
(20, 36, 8),
(20, 37, 7),
(20, 39, 3),
(21, 31, 32),
(21, 33, 29),
(22, 31, 26),
(22, 32, 13),
(22, 33, 2),
(22, 34, 3),
(22, 35, 4),
(23, 33, 13),
(23, 34, 7),
(23, 35, 23),
(24, 37, 3),
(24, 38, 14),
(24, 39, 4),
(25, 38, 12),
(25, 39, 5),
(26, 38, 12),
(26, 39, 7),
(26, 40, 6),
(26, 41, 9),
(27, 39, 5),
(27, 40, 4),
(27, 41, 3),
(28, 32, 4),
(28, 33, 14),
(29, 31, 7),
(29, 34, 3),
(30, 31, 20);

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `idBill` int(10) UNSIGNED NOT NULL,
  `billDate` date NOT NULL,
  `billTime` time NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`idBill`, `billDate`, `billTime`, `totalPrice`) VALUES
(6, '2020-09-01', '17:12:23', 11203),
(7, '2020-09-06', '17:29:56', 5516.5),
(8, '2020-09-13', '21:49:45', 14372);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `idBrand` int(10) UNSIGNED NOT NULL,
  `nameBrand` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pictureBrand` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`idBrand`, `nameBrand`, `pictureBrand`) VALUES
(1, 'Adidas', 'adidas.png'),
(2, 'Nike', 'nike.png'),
(3, 'Puma', 'puma.png'),
(4, 'Reebok', 'reebok.png'),
(5, 'New Balance', 'newbalance.png'),
(6, 'Asics', 'asics.png'),
(8, 'Hummel', 'hummel.png'),
(9, 'Kappa', 'kappa.png'),
(10, 'Skechers', 'skechers.png'),
(11, 'Converse', 'converse.png');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `idCart` bigint(20) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `idItem` int(10) UNSIGNED NOT NULL,
  `idBill` int(10) UNSIGNED DEFAULT NULL,
  `amount` smallint(5) UNSIGNED NOT NULL,
  `idSize` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`idCart`, `idUser`, `idItem`, `idBill`, `amount`, `idSize`) VALUES
(56, 2, 1, 6, 2, 36),
(60, 2, 1, 7, 1, 36),
(68, 2, 4, 8, 1, 31),
(69, 2, 6, 8, 2, 2),
(70, 2, 2, NULL, 1, 36);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `idCategory` int(10) UNSIGNED NOT NULL,
  `nameCategory` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`idCategory`, `nameCategory`) VALUES
(1, 'OBUĆA'),
(2, 'AKSESOARI'),
(3, 'ODEĆA'),
(4, 'PATIKE');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `idItem` int(10) UNSIGNED NOT NULL,
  `nameItem` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` double UNSIGNED NOT NULL,
  `discount` int(10) UNSIGNED NOT NULL,
  `gender` enum('male','female','children','unisex') COLLATE utf8_unicode_ci NOT NULL,
  `idBrand` int(10) UNSIGNED NOT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`idItem`, `nameItem`, `price`, `discount`, `gender`, `idBrand`, `active`) VALUES
(1, 'MEN SNEAKERS PUMA SMASH V2', 6490, 15, 'male', 3, 1),
(2, 'MEN SNEAKERS PUMA RS-X3 MILLENIUM', 12980, 0, 'male', 3, 1),
(3, 'WOMEN SNEAKERS ADIDAS FALCON W', 11990, 0, 'female', 1, 1),
(4, 'WOMEN SNEAKERS PUMA ALTERATION BLITZ', 8990, 20, 'female', 3, 1),
(5, 'CHILD SNEAKERS NIKE AIR MAX EXCEE (GS)', 11190, 0, 'children', 2, 1),
(6, 'WOMEN TIGHTS ADIDAS LRG LOGO TIGHTS', 3590, 0, 'female', 1, 1),
(7, 'WOMEN TIGHTS PUMA ACTIVE ESS 3/4 LEGGINGS', 3490, 20, 'female', 3, 1),
(8, 'MEN SHIRT NIKE M NSW SS TEE SWOOSH PK 2', 3290, 0, 'male', 2, 1),
(9, 'CHILD SHIRT NIKE G NSW TEE CROP JDIY FUTURA FL', 2590, 20, 'children', 2, 1),
(10, 'MEN SWEATSUIT NIKE M NSW CE TRK SUIT HD WVN', 11190, 0, 'male', 2, 1),
(11, 'MEN SWEATSUIT  NIKE M NSW CE TRK SUIT PK BASIC', 8590, 0, 'male', 2, 1),
(12, 'WOMEN SLIPPERS PUMA POPCAT 20 WNS', 2990, 0, 'female', 3, 1),
(13, 'UNISEX SLIPPERS PUMA POPCAT 20', 2990, 20, 'unisex', 3, 1),
(14, 'WOMEN FLIP FLOPS PUMA EPIC FLIP V2', 1290, 23, 'female', 3, 1),
(15, 'CHILD SNEAKERS NIKE STAR RUNNER 2 (TDV)', 3990, 30, 'children', 2, 1),
(16, 'WOMEN SNEAKERS REEBOK FLEXAGON FORCE 2.0', 6990, 40, 'female', 4, 1),
(17, 'WOMEN SNEAKERS ADIDAS STRUTTER', 7990, 40, 'female', 1, 1),
(18, 'ADIDAS SHIRT M CH1 GFX SS TEE', 14990, 0, 'male', 1, 1),
(19, 'NIKE SHORTS Q54 JD AIR BBALL SHORT', 7990, 0, 'male', 2, 1),
(20, 'NIKE SOCKS U NK EVERYDAY LTWT CREW 3PR', 1690, 0, 'male', 2, 1),
(21, 'WOMEN SNEAKERS NEW BALANCE 574', 9490, 30, 'female', 5, 1),
(22, 'WOMEN SNEAKERS REEBOK AZTREK DOUBLE MIX TRAIL', 11990, 30, 'female', 4, 1),
(23, 'WOMEN SNEAKERS ADIDAS QUESTAR FLOW', 6290, 0, 'female', 1, 1),
(24, 'MEN SNEAKERS REEBOK QUICK MOTION 2.0', 5390, 20, 'male', 4, 1),
(25, 'MEN SNEAKERS REEBOK ROYAL TURBO IMPULSE', 7790, 20, 'male', 4, 1),
(26, 'MEN SNEAKERS NEW BALANCE M SHARK', 10690, 0, 'male', 5, 1),
(27, 'MEN SNEAKERS NEW BALANCE 997', 10990, 20, 'male', 5, 1),
(28, 'WOMEN SNEAKERS NEW BALANCE Z 997', 10190, 0, 'female', 5, 1),
(29, 'WOMEN SNEAKERS REEBOK RUNNER 4.0', 5900, 40, 'female', 4, 1),
(30, 'WOMEN SNEAKERS REEBOK AZTREK 96', 6390, 40, 'female', 4, 1);

--
-- Triggers `items`
--
DELIMITER $$
CREATE TRIGGER `trig_insertItem` AFTER INSERT ON `items` FOR EACH ROW BEGIN
	INSERT INTO items_log
    VALUES(NEW.idItem, NEW.price, NEW.discount, NOW(), NOW(), NULL, NULL);
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_updatePrice` AFTER UPDATE ON `items` FOR EACH ROW BEGIN
      IF (OLD.price!=NEW.price OR OLD.discount!=NEW.discount) THEN
          UPDATE items_log
          SET dateTo=NOW(), timeTo=NOW()
          WHERE items_log.idItem=OLD.idItem AND
          dateTo is NULL AND timeTo is NULL;

          INSERT INTO items_log
              VALUES (NEW.idItem, NEW.price, NEW.discount, NOW(), NOW(),NULL, NULL);
     END IF;
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items_log`
--

CREATE TABLE `items_log` (
  `idItem` int(10) UNSIGNED NOT NULL,
  `price` double UNSIGNED NOT NULL,
  `discount` int(10) UNSIGNED NOT NULL,
  `dateFrom` date NOT NULL,
  `timeFrom` time NOT NULL,
  `dateTo` date DEFAULT NULL,
  `timeTo` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `items_log`
--

INSERT INTO `items_log` (`idItem`, `price`, `discount`, `dateFrom`, `timeFrom`, `dateTo`, `timeTo`) VALUES
(1, 6590, 15, '2020-09-01', '16:14:28', '2020-09-01', '17:36:34'),
(1, 6490, 15, '2020-09-01', '17:36:34', NULL, NULL),
(2, 12980, 0, '2020-09-01', '16:14:28', NULL, NULL),
(3, 11990, 0, '2020-09-01', '16:14:28', NULL, NULL),
(4, 8990, 20, '2020-09-01', '16:14:28', NULL, NULL),
(5, 11190, 0, '2020-09-01', '16:14:28', NULL, NULL),
(6, 3590, 0, '2020-09-01', '16:14:28', NULL, NULL),
(7, 3490, 20, '2020-09-01', '16:14:28', NULL, NULL),
(8, 3290, 0, '2020-09-01', '16:14:28', NULL, NULL),
(9, 2590, 20, '2020-09-01', '16:14:28', NULL, NULL),
(10, 11190, 0, '2020-09-01', '16:14:28', NULL, NULL),
(11, 8590, 0, '2020-09-01', '16:14:28', NULL, NULL),
(12, 2990, 0, '2020-09-01', '16:14:28', NULL, NULL),
(13, 2990, 20, '2020-09-01', '16:14:28', NULL, NULL),
(14, 1290, 23, '2020-09-01', '16:14:28', NULL, NULL),
(15, 3990, 30, '2020-09-01', '16:14:28', NULL, NULL),
(16, 6990, 40, '2020-09-01', '16:14:28', NULL, NULL),
(17, 7790, 40, '2020-09-01', '17:08:30', NULL, NULL),
(18, 14990, 0, '2020-09-01', '16:14:28', NULL, NULL),
(19, 7990, 0, '2020-09-01', '16:14:28', NULL, NULL),
(20, 1690, 0, '2020-09-01', '16:14:28', NULL, NULL),
(21, 9490, 30, '2020-09-01', '16:14:28', NULL, NULL),
(22, 11990, 30, '2020-09-01', '16:14:28', NULL, NULL),
(23, 6290, 0, '2020-09-01', '16:14:28', NULL, NULL),
(24, 5390, 20, '2020-09-01', '16:14:28', NULL, NULL),
(25, 7790, 20, '2020-09-01', '16:14:28', NULL, NULL),
(26, 10690, 0, '2020-09-01', '16:14:28', NULL, NULL),
(27, 10990, 20, '2020-09-01', '16:14:28', NULL, NULL),
(28, 10190, 0, '2020-09-01', '16:14:28', NULL, NULL),
(29, 5900, 40, '2020-09-01', '16:14:28', NULL, NULL),
(30, 6390, 40, '2020-09-01', '16:14:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `idPicture` int(10) UNSIGNED NOT NULL,
  `urlPicture` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `idItem` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`idPicture`, `urlPicture`, `idItem`) VALUES
(1, 'puma-smash-v2-364989-43.jpg', 1),
(2, 'puma-smash-v2-364989-43-1.jpg', 1),
(3, 'puma-smash-v2-364989-43-2.jpg', 1),
(4, 'puma-smash-v2-364989-43-3.jpg', 1),
(6, 'puma-smash-v2-364989-43-4.jpg', 1),
(7, 'puma-rs-x3-millenium-373236-03.png', 2),
(8, 'puma-rs-x3-millenium-373236-03-3.jpg', 2),
(9, 'puma-rs-x3-millenium-373236-03-2.jpg', 2),
(10, 'puma-rs-x3-millenium-373236-03-1.jpg', 2),
(11, 'puma-rs-x3-millenium-373236-03-8.png', 2),
(12, 'zenske-patike-adidas-falcon-w-FV1104.jpg', 3),
(13, 'zenske-patike-adidas-falcon-w-FV1104-1.jpg', 3),
(14, 'zenske-patike-adidas-falcon-w-FV1104-2.jpg', 3),
(15, 'zenske-patike-adidas-falcon-w-FV1104-3.jpg', 3),
(16, 'zenske-patike-adidas-falcon-w-FV1104-4.jpg', 3),
(17, 'zenske-patike-puma-alteration-blitz-370931-01.jpg', 4),
(18, 'zenske-patike-puma-alteration-blitz-370931-01-1.jpg', 4),
(19, 'zenske-patike-puma-alteration-blitz-370931-01-2.jpg', 4),
(20, 'zenske-patike-puma-alteration-blitz-370931-01-3.jpg', 4),
(21, 'zenske-patike-puma-alteration-blitz-370931-01-4.jpg', 4),
(22, 'decije-patike-nike-air-max-excee-gs--CD6894-106.jpg', 5),
(23, 'decije-patike-nike-air-max-excee-gs--CD6894-106-1.jpg', 5),
(24, 'decije-patike-nike-air-max-excee-gs--CD6894-106-2.jpg', 5),
(25, 'decije-patike-nike-air-max-excee-gs--CD6894-106-3.jpg', 5),
(26, 'decije-patike-nike-air-max-excee-gs--CD6894-106-4.jpg', 5),
(27, 'zenske-helanke-adidas-lrg-logo-tights-GD2252.jpg', 6),
(28, 'zenske-helanke-adidas-lrg-logo-tights-GD2252-1.jpg', 6),
(29, 'zenske-helanke-adidas-lrg-logo-tights-GD2252-2.jpg', 6),
(30, 'zenske-helanke-adidas-lrg-logo-tights-GD2252-3.jpg', 6),
(31, 'zenske-helanke-puma-active-ess-3-4-leggings-851778-01.jpg', 7),
(32, 'zenske-helanke-puma-active-ess-3-4-leggings-851778-01-1.jpg', 7),
(33, 'zenske-helanke-puma-active-ess-3-4-leggings-851778-01-2.jpg', 7),
(34, 'muska-majica-nike-m-nsw-ss-tee-swoosh-pk-2-CU7278-100.jpg', 8),
(35, 'muska-majica-nike-m-nsw-ss-tee-swoosh-pk-2-CU7278-100-1.jpg', 8),
(36, 'muska-majica-nike-m-nsw-ss-tee-swoosh-pk-2-CU7278-100-2.jpg', 8),
(37, 'muska-majica-nike-m-nsw-ss-tee-swoosh-pk-2-CU7278-100-3.jpg', 8),
(38, 'decija-majica-nike-g-nsw-tee-crop-jdiy-futura-fl-CT2789-100.jpg', 9),
(39, 'decija-majica-nike-g-nsw-tee-crop-jdiy-futura-fl-CT2789-100-1.jpg', 9),
(40, 'muska-trenerka-nike-m-nsw-ce-trk-suit-hd-wvn-BV3025-010.jpg', 10),
(41, 'muska-trenerka-nike-m-nsw-ce-trk-suit-hd-wvn-BV3025-010-1.jpg', 10),
(42, 'muska-trenerka-nike-m-nsw-ce-trk-suit-hd-wvn-BV3025-010-2.jpg', 10),
(43, 'muska-trenerka-nike-m-nsw-ce-trk-suit-hd-wvn-BV3025-010-3.jpg', 10),
(44, 'muska-trenerka-nike-m-nsw-ce-trk-suit-pk-basic-BV3034-010.jpg', 11),
(45, 'muska-trenerka-nike-m-nsw-ce-trk-suit-pk-basic-BV3034-010-1.jpg', 11),
(46, 'muska-trenerka-nike-m-nsw-ce-trk-suit-pk-basic-BV3034-010-2.jpg\r\n                                   ', 11),
(47, 'muska-trenerka-nike-m-nsw-ce-trk-suit-pk-basic-BV3034-010-3.jpg', 11),
(48, 'zenske-papuce-puma-popcat-20-wns-372848-01.jpg', 12),
(49, 'zenske-papuce-puma-popcat-20-wns-372848-01-1.jpg', 12),
(50, 'zenske-papuce-puma-popcat-20-wns-372848-01-2.jpg', 12),
(51, 'zenske-papuce-puma-epic-flip-v2-360248-31.jpg', 14),
(52, 'zenske-papuce-puma-epic-flip-v2-360248-31-1.jpg', 14),
(53, 'zenske-papuce-puma-epic-flip-v2-360248-31-2.jpg', 14),
(54, 'unisex-papuce-puma-popcat-20-372279-01.jpg', 13),
(55, 'unisex-papuce-puma-popcat-20-372279-01-1.jpg', 13),
(56, 'unisex-papuce-puma-popcat-20-372279-01-2.jpg', 13),
(57, 'decije-patike-nike-star-runner-2-tdv--AT1803-005.jpg', 15),
(58, 'decije-patike-nike-star-runner-2-tdv--AT1803-005-1.jpg', 15),
(59, 'decije-patike-nike-star-runner-2-tdv--AT1803-005-2.jpg', 15),
(60, 'decije-patike-nike-star-runner-2-tdv--AT1803-005-3.jpg', 15),
(61, 'decije-patike-nike-star-runner-2-tdv--AT1803-005-4.jpg', 15),
(62, 'zenske-patike-reebok-flexagon-force-2-0-EH3559.jpg', 16),
(63, 'zenske-patike-reebok-flexagon-force-2-0-EH3559-1.jpg', 16),
(64, 'zenske-patike-reebok-flexagon-force-2-0-EH3559-2.jpg', 16),
(65, 'zenske-patike-reebok-flexagon-force-2-0-EH3559-3.jpg', 16),
(66, 'zenske-patike-reebok-flexagon-force-2-0-EH3559-4.jpg', 16),
(67, 'zenske-patike-adidas-strutter-EG8008.jpg', 17),
(68, 'zenske-patike-adidas-strutter-EG8008-1.jpg', 17),
(69, 'zenske-patike-adidas-strutter-EG8008-2.jpg', 17),
(70, 'zenske-patike-adidas-strutter-EG8008-3.jpg', 17),
(71, 'zenske-patike-adidas-strutter-EG8008-4.jpg', 17),
(72, 'GK4389_800_800px.jpg', 18),
(73, 'GK4389_800_800px-1.jpg', 18),
(74, 'CW4098-133_600_600px.jpg', 19),
(75, 'CW4098-133_600_600px-1.jpg', 19),
(76, 'SX7676-901_800_800px.jpg', 20),
(77, 'SX7676-901_800_800px.jpg', 20),
(78, 'zenske-patike-new-balance-574-WL574SOP.jpg', 21),
(79, 'zenske-patike-new-balance-574-WL574SOP-1.jpg', 21),
(80, 'zenske-patike-new-balance-574-WL574SOP-2.jpg', 21),
(81, 'zenske-patike-new-balance-574-WL574SOP-3.jpg', 21),
(82, 'zenske-patike-new-balance-574-WL574SOP-4.jpg', 21),
(83, 'zenske-patike-reebok-aztrek-double-mix-trail-EF9144.jpg', 22),
(84, 'zenske-patike-reebok-aztrek-double-mix-trail-EF9144-1.jpg', 22),
(85, 'zenske-patike-reebok-aztrek-double-mix-trail-EF9144-2.jpg', 22),
(86, 'zenske-patike-reebok-aztrek-double-mix-trail-EF9144-3.jpg', 22),
(87, 'zenske-patike-reebok-aztrek-double-mix-trail-EF9144-4.jpg', 22),
(88, 'zenske-patike-za-trcanje-adidas-questar-flow-EF0795.jpg', 23),
(89, 'zenske-patike-za-trcanje-adidas-questar-flow-EF0795-1.jpg', 23),
(90, 'zenske-patike-za-trcanje-adidas-questar-flow-EF0795-2.jpg', 23),
(91, 'zenske-patike-za-trcanje-adidas-questar-flow-EF0795-3.jpg', 23),
(92, 'zenske-patike-za-trcanje-adidas-questar-flow-EF0795-4.jpg', 23),
(93, 'muske-patike-reebok-quick-motion-2-0-EF6394.jpg', 24),
(94, 'muske-patike-reebok-quick-motion-2-0-EF6394-1.jpg', 24),
(95, 'muske-patike-reebok-quick-motion-2-0-EF6394-2.jpg', 24),
(96, 'muske-patike-reebok-quick-motion-2-0-EF6394-3.jpg', 24),
(97, 'muske-patike-reebok-quick-motion-2-0-EF6394-4.jpg', 24),
(98, 'muske-patike-za-trcanje-reebok-royal-turbo-impulse-EF8015.jpg', 25),
(99, 'muske-patike-za-trcanje-reebok-royal-turbo-impulse-EF8015-1.jpg', 25),
(100, 'muske-patike-za-trcanje-reebok-royal-turbo-impulse-EF8015-2.jpg', 25),
(101, 'muske-patike-za-trcanje-reebok-royal-turbo-impulse-EF8015-3.jpg', 25),
(102, 'muske-patike-za-trcanje-reebok-royal-turbo-impulse-EF8015-4.jpg', 25),
(103, 'muske-patike-new-balance-m-shark-MTSHARK.jpg', 26),
(104, 'muske-patike-new-balance-m-shark-MTSHARK-1.jpg', 26),
(105, 'muske-patike-new-balance-m-shark-MTSHARK-2.jpg', 26),
(106, 'muske-patike-new-balance-m-shark-MTSHARK-3.jpg', 26),
(107, 'muske-patike-new-balance-m-shark-MTSHARK-4.jpg', 26),
(108, 'muske-patike-new-balance-997-CMT997HD.jpg', 27),
(109, 'muske-patike-new-balance-997-CMT997HD-1.jpg', 27),
(110, 'muske-patike-new-balance-997-CMT997HD-2.jpg\r\n                                                           ', 27),
(111, 'muske-patike-new-balance-997-CMT997HD-3.jpg', 27),
(112, 'muske-patike-new-balance-997-CMT997HD-4.jpg', 27),
(113, 'zenske-patike-new-balance-z-997-CW997HYB.jpg', 28),
(114, 'zenske-patike-new-balance-z-997-CW997HYB-1.jpg', 28),
(115, 'zenske-patike-new-balance-z-997-CW997HYB-2.jpg', 28),
(116, 'zenske-patike-new-balance-z-997-CW997HYB-3.jpg', 28),
(117, 'zenske-patike-new-balance-z-997-CW997HYB-4.jpg', 28),
(118, 'zenske-patike-za-trcanje-reebok-runner-4-0-EF7322.jpg', 29),
(119, 'zenske-patike-za-trcanje-reebok-runner-4-0-EF7322-1.jpg', 29),
(120, 'zenske-patike-za-trcanje-reebok-runner-4-0-EF7322-2.jpg', 29),
(121, 'zenske-patike-za-trcanje-reebok-runner-4-0-EF7322-3.jpg', 29),
(122, 'zenske-patike-za-trcanje-reebok-runner-4-0-EF7322-4.jpg', 29),
(123, 'zenske-patike-reebok-aztrek-96-DV8528.jpg', 30),
(124, 'zenske-patike-reebok-aztrek-96-DV8528-1.jpg', 30),
(125, 'zenske-patike-reebok-aztrek-96-DV8528-2.jpg', 30),
(126, 'zenske-patike-reebok-aztrek-96-DV8528-3.jpg', 30),
(127, 'zenske-patike-reebok-aztrek-96-DV8528-4.jpg', 30);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `idSize` int(10) UNSIGNED NOT NULL,
  `nameSize` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  ` purpose` enum('clothes','shoes') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`idSize`, `nameSize`, ` purpose`) VALUES
(1, 'S', 'clothes'),
(2, 'M', 'clothes'),
(3, 'L', 'clothes'),
(4, 'XL', 'clothes'),
(5, 'XXL', 'clothes'),
(6, 'XXXL', 'clothes'),
(7, '6', 'clothes'),
(8, '8', 'clothes'),
(9, '10', 'clothes'),
(10, '12', 'clothes'),
(11, '17', 'shoes'),
(12, '18', 'shoes'),
(13, '19', 'shoes'),
(14, '20', 'shoes'),
(15, '21', 'shoes'),
(16, '22', 'shoes'),
(17, '23', 'shoes'),
(18, '24', 'shoes'),
(19, '25', 'shoes'),
(20, '26', 'shoes'),
(21, '27', 'shoes'),
(22, '28', 'shoes'),
(23, '29', 'shoes'),
(25, '30', 'shoes'),
(26, '31', 'shoes'),
(27, '32', 'shoes'),
(28, '33', 'shoes'),
(29, '34', 'shoes'),
(30, '35', 'shoes'),
(31, '36', 'shoes'),
(32, '37', 'shoes'),
(33, '38', 'shoes'),
(34, '39', 'shoes'),
(35, '40', 'shoes'),
(36, '41', 'shoes'),
(37, '42', 'shoes'),
(38, '43', 'shoes'),
(39, '44', 'shoes'),
(40, '45', 'shoes'),
(41, '46', 'shoes');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `idSubcategory` int(10) UNSIGNED NOT NULL,
  `nameSubcategory` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `idCategory` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`idSubcategory`, `nameSubcategory`, `idCategory`) VALUES
(1, 'MAJICE', 3),
(2, 'ČARAPE', 3),
(3, 'ŠORCEVI', 3),
(4, 'TRENERKE', 3),
(5, 'ČIZME', 1),
(6, ' PAPUČE', 1),
(8, 'JAPANKE', 1),
(9, 'SPORTSKE PATIKE', 4),
(10, 'LIFESTYLE PATIKE', 4),
(11, 'HELANKE', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phoneNumber` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('administrator','user') COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `password` varchar(30) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUser`, `firstName`, `lastName`, `address`, `phoneNumber`, `role`, `username`, `password`, `email`, `active`) VALUES
(1, 'Nikola', 'Nikolic', 'Kralja Petra 1, Beograd 11000', '063/443-442', 'administrator', 'adminadmin', 'adminadmin', 'nikolanikolic@gmail.com', 1),
(2, 'Ana', 'Anic', 'Kraljice Marije 3, Beograd 11000', '063/443-4422', 'user', 'useruser', 'useruser', 'anaanic@gmail.com', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_showbrands`
-- (See below for the actual view)
--
CREATE TABLE `view_showbrands` (
`idBrand` int(10) unsigned
,`nameBrand` varchar(20)
,`pictureBrand` varchar(100)
,`amount` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_showcategories`
-- (See below for the actual view)
--
CREATE TABLE `view_showcategories` (
`idCategory` int(10) unsigned
,`nameCategory` varchar(30)
,`amount` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_showdiscounts`
-- (See below for the actual view)
--
CREATE TABLE `view_showdiscounts` (
`discount` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_showsubcategories`
-- (See below for the actual view)
--
CREATE TABLE `view_showsubcategories` (
`idSubcategory` int(10) unsigned
,`nameSubcategory` varchar(30)
,`amount` bigint(21)
,`idCategory` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Structure for view `view_showbrands`
--
DROP TABLE IF EXISTS `view_showbrands`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_showbrands`  AS  select `b`.`idBrand` AS `idBrand`,`b`.`nameBrand` AS `nameBrand`,`b`.`pictureBrand` AS `pictureBrand`,(select count(0) from `items` `i` where `i`.`idBrand` = `b`.`idBrand`) AS `amount` from `brands` `b` ;

-- --------------------------------------------------------

--
-- Structure for view `view_showcategories`
--
DROP TABLE IF EXISTS `view_showcategories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_showcategories`  AS  select `c`.`idCategory` AS `idCategory`,`c`.`nameCategory` AS `nameCategory`,(select count(0) from `subcategories` where `subcategories`.`idCategory` = `c`.`idCategory`) AS `amount` from `categories` `c` ;

-- --------------------------------------------------------

--
-- Structure for view `view_showdiscounts`
--
DROP TABLE IF EXISTS `view_showdiscounts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_showdiscounts`  AS  select distinct `items`.`discount` AS `discount` from `items` where `items`.`discount` > 0 ;

-- --------------------------------------------------------

--
-- Structure for view `view_showsubcategories`
--
DROP TABLE IF EXISTS `view_showsubcategories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_showsubcategories`  AS  select `s`.`idSubcategory` AS `idSubcategory`,`s`.`nameSubcategory` AS `nameSubcategory`,(select count(0) from `belonging_items_subcategories` where `belonging_items_subcategories`.`idSubcategory` = `s`.`idSubcategory`) AS `amount`,`s`.`idCategory` AS `idCategory` from `subcategories` `s` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `belonging_items_subcategories`
--
ALTER TABLE `belonging_items_subcategories`
  ADD PRIMARY KEY (`idItem`,`idSubcategory`),
  ADD KEY `idItem` (`idItem`),
  ADD KEY `idSubcategory` (`idSubcategory`);

--
-- Indexes for table `belonging_sizes_items`
--
ALTER TABLE `belonging_sizes_items`
  ADD PRIMARY KEY (`idItem`,`idSize`),
  ADD KEY `idItem` (`idItem`),
  ADD KEY `idSize` (`idSize`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`idBill`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`idBrand`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`idCart`),
  ADD KEY `idItem` (`idItem`),
  ADD KEY `idSize` (`idSize`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idBill` (`idBill`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategory`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `idBrand` (`idBrand`);

--
-- Indexes for table `items_log`
--
ALTER TABLE `items_log`
  ADD PRIMARY KEY (`idItem`,`dateFrom`,`timeFrom`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`idPicture`),
  ADD KEY `idItem` (`idItem`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`idSize`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`idSubcategory`),
  ADD KEY `idCategory` (`idCategory`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `idBill` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `idBrand` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `idCart` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `idCategory` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `idItem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `idPicture` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `idSize` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `idSubcategory` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `belonging_items_subcategories`
--
ALTER TABLE `belonging_items_subcategories`
  ADD CONSTRAINT `belonging_items_subcategories_ibfk_1` FOREIGN KEY (`idItem`) REFERENCES `items` (`idItem`),
  ADD CONSTRAINT `belonging_items_subcategories_ibfk_2` FOREIGN KEY (`idSubcategory`) REFERENCES `subcategories` (`idSubcategory`),
  ADD CONSTRAINT `belonging_items_subcategories_ibfk_3` FOREIGN KEY (`idSubcategory`) REFERENCES `subcategories` (`idSubcategory`),
  ADD CONSTRAINT `belonging_items_subcategories_ibfk_4` FOREIGN KEY (`idSubcategory`) REFERENCES `subcategories` (`idSubcategory`),
  ADD CONSTRAINT `belonging_items_subcategories_ibfk_5` FOREIGN KEY (`idSubcategory`) REFERENCES `subcategories` (`idSubcategory`);

--
-- Constraints for table `belonging_sizes_items`
--
ALTER TABLE `belonging_sizes_items`
  ADD CONSTRAINT `belonging_sizes_items_ibfk_2` FOREIGN KEY (`idItem`) REFERENCES `items` (`idItem`),
  ADD CONSTRAINT `belonging_sizes_items_ibfk_3` FOREIGN KEY (`idSize`) REFERENCES `sizes` (`idSize`),
  ADD CONSTRAINT `belonging_sizes_items_ibfk_4` FOREIGN KEY (`idSize`) REFERENCES `sizes` (`idSize`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`idItem`) REFERENCES `items` (`idItem`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`idSize`) REFERENCES `sizes` (`idSize`),
  ADD CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`),
  ADD CONSTRAINT `carts_ibfk_4` FOREIGN KEY (`idSize`) REFERENCES `sizes` (`idSize`),
  ADD CONSTRAINT `carts_ibfk_5` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`),
  ADD CONSTRAINT `carts_ibfk_6` FOREIGN KEY (`idBill`) REFERENCES `bills` (`idBill`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`idBrand`) REFERENCES `brands` (`idBrand`);

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`idItem`) REFERENCES `items` (`idItem`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `categories` (`idCategory`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
