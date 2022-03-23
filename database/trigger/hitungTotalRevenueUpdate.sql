DELIMITER $$

USE `revenue`$$

DROP TRIGGER /*!50032 IF EXISTS */ `hitungTotalRevenueUpdate`$$

CREATE
    /*!50017 DEFINER = 'root'@'localhost' */
    TRIGGER `hitungTotalRevenueUpdate` AFTER UPDATE ON `trrevenuetransaksidetail` 
    FOR EACH ROW BEGIN
	    
	DECLARE totalRevenueOld DECIMAL(14,2);
	DECLARE totalRevenueNew DECIMAL(14,2);
	DECLARE totalRevenueTrRevenueTransaksi DECIMAL(14,2);
	DECLARE totalRevenueCurrent DECIMAL(14,2);
	DECLARE idTrRevenueTransaksi BIGINT;
	/*
	IF (old.idKategori = '49' OR  old.idKategori = '50' OR  old.idKategori = '51' OR  old.idKategori = '52' OR  old.idKategori = '53' OR  old.idKategori = '57') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueOld = old.totalRevenue;
		SET totalRevenueNew = new.totalRevenue;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi - totalRevenueOld;
		SET totalRevenueCurrent = totalRevenueCurrent + totalRevenueNew;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	IF (old.idKategori = '54') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueOld = old.totalRevenue;
		SET totalRevenueNew = new.totalRevenue;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi + totalRevenueOld;
		SET totalRevenueCurrent = totalRevenueCurrent - totalRevenueNew;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	*/
	IF (old.idKategori = '58') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueOld = old.totalRevenue;
		SET totalRevenueNew = new.totalRevenue;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi - totalRevenueOld;
		SET totalRevenueCurrent = totalRevenueCurrent + totalRevenueNew;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
    END;
$$

DELIMITER ;