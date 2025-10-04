-- Create users table based on the database image
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `number` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data based on the image
INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`) VALUES
(1, 'John Doe', 'john.doe@example.com', '09171234567', ''),
(2, 'Jane Smith', 'jane.smith@example.com', '09182345678', ''),
(3, 'Michael Johnson', 'michael.johnson@example.com', '09193456789', ''),
(4, 'Emily Davis', 'emily.davis@example.com', '09204567890', ''),
(5, 'Daniel Garcia', 'daniel.garcia@example.com', '09215678901', ''),
(6, 'dan', 'icalladan@gmail.com', '09362144338', ''),
(8, 'John Doe', 'johndoe@example.com', '09123456789', '');
