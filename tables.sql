

DROP TABLE IF EXISTS ensembles;
CREATE TABLE `ensembles` (
  `e_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `e_code` varchar(50) NOT NULL UNIQUE,
  `e_name` varchar(255) NOT NULL
);

DROP TABLE IF EXISTS presets;
CREATE TABLE `presets` (
  `p_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `p_created` date NOT NULL,
  `p_modified` date,
  `p_desc` varchar(255),
  `p_ensemble` varchar(50) NOT NULL,
  `p_width` int(11),
  `p_height` int(11),
  `p_seconds` int(11),
  `p_slide_dur` int(11),
  `p_init_pause` int(11),
  `p_foreground` varchar(50),
  `p_background`varchar(50),
  FOREIGN KEY (`p_ensemble`) REFERENCES ensembles(e_code)
);


DROP TABLE IF EXISTS conductors;
CREATE TABLE `conductors` (
  `u_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_login` varchar(50) NOT NULL UNIQUE,
  `u_name` varchar(255),
  `u_password` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL UNIQUE,
  `u_ensemble` varchar(50) NOT NULL,
  `temp_password` varchar(255),
  FOREIGN KEY (`u_ensemble`) REFERENCES ensembles(e_code)
);
