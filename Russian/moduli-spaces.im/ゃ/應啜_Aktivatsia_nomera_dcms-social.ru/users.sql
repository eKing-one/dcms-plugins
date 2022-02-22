ALTER TABLE `user` ADD `phone_activ` enum('0','1') NOT NULL default '1';
ALTER TABLE `user` ADD `phone` varchar(10) default NULL;
ALTER TABLE `user` ADD `code` varchar(6) default NULL;
ALTER TABLE `user` DROP `ank_n_tel`;