-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2022 at 02:38 AM
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
-- Database: `ipd_app_new`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GenerateBudgetSummary` (IN `pDeptid` INT, IN `pPeriod` INT, IN `pAmount` DECIMAL(15,2))  BEGIN
	DECLARE cAmount decimal(15,2);    
    DECLARE rCount int;
    
    SET rCount = (SELECT COUNT(*) FROM t_budget_summary WHERE department = pDeptid AND budget_period = pPeriod);
    
    IF rCount > 0 THEN
    	SELECT amount into cAmount from t_budget_summary WHERE department = pDeptid AND budget_period = pPeriod;
   		UPDATE t_budget_summary set amount = cAmount+pAmount WHERE department = pDeptid AND budget_period = pPeriod;
    ELSE
    	INSERT INTO t_budget_summary(department,budget_period,amount,issuing_amount,budget_status)
        VALUES(pDeptid,pPeriod,pAmount,0,'1');
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetMaterialPriceHistory` (IN `pYear` INT, IN `pMaterial` VARCHAR(70))  BEGIN

	if pMaterial = 'ALL' THEN
        SELECT DISTINCT pYear as 'year',a.material, c.matdesc, a.vendor, b.supplier_name, 
        fGetMaterialPrice(a.material,1,pYear) as 'Jan', 
        fGetMaterialPrice(a.material,2,pYear) as 'Feb',
        fGetMaterialPrice(a.material,3,pYear) as 'Mar',
        fGetMaterialPrice(a.material,4,pYear) as 'Apr',
        fGetMaterialPrice(a.material,5,pYear) as 'May',
        fGetMaterialPrice(a.material,6,pYear) as 'Jun',
        fGetMaterialPrice(a.material,7,pYear) as 'Jul', 
        fGetMaterialPrice(a.material,8,pYear) as 'Aug',
        fGetMaterialPrice(a.material,9,pYear) as 'Sep',
        fGetMaterialPrice(a.material,10,pYear) as 'Oct',
        fGetMaterialPrice(a.material,11,pYear) as 'Nov',
        fGetMaterialPrice(a.material,12,pYear) as 'Dec'
        from v_material_price_history as a inner join t_supplier as b on a.vendor = b.supplier_id INNER JOIN t_material as c on a.material = c.material WHERE a.year = pYear order by a.material;
    else
        SELECT DISTINCT pYear as 'year',a.material, c.matdesc, a.vendor, b.supplier_name, 
        fGetMaterialPrice(a.material,1,pYear) as 'Jan', 
        fGetMaterialPrice(a.material,2,pYear) as 'Feb',
        fGetMaterialPrice(a.material,3,pYear) as 'Mar',
        fGetMaterialPrice(a.material,4,pYear) as 'Apr',
        fGetMaterialPrice(a.material,5,pYear) as 'May',
        fGetMaterialPrice(a.material,6,pYear) as 'Jun',
        fGetMaterialPrice(a.material,7,pYear) as 'Jul', 
        fGetMaterialPrice(a.material,8,pYear) as 'Aug',
        fGetMaterialPrice(a.material,9,pYear) as 'Sep',
        fGetMaterialPrice(a.material,10,pYear) as 'Oct',
        fGetMaterialPrice(a.material,11,pYear) as 'Nov',
        fGetMaterialPrice(a.material,12,pYear) as 'Dec'
        from v_material_price_history as a inner join t_supplier as b on a.vendor = b.supplier_id INNER JOIN t_material as c on a.material = c.material WHERE a.year = pYear and a.material = pMaterial order by a.material;
    END IF;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InventoryStock` (IN `pMaterial` VARCHAR(70), IN `pQuantity` DECIMAL(15,3), IN `pMvt` VARCHAR(10), IN `pUnit` VARCHAR(10), IN `pWhs` VARCHAR(11))  NO SQL
BEGIN
	DECLARE currentqty decimal(15,2) DEFAULT 0;
    DECLARE _currentqty decimal(15,2) DEFAULT 0;
    
    
	Select quantity INTO currentqty from t_inventory_stock WHERE material = pMaterial and warehouseid = pWhs;        
    
    if pMvt = '101' THEN    
    	INSERT INTO t_inventory_stock (material,warehouseid,quantity,unit)       VALUES(pMaterial,pWhs, pQuantity, pUnit) ON DUPLICATE KEY UPDATE quantity = currentqty + pQuantity;
    
    elseif pMvt = '561' THEN
    INSERT INTO t_inventory_stock (material,warehouseid,quantity,unit) VALUES(pMaterial, pWhs, pQuantity, pUnit) ON DUPLICATE KEY UPDATE quantity = pQuantity;
    
    elseif pMvt = '601' THEN
    INSERT INTO t_inventory_stock (material,warehouseid,quantity,unit) VALUES(pMaterial,pWhs, pQuantity, pUnit) ON DUPLICATE KEY UPDATE quantity = currentqty - pQuantity;
    end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_IssuingBudget` (IN `pDeptid` INT, IN `pPeriod` INT, IN `pAmount` DECIMAL(15,2), IN `pUserid` VARCHAR(50), IN `pRefnum` VARCHAR(10), IN `pRefitem` INT)  BEGIN
	DECLARE cAmount decimal(15,2);
    DECLARE iAmount decimal(15,2);
    
    SELECT amount into cAmount from t_budget_summary WHERE department = pDeptid AND budget_period = pPeriod;
    
    IF cAmount > pAmount THEN
    	SELECT issuing_amount into iAmount from t_budget_summary WHERE department = pDeptid AND budget_period = pPeriod;
   		UPDATE t_budget_summary set amount = cAmount-pAmount, issuing_amount=iAmount+pAmount WHERE department = pDeptid AND budget_period = pPeriod;
        
        INSERT INTO t_budget_history (deptid,budget_period,amount,budget_type,note,refnum,refitem,createdon,createdby) VALUES(pDeptid,pPeriod,pAmount,'D','Budget Issuing',pRefnum,pRefitem,NOW(),pUserid);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_NextNriv` (IN `pObject` TEXT)  BEGIN

	DECLARE nextnumb bigint DEFAULT 0;
    
    Select currentnum INTO nextnumb from t_nriv WHERE object = pObject;
    
    if nextnumb = ''
    then 
    	Select fromnum INTO nextnumb from t_nriv WHERE object = pObject;
    end if;
    select nextnumb;
    
    UPDATE t_nriv set currentnum = nextnumb + 1 WHERE object = pObject;
    
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ResetData` ()  BEGIN
	TRUNCATE t_pr01;
    TRUNCATE t_pr02;
    TRUNCATE t_inventory;
    TRUNCATE t_stock;
    TRUNCATE t_budget_summary;
    TRUNCATE t_budget_history;
    TRUNCATE t_budget;
    TRUNCATE t_inventory_stock;
    TRUNCATE t_movement_01;
    TRUNCATE t_stock;
    UPDATE t_nriv set currentnum = '' WHERE object in('INVENTORY','PR');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_TriggerMovement` (IN `pMaterial` VARCHAR(70), IN `pUnit` VARCHAR(15), IN `pQuantity` DECIMAL(15,3), IN `pPonum` VARCHAR(12), IN `pPoitem` INT, IN `pWarehouse` VARCHAR(10), IN `pMvt` INT(10))  NO SQL
BEGIN
	if pMvt = '101' THEN
    call sp_InventoryStock(pMaterial, pQuantity, pMvt,pUnit,pWarehouse);
    	call sp_UpdateGRPOStatus(pPonum,pPoitem,pQuantity); 
    END if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UpdateGRPOStatus` (IN `pPonum` VARCHAR(15), IN `pPoitem` INT, IN `pGrqty` DECIMAL(15,3))  NO SQL
