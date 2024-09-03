SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `Calendar_SV` (`file_name` text NOT NULL, `ics` longblob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_SV` ADD PRIMARY KEY (`file_name`(200));  

CREATE TABLE `Calendar_W1` (`file_name` text NOT NULL, `ics` longblob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_W1` ADD PRIMARY KEY (`file_name`(200));

CREATE TABLE `Calendar_W2` (`file_name` text NOT NULL, `ics` longblob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_W2` ADD PRIMARY KEY (`file_name`(200));

CREATE TABLE `Calendar_W3` (`file_name` text NOT NULL, `ics` longblob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_W3` ADD PRIMARY KEY (`file_name`(200));

COMMIT;