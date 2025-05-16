-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: auth_db
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test@example.com','$2y$10$1KMNMHAv0o7V4yJhPAkbFO7lVGub7lT8MgqP3lN4tnN2EMhmB5J8O',NULL,NULL,'2025-05-14 14:29:17','2025-05-14 14:29:17'),(2,'ahlflorew@gmail.com','$2y$10$.N/P5nrehJ/P982J00Fo/eqvBauoo9tOCcOrZHhlUC7ejOkbYQsY2',NULL,NULL,'2025-05-14 17:17:02','2025-05-14 17:17:02'),(3,'ahlflorew1@gmail.com','$2y$10$RuCIUl7Wu1e5WWgU6KWzL.CFsZUTGexjrXWL4RUFEB5YRoLwWWLUO',NULL,NULL,'2025-05-14 17:38:07','2025-05-14 17:38:07'),(4,'ahlflorew12@gmail.com','$2y$10$Ts8N1o85gHojBvYEEgZ2BenPi.J4hkmNYI.mFGOLWw.rtqA9V3cAu',NULL,NULL,'2025-05-14 22:40:02','2025-05-14 22:40:02'),(5,'AHOLOU','$2y$10$/qERHz.viYkiaqCE1g2TvuxIlmjJtJIMs9zVA4.WI1muB2Bxb00H.','Florette','ahlflorew3@gmail.com','2025-05-15 06:24:19','2025-05-15 06:24:19'),(8,'ahlflorew3@gmail.com','$2y$10$2e/vJ7Y0KcBNlKoBV//nceaEXPqPu64cSFHnSas2EhBgKNBUUSyQ.','Flo','ahlflorew4@gmail.com','2025-05-15 06:30:31','2025-05-15 06:30:31'),(9,'ahlflorew5@gmail.com','$2y$10$UdzcZOnB3ur5evseCOwm4e5112nAFeijAeYVzDJJUb05pGWU5nWCa','KAKAHOUN','Wallys','2025-05-15 07:18:31','2025-05-15 08:25:54'),(10,'ahljohn1@gmail.com','$2y$10$n3JSLWAhD42DxdAoTeDhBejDvtFv4edQvNZKw4LKvyUmlth3vALAO','AHOLOU','Cliff','2025-05-15 08:38:05','2025-05-15 08:42:29'),(11,'ahljohn2@gmail.com','$2y$10$HzE7iilAEWCDRxA/FCO0zeyk8TlsF574TvrEnkKYcDPJCSreP6sVC','AHOLOU','Wicliffe','2025-05-15 09:08:57','2025-05-15 09:17:51'),(12,'alice@example.com','$2y$10$lgp1dFq8XszXC.bPS4HLguaQ69VSmqeldVVkKOQ1hquyJHOVBJTtG','Alice','Dupont','2025-05-15 10:56:18','2025-05-15 10:56:18'),(13,'ahljohn3@gmail.com','$2y$10$IvsxF/m4ZwbaoCLFpNerjuc.UpL2ERNSPgfctey7p9wFNdnsT4mTG','AHOLOU','Wicliffe','2025-05-15 13:46:07','2025-05-15 13:49:36'),(14,'ahljohn4@gmail.com','$2y$10$DiukAMml4SkGGZAXBRMxYOw/YwLMfXyjRAGXmWvNpgj9ayoJQJLNy','AHOLOU','Florette','2025-05-15 21:38:57','2025-05-15 21:43:18'),(15,'ahljohn6@gmail.com','$2y$10$WAScTVvkM3TDcqZdvU/zt.v0gLHk.jVQoXcYczCfMqhyiByisVdnS','AHL','Test','2025-05-15 21:46:46','2025-05-16 07:14:46'),(16,'kkhstan@gmail.com','$2y$10$VxGrlG7g9xrVeaBzujit4uIAv4yVtkSMc2M/pKJ8X1SswXWAyyrCm','KAKAHOUN','Wallys Stanic','2025-05-16 16:27:13','2025-05-16 16:29:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-16 18:19:05
