-- Add authentication columns to existing simplecrud_tb table
ALTER TABLE `simplecrud_tb` 
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `number`,
ADD COLUMN `email_token` VARCHAR(32) NULL AFTER `password`,
ADD COLUMN `email_verified_at` DATETIME NULL AFTER `email_token`;
