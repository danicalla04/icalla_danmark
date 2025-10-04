-- Add password column to existing simplecrud_tb table for authentication
USE simplecrud;

ALTER TABLE `simplecrud_tb` 
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `number`;