BEGIN
	DECLARE totalgr decimal(15,3);
    DECLARE qtypo decimal(15,3);
    
    SELECT quantity INTO qtypo  FROM t_po02 WHERE ponum = pPonum and poitem = pPoitem;
    SELECT grqty INTO totalgr FROM t_po02 WHERE ponum = pPonum and poitem = pPoitem;
    
    set totalgr = totalgr + pGrqty;

    if totalgr = qtypo THEN
    	UPDATE t_po02 SET grqty = totalgr, grstatus = 'X', pocomplete = 'Y' WHERE ponum = pPonum and poitem = pPoitem;
    else 
    	UPDATE t_po02 SET grqty = totalgr WHERE ponum = pPonum and poitem = pPoitem;
        end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UpdateRequestSlipStatus` (IN `pReqnum` VARCHAR(15), IN `pReqitem` INT, IN `pMaterial` VARCHAR(70), IN `pPrice` DECIMAL(15,2))  BEGIN
	DECLARE prQty decimal(15,3);
    DECLARE poQty decimal(15,3);
    SELECT quantity into prQty from t_request_slip02 where requestnum = pReqnum and request_item = pReqitem;
    SELECT sum(quantity) into poQty from t_po02 where requestnum = pReqnum and request_item = pReqitem;
   
   IF poQty >= prQty then  
		UPDATE t_request_slip02 set po_created = 'Y' WHERE requestnum = pReqnum AND request_item = pReqitem;
    END IF;  
    
    UPDATE t_material set stdprice = pPrice WHERE material = pMaterial;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UpdateStock` (IN `pMaterial` VARCHAR(70), IN `pDept` INT, IN `pQuantity` DECIMAL(15,3), IN `pMvt` VARCHAR(5), IN `pUnit` VARCHAR(5))  BEGIN
	DECLARE currentqty decimal(15,2) DEFAULT 0;
    DECLARE _currentqty decimal(15,2) DEFAULT 0;
    
    
	Select quantity INTO currentqty from t_stock WHERE material = pMaterial;        
    
    if pMvt = '101' THEN    
    	INSERT INTO t_stock (material,quantity,unit) VALUES(pMaterial, pQuantity, pUnit) ON DUPLICATE KEY UPDATE quantity = currentqty + pQuantity;
    
    elseif pMvt = '201' THEN
    
    	UPDATE t_stock set quantity = currentqty - pQuantity WHERE material = pMaterial;
    	
    end if;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fGetApproveDatePR` (`pPrnum` VARCHAR(20)) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci BEGIN
    DECLARE hasil VARCHAR(50);
	
    SET hasil = (SELECT approvedate from t_pr02 where prnum = pPrnum and final_approve = 'X' and approvestat not in('5','1') LIMIT 1);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetDepatment` (`pId` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci BEGIN
    DECLARE hasil VARCHAR(50);
	
    SET hasil = (SELECT department from t_department where id = pId);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetMaterialPrice` (`pMaterial` VARCHAR(70), `pMonth` INT, `pYear` INT) RETURNS DECIMAL(15,2) BEGIN
    DECLARE hasil decimal(15,2);
    DECLARE totalPO int;
    
    SET totalPO = (SELECT count(*) from t_po01 as a INNER JOIN t_po02 as b on a.ponum = b.ponum where month(a.podat) = pMonth and year(a.podat) = pYear and b.material = pMaterial and a.is_rejected = 'N');
	
    SET hasil = (SELECT total_unitprice from v_material_price_history where month = pMonth and year = pYear and material = pMaterial);
    	-- return the customer level
	RETURN (hasil/totalPO);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetUserDepartment` (`puserName` VARCHAR(50)) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NO SQL
BEGIN
    DECLARE hasil VARCHAR(20);
	
    SET hasil = (SELECT deptname from v_user where username = pUsername);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetUserName` (`pUser` VARCHAR(50)) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci BEGIN
    DECLARE hasil VARCHAR(50);
	
    SET hasil = (SELECT nama from t_user where username = pUser);
    	-- return the customer level
	RETURN (hasil);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tblsetting`
--

CREATE TABLE `tblsetting` (
  `id` int(11) NOT NULL,
  `company` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblsetting`
--

INSERT INTO `tblsetting` (`id`, `company`, `address`, `createdby`) VALUES
(1, 'Purchase Request - System', 'Company Address', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_actionlist`
--

CREATE TABLE `t_actionlist` (
  `id` int(11) NOT NULL,
  `actionname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_actionlist`
--

INSERT INTO `t_actionlist` (`id`, `actionname`, `createdon`, `createdby`) VALUES
(1, 'Retest', '2021-08-08', 'sys-admin'),
(2, 'Replaced', '2021-08-08', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_approval`
--

CREATE TABLE `t_approval` (
  `object` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctype` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `creator` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mapping Approval PR PO';

--
-- Dumping data for table `t_approval`
--

INSERT INTO `t_approval` (`object`, `doctype`, `level`, `creator`, `approval`) VALUES
('PO', '', 1, 'sys-admin', 'sys-admin'),
('PR', 'PR01', 1, 'sys-admin', 'sys-admin'),
('PR', 'PR01', 1, 'user1', 'sys-admin'),
('PR', 'PR01', 1, 'user2', 'sys-admin'),
('PR', 'PR02', 1, 'sys-admin', 'sys-admin'),
('PR', 'PR02', 1, 'user2', 'user3'),
('RS', '', 1, 'sys-admin', 'sys-admin'),
('RS', '', 2, 'sys-admin', 'user1');

-- --------------------------------------------------------

--
-- Table structure for table `t_auth_object`
--

CREATE TABLE `t_auth_object` (
  `ob_auth` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Authorization Object';

-- --------------------------------------------------------

--
-- Table structure for table `t_budget`
--

CREATE TABLE `t_budget` (
  `id` int(11) NOT NULL,
  `deptid` int(11) NOT NULL,
  `budget_period` int(4) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Budget';

--
-- Dumping data for table `t_budget`
--

INSERT INTO `t_budget` (`id`, `deptid`, `budget_period`, `amount`, `currency`, `budget_status`, `createdby`, `createdon`) VALUES
(1, 1, 2021, '100000000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(2, 2, 2021, '2000000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(3, 3, 2021, '5500000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(4, 1, 2021, '100000000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(5, 2, 2021, '9000000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(6, 3, 2021, '9000000.00', 'PHP', '1', 'sys-admin', '2021-11-02 00:00:00'),
(7, 1, 2022, '999999.00', 'PHP', '1', 'sys-admin', '2022-01-04 00:00:00');

--
-- Triggers `t_budget`
--
DELIMITER $$
CREATE TRIGGER `insertBudgetHistory` BEFORE INSERT ON `t_budget` FOR EACH ROW INSERT INTO t_budget_history (deptid,budget_period,amount,budget_type,note,refnum,refitem,createdon,createdby) VALUES(NEW.deptid,NEW.budget_period,NEW.amount,'C','Budget Allocation',NULL,NULL,NOW(),NEW.createdby)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateBudgetSummary` AFTER INSERT ON `t_budget` FOR EACH ROW CALL sp_GenerateBudgetSummary(NEW.deptid,NEW.budget_period,NEW.amount)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_budget_history`
--

CREATE TABLE `t_budget_history` (
  `id` int(11) NOT NULL,
  `deptid` int(11) NOT NULL,
  `budget_period` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `budget_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refnum` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refitem` int(11) DEFAULT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Budget History';

--
-- Dumping data for table `t_budget_history`
--

INSERT INTO `t_budget_history` (`id`, `deptid`, `budget_period`, `amount`, `budget_type`, `note`, `refnum`, `refitem`, `createdon`, `createdby`) VALUES
(6, 1, 2021, '100000000.00', 'C', 'Budget Allocation', NULL, NULL, '2021-11-02', 'sys-admin'),
(7, 2, 2021, '9000000.00', 'C', 'Budget Allocation', NULL, NULL, '2021-11-02', 'sys-admin'),
(8, 1, 2021, '8001.90', 'D', 'Budget Issuing', '1000000000', 1, '2021-11-02', 'sys-admin'),
(9, 1, 2021, '29998.50', 'D', 'Budget Issuing', '1000000000', 2, '2021-11-02', 'sys-admin'),
(10, 1, 2021, '8001.90', 'D', 'Budget Issuing', '1000000000', 1, '2021-11-02', 'sys-admin'),
(11, 1, 2021, '29998.50', 'D', 'Budget Issuing', '1000000000', 2, '2021-11-02', 'sys-admin'),
(12, 1, 2021, '10007.40', 'D', 'Budget Issuing', '1000000001', 1, '2021-11-02', 'sys-admin'),
(13, 2, 2021, '8001.90', 'D', 'Budget Issuing', '1000000002', 1, '2021-11-02', 'sys-admin'),
(14, 2, 2021, '29998.50', 'D', 'Budget Issuing', '1000000002', 2, '2021-11-02', 'sys-admin'),
(15, 2, 2021, '5003.70', 'D', 'Budget Issuing', '1000000002', 3, '2021-11-02', 'sys-admin'),
(16, 1, 2021, '20004.75', 'D', 'Budget Issuing', '1000000003', 1, '2021-11-02', 'sys-admin'),
(17, 3, 2021, '9000000.00', 'C', 'Budget Allocation', NULL, NULL, '2021-11-02', 'sys-admin'),
(18, 1, 2021, '20004.75', 'D', 'Budget Issuing', '1000000004', 1, '2021-11-03', 'sys-admin'),
(19, 1, 2021, '4000.95', 'D', 'Budget Issuing', '1000000011', 1, '2021-11-04', 'sys-admin'),
(20, 1, 2021, '1000.74', 'D', 'Budget Issuing', '1000000011', 2, '2021-11-04', 'sys-admin'),
(21, 1, 2022, '999999.00', 'C', 'Budget Allocation', NULL, NULL, '2022-01-04', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_budget_summary`
--

CREATE TABLE `t_budget_summary` (
  `department` int(11) NOT NULL,
  `budget_period` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `issuing_amount` decimal(15,2) NOT NULL,
  `budget_status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Budget Summary';

--
-- Dumping data for table `t_budget_summary`
--

INSERT INTO `t_budget_summary` (`department`, `budget_period`, `amount`, `issuing_amount`, `budget_status`) VALUES
(1, 2021, '99868980.61', '131019.39', '1'),
(1, 2022, '999999.00', '0.00', '1'),
(2, 2021, '8956995.90', '43004.10', '1'),
(3, 2021, '9000000.00', '0.00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `t_causelist`
--

CREATE TABLE `t_causelist` (
  `id` int(11) NOT NULL,
  `causename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_causelist`
--

INSERT INTO `t_causelist` (`id`, `causename`, `createdon`, `createdby`) VALUES
(1, 'Electrical Defect', '2021-08-08', 'sys-admin'),
(2, 'Machine Error', '2021-08-08', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_currency`
--

CREATE TABLE `t_currency` (
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrykey` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimalplace` int(11) NOT NULL DEFAULT 0,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Currency List';

--
-- Dumping data for table `t_currency`
--

INSERT INTO `t_currency` (`currency`, `contrykey`, `description`, `decimalplace`, `createdby`) VALUES
('PHP', 'PH', 'Philippine Peso', 2, 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_defectlist`
--

CREATE TABLE `t_defectlist` (
  `id` int(11) NOT NULL,
  `defectname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_defectlist`
--

INSERT INTO `t_defectlist` (`id`, `defectname`, `createdon`, `createdby`) VALUES
(1, 'Component Fail', '2021-08-08', 'sys-admin'),
(2, 'Voltage Error', '2021-08-08', 'sys-admin'),
(3, 'Damage Part', '2021-08-08', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_department`
--

CREATE TABLE `t_department` (
  `id` int(11) NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_department`
--

INSERT INTO `t_department` (`id`, `department`, `createdon`, `createdby`) VALUES
(1, 'I T', '2021-08-14 09:08:32', 'sys-admin'),
(2, 'PURCHASING', '2021-08-14 09:08:43', 'sys-admin'),
(3, 'QUALITY CONTROL', '2021-08-14 09:08:12', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_dept_section`
--

CREATE TABLE `t_dept_section` (
  `deptid` int(11) NOT NULL,
  `sectionid` int(11) NOT NULL,
  `deskripsi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_email_config`
--

CREATE TABLE `t_email_config` (
  `driver` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encryption` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_email_config`
--

INSERT INTO `t_email_config` (`driver`, `host`, `port`, `encryption`, `username`, `password`, `sender_name`, `sender_email`) VALUES
('smtp', 'smtp.gmail.com', '587', 'tls', 'to3kangketik@gmail.com', 'S@turnus', 'System-Info', 'to3kangketik@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_inventory`
--

CREATE TABLE `t_inventory` (
  `ivnum` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ivyear` int(11) NOT NULL,
  `ivitem` int(11) NOT NULL,
  `note` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matdesc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `matunit` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `movement_date` date NOT NULL,
  `movement_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refrence` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refnum` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refitem` int(11) DEFAULT NULL,
  `deptid` int(11) DEFAULT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `t_inventory`
--
DELIMITER $$
CREATE TRIGGER `updateInventoryStock` AFTER INSERT ON `t_inventory` FOR EACH ROW call sp_UpdateStock(NEW.material,NEW.deptid,NEW.quantity,NEW.movement_type,NEW.matunit)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_inventory_stock`
--

CREATE TABLE `t_inventory_stock` (
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouseid` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_inventory_stock`
--

INSERT INTO `t_inventory_stock` (`material`, `warehouseid`, `quantity`, `unit`) VALUES
('PART005', 'WH01', '5300.000', 'PC'),
('PART006', 'WH01', '1500.000', 'PC'),
('PART01', 'WH01', '500.000', 'PC'),
('PART02', 'WH01', '3200.000', 'PC'),
('PART03', 'WH01', '9000.000', 'PC');

-- --------------------------------------------------------

--
-- Table structure for table `t_ipd_forms`
--

CREATE TABLE `t_ipd_forms` (
  `transactionid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prod_date` date NOT NULL,
  `partnumber` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `partmodel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_ipd_process`
--

CREATE TABLE `t_ipd_process` (
  `transactionid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process4` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process5` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process6` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process7` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process8` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process9` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_process` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cause` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastprocess` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_ipd_repair`
--

CREATE TABLE `t_ipd_repair` (
  `transactionid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process4` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process5` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process6` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastrepair` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_kurs`
--

CREATE TABLE `t_kurs` (
  `currency1` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kurs1` decimal(15,2) NOT NULL,
  `currency2` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kurs2` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kurs`
--

INSERT INTO `t_kurs` (`currency1`, `kurs1`, `currency2`, `kurs2`) VALUES
('IDR', '14500.00', 'USD', '1.00'),
('USD', '1.00', 'IDR', '15000.00');

-- --------------------------------------------------------

--
-- Table structure for table `t_locationlist`
--

CREATE TABLE `t_locationlist` (
  `id` int(11) NOT NULL,
  `locationname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_locationlist`
--

INSERT INTO `t_locationlist` (`id`, `locationname`, `createdon`, `createdby`) VALUES
(1, 'C111', '2021-08-08', 'sys-admin'),
(2, 'C222', '2021-08-08', 'sys-admin'),
(3, 'D45', '2021-08-08', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_material`
--

CREATE TABLE `t_material` (
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mattype` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matgroup` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matunit` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minstock` decimal(15,2) DEFAULT NULL,
  `orderunit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stdprice` decimal(15,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material Master';

--
-- Dumping data for table `t_material`
--

INSERT INTO `t_material` (`material`, `brand`, `matdesc`, `supplier`, `mattype`, `matgroup`, `color`, `size`, `matunit`, `minstock`, `orderunit`, `stdprice`, `image`, `active`, `createdon`, `createdby`) VALUES
('PART005', 'HONDA', 'Test Part Image', 'Test', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '455.00', 'PART005-kitchen.jpeg', NULL, '2021-11-04 05:11:46', 'sys-admin'),
('PART006', 'YAMAHA', 'Test Part With Image', 'YAMAHA', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '556.00', 'PART006-lemari.jpg', NULL, '2021-11-04 11:11:59', 'sys-admin'),
('PART01', 'YAMAHA', 'Part01 Testing', 'YAMAHADO', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '556.00', 'PART01-ic_home.png', NULL, '2021-08-15 11:08:12', 'sys-admin'),
('PART02', 'YAMAHA', 'Part02 Testing', 'YAMAHADO', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '9999.50', 'PART02-user.ico', NULL, '2021-08-15 11:08:20', 'sys-admin'),
('PART03', 'HONDA', 'Test Part With Image', 'HONDA', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '100.00', 'PART03-home.PNG', NULL, '2021-11-04 05:11:11', 'sys-admin'),
('PART04', 'Honda', 'Testing', 'Test', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '29000.00', 'PART04-', NULL, '2022-01-04 01:01:34', 'sys-admin'),
('YPART-01', 'HONDA', 'PART HONDA 1010', 'HONDAAA', NULL, NULL, NULL, NULL, 'PC', NULL, NULL, '1000.74', 'YPART-01-home.PNG', NULL, '2021-10-24 11:10:34', 'sys-admin');

--
-- Triggers `t_material`
--
DELIMITER $$
CREATE TRIGGER `DELETE_MATERIAL` AFTER DELETE ON `t_material` FOR EACH ROW DELETE FROM t_material2 where material = OLD.material
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `INSERT_TO_ALT_UOM` AFTER INSERT ON `t_material` FOR EACH ROW INSERT INTO t_material2 VALUES(NEW.material,NEW.matunit,1,NEW.matunit,1,NEW.createdon,NEW.createdby)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_material2`
--

CREATE TABLE `t_material2` (
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `altuom` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `convalt` decimal(15,2) NOT NULL,
  `baseuom` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `convbase` decimal(15,2) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material Alternative UOM';

--
-- Dumping data for table `t_material2`
--

INSERT INTO `t_material2` (`material`, `altuom`, `convalt`, `baseuom`, `convbase`, `createdon`, `createdby`) VALUES
('PAR001', 'PC', '1.00', 'PC', '1.00', '2021-08-15 11:08:12', 'sys-admin'),
('PART005', 'PC', '1.00', 'PC', '1.00', '2021-11-04 05:11:46', 'sys-admin'),
('PART006', 'PC', '1.00', 'PC', '1.00', '2021-11-04 11:11:59', 'sys-admin'),
('PART02', 'PC', '1.00', 'PC', '1.00', '2021-08-15 11:08:20', 'sys-admin'),
('PART03', 'PC', '1.00', 'PC', '1.00', '2021-11-04 05:11:11', 'sys-admin'),
('PART04', 'PC', '1.00', 'PC', '1.00', '2022-01-04 01:01:34', 'sys-admin'),
('YPART-01', 'PC', '1.00', 'PC', '1.00', '2021-10-24 11:10:34', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_menugroups`
--

CREATE TABLE `t_menugroups` (
  `menugroup` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` timestamp NOT NULL DEFAULT current_timestamp(),
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_menugroups`
--

INSERT INTO `t_menugroups` (`menugroup`, `description`, `icon`, `createdon`, `createdby`) VALUES
(1, 'MASTER DATA', 'storage', '2021-08-06 14:01:33', 'sys-admin'),
(2, 'TRANSACTION', 'archive', '2021-08-06 14:01:33', ''),
(3, 'REPORTS', 'library_books', '2021-08-06 14:02:16', 'sys-admin'),
(4, 'SETTINGS', 'settings', '2021-08-06 14:02:16', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_menus`
--

CREATE TABLE `t_menus` (
  `id` int(11) NOT NULL,
  `menu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menugroup` int(11) NOT NULL,
  `grouping` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_sorting` int(11) DEFAULT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Application Menus';

--
-- Dumping data for table `t_menus`
--

INSERT INTO `t_menus` (`id`, `menu`, `route`, `type`, `icon`, `menugroup`, `grouping`, `_sorting`, `createdon`, `createdby`) VALUES
(1, 'Material Master', 'material', 'parent', '', 1, NULL, 1, '2021-08-07 00:00:00', 'sys-admin'),
(2, 'Material Type', 'material', 'parent', '', 1, NULL, 2, '2021-08-07 00:00:00', 'sys-admin'),
(3, 'Generate Process Form', 'transaction/form', 'parent', '', 2, NULL, 1, '2021-08-07 00:00:00', 'sys-admin'),
(4, 'Transaction Process', 'transaction/process', 'parent', '', 2, NULL, 2, '2021-08-07 00:00:00', 'sys-admin'),
(5, 'Transaction Report', 'reports/transaction', 'parent', '', 3, NULL, 1, '2021-08-07 00:00:00', 'sys-admin'),
(6, 'Maintain User', 'user', 'parent', '', 4, NULL, 1, '2021-08-07 00:00:00', 'sys-admin'),
(7, 'Maintain System Menu', 'menu', 'parent', '', 4, NULL, 2, '2021-08-07 00:00:00', 'sys-admin'),
(8, 'Maintain Role', 'role', 'parent', '', 4, NULL, 3, '2021-08-07 00:00:00', 'sys-admin'),
(9, 'Maintain Menu Role', 'menurole', 'parent', '', 4, NULL, 4, '2021-08-07 00:00:00', 'sys-admin'),
(10, 'Maintain User Role', 'userrole', 'parent', '', 4, NULL, 5, '2021-08-07 00:00:00', 'sys-admin'),
(11, 'General Setting', 'generalsetting', 'parent', '', 4, NULL, 6, '2021-08-07 00:00:00', 'sys-admin'),
(12, 'Transaction Repair', 'transaction/repair', 'parent', '', 2, NULL, 3, '2021-08-07 00:00:00', 'sys-admin'),
(13, 'Defect List', 'master/defect', 'parent', '', 1, NULL, 3, '2021-08-08 00:00:00', 'sys-admin'),
(14, 'Location', 'master/location', 'parent', '', 1, NULL, 4, '2021-08-08 00:00:00', 'sys-admin'),
(15, 'Cause List', 'master/cause', 'parent', '', 1, NULL, 5, '2021-08-08 00:00:00', 'sys-admin'),
(16, 'Action List', 'master/action', 'parent', '', 1, NULL, 6, '2021-08-08 00:00:00', 'sys-admin'),
(17, 'Process Flow', 'processflow', 'parent', '', 4, NULL, 8, '2021-08-09 00:00:00', 'sys-admin'),
(18, 'Create Purchase Request', 'pr', 'parent', '', 2, NULL, 5, '2021-08-14 00:00:00', 'sys-admin'),
(19, 'Department List', 'department', 'parent', '', 1, NULL, 2, '2021-08-14 00:00:00', 'sys-admin'),
(20, 'Budget Allocation', 'budgeting', 'parent', '', 2, NULL, 4, '2021-08-14 00:00:00', 'sys-admin'),
(21, 'Approve Purchase Request', 'approvepr', 'parent', '', 2, NULL, 6, '2021-08-14 00:00:00', 'sys-admin'),
(22, 'Mapping Approval', 'approval', 'parent', '', 4, NULL, 7, '2021-08-14 00:00:00', 'sys-admin'),
(23, 'Report Purchase Request', 'reports/reportpr', 'parent', '', 3, NULL, 2, '2021-08-14 00:00:00', 'sys-admin'),
(24, 'Report Budget Issuing', 'reports/budgetissuing', 'parent', '', 3, NULL, 3, '2021-08-17 00:00:00', 'sys-admin'),
(25, 'Transfer Budget', 'budgeting/transfer', 'parent', '', 2, NULL, 7, '2021-08-17 00:00:00', 'sys-admin'),
(26, 'Parts Deliver', 'inventory/deliver', 'parent', '', 2, NULL, 8, '2021-10-24 00:00:00', 'sys-admin'),
(27, 'Stock Adjustment', 'inventory/adjustment', 'parent', '', 2, NULL, 9, '2021-10-24 00:00:00', 'sys-admin'),
(28, 'Stock Report', 'stockreport', 'parent', '', 3, NULL, 4, '2021-10-24 00:00:00', 'sys-admin'),
(29, 'Issued Stock Report', 'stockreport/issuingstock', 'parent', '', 3, NULL, 5, '2021-10-24 00:00:00', 'sys-admin'),
(30, 'Warehouse Master', 'warehouse', 'parent', '', 1, NULL, 7, '2021-11-02 00:00:00', 'sys-admin'),
(31, 'Purchase Request Type', 'prtype', 'parent', '', 1, NULL, 8, '2021-11-03 00:00:00', 'sys-admin'),
(32, 'Create Request Slip', 'requestslip', 'parent', '', 2, NULL, 6, '2021-11-17 00:00:00', 'sys-admin'),
(33, 'Supplier Master', 'supplier', 'parent', '', 1, NULL, 9, '2021-11-17 00:00:00', 'sys-admin'),
(36, 'Create Purchase Order', 'po', 'parent', '', 2, NULL, 7, '2021-11-17 00:00:00', 'sys-admin'),
(37, 'Request Slip', 'requestslip/requestlist', 'parent', '', 2, NULL, NULL, '2021-12-19 00:00:00', 'sys-admin'),
(38, 'Submit Request for PO', 'requestslip/submitpo', 'parent', '', 2, NULL, NULL, '2021-12-19 00:00:00', 'sys-admin'),
(39, 'Approve Request Slip', 'approveslip', 'parent', '', 2, NULL, 6, '2021-12-22 00:00:00', 'sys-admin'),
(40, 'Receipt Purchase Order', 'grpo', 'parent', '', 2, NULL, 8, '2021-12-26 00:00:00', 'sys-admin'),
(41, 'Approve Purchase Order', 'approvepo', 'parent', '', 2, NULL, 7, '2021-12-26 00:00:00', 'sys-admin'),
(42, 'Report Purchase Order', 'reportpo', 'parent', '', 3, NULL, 6, '2022-01-04 00:00:00', 'sys-admin'),
(43, 'Report Receipt Purchase Order', 'reportgrpo', 'parent', '', 3, NULL, 7, '2022-01-04 00:00:00', 'sys-admin'),
(44, 'Material Price Order History', 'materialprice', 'parent', '', 3, NULL, 8, '2022-01-04 00:00:00', 'sys-admin'),
(45, 'Report Request Slip', 'reportslip', 'parent', '', 3, NULL, NULL, '2022-01-12 00:00:00', 'sys-admin'),
(46, 'Open Purchase Order', 'reportpo/openpo', 'parent', '', 3, NULL, NULL, '2022-01-20 00:00:00', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_movement_01`
--

CREATE TABLE `t_movement_01` (
  `movement_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_year` int(11) NOT NULL,
  `movement_date` date NOT NULL,
  `movement_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_note` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Inventory Movement Header';

--
-- Dumping data for table `t_movement_01`
--

INSERT INTO `t_movement_01` (`movement_number`, `movement_year`, `movement_date`, `movement_type`, `movement_note`, `createdby`, `createdon`) VALUES
('5000000008', 2021, '2021-12-26', '101', 'Terima Barang', 'sys-admin', '2021-12-26'),
('5000000009', 2021, '2021-12-26', '101', 'GR PO', 'sys-admin', '2021-12-26'),
('5000000010', 2021, '2021-12-26', '101', 'Test GR PO', 'sys-admin', '2021-12-26'),
('5000000011', 2021, '2021-12-26', '101', 'Receipt 2', 'sys-admin', '2021-12-26'),
('5000000012', 2021, '2021-12-28', '101', '', 'sys-admin', '2021-12-28'),
('5000000013', 2021, '2021-12-28', '101', 'Tes', 'sys-admin', '2021-12-28'),
('5000000014', 2021, '2021-12-28', '101', 'Receipt 2', 'sys-admin', '2021-12-28'),
('5000000015', 2022, '2022-01-13', '101', 'Tes PO', 'sys-admin', '2022-01-13');

--
-- Triggers `t_movement_01`
--
DELIMITER $$
CREATE TRIGGER `deletemovementitems` AFTER DELETE ON `t_movement_01` FOR EACH ROW DELETE FROM t_movement_02 where movement_number = OLD.movement_number and movement_year = OLD.movement_year
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_movement_02`
--

CREATE TABLE `t_movement_02` (
  `movement_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_year` int(11) NOT NULL,
  `movement_item` int(11) NOT NULL,
  `movement_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouseid` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matdesc` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batchnumber` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouseto` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `prnum` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pritem` int(11) DEFAULT NULL,
  `ponum` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poitem` int(11) DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Inventory Movement Items';

--
-- Dumping data for table `t_movement_02`
--

INSERT INTO `t_movement_02` (`movement_number`, `movement_year`, `movement_item`, `movement_type`, `warehouseid`, `material`, `matdesc`, `batchnumber`, `quantity`, `unit`, `warehouseto`, `unit_price`, `prnum`, `pritem`, `ponum`, `poitem`, `createdby`, `createdon`) VALUES
('5000000008', 2021, 1, '101', 'WH01', 'PART01', 'Part01 Testing', NULL, '1000.000', 'PC', NULL, NULL, NULL, NULL, '100001', 1, 'sys-admin', '2021-12-26'),
('5000000008', 2021, 2, '101', 'WH01', 'PART02', 'Part02 Testing', NULL, '3000.000', 'PC', NULL, NULL, NULL, NULL, '100001', 2, 'sys-admin', '2021-12-26'),
('5000000009', 2021, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '1000.000', 'PC', NULL, NULL, NULL, NULL, '100000', 1, 'sys-admin', '2021-12-26'),
('5000000010', 2021, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '4000.000', 'PC', NULL, NULL, NULL, NULL, '100003', 1, 'sys-admin', '2021-12-26'),
('5000000010', 2021, 2, '101', 'WH01', 'PART02', 'Part02 Testing', NULL, '2500.000', 'PC', NULL, NULL, NULL, NULL, '100003', 2, 'sys-admin', '2021-12-26'),
('5000000011', 2021, 1, '101', 'WH01', 'PART02', 'Part02 Testing', NULL, '700.000', 'PC', NULL, NULL, NULL, NULL, '100004', 1, 'sys-admin', '2021-12-26'),
('5000000011', 2021, 2, '101', 'WH01', 'PART03', 'Test Part With Image', NULL, '9000.000', 'PC', NULL, NULL, NULL, NULL, '100004', 2, 'sys-admin', '2021-12-26'),
('5000000012', 2021, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '200.000', 'PC', NULL, NULL, NULL, NULL, '100009', 1, 'sys-admin', '2021-12-28'),
('5000000012', 2021, 2, '101', 'WH01', 'PART006', 'Test Part With Image', NULL, '600.000', 'PC', NULL, NULL, NULL, NULL, '100009', 2, 'sys-admin', '2021-12-28'),
('5000000012', 2021, 3, '101', 'WH01', 'PART01', 'Part01 Testing', NULL, '500.000', 'PC', NULL, NULL, NULL, NULL, '100009', 3, 'sys-admin', '2021-12-28'),
('5000000013', 2021, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '499.000', 'PC', NULL, NULL, NULL, NULL, '100009', 1, 'sys-admin', '2021-12-28'),
('5000000014', 2021, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '1.000', 'PC', NULL, NULL, NULL, NULL, '100009', 1, 'sys-admin', '2021-12-28'),
('5000000015', 2022, 1, '101', 'WH01', 'PART005', 'Test Part Image', NULL, '600.000', 'PC', NULL, NULL, NULL, NULL, '899999', 1, 'sys-admin', '2022-01-13'),
('5000000015', 2022, 2, '101', 'WH01', 'PART006', 'Test Part With Image', NULL, '900.000', 'PC', NULL, NULL, NULL, NULL, '899999', 2, 'sys-admin', '2022-01-13');

--
-- Triggers `t_movement_02`
--
DELIMITER $$
CREATE TRIGGER `updateInventory` AFTER INSERT ON `t_movement_02` FOR EACH ROW call sp_TriggerMovement(new.material,new.unit, new.quantity, new.ponum, new.poitem, new.warehouseid,new.movement_type)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_nriv`
--

CREATE TABLE `t_nriv` (
  `object` varchar(15) NOT NULL,
  `fromnum` varchar(15) NOT NULL,
  `tonumber` varchar(15) NOT NULL,
  `currentnum` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_nriv`
--

INSERT INTO `t_nriv` (`object`, `fromnum`, `tonumber`, `currentnum`) VALUES
('GRPO', '5000000000', '5999999999', '5000000016'),
('INVENTORY', '2000000000', '2999999999', '2000000009'),
('PR', '1000000000', '1999999999', '1000000013'),
('REQ_SLIP', '3000000000', '3999999999', '3000000020'),
('SUPPLIER', '2000000000', '2999999999', '2000000004');

-- --------------------------------------------------------

--
-- Table structure for table `t_po01`
--

CREATE TABLE `t_po01` (
  `ponum` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext_ponum` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `potype` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `podat` date DEFAULT NULL,
  `vendor` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_approve` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `is_rejected` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `reject_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Order Header';

--
-- Dumping data for table `t_po01`
--

INSERT INTO `t_po01` (`ponum`, `ext_ponum`, `potype`, `podat`, `vendor`, `note`, `currency`, `approvestat`, `appby`, `final_approve`, `is_rejected`, `reject_note`, `completed`, `warehouse`, `createdon`, `createdby`) VALUES
('100000', '100000', NULL, '2021-12-24', '2000000000', 'PO 1 slip 3000000001', 'PHP', '1', NULL, 'Y', 'N', NULL, NULL, NULL, '2021-12-24 09:12:25', 'sys-admin'),
('100001', '100001', NULL, '2021-12-24', '2000000000', 'PO 2 slip 3000000001', 'PHP', '1', NULL, 'Y', 'N', NULL, NULL, NULL, '2021-12-24 09:12:56', 'sys-admin'),
('100003', '100003', NULL, '2021-12-26', '2000000000', 'Testing PO Full CYcle', 'PHP', '1', NULL, 'Y', 'N', NULL, NULL, NULL, '2021-12-26 09:12:51', 'sys-admin'),
('100004', '100004', NULL, '2021-12-26', '2000000000', 'Tes PO', 'PHP', '1', NULL, 'Y', 'N', NULL, NULL, NULL, '2021-12-26 09:12:23', 'sys-admin'),
('100009', '100009', NULL, '2021-12-28', '2000000000', 'Test', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2021-12-28 09:12:32', 'sys-admin'),
('190000', '190000', NULL, '2022-01-03', '2000000000', 'Test PO', 'PHP', '99', 'sys-admin', 'Y', 'Y', 'Test Reject PO', NULL, NULL, '2022-01-03 02:01:36', 'sys-admin'),
('190001', '190001', NULL, '2022-01-03', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-03 02:01:16', 'sys-admin'),
('700000', '700000', NULL, '2022-01-16', '2000000000', 'Note', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-16 05:01:35', 'sys-admin'),
('899998', '899998', NULL, '2022-01-13', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-13 02:01:16', 'sys-admin'),
('899999', '899999', NULL, '2022-01-13', '2000000000', 'Test', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-13 02:01:52', 'sys-admin'),
('900000', '900000', NULL, '2022-01-04', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-04 01:01:02', 'sys-admin'),
('900005', '900005', NULL, '2022-01-05', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-05 10:01:14', 'sys-admin'),
('900006', '900006', NULL, '2022-01-05', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-05 10:01:00', 'sys-admin'),
('900007', '900007', NULL, '2022-01-05', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-05 10:01:42', 'sys-admin'),
('900008', '900008', NULL, '2022-01-05', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-05 10:01:48', 'sys-admin'),
('900009', '900009', NULL, '2022-01-05', '2000000000', 'Tes PO', 'PHP', '2', NULL, 'Y', 'N', NULL, NULL, NULL, '2022-01-05 10:01:40', 'sys-admin'),
('923456', '923456', NULL, '2022-01-20', '2000000000', 'Tes PO', 'PHP', '1', NULL, 'N', 'N', NULL, NULL, NULL, '2022-01-20 02:01:07', 'sys-admin'),
('993456', '993456', NULL, '2022-01-20', '2000000000', 'Tes PO', 'PHP', '1', NULL, 'N', 'N', NULL, NULL, NULL, '2022-01-20 02:01:00', 'sys-admin');

--
-- Triggers `t_po01`
--
DELIMITER $$
CREATE TRIGGER `deleteitem` AFTER DELETE ON `t_po01` FOR EACH ROW DELETE FROM t_po02 WHERE ponum = OLD.ponum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_po02`
--

CREATE TABLE `t_po02` (
  `ponum` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poitem` int(11) NOT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,3) DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `grqty` decimal(15,3) DEFAULT NULL,
  `requestnum` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_item` int(11) DEFAULT NULL,
  `grstatus` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pocomplete` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `paymentstat` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_approve` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvedate` date DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='PO Item';

--
-- Dumping data for table `t_po02`
--

INSERT INTO `t_po02` (`ponum`, `poitem`, `material`, `matdesc`, `quantity`, `unit`, `price`, `tax`, `discount`, `grqty`, `requestnum`, `request_item`, `grstatus`, `pocomplete`, `paymentstat`, `approvestat`, `approvedby`, `final_approve`, `approvedate`, `createdon`, `createdby`) VALUES
('100000', 1, 'PART005', 'Test Part Image', '1000.000', 'PC', '200.00', NULL, NULL, '1000.000', '3000000001', 1, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-24', 'sys-admin'),
('100001', 1, 'PART01', 'Part01 Testing', '1000.000', 'PC', '350.00', NULL, NULL, '1000.000', '3000000001', 3, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-24', 'sys-admin'),
('100001', 2, 'PART02', 'Part02 Testing', '3000.000', 'PC', '750.00', NULL, NULL, '3000.000', '3000000001', 4, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-24', 'sys-admin'),
('100003', 1, 'PART005', 'Test Part Image', '4000.000', 'PC', '55.00', NULL, NULL, '4000.000', '3000000002', 1, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-26', 'sys-admin'),
('100003', 2, 'PART02', 'Part02 Testing', '2500.000', 'PC', '789.00', NULL, NULL, '2500.000', '3000000002', 2, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-26', 'sys-admin'),
('100004', 1, 'PART02', 'Part02 Testing', '700.000', 'PC', '900.00', NULL, NULL, '700.000', '3000000003', 1, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-26', 'sys-admin'),
('100004', 2, 'PART03', 'Test Part With Image', '9000.000', 'PC', '999.00', NULL, NULL, '9000.000', '3000000003', 2, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-26', 'sys-admin'),
('100009', 1, 'PART005', 'Test Part Image', '700.000', 'PC', '99.00', NULL, NULL, '700.000', '3000000008', 1, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-28', 'sys-admin'),
('100009', 2, 'PART006', 'Test Part With Image', '600.000', 'PC', '89.00', NULL, NULL, '600.000', '3000000008', 2, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-28', 'sys-admin'),
('100009', 3, 'PART01', 'Part01 Testing', '500.000', 'PC', '66.00', NULL, NULL, '500.000', '3000000008', 3, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2021-12-28', 'sys-admin'),
('190000', 1, 'PART006', 'Test Part With Image', '1500.000', 'PC', '100.00', NULL, NULL, '0.000', '3000000001', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-03', 'sys-admin'),
('190001', 1, 'PART005', 'Test Part Image', '100.000', 'PC', '100.00', NULL, NULL, '0.000', '3000000004', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-03', 'sys-admin'),
('190001', 2, 'PART006', 'Test Part With Image', '2000.000', 'PC', '99.00', NULL, NULL, '0.000', '3000000004', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-03', 'sys-admin'),
('700000', 1, 'PART005', 'Test Part Image', '100.000', 'PC', '102.00', NULL, NULL, '0.000', '3000000019', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-16', 'sys-admin'),
('700000', 2, 'PART006', 'Test Part With Image', '250.000', 'PC', '99.00', NULL, NULL, '0.000', '3000000019', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-16', 'sys-admin'),
('899998', 1, 'PART006', 'Test Part With Image', '50.000', 'PC', '45.00', NULL, NULL, '0.000', '3000000015', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-13', 'sys-admin'),
('899998', 2, 'PART01', 'Part01 Testing', '100.000', 'PC', '65.00', NULL, NULL, '0.000', '3000000015', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-13', 'sys-admin'),
('899998', 3, 'PART02', 'Part02 Testing', '150.000', 'PC', '66.00', NULL, NULL, '0.000', '3000000015', 3, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-13', 'sys-admin'),
('899999', 1, 'PART005', 'Test Part Image', '600.000', 'PC', '100.00', NULL, NULL, '600.000', '3000000010', 1, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2022-01-13', 'sys-admin'),
('899999', 2, 'PART006', 'Test Part With Image', '900.000', 'PC', '200.00', NULL, NULL, '900.000', '3000000010', 2, 'X', 'Y', NULL, '1', NULL, NULL, NULL, '2022-01-13', 'sys-admin'),
('900000', 1, 'PART03', 'Test Part With Image', '800.000', 'PC', '450.00', NULL, NULL, '0.000', '3000000007', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-04', 'sys-admin'),
('900000', 2, 'YPART-01', 'PART HONDA 1010', '900.000', 'PC', '900.00', NULL, NULL, '0.000', '3000000007', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-04', 'sys-admin'),
('900005', 1, 'PART005', 'Test Part Image', '4000.000', 'PC', '100.00', NULL, NULL, '0.000', '3000000005', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900005', 2, 'PART006', 'Test Part With Image', '3000.000', 'PC', '300.00', NULL, NULL, '0.000', '3000000005', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900006', 1, 'PART005', 'Test Part Image', '600.000', 'PC', '100.00', NULL, NULL, '0.000', '3000000006', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900006', 2, 'PART006', 'Test Part With Image', '8000.000', 'PC', '300.00', NULL, NULL, '0.000', '3000000006', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900007', 1, 'PART005', 'Test Part Image', '100.000', 'PC', '900.00', NULL, NULL, '0.000', '3000000009', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900007', 2, 'PART006', 'Test Part With Image', '200.000', 'PC', '800.00', NULL, NULL, '0.000', '3000000009', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900008', 1, 'PART005', 'Test Part Image', '100.000', 'PC', '550.00', NULL, NULL, '0.000', '3000000011', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900008', 2, 'PART006', 'Test Part With Image', '200.000', 'PC', '8.77', NULL, NULL, '0.000', '3000000011', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900009', 1, 'PART005', 'Test Part Image', '3.000', 'PC', '5000.00', NULL, NULL, '0.000', '3000000012', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('900009', 2, 'PART006', 'Test Part With Image', '3.000', 'PC', '6500.00', NULL, NULL, '0.000', '3000000012', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-05', 'sys-admin'),
('923456', 1, 'PART005', 'Test Part Image', '60.000', 'PC', '69.00', NULL, NULL, '0.000', '3000000016', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-20', 'sys-admin'),
('923456', 2, 'PART006', 'Test Part With Image', '600.000', 'PC', '876.00', NULL, NULL, '0.000', '3000000016', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-20', 'sys-admin'),
('923456', 3, 'PART01', 'Part01 Testing', '560.000', 'PC', '556.00', NULL, NULL, '0.000', '3000000016', 3, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-20', 'sys-admin'),
('993456', 1, 'PART005', 'Test Part Image', '100.000', 'PC', '455.00', NULL, NULL, '0.000', '3000000017', 1, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-20', 'sys-admin'),
('993456', 2, 'PART006', 'Test Part With Image', '200.000', 'PC', '556.00', NULL, NULL, '0.000', '3000000017', 2, NULL, 'N', NULL, '1', NULL, NULL, NULL, '2022-01-20', 'sys-admin');

--
-- Triggers `t_po02`
--
DELIMITER $$
CREATE TRIGGER `tg_UpdateRequestSlipPOStatus` AFTER DELETE ON `t_po02` FOR EACH ROW UPDATE t_request_slip02 SET po_created='N' WHERE requestnum=OLD.requestnum AND request_item=OLD.request_item
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_UpdateRequestSlipPOStatusAndMaterialPrice` AFTER INSERT ON `t_po02` FOR EACH ROW CALL sp_UpdateRequestSlipStatus(NEW.requestnum, NEW.request_item, NEW.material, NEW.price)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_po03`
--

CREATE TABLE `t_po03` (
  `id` int(11) NOT NULL,
  `ponum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `efile` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_po03`
--

INSERT INTO `t_po03` (`id`, `ponum`, `efile`) VALUES
(3, '190001', '190001-Invoice Vimec 07122021.pdf'),
(4, '190001', '190001-Invoice Vimec 31122021.pdf'),
(5, '900000', '900000-Invoice Vimec 07122021.pdf'),
(6, '900000', '900000-Invoice Vimec 31122021.pdf'),
(7, '900005', '900005-Invoice Vimec 31122021.pdf'),
(8, '900006', '900006-Invoice Vimec 31122021.pdf'),
(9, '900007', '900007-Invoice Vimec 31122021.pdf'),
(10, '900008', '900008-'),
(11, '900009', '900009-'),
(12, '899999', '899999-'),
(13, '899998', '899998-'),
(14, '700000', '700000-Link Char SO.txt'),
(15, '700000', '700000-link restfull api node js.txt'),
(16, '923456', '923456-'),
(17, '993456', '993456-');

-- --------------------------------------------------------

--
-- Table structure for table `t_po04`
--

CREATE TABLE `t_po04` (
  `ponum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poitem` int(11) NOT NULL,
  `supplier_id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `podate` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material PO History';

-- --------------------------------------------------------

--
-- Table structure for table `t_po05`
--

CREATE TABLE `t_po05` (
  `ponum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approve_level` int(11) NOT NULL,
  `approve_date` date NOT NULL,
  `approve_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_po05`
--

INSERT INTO `t_po05` (`ponum`, `approve_level`, `approve_date`, `approve_by`) VALUES
('700000', 1, '2022-01-16', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_pr01`
--

CREATE TABLE `t_pr01` (
  `prnum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prtype` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prdate` date DEFAULT NULL,
  `relgroup` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requestby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deptid` int(11) DEFAULT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedon` date DEFAULT NULL,
  `changedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Requisition Header';

--
-- Dumping data for table `t_pr01`
--

INSERT INTO `t_pr01` (`prnum`, `prtype`, `note`, `prdate`, `relgroup`, `approvestat`, `requestby`, `warehouse`, `deptid`, `currency`, `appby`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
('1000000000', 'PR01', 'Test PR Production Supplies', '2021-11-02', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-11-02', 'sys-admin', NULL, NULL),
('1000000001', 'PR02', 'PR Office Supplies', '2021-11-02', NULL, '1', 'Administrator', 'WH02', 1, 'PHP', NULL, '2021-11-02', 'sys-admin', NULL, NULL),
('1000000002', 'PR01', '', '2021-11-02', NULL, '1', 'User Demo Purchasing 01', 'WH01', 2, 'PHP', NULL, '2021-11-02', 'user1', NULL, NULL),
('1000000003', 'PR01', 'tes', '2021-11-02', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-11-02', 'sys-admin', NULL, NULL),
('1000000004', 'PR02', 'Tes', '2021-11-03', NULL, '1', 'Administrator', 'WH02', 1, 'PHP', NULL, '2021-11-03', 'sys-admin', NULL, NULL),
('1000000008', 'PR01', '', '2021-11-03', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-11-03', 'sys-admin', NULL, NULL),
('1000000009', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, '2021-11-04', 'sys-admin', NULL, NULL),
('1000000010', 'PR01', 'Note', '2021-11-04', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-11-04', 'sys-admin', NULL, NULL),
('1000000011', 'PR01', 'Testing PR', '2021-11-04', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-11-04', 'sys-admin', NULL, NULL),
('1000000012', 'PR01', 'PR Production Supplies', '2021-12-21', NULL, '1', 'Administrator', 'WH01', 1, 'PHP', NULL, '2021-12-21', 'sys-admin', NULL, NULL),
('3000000000', NULL, NULL, '2021-12-18', NULL, '1', 'Administrator', NULL, NULL, NULL, NULL, '2021-12-18', 'sys-admin', NULL, NULL);

--
-- Triggers `t_pr01`
--
DELIMITER $$
CREATE TRIGGER `deletepritem` AFTER DELETE ON `t_pr01` FOR EACH ROW DELETE FROM t_pr02 WHERE prnum = OLD.prnum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_pr02`
--

CREATE TABLE `t_pr02` (
  `prnum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pritem` int(11) NOT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(18,3) DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pocreated` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approveby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_approve` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvedate` date DEFAULT NULL,
  `remark` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deptid` int(11) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `changedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Order Item';

--
-- Dumping data for table `t_pr02`
--

INSERT INTO `t_pr02` (`prnum`, `pritem`, `material`, `matdesc`, `quantity`, `unit`, `price`, `currency`, `warehouse`, `pocreated`, `approvestat`, `approveby`, `final_approve`, `approvedate`, `remark`, `deptid`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
('1000000000', 1, 'PART01', 'Part01 Testing', '2.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 1, '2021-11-02 00:00:00', 'sys-admin', NULL, NULL),
('1000000000', 2, 'PART02', 'Part02 Testing', '3.000', 'PC', '9999.50', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 1, '2021-11-02 00:00:00', 'sys-admin', NULL, NULL),
('1000000001', 1, 'YPART-01', 'PART HONDA 1010', '10.000', 'PC', '1000.74', 'PHP', 'WH02', NULL, '2', 'sys-admin', 'X', '2021-11-02', 'test', 1, '2021-11-02 00:00:00', 'sys-admin', NULL, NULL),
('1000000002', 1, 'PART01', 'Part01 Testing', '2.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 2, '2021-11-02 00:00:00', 'user1', NULL, NULL),
('1000000002', 2, 'PART02', 'Part02 Testing', '3.000', 'PC', '9999.50', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 2, '2021-11-02 00:00:00', 'user1', NULL, NULL),
('1000000002', 3, 'YPART-01', 'PART HONDA 1010', '5.000', 'PC', '1000.74', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 2, '2021-11-02 00:00:00', 'user1', NULL, NULL),
('1000000003', 1, 'PART01', 'Part01 Testing', '5.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-02', '', 1, '2021-11-02 00:00:00', 'sys-admin', NULL, NULL),
('1000000004', 1, 'PART01', 'Part01 Testing', '5.000', 'PC', '4000.95', 'PHP', 'WH02', NULL, '2', 'sys-admin', 'X', '2021-11-03', '', 1, '2021-11-03 00:00:00', 'sys-admin', NULL, NULL),
('1000000008', 1, 'YPART-01', 'PART HONDA 1010', '4.000', 'PC', '1000.74', 'PHP', 'WH01', NULL, '1', NULL, 'N', NULL, '', 1, '2021-11-03 00:00:00', 'sys-admin', NULL, NULL),
('1000000008', 2, 'PART01', 'Part01 Testing', '50.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '1', NULL, 'N', NULL, '', 1, '2021-11-03 00:00:00', 'sys-admin', NULL, NULL),
('1000000010', 1, 'PART01', 'Part01 Testing', '1.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '1', NULL, 'N', NULL, 'Tes', 1, '2021-11-04 00:00:00', 'sys-admin', NULL, NULL),
('1000000010', 2, 'YPART-01', 'PART HONDA 1010', '1.000', 'PC', '1000.74', 'PHP', 'WH01', NULL, '1', NULL, 'N', NULL, 'test', 1, '2021-11-04 00:00:00', 'sys-admin', NULL, NULL),
('1000000011', 1, 'PART01', 'Part01 Testing', '1.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-04', 'Tes', 1, '2021-11-04 00:00:00', 'sys-admin', NULL, NULL),
('1000000011', 2, 'YPART-01', 'PART HONDA 1010', '1.000', 'PC', '1000.74', 'PHP', 'WH01', NULL, '2', 'sys-admin', 'X', '2021-11-04', '', 1, '2021-11-04 00:00:00', 'sys-admin', NULL, NULL),
('1000000012', 1, 'PART01', 'Part01 Testing', '20.000', 'PC', '4000.95', 'PHP', 'WH01', NULL, '1', NULL, 'N', NULL, '', 1, '2021-12-21 00:00:00', 'sys-admin', NULL, NULL);

--
-- Triggers `t_pr02`
--
DELIMITER $$
CREATE TRIGGER `updateIssuingBudget` AFTER UPDATE ON `t_pr02` FOR EACH ROW IF NEW.final_approve = 'X' and NEW.approvestat <> 'R' THEN
	CALL sp_IssuingBudget(OLD.deptid,YEAR(now()),OLD.quantity*OLD.price,NEW.approveby,OLD.prnum,OLD.pritem);
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_process_sequence`
--

CREATE TABLE `t_process_sequence` (
  `id` int(11) NOT NULL,
  `transtype` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_process_sequence`
--

INSERT INTO `t_process_sequence` (`id`, `transtype`, `processname`, `sequence`, `username`) VALUES
(1, 'process', 'AOI SMT-BOTTOM (1st)', 1, 'user1'),
(2, 'process', 'AOI SMT-TOP (2nd)', 2, 'user2'),
(3, 'process', 'SMT SI', 3, 'user3'),
(4, 'process', 'ICT', 4, 'user4'),
(5, 'process', 'QPIT', 5, 'user5'),
(6, 'process', 'AOI HW-TOP', 6, 'user6'),
(7, 'process', 'AOI HW-BOTTOM', 7, 'user7'),
(8, 'process', 'FVI', 8, 'user8'),
(9, 'repair', 'AFTER REPAIR-ICT', 1, 'user1'),
(10, 'repair', 'AFTER REPAIR-QPIT', 2, 'user2'),
(11, 'repair', 'AFTER REPAIR-AOI TOP', 3, 'user3'),
(12, 'repair', 'AFTER REPAIR-BOT', 4, 'user4'),
(13, 'repair', 'AFTER REPAIR-FVI', 5, 'user5'),
(14, 'repair', 'OQA', 6, 'user6'),
(15, 'process', 'QQA', 9, 'user9');

-- --------------------------------------------------------

--
-- Table structure for table `t_prtype`
--

CREATE TABLE `t_prtype` (
  `prtype` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_prtype`
--

INSERT INTO `t_prtype` (`prtype`, `description`, `createdby`, `createdon`) VALUES
('PR01', 'Production Supplies', 'sys-admin', '2021-10-30'),
('PR02', 'Office Supplies', 'sys-admin', '2021-10-30');

-- --------------------------------------------------------

--
-- Table structure for table `t_quotation01`
--

CREATE TABLE `t_quotation01` (
  `quotation` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` int(11) NOT NULL,
  `qoutation_date` date NOT NULL,
  `request_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline_date` date NOT NULL,
  `purch_approved` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_status` int(11) NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_request_slip01`
--

CREATE TABLE `t_request_slip01` (
  `requestnum` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_date` date NOT NULL,
  `request_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_note` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_status` int(11) NOT NULL DEFAULT 1,
  `final_approve` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `is_rejected` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `reject_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deptid` int(11) NOT NULL,
  `efile` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_request_slip01`
--

INSERT INTO `t_request_slip01` (`requestnum`, `request_date`, `request_by`, `request_note`, `request_status`, `final_approve`, `is_rejected`, `reject_note`, `deptid`, `efile`, `createdon`, `createdby`) VALUES
('3000000001', '2021-12-18', 'Administrator', 'Test Request Slip', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-18', 'sys-admin'),
('3000000002', '2021-12-19', 'Administrator', 'Request Slip 2', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-19', 'sys-admin'),
('3000000003', '2021-12-19', 'Administrator', 'Test Slip', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-19', 'sys-admin'),
('3000000004', '2021-12-21', 'Administrator', 'Test Slip', 3, 'Y', 'N', NULL, 1, '3000000004-WSU - IPD System.docx', '2021-12-21', 'sys-admin'),
('3000000005', '2021-12-21', 'Administrator', 'Test Slip with attachment', 3, 'Y', 'N', NULL, 1, '3000000005-WSU - IPD System.docx', '2021-12-21', 'sys-admin'),
('3000000006', '2021-12-21', 'Administrator', 'Test Request Slip', 3, 'Y', 'N', NULL, 1, '3000000006-WSU - IPD System.docx', '2021-12-21', 'sys-admin'),
('3000000007', '2021-12-21', 'Administrator', 'Test Slip with attachment', 3, 'Y', 'N', NULL, 1, '3000000007-TemplateReportGI.xlsx', '2021-12-21', 'sys-admin'),
('3000000008', '2021-12-22', 'Administrator', 'Test Slip', 3, 'Y', 'N', NULL, 1, '3000000008-Testing BAPI DP PO.docx', '2021-12-22', 'sys-admin'),
('3000000009', '2021-12-23', 'Administrator', 'Test Multiple Attachment', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-23', 'sys-admin'),
('3000000010', '2021-12-23', 'Administrator', 'Test Multiple Attachment', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-23', 'sys-admin'),
('3000000011', '2021-12-23', 'Administrator', 'Reqeust with multiple attachment', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-23', 'sys-admin'),
('3000000012', '2021-12-23', 'Administrator', 'Test Request Slip', 3, 'Y', 'N', NULL, 1, NULL, '2021-12-23', 'sys-admin'),
('3000000013', '2022-01-04', 'Administrator', 'Test Request Slip', 2, 'N', 'N', NULL, 1, NULL, '2022-01-04', 'sys-admin'),
('3000000014', '2022-01-05', 'Administrator', 'Test Request Slip', 2, 'N', 'N', NULL, 1, NULL, '2022-01-05', 'sys-admin'),
('3000000015', '2022-01-05', 'Administrator', 'Test Request Slip', 99, 'Y', 'Y', 'Testing Reject Slip', 1, NULL, '2022-01-05', 'sys-admin'),
('3000000016', '2022-01-05', 'Administrator', 'Test Request Slip', 99, 'Y', 'Y', 'harga terlalu mahal', 1, NULL, '2022-01-05', 'sys-admin'),
('3000000017', '2022-01-05', 'Administrator', 'Test Request Slip', 99, 'Y', 'Y', 'ganti vendor, vendor sudah tidak bisa supply', 1, NULL, '2022-01-05', 'sys-admin'),
('3000000018', '2022-01-05', 'Administrator', 'Test Request Slip', 2, 'N', 'N', NULL, 1, NULL, '2022-01-05', 'sys-admin'),
('3000000019', '2022-01-16', 'Administrator', 'Test Approve', 3, 'Y', 'N', NULL, 1, NULL, '2022-01-16', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_request_slip02`
--

CREATE TABLE `t_request_slip02` (
  `requestnum` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_item` int(11) NOT NULL,
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `approvestat` int(11) NOT NULL DEFAULT 1,
  `po_created` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_request_slip02`
--

INSERT INTO `t_request_slip02` (`requestnum`, `request_item`, `material`, `quantity`, `unit`, `unit_price`, `approvestat`, `po_created`, `createdon`, `createdby`) VALUES
('3000000001', 1, 'PART005', '1000.000', 'PC', '650.00', 1, 'Y', '2021-12-18', 'sys-admin'),
('3000000001', 2, 'PART006', '1500.000', 'PC', '850.00', 1, 'Y', '2021-12-18', 'sys-admin'),
('3000000001', 3, 'PART01', '1000.000', 'PC', '4000.95', 1, 'Y', '2021-12-18', 'sys-admin'),
('3000000001', 4, 'PART02', '3000.000', 'PC', '9999.50', 1, 'Y', '2021-12-18', 'sys-admin'),
('3000000002', 1, 'PART005', '4000.000', 'PC', '700.00', 1, 'Y', '2021-12-19', 'sys-admin'),
('3000000002', 2, 'PART02', '2500.000', 'PC', '650.00', 1, 'Y', '2021-12-19', 'sys-admin'),
('3000000003', 1, 'PART02', '700.000', 'PC', '9999.50', 1, 'Y', '2021-12-19', 'sys-admin'),
('3000000003', 2, 'PART03', '9000.000', 'PC', '100.00', 1, 'Y', '2021-12-19', 'sys-admin'),
('3000000004', 1, 'PART005', '100.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000004', 2, 'PART006', '2000.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000005', 1, 'PART005', '4000.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000005', 2, 'PART006', '3000.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000006', 1, 'PART005', '600.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000006', 2, 'PART006', '8000.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000007', 1, 'PART03', '800.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000007', 2, 'YPART-01', '900.000', 'PC', '0.00', 1, 'Y', '2021-12-21', 'sys-admin'),
('3000000008', 1, 'PART005', '700.000', 'PC', '0.00', 1, 'Y', '2021-12-22', 'sys-admin'),
('3000000008', 2, 'PART006', '600.000', 'PC', '0.00', 1, 'Y', '2021-12-22', 'sys-admin'),
('3000000008', 3, 'PART01', '500.000', 'PC', '0.00', 1, 'Y', '2021-12-22', 'sys-admin'),
('3000000009', 1, 'PART005', '100.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000009', 2, 'PART006', '200.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000010', 1, 'PART005', '600.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000010', 2, 'PART006', '900.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000011', 1, 'PART005', '100.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000011', 2, 'PART006', '200.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000012', 1, 'PART005', '3.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000012', 2, 'PART006', '3.000', 'PC', '0.00', 1, 'Y', '2021-12-23', 'sys-admin'),
('3000000013', 1, 'PART01', '150.000', 'PC', '0.00', 1, 'N', '2022-01-04', 'sys-admin'),
('3000000013', 2, 'PART02', '130.000', 'PC', '0.00', 1, 'N', '2022-01-04', 'sys-admin'),
('3000000013', 3, 'PART03', '170.000', 'PC', '0.00', 1, 'N', '2022-01-04', 'sys-admin'),
('3000000013', 4, 'PART04', '165.000', 'PC', '0.00', 1, 'N', '2022-01-04', 'sys-admin'),
('3000000014', 1, 'PART005', '900.000', 'PC', '0.00', 1, 'N', '2022-01-05', 'sys-admin'),
('3000000014', 2, 'PART006', '400.000', 'PC', '0.00', 1, 'N', '2022-01-05', 'sys-admin'),
('3000000015', 1, 'PART006', '50.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000015', 2, 'PART01', '100.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000015', 3, 'PART02', '150.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000016', 1, 'PART005', '60.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000016', 2, 'PART006', '600.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000016', 3, 'PART01', '560.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000017', 1, 'PART005', '100.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000017', 2, 'PART006', '200.000', 'PC', '0.00', 1, 'Y', '2022-01-05', 'sys-admin'),
('3000000018', 1, 'PART006', '4.000', 'PC', '0.00', 1, 'N', '2022-01-05', 'sys-admin'),
('3000000018', 2, 'PART005', '3.000', 'PC', '0.00', 1, 'N', '2022-01-05', 'sys-admin'),
('3000000019', 1, 'PART005', '100.000', 'PC', '0.00', 1, 'Y', '2022-01-16', 'sys-admin'),
('3000000019', 2, 'PART006', '250.000', 'PC', '0.00', 1, 'Y', '2022-01-16', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_request_slip03`
--

CREATE TABLE `t_request_slip03` (
  `id` int(11) NOT NULL,
  `requestnum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `efile` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Request Slip attachments';

--
-- Dumping data for table `t_request_slip03`
--

INSERT INTO `t_request_slip03` (`id`, `requestnum`, `efile`) VALUES
(4, '3000000010', '3000000010-image2.jpg'),
(5, '3000000010', '3000000010-login-wlp.jpg'),
(6, '3000000010', '3000000010-login-wlp2.jpg'),
(7, '3000000011', '3000000011-city1.jpg'),
(8, '3000000011', '3000000011-city2.jpg'),
(9, '3000000011', '3000000011-image2.jpg'),
(10, '3000000011', '3000000011-login-wlp2.jpg'),
(11, '3000000012', '3000000012-ABAP - CDS.pptx'),
(12, '3000000012', '3000000012-ZTFI011.XLSX'),
(13, '3000000013', '3000000013-Invoice Vimec 31122021.pdf'),
(14, '3000000014', '3000000014-Invoice Vimec 31122021.pdf'),
(15, '3000000015', '3000000015-Invoice Vimec 31122021.pdf'),
(16, '3000000016', '3000000016-Invoice Vimec 31122021.pdf'),
(17, '3000000017', '3000000017-'),
(18, '3000000018', '3000000018-'),
(19, '3000000019', '3000000019-Link Char SO.txt');

-- --------------------------------------------------------

--
-- Table structure for table `t_request_slip04`
--

CREATE TABLE `t_request_slip04` (
  `requestnum` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approve_level` int(11) NOT NULL,
  `approve_date` date NOT NULL,
  `approve_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_request_slip04`
--

INSERT INTO `t_request_slip04` (`requestnum`, `approve_level`, `approve_date`, `approve_by`) VALUES
('3000000019', 1, '2022-01-16', 'sys-admin'),
('3000000019', 2, '2022-01-16', 'user1');

-- --------------------------------------------------------

--
-- Table structure for table `t_role`
--

CREATE TABLE `t_role` (
  `roleid` int(11) NOT NULL,
  `rolename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Role Master';

--
-- Dumping data for table `t_role`
--

INSERT INTO `t_role` (`roleid`, `rolename`, `createdon`, `createdby`) VALUES
(1, 'SYS-ADMIN', '2021-08-06 00:00:00', 'sys-admin'),
(2, 'ROLE01', '2021-08-08 00:00:00', 'sys-admin'),
(3, 'ROLE02', '2021-08-08 00:00:00', 'sys-admin'),
(4, 'APPROVAL_PR', '2021-10-12 00:00:00', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_rolemenu`
--

CREATE TABLE `t_rolemenu` (
  `roleid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Role Menu';

--
-- Dumping data for table `t_rolemenu`
--

INSERT INTO `t_rolemenu` (`roleid`, `menuid`, `createdon`, `createdby`) VALUES
(1, 1, '2021-08-07 00:00:00', 'sys-admin'),
(1, 6, '2021-08-07 00:00:00', 'sys-admin'),
(1, 7, '2021-08-07 00:00:00', 'sys-admin'),
(1, 8, '2021-08-07 00:00:00', 'sys-admin'),
(1, 9, '2021-08-07 00:00:00', 'sys-admin'),
(1, 10, '2021-08-07 00:00:00', 'sys-admin'),
(1, 11, '2021-08-07 00:00:00', 'sys-admin'),
(1, 18, '2021-08-14 00:00:00', 'sys-admin'),
(1, 19, '2021-08-14 00:00:00', 'sys-admin'),
(1, 20, '2021-08-14 00:00:00', 'sys-admin'),
(1, 21, '2021-08-14 00:00:00', 'sys-admin'),
(1, 22, '2021-08-14 00:00:00', 'sys-admin'),
(1, 23, '2021-08-15 00:00:00', 'sys-admin'),
(1, 24, '2021-08-17 00:00:00', 'sys-admin'),
(1, 26, '2021-10-24 00:00:00', 'sys-admin'),
(1, 27, '2021-10-24 00:00:00', 'sys-admin'),
(1, 28, '2021-10-24 00:00:00', 'sys-admin'),
(1, 29, '2021-10-24 00:00:00', 'sys-admin'),
(1, 30, '2021-11-02 00:00:00', 'sys-admin'),
(1, 31, '2021-11-03 00:00:00', 'sys-admin'),
(1, 32, '2021-11-17 00:00:00', 'sys-admin'),
(1, 33, '2021-11-17 00:00:00', 'sys-admin'),
(1, 36, '2021-11-17 00:00:00', 'sys-admin'),
(1, 39, '2021-12-22 00:00:00', 'sys-admin'),
(1, 40, '2021-12-26 00:00:00', 'sys-admin'),
(1, 41, '2021-12-28 00:00:00', 'sys-admin'),
(1, 42, '2022-01-04 00:00:00', 'sys-admin'),
(1, 43, '2022-01-04 00:00:00', 'sys-admin'),
(1, 44, '2022-01-04 00:00:00', 'sys-admin'),
(1, 45, '2022-01-12 00:00:00', 'sys-admin'),
(1, 46, '2022-01-20 00:00:00', 'sys-admin'),
(2, 18, '2021-08-17 00:00:00', 'sys-admin'),
(2, 23, '2021-08-17 00:00:00', 'sys-admin'),
(2, 39, '2021-12-22 00:00:00', 'sys-admin'),
(3, 21, '2021-08-17 00:00:00', 'sys-admin'),
(3, 23, '2021-08-17 00:00:00', 'sys-admin'),
(3, 24, '2021-08-17 00:00:00', 'sys-admin'),
(4, 21, '2021-10-12 00:00:00', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_role_avtivity`
--

CREATE TABLE `t_role_avtivity` (
  `roleid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `activity` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Activity Auth';

--
-- Dumping data for table `t_role_avtivity`
--

INSERT INTO `t_role_avtivity` (`roleid`, `menuid`, `activity`, `status`, `createdon`) VALUES
(1, 1, 'Create', 1, '2021-08-07'),
(1, 1, 'Delete', 1, '2021-08-07'),
(1, 1, 'Read', 1, '2021-08-07'),
(1, 1, 'Update', 1, '2021-08-07'),
(1, 6, 'Create', 1, '2021-08-07'),
(1, 6, 'Delete', 1, '2021-08-07'),
(1, 6, 'Edit', 1, '2021-08-07'),
(1, 6, 'Read', 1, '2021-08-07'),
(1, 6, 'Update', 1, '2021-08-07'),
(1, 7, 'Create', 1, '2021-08-07'),
(1, 7, 'Delete', 1, '2021-08-07'),
(1, 7, 'Read', 1, '2021-08-07'),
(1, 7, 'Update', 1, '2021-08-07'),
(1, 8, 'Create', 1, '2021-08-07'),
(1, 8, 'Delete', 1, '2021-08-07'),
(1, 8, 'Read', 1, '2021-08-07'),
(1, 8, 'Update', 1, '2021-08-07'),
(1, 9, 'Create', 1, '2021-08-07'),
(1, 9, 'Delete', 1, '2021-08-07'),
(1, 9, 'Read', 1, '2021-08-07'),
(1, 9, 'Update', 1, '2021-08-07'),
(1, 10, 'Create', 1, '2021-08-07'),
(1, 10, 'Delete', 1, '2021-08-07'),
(1, 10, 'Read', 1, '2021-08-07'),
(1, 10, 'Update', 1, '2021-08-07'),
(1, 11, 'Create', 1, '2021-08-07'),
(1, 11, 'Delete', 1, '2021-08-07'),
(1, 11, 'Read', 1, '2021-08-07'),
(1, 11, 'Update', 1, '2021-08-07'),
(1, 18, 'Create', 1, '2021-08-14'),
(1, 18, 'Delete', 1, '2021-08-14'),
(1, 18, 'Read', 1, '2021-08-14'),
(1, 18, 'Update', 1, '2021-08-14'),
(1, 19, 'Create', 1, '2021-08-14'),
(1, 19, 'Delete', 1, '2021-08-14'),
(1, 19, 'Read', 1, '2021-08-14'),
(1, 19, 'Update', 1, '2021-08-14'),
(1, 20, 'Create', 1, '2021-08-14'),
(1, 20, 'Delete', 1, '2021-08-14'),
(1, 20, 'Read', 1, '2021-08-14'),
(1, 20, 'Update', 1, '2021-08-14'),
(1, 21, 'Create', 1, '2021-08-14'),
(1, 21, 'Delete', 0, '2021-08-14'),
(1, 21, 'Read', 1, '2021-08-14'),
(1, 21, 'Update', 1, '2021-08-14'),
(1, 22, 'Create', 1, '2021-08-14'),
(1, 22, 'Delete', 1, '2021-08-14'),
(1, 22, 'Read', 1, '2021-08-14'),
(1, 22, 'Update', 1, '2021-08-14'),
(1, 23, 'Create', 0, '2021-08-15'),
(1, 23, 'Delete', 0, '2021-08-15'),
(1, 23, 'Read', 1, '2021-08-15'),
(1, 23, 'Update', 0, '2021-08-15'),
(1, 24, 'Create', 0, '2021-08-17'),
(1, 24, 'Delete', 0, '2021-08-17'),
(1, 24, 'Read', 1, '2021-08-17'),
(1, 24, 'Update', 0, '2021-08-17'),
(1, 26, 'Create', 1, '2021-10-24'),
(1, 26, 'Delete', 1, '2021-10-24'),
(1, 26, 'Read', 1, '2021-10-24'),
(1, 26, 'Update', 1, '2021-10-24'),
(1, 27, 'Create', 1, '2021-10-24'),
(1, 27, 'Delete', 1, '2021-10-24'),
(1, 27, 'Read', 1, '2021-10-24'),
(1, 27, 'Update', 1, '2021-10-24'),
(1, 28, 'Create', 0, '2021-10-24'),
(1, 28, 'Delete', 0, '2021-10-24'),
(1, 28, 'Read', 1, '2021-10-24'),
(1, 28, 'Update', 0, '2021-10-24'),
(1, 29, 'Create', 0, '2021-10-24'),
(1, 29, 'Delete', 0, '2021-10-24'),
(1, 29, 'Read', 1, '2021-10-24'),
(1, 29, 'Update', 0, '2021-10-24'),
(1, 30, 'Create', 1, '2021-11-02'),
(1, 30, 'Delete', 1, '2021-11-02'),
(1, 30, 'Read', 1, '2021-11-02'),
(1, 30, 'Update', 1, '2021-11-02'),
(1, 31, 'Create', 1, '2021-11-03'),
(1, 31, 'Delete', 1, '2021-11-03'),
(1, 31, 'Read', 1, '2021-11-03'),
(1, 31, 'Update', 1, '2021-11-03'),
(1, 32, 'Create', 1, '2021-11-17'),
(1, 32, 'Delete', 1, '2021-11-17'),
(1, 32, 'Read', 1, '2021-11-17'),
(1, 32, 'Update', 1, '2021-11-17'),
(1, 33, 'Create', 1, '2021-11-17'),
(1, 33, 'Delete', 1, '2021-11-17'),
(1, 33, 'Read', 1, '2021-11-17'),
(1, 33, 'Update', 1, '2021-11-17'),
(1, 36, 'Create', 1, '2021-11-17'),
(1, 36, 'Delete', 1, '2021-11-17'),
(1, 36, 'Read', 1, '2021-11-17'),
(1, 36, 'Update', 1, '2021-11-17'),
(1, 39, 'Create', 1, '2021-12-22'),
(1, 39, 'Delete', 0, '2021-12-22'),
(1, 39, 'Read', 1, '2021-12-22'),
(1, 39, 'Update', 1, '2021-12-22'),
(1, 40, 'Create', 1, '2021-12-26'),
(1, 40, 'Delete', 1, '2021-12-26'),
(1, 40, 'Read', 1, '2021-12-26'),
(1, 40, 'Update', 1, '2021-12-26'),
(1, 41, 'Create', 1, '2021-12-28'),
(1, 41, 'Delete', 1, '2021-12-28'),
(1, 41, 'Read', 1, '2021-12-28'),
(1, 41, 'Update', 1, '2021-12-28'),
(1, 42, 'Create', 0, '2022-01-04'),
(1, 42, 'Delete', 0, '2022-01-04'),
(1, 42, 'Read', 1, '2022-01-04'),
(1, 42, 'Update', 0, '2022-01-04'),
(1, 43, 'Create', 0, '2022-01-04'),
(1, 43, 'Delete', 0, '2022-01-04'),
(1, 43, 'Read', 1, '2022-01-04'),
(1, 43, 'Update', 0, '2022-01-04'),
(1, 44, 'Create', 0, '2022-01-04'),
(1, 44, 'Delete', 0, '2022-01-04'),
(1, 44, 'Read', 1, '2022-01-04'),
(1, 44, 'Update', 0, '2022-01-04'),
(1, 45, 'Create', 0, '2022-01-12'),
(1, 45, 'Delete', 0, '2022-01-12'),
(1, 45, 'Read', 1, '2022-01-12'),
(1, 45, 'Update', 0, '2022-01-12'),
(1, 46, 'Create', 0, '2022-01-20'),
(1, 46, 'Delete', 0, '2022-01-20'),
(1, 46, 'Read', 1, '2022-01-20'),
(1, 46, 'Update', 0, '2022-01-20'),
(2, 18, 'Create', 1, '2021-08-17'),
(2, 18, 'Delete', 1, '2021-08-17'),
(2, 18, 'Read', 1, '2021-08-17'),
(2, 18, 'Update', 1, '2021-08-17'),
(2, 23, 'Create', 0, '2021-08-17'),
(2, 23, 'Delete', 0, '2021-08-17'),
(2, 23, 'Read', 1, '2021-08-17'),
(2, 23, 'Update', 0, '2021-08-17'),
(2, 39, 'Create', 1, '2021-12-22'),
(2, 39, 'Delete', 0, '2021-12-22'),
(2, 39, 'Read', 1, '2021-12-22'),
(2, 39, 'Update', 1, '2021-12-22'),
(3, 21, 'Create', 1, '2021-08-17'),
(3, 21, 'Delete', 1, '2021-08-17'),
(3, 21, 'Read', 1, '2021-08-17'),
(3, 21, 'Update', 1, '2021-08-17'),
(3, 23, 'Create', 0, '2021-08-17'),
(3, 23, 'Delete', 0, '2021-08-17'),
(3, 23, 'Read', 1, '2021-08-17'),
(3, 23, 'Update', 0, '2021-08-17'),
(3, 24, 'Create', 0, '2021-08-17'),
(3, 24, 'Delete', 0, '2021-08-17'),
(3, 24, 'Read', 1, '2021-08-17'),
(3, 24, 'Update', 0, '2021-08-17'),
(4, 21, 'Create', 1, '2021-10-12'),
(4, 21, 'Delete', 1, '2021-10-12'),
(4, 21, 'Read', 1, '2021-10-12'),
(4, 21, 'Update', 1, '2021-10-12');

-- --------------------------------------------------------

--
-- Table structure for table `t_stock`
--

CREATE TABLE `t_stock` (
  `material` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deptid` int(11) NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_supplier`
--

CREATE TABLE `t_supplier` (
  `supplier_id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_supplier`
--

INSERT INTO `t_supplier` (`supplier_id`, `supplier_name`, `telephone`, `email`, `address`, `createdby`, `createdon`) VALUES
('2000000000', 'Test Supplier', '123456788', 'husnulmub@gmail.com', 'Test Address update yt', 'sys-admin', '2021-12-23');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `jabatan` int(11) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `approval` varchar(50) DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`username`, `password`, `nama`, `email`, `department`, `jabatan`, `section`, `approval`, `createdby`, `createdon`) VALUES
('sys-admin', '$2y$12$YCj4abvz4tMxEoYys4/9sul.FX.9lyhoQzRdl8rI8LWxg1rQb7l/W', 'Administrator', 'husnulmub@gmail.com', 1, NULL, NULL, NULL, NULL, '2021-08-07'),
('tes user', '$2y$12$L6Ze9EQ8jIB9BH8YWUtitOamtKnHTMfc1KdPkV05EepFOZVHaPBE6', 'test', 'husnulmub@gmail.com', 1, NULL, NULL, NULL, 'sys-admin', '2021-08-25'),
('user1', '$2y$12$SRZKODU0plLDEMZAaYI.fui6/KDGc6.E4Yqs94VJOlQM/4wFhhl0C', 'User Demo Purchasing 01', 'to3kangketik@gmail.com', 2, NULL, NULL, NULL, 'sys-admin', '2021-08-08'),
('user2', '$2y$12$4ciytjgZ4uP1lzk9YmMRN.5QYdOlTRnc4agF2XYNBAc8um1mBUHu6', 'User Demo Purchasing 02', 'admin@toekangketik.com', 2, NULL, NULL, NULL, 'sys-admin', '2021-08-17'),
('user3', '$2y$12$1zW1XHG6lAydJbxLxj9FbOcgiegdg6L/cf/5.8o0QkqOhtDl2EJFW', 'User QQA', NULL, 3, NULL, NULL, NULL, 'sys-admin', '2021-08-23'),
('user4', '$2y$12$sM3vJkkq5rZb6Lo8R6X5WeU2ZQ5DQ4OOtXFAq/cRlbrpgF4i6Ns36', 'User4', '', 2, NULL, NULL, NULL, 'sys-admin', '2021-10-12');

-- --------------------------------------------------------

--
-- Table structure for table `t_user_object_auth`
--

CREATE TABLE `t_user_object_auth` (
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ob_auth` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ob_value` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User Object Authorization';

-- --------------------------------------------------------

--
-- Table structure for table `t_user_role`
--

CREATE TABLE `t_user_role` (
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleid` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User Role';

--
-- Dumping data for table `t_user_role`
--

INSERT INTO `t_user_role` (`username`, `roleid`, `createdon`, `createdby`) VALUES
('sys-admin', 1, '2021-08-07 00:00:00', 'sys-admin'),
('user1', 2, '2021-08-17 00:00:00', 'sys-admin'),
('user2', 2, '2021-10-12 00:00:00', 'sys-admin'),
('user2', 3, '2021-08-17 00:00:00', 'sys-admin'),
('user3', 2, '2021-08-23 00:00:00', 'sys-admin'),
('user3', 4, '2021-10-12 00:00:00', 'sys-admin'),
('user4', 2, '2021-10-12 00:00:00', 'sys-admin'),
('user4', 3, '2021-10-12 00:00:00', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_warehouse`
--

CREATE TABLE `t_warehouse` (
  `warehouseid` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehousename` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Warehouse Masters';

--
-- Dumping data for table `t_warehouse`
--

INSERT INTO `t_warehouse` (`warehouseid`, `warehousename`, `createdon`, `createdby`) VALUES
('WH01', 'Production Warehouse', '2021-11-02', 'sys-admin'),
('WH02', 'Office Warehouse', '2021-11-02', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `t_whs_prtype`
--

CREATE TABLE `t_whs_prtype` (
  `prtype` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouseid` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_whs_prtype`
--

INSERT INTO `t_whs_prtype` (`prtype`, `warehouseid`) VALUES
('PR01', 'WH01'),
('PR02', 'WH02');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_grpo01`
-- (See below for the actual view)
--
CREATE TABLE `v_grpo01` (
`ponum` varchar(25)
,`poitem` int(11)
,`podat` date
,`vendor` varchar(10)
,`supplier_name` varchar(100)
,`note` text
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`grqty` decimal(15,3)
,`unit` varchar(10)
,`price` decimal(15,2)
,`gramount` decimal(30,5)
,`movement_number` varchar(10)
,`movement_year` int(11)
,`movement_item` int(11)
,`movement_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_material_price_history`
-- (See below for the actual view)
--
CREATE TABLE `v_material_price_history` (
`month` int(2)
,`year` int(4)
,`vendor` varchar(10)
,`material` varchar(70)
,`totalqty` decimal(37,3)
,`unit` varchar(10)
,`total_unitprice` decimal(37,2)
,`totalprice` decimal(15,2)
,`avg_price` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_po01`
-- (See below for the actual view)
--
CREATE TABLE `v_po01` (
`podat` date
,`month` int(2)
,`year` int(4)
,`vendor` varchar(10)
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`price` decimal(15,2)
,`totalprice` decimal(30,5)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_po02`
-- (See below for the actual view)
--
CREATE TABLE `v_po02` (
`ponum` varchar(25)
,`podat` date
,`month` int(2)
,`year` int(4)
,`vendor` varchar(10)
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`grqty` decimal(15,3)
,`openqty` decimal(16,3)
,`unit` varchar(10)
,`price` decimal(15,2)
,`totalprice` decimal(30,5)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pr004`
-- (See below for the actual view)
--
CREATE TABLE `v_pr004` (
`prnum` varchar(15)
,`pritem` int(11)
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(18,3)
,`unit` varchar(10)
,`price` decimal(15,2)
,`pocreated` varchar(1)
,`approvestat` varchar(10)
,`approveby` varchar(50)
,`remark` varchar(100)
,`createdon` datetime
,`createdby` varchar(50)
,`final_approve` varchar(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pr04`
-- (See below for the actual view)
--
CREATE TABLE `v_pr04` (
`prnum` varchar(15)
,`pritem` int(11)
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(18,3)
,`unit` varchar(10)
,`price` decimal(15,2)
,`pocreated` varchar(1)
,`approvestat` varchar(10)
,`approveby` varchar(50)
,`remark` varchar(100)
,`createdon` datetime
,`createdby` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_report_grpo`
-- (See below for the actual view)
--
CREATE TABLE `v_report_grpo` (
`movement_number` varchar(10)
,`movement_year` int(11)
,`movement_item` int(11)
,`movement_date` date
,`movement_type` varchar(5)
,`movement_note` varchar(100)
,`material` varchar(70)
,`matdesc` varchar(70)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`unit_price` decimal(15,2)
,`totalprice` decimal(15,2)
,`ponum` varchar(25)
,`poitem` int(11)
,`vendor` varchar(10)
,`supplier_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_report_transaction`
-- (See below for the actual view)
--
CREATE TABLE `v_report_transaction` (
`transactionid` varchar(20)
,`prod_date` date
,`partnumber` varchar(70)
,`partmodel` varchar(100)
,`serial_no` varchar(30)
,`createdon` date
,`process1` varchar(30)
,`process2` varchar(30)
,`process3` varchar(30)
,`process4` varchar(30)
,`process5` varchar(30)
,`process6` varchar(30)
,`process7` varchar(30)
,`process8` varchar(30)
,`process9` varchar(30)
,`lastprocess` int(11)
,`error_process` varchar(50)
,`defect_name` varchar(50)
,`location` varchar(50)
,`cause` varchar(50)
,`action` varchar(50)
,`repair1` varchar(30)
,`repair2` varchar(30)
,`repair3` varchar(30)
,`repair4` varchar(30)
,`repair5` varchar(30)
,`repair6` varchar(30)
,`remark` varchar(50)
,`repair_defect` varchar(50)
,`repair_location` varchar(50)
,`repair_action` varchar(50)
,`lastrepair` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user`
-- (See below for the actual view)
--
CREATE TABLE `v_user` (
`username` varchar(100)
,`password` varchar(255)
,`nama` varchar(50)
,`department` int(11)
,`createdby` varchar(50)
,`createdon` date
,`deptname` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_menu`
-- (See below for the actual view)
--
CREATE TABLE `v_user_menu` (
`username` varchar(100)
,`roleid` int(11)
,`rolename` varchar(50)
,`menuid` int(11)
,`id` int(11)
,`menu` varchar(50)
,`route` varchar(50)
,`type` varchar(20)
,`menugroup` int(11)
,`grouping` varchar(30)
,`icon` varchar(50)
,`createdon` datetime
,`createdby` varchar(50)
,`_sorting` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_menugroup`
-- (See below for the actual view)
--
CREATE TABLE `v_user_menugroup` (
`menugroup` int(11)
,`description` varchar(50)
,`icon` varchar(200)
,`createdon` timestamp
,`createdby` varchar(50)
,`username` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_role_avtivity`
-- (See below for the actual view)
--
CREATE TABLE `v_user_role_avtivity` (
`roleid` int(11)
,`menuid` int(11)
,`activity` varchar(10)
,`status` tinyint(1)
,`createdon` date
,`route` varchar(50)
,`menu` varchar(50)
,`username` varchar(100)
,`rolename` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `v_grpo01`
--
DROP TABLE IF EXISTS `v_grpo01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_grpo01`  AS  select `a`.`ponum` AS `ponum`,`b`.`poitem` AS `poitem`,`a`.`podat` AS `podat`,`a`.`vendor` AS `vendor`,`f`.`supplier_name` AS `supplier_name`,`a`.`note` AS `note`,`b`.`material` AS `material`,`b`.`matdesc` AS `matdesc`,`b`.`quantity` AS `quantity`,`c`.`quantity` AS `grqty`,`b`.`unit` AS `unit`,`b`.`price` AS `price`,`c`.`quantity` * `b`.`price` AS `gramount`,`c`.`movement_number` AS `movement_number`,`c`.`movement_year` AS `movement_year`,`c`.`movement_item` AS `movement_item`,`d`.`movement_date` AS `movement_date` from ((((`t_po01` `a` join `t_po02` `b` on(`a`.`ponum` = `b`.`ponum`)) join `t_movement_02` `c` on(`b`.`ponum` = `c`.`ponum` and `b`.`poitem` = `c`.`poitem`)) join `t_movement_01` `d` on(`c`.`movement_number` = `d`.`movement_number` and `c`.`movement_year` = `d`.`movement_year`)) join `t_supplier` `f` on(`a`.`vendor` = `f`.`supplier_id`)) order by `a`.`vendor`,`a`.`ponum`,`b`.`poitem` ;

-- --------------------------------------------------------

--
-- Structure for view `v_material_price_history`
--
DROP TABLE IF EXISTS `v_material_price_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_material_price_history`  AS  select `v_po01`.`month` AS `month`,`v_po01`.`year` AS `year`,`v_po01`.`vendor` AS `vendor`,`v_po01`.`material` AS `material`,sum(`v_po01`.`quantity`) AS `totalqty`,`v_po01`.`unit` AS `unit`,sum(`v_po01`.`price`) AS `total_unitprice`,cast(sum(`v_po01`.`quantity`) * sum(`v_po01`.`quantity`) as decimal(15,2)) AS `totalprice`,cast(sum(`v_po01`.`totalprice`) / sum(`v_po01`.`quantity`) as decimal(15,2)) AS `avg_price` from `v_po01` group by `v_po01`.`month`,`v_po01`.`year`,`v_po01`.`vendor`,`v_po01`.`material`,`v_po01`.`unit` order by `v_po01`.`month`,`v_po01`.`year`,`v_po01`.`vendor`,`v_po01`.`material` ;

-- --------------------------------------------------------

--
-- Structure for view `v_po01`
--
DROP TABLE IF EXISTS `v_po01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po01`  AS  select `a`.`podat` AS `podat`,month(`a`.`podat`) AS `month`,year(`a`.`podat`) AS `year`,`a`.`vendor` AS `vendor`,`b`.`material` AS `material`,`b`.`matdesc` AS `matdesc`,`b`.`quantity` AS `quantity`,`b`.`unit` AS `unit`,`b`.`price` AS `price`,`b`.`quantity` * `b`.`price` AS `totalprice` from (`t_po01` `a` join `t_po02` `b` on(`a`.`ponum` = `b`.`ponum`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_po02`
--
DROP TABLE IF EXISTS `v_po02`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po02`  AS  select `a`.`ponum` AS `ponum`,`a`.`podat` AS `podat`,month(`a`.`podat`) AS `month`,year(`a`.`podat`) AS `year`,`a`.`vendor` AS `vendor`,`b`.`material` AS `material`,`b`.`matdesc` AS `matdesc`,`b`.`quantity` AS `quantity`,`b`.`grqty` AS `grqty`,`b`.`quantity` - `b`.`grqty` AS `openqty`,`b`.`unit` AS `unit`,`b`.`price` AS `price`,`b`.`quantity` * `b`.`price` AS `totalprice` from (`t_po01` `a` join `t_po02` `b` on(`a`.`ponum` = `b`.`ponum`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_pr004`
--
DROP TABLE IF EXISTS `v_pr004`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pr004`  AS  select `a`.`prnum` AS `prnum`,`a`.`pritem` AS `pritem`,`a`.`material` AS `material`,`a`.`matdesc` AS `matdesc`,`a`.`quantity` AS `quantity`,`a`.`unit` AS `unit`,`a`.`price` AS `price`,`a`.`pocreated` AS `pocreated`,`a`.`approvestat` AS `approvestat`,`a`.`approveby` AS `approveby`,`a`.`remark` AS `remark`,`a`.`createdon` AS `createdon`,`a`.`createdby` AS `createdby`,`a`.`final_approve` AS `final_approve` from (`t_pr02` `a` left join `t_material` `b` on(`a`.`material` = `b`.`material`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_pr04`
--
DROP TABLE IF EXISTS `v_pr04`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pr04`  AS  select `a`.`prnum` AS `prnum`,`a`.`pritem` AS `pritem`,`a`.`material` AS `material`,`a`.`matdesc` AS `matdesc`,`a`.`quantity` AS `quantity`,`a`.`unit` AS `unit`,`a`.`price` AS `price`,`a`.`pocreated` AS `pocreated`,`a`.`approvestat` AS `approvestat`,`a`.`approveby` AS `approveby`,`a`.`remark` AS `remark`,`a`.`createdon` AS `createdon`,`a`.`createdby` AS `createdby` from (`t_pr02` `a` left join `t_material` `b` on(`a`.`material` = `b`.`material`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_report_grpo`
--
DROP TABLE IF EXISTS `v_report_grpo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_report_grpo`  AS  select `a`.`movement_number` AS `movement_number`,`a`.`movement_year` AS `movement_year`,`b`.`movement_item` AS `movement_item`,`a`.`movement_date` AS `movement_date`,`a`.`movement_type` AS `movement_type`,`a`.`movement_note` AS `movement_note`,`b`.`material` AS `material`,`b`.`matdesc` AS `matdesc`,`b`.`quantity` AS `quantity`,`b`.`unit` AS `unit`,`f`.`price` AS `unit_price`,cast(`b`.`quantity` * `f`.`price` as decimal(15,2)) AS `totalprice`,`b`.`ponum` AS `ponum`,`b`.`poitem` AS `poitem`,`c`.`vendor` AS `vendor`,`d`.`supplier_name` AS `supplier_name` from ((((`t_movement_01` `a` join `t_movement_02` `b` on(`a`.`movement_number` = `b`.`movement_number` and `a`.`movement_year` = `b`.`movement_year`)) join `t_po01` `c` on(`c`.`ponum` = `b`.`ponum`)) join `t_supplier` `d` on(`c`.`vendor` = `d`.`supplier_id`)) join `t_po02` `f` on(`b`.`ponum` = `f`.`ponum` and `b`.`poitem` = `f`.`poitem`)) where `a`.`movement_type` = '101' order by `a`.`movement_number`,`a`.`movement_year`,`b`.`movement_item` ;

-- --------------------------------------------------------

--
-- Structure for view `v_report_transaction`
--
DROP TABLE IF EXISTS `v_report_transaction`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_report_transaction`  AS  select `a`.`transactionid` AS `transactionid`,`a`.`prod_date` AS `prod_date`,`a`.`partnumber` AS `partnumber`,`a`.`partmodel` AS `partmodel`,`a`.`serial_no` AS `serial_no`,`a`.`createdon` AS `createdon`,`b`.`process1` AS `process1`,`b`.`process2` AS `process2`,`b`.`process3` AS `process3`,`b`.`process4` AS `process4`,`b`.`process5` AS `process5`,`b`.`process6` AS `process6`,`b`.`process7` AS `process7`,`b`.`process8` AS `process8`,`b`.`process9` AS `process9`,`b`.`lastprocess` AS `lastprocess`,`b`.`error_process` AS `error_process`,`b`.`defect_name` AS `defect_name`,`b`.`location` AS `location`,`b`.`cause` AS `cause`,`b`.`action` AS `action`,`c`.`process1` AS `repair1`,`c`.`process2` AS `repair2`,`c`.`process3` AS `repair3`,`c`.`process4` AS `repair4`,`c`.`process5` AS `repair5`,`c`.`process6` AS `repair6`,`c`.`remark` AS `remark`,`c`.`defect_name` AS `repair_defect`,`c`.`location` AS `repair_location`,`c`.`action` AS `repair_action`,`c`.`lastrepair` AS `lastrepair` from ((`t_ipd_forms` `a` left join `t_ipd_process` `b` on(`a`.`transactionid` = `b`.`transactionid`)) left join `t_ipd_repair` `c` on(`a`.`transactionid` = `c`.`transactionid`)) order by `a`.`transactionid`,`a`.`serial_no`,`a`.`partnumber` ;

-- --------------------------------------------------------

--
-- Structure for view `v_user`
--
DROP TABLE IF EXISTS `v_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user`  AS  select `a`.`username` AS `username`,`a`.`password` AS `password`,`a`.`nama` AS `nama`,`a`.`department` AS `department`,`a`.`createdby` AS `createdby`,`a`.`createdon` AS `createdon`,`b`.`department` AS `deptname` from (`t_user` `a` left join `t_department` `b` on(`a`.`department` = `b`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_menu`
--
DROP TABLE IF EXISTS `v_user_menu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_menu`  AS  select `a`.`username` AS `username`,`b`.`roleid` AS `roleid`,`f`.`rolename` AS `rolename`,`c`.`menuid` AS `menuid`,`d`.`id` AS `id`,`d`.`menu` AS `menu`,`d`.`route` AS `route`,`d`.`type` AS `type`,`d`.`menugroup` AS `menugroup`,`d`.`grouping` AS `grouping`,`d`.`icon` AS `icon`,`d`.`createdon` AS `createdon`,`d`.`createdby` AS `createdby`,`d`.`_sorting` AS `_sorting` from ((((`t_user` `a` join `t_user_role` `b` on(`a`.`username` = `b`.`username`)) join `t_rolemenu` `c` on(`c`.`roleid` = `b`.`roleid`)) join `t_menus` `d` on(`d`.`id` = `c`.`menuid`)) join `t_role` `f` on(`f`.`roleid` = `b`.`roleid`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_menugroup`
--
DROP TABLE IF EXISTS `v_user_menugroup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_menugroup`  AS  select `a`.`menugroup` AS `menugroup`,`a`.`description` AS `description`,`a`.`icon` AS `icon`,`a`.`createdon` AS `createdon`,`a`.`createdby` AS `createdby`,`b`.`username` AS `username` from (`t_menugroups` `a` join `v_user_menu` `b` on(`a`.`menugroup` = `b`.`menugroup`)) order by `a`.`menugroup` ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_role_avtivity`
--
DROP TABLE IF EXISTS `v_user_role_avtivity`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_role_avtivity`  AS  select `a`.`roleid` AS `roleid`,`a`.`menuid` AS `menuid`,`a`.`activity` AS `activity`,`a`.`status` AS `status`,`a`.`createdon` AS `createdon`,`b`.`route` AS `route`,`b`.`menu` AS `menu`,`c`.`username` AS `username`,`d`.`rolename` AS `rolename` from (((`t_role_avtivity` `a` join `t_menus` `b` on(`a`.`menuid` = `b`.`id`)) join `t_user_role` `c` on(`a`.`roleid` = `c`.`roleid`)) join `t_role` `d` on(`a`.`roleid` = `d`.`roleid`)) order by `c`.`username`,`d`.`rolename` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblsetting`
--
ALTER TABLE `tblsetting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_actionlist`
--
ALTER TABLE `t_actionlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_approval`
--
ALTER TABLE `t_approval`
  ADD PRIMARY KEY (`object`,`doctype`,`level`,`creator`,`approval`);

--
-- Indexes for table `t_auth_object`
--
ALTER TABLE `t_auth_object`
  ADD PRIMARY KEY (`ob_auth`);

--
-- Indexes for table `t_budget`
--
ALTER TABLE `t_budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_budget_history`
--
ALTER TABLE `t_budget_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_budget_summary`
--
ALTER TABLE `t_budget_summary`
  ADD PRIMARY KEY (`department`,`budget_period`);

--
-- Indexes for table `t_causelist`
--
ALTER TABLE `t_causelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_currency`
--
ALTER TABLE `t_currency`
  ADD PRIMARY KEY (`currency`);

--
-- Indexes for table `t_defectlist`
--
ALTER TABLE `t_defectlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_department`
--
ALTER TABLE `t_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_email_config`
--
ALTER TABLE `t_email_config`
  ADD PRIMARY KEY (`driver`,`host`,`port`,`username`,`sender_name`,`sender_email`);

--
-- Indexes for table `t_inventory`
--
ALTER TABLE `t_inventory`
  ADD PRIMARY KEY (`ivnum`,`ivyear`,`ivitem`);

--
-- Indexes for table `t_inventory_stock`
--
ALTER TABLE `t_inventory_stock`
  ADD PRIMARY KEY (`material`,`warehouseid`);

--
-- Indexes for table `t_ipd_forms`
--
ALTER TABLE `t_ipd_forms`
  ADD PRIMARY KEY (`transactionid`);

--
-- Indexes for table `t_ipd_process`
--
ALTER TABLE `t_ipd_process`
  ADD PRIMARY KEY (`transactionid`);

--
-- Indexes for table `t_ipd_repair`
--
ALTER TABLE `t_ipd_repair`
  ADD PRIMARY KEY (`transactionid`);

--
-- Indexes for table `t_kurs`
--
ALTER TABLE `t_kurs`
  ADD PRIMARY KEY (`currency1`);

--
-- Indexes for table `t_locationlist`
--
ALTER TABLE `t_locationlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_material`
--
ALTER TABLE `t_material`
  ADD PRIMARY KEY (`material`);

--
-- Indexes for table `t_material2`
--
ALTER TABLE `t_material2`
  ADD PRIMARY KEY (`material`,`altuom`);

--
-- Indexes for table `t_menugroups`
--
ALTER TABLE `t_menugroups`
  ADD PRIMARY KEY (`menugroup`);

--
-- Indexes for table `t_menus`
--
ALTER TABLE `t_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_movement_01`
--
ALTER TABLE `t_movement_01`
  ADD PRIMARY KEY (`movement_number`,`movement_year`);

--
-- Indexes for table `t_movement_02`
--
ALTER TABLE `t_movement_02`
  ADD PRIMARY KEY (`movement_number`,`movement_year`,`movement_item`);

--
-- Indexes for table `t_nriv`
--
ALTER TABLE `t_nriv`
  ADD PRIMARY KEY (`object`);

--
-- Indexes for table `t_po01`
--
ALTER TABLE `t_po01`
  ADD PRIMARY KEY (`ponum`),
  ADD KEY `podat` (`podat`,`vendor`);

--
-- Indexes for table `t_po02`
--
ALTER TABLE `t_po02`
  ADD PRIMARY KEY (`ponum`,`poitem`),
  ADD KEY `material` (`material`,`requestnum`,`request_item`);

--
-- Indexes for table `t_po03`
--
ALTER TABLE `t_po03`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_po04`
--
ALTER TABLE `t_po04`
  ADD PRIMARY KEY (`ponum`,`poitem`);

--
-- Indexes for table `t_po05`
--
ALTER TABLE `t_po05`
  ADD PRIMARY KEY (`ponum`,`approve_level`);

--
-- Indexes for table `t_pr01`
--
ALTER TABLE `t_pr01`
  ADD PRIMARY KEY (`prnum`),
  ADD KEY `prnum` (`prnum`),
  ADD KEY `typepr` (`prtype`),
  ADD KEY `prdate` (`prdate`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `t_pr02`
--
ALTER TABLE `t_pr02`
  ADD PRIMARY KEY (`prnum`,`pritem`),
  ADD KEY `material` (`material`),
  ADD KEY `prnum` (`prnum`);

--
-- Indexes for table `t_process_sequence`
--
ALTER TABLE `t_process_sequence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_prtype`
--
ALTER TABLE `t_prtype`
  ADD PRIMARY KEY (`prtype`);

--
-- Indexes for table `t_quotation01`
--
ALTER TABLE `t_quotation01`
  ADD PRIMARY KEY (`quotation`);

--
-- Indexes for table `t_request_slip01`
--
ALTER TABLE `t_request_slip01`
  ADD PRIMARY KEY (`requestnum`);

--
-- Indexes for table `t_request_slip02`
--
ALTER TABLE `t_request_slip02`
  ADD PRIMARY KEY (`requestnum`,`request_item`);

--
-- Indexes for table `t_request_slip03`
--
ALTER TABLE `t_request_slip03`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_request_slip04`
--
ALTER TABLE `t_request_slip04`
  ADD PRIMARY KEY (`requestnum`,`approve_level`);

--
-- Indexes for table `t_role`
--
ALTER TABLE `t_role`
  ADD PRIMARY KEY (`roleid`),
  ADD KEY `idxrolename` (`rolename`),
  ADD KEY `roleid` (`roleid`);

--
-- Indexes for table `t_rolemenu`
--
ALTER TABLE `t_rolemenu`
  ADD PRIMARY KEY (`roleid`,`menuid`),
  ADD KEY `roleid` (`roleid`),
  ADD KEY `menuid` (`menuid`);

--
-- Indexes for table `t_role_avtivity`
--
ALTER TABLE `t_role_avtivity`
  ADD PRIMARY KEY (`roleid`,`menuid`,`activity`);

--
-- Indexes for table `t_stock`
--
ALTER TABLE `t_stock`
  ADD PRIMARY KEY (`material`,`deptid`);

--
-- Indexes for table `t_supplier`
--
ALTER TABLE `t_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`username`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `t_user_object_auth`
--
ALTER TABLE `t_user_object_auth`
  ADD PRIMARY KEY (`username`,`ob_auth`,`ob_value`);

--
-- Indexes for table `t_user_role`
--
ALTER TABLE `t_user_role`
  ADD PRIMARY KEY (`username`,`roleid`),
  ADD KEY `roleid` (`roleid`);

--
-- Indexes for table `t_warehouse`
--
ALTER TABLE `t_warehouse`
  ADD PRIMARY KEY (`warehouseid`);

--
-- Indexes for table `t_whs_prtype`
--
ALTER TABLE `t_whs_prtype`
  ADD PRIMARY KEY (`prtype`,`warehouseid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_actionlist`
--
ALTER TABLE `t_actionlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_budget`
--
ALTER TABLE `t_budget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `t_budget_history`
--
ALTER TABLE `t_budget_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `t_causelist`
--
ALTER TABLE `t_causelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_defectlist`
--
ALTER TABLE `t_defectlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_department`
--
ALTER TABLE `t_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_locationlist`
--
ALTER TABLE `t_locationlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_menugroups`
--
ALTER TABLE `t_menugroups`
  MODIFY `menugroup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_menus`
--
ALTER TABLE `t_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `t_po03`
--
ALTER TABLE `t_po03`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `t_process_sequence`
--
ALTER TABLE `t_process_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `t_request_slip03`
--
ALTER TABLE `t_request_slip03`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `t_role`
--
ALTER TABLE `t_role`
  MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_rolemenu`
--
ALTER TABLE `t_rolemenu`
  ADD CONSTRAINT `t_rolemenu_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `t_role` (`roleid`),
  ADD CONSTRAINT `t_rolemenu_ibfk_2` FOREIGN KEY (`menuid`) REFERENCES `t_menus` (`id`);

--
-- Constraints for table `t_user_role`
--
ALTER TABLE `t_user_role`
  ADD CONSTRAINT `t_user_role_ibfk_1` FOREIGN KEY (`username`) REFERENCES `t_user` (`username`),
  ADD CONSTRAINT `t_user_role_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `t_role` (`roleid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
