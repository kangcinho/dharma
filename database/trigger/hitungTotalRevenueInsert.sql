DELIMITER $$

USE `revenue`$$

DROP TRIGGER /*!50032 IF EXISTS */ `hitungTotalRevenueInsert`$$

CREATE
    /*!50017 DEFINER = 'root'@'localhost' */
  TRIGGER `hitungTotalRevenueInsert` AFTER INSERT ON `trrevenuetransaksidetail` 
    FOR EACH ROW BEGIN
	DECLARE totalRevenueTrRevenueTransaksi DECIMAL(14,2);
	DECLARE idTrRevenueTransaksi BIGINT;
	DECLARE totalRevenueCurrent DECIMAL(14,2);
	/*
	IF (new.idKategori = '49' OR  new.idKategori = '50' OR  new.idKategori = '51' OR  new.idKategori = '52' OR  new.idKategori = '53' OR  new.idKategori = '57') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi + new.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;	
	IF (new.idKategori = '54') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi - new.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
	*/
	IF(new.idKategori = '58') THEN
		SET idTrRevenueTransaksi = new.idTrRevenueTransaksi;
		SET totalRevenueTrRevenueTransaksi = (SELECT totalRevenue FROM trrevenuetransaksi WHERE id = idTrRevenueTransaksi);
		SET totalRevenueCurrent = totalRevenueTrRevenueTransaksi + new.totalRevenue;
		UPDATE trrevenuetransaksi SET totalRevenue = totalRevenueCurrent WHERE id = idTrRevenueTransaksi;
	END IF;
    END;
$$

DELIMITER ;