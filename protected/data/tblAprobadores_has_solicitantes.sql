-- --------------------------------------------------------
-- Host:                         192.168.15.21
-- Versión del servidor:         5.5.34-0ubuntu0.13.04.1 - (Ubuntu)
-- SO del servidor:              debian-linux-gnu
-- HeidiSQL Versión:             8.0.0.4396
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Volcando datos para la tabla workflownotas.tblAprobadores_has_solicitantes: 8 rows
/*!40000 ALTER TABLE `tblAprobadores_has_solicitantes` DISABLE KEYS */;
INSERT INTO `tblAprobadores_has_solicitantes` (`id`, `aprobador`, `solicitante`) VALUES
	(1, 14, 10),
	(2, 14, 11),
	(3, 14, 12),
	(4, 14, 9),
	(5, 19, 3),
	(6, 19, 4),
	(7, 19, 5),
	(8, 19, 6);
/*!40000 ALTER TABLE `tblAprobadores_has_solicitantes` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
