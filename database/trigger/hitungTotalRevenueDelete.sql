DELIMITER $$

USE `revenue`$$

DROP TRIGGER /*!50032 IF EXISTS */ `hitungTotalRevenueDelete`$$

CREATE
    /*!50017 DEFINER = 'root'@'localhost' */
    TRIGGER `hitungTotalRevenueDelete` AFTER DELETE ON `trrevenuetransaksidetail` 
    FOR EACH ROW BEGIN
	DECLARE totalRevenueTrRevenueTransaksi DECIMAL(14,2);
	DECLARE idTrRevenueTransaksi BIGINT;
	DECLARE totalRevenueCurrent DECIMAL(14,2);
	/*
	IF (old.idKategori = '49' OR  old.idKategori = '50' OR  old.idKategori = '51' OR  old.idKategori = '52' OR  old.idKategori = '53' OR old.idKategori = '57') THEN
		SET idTrRevenueTransaksi = old.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi - old.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	IF (old.idKategori = '54') THEN
		SET idTrRevenueTransaksi = old.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi + old.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	*/
	IF (old.idKategori = '58') THEN
		SET idTrRevenueTransaksi = old.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi - old.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	
    END;
$$

DELIMITER ;