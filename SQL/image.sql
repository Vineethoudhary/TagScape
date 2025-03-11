SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone ="+00:00";

CREATE TABLE 'images'(
    'id' int(11) NOT NULL,
    'image' longblob NOT NULL,
    'created' datetime NOT NULL DEFAULT current_timestamp()
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;