SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `Calendar_WM` (
  `file_name` text NOT NULL,
  `ics` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_WM`
  ADD PRIMARY KEY (`file_name`(200));

CREATE TABLE `Calendar_WM_a` (
  `file_name` text NOT NULL,
  `ics` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_WM_a`
  ADD PRIMARY KEY (`file_name`(200));

CREATE TABLE `Calendar_WM_b` (
  `file_name` text NOT NULL,
  `ics` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_WM_b`
  ADD PRIMARY KEY (`file_name`(200));

CREATE TABLE `Calendar_SVC` (
  `file_name` text NOT NULL,
  `ics` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `Calendar_SVC`
  ADD PRIMARY KEY (`file_name`(200));  

COMMIT;