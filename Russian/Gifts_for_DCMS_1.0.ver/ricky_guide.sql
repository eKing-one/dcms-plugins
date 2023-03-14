-- Created by http://keo.su 
-- Database: `db1464622136`


-- --------------------------------------------------------
-- 
-- Table structure for table `gift_categories`
-- 

CREATE TABLE `gift_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------
-- 
-- Dumping data for table `gift_categories`
-- 

INSERT INTO `gift_categories` VALUES ('1','LoveTap.Ru');
INSERT INTO `gift_categories` VALUES ('21','汽车和摩托');
INSERT INTO `gift_categories` VALUES ('24','家里');
INSERT INTO `gift_categories` VALUES ('25','食物');
INSERT INTO `gift_categories` VALUES ('26','野兽');
INSERT INTO `gift_categories` VALUES ('28','亲密');
INSERT INTO `gift_categories` VALUES ('29','卡斯梅季卡');
INSERT INTO `gift_categories` VALUES ('30','爱');
INSERT INTO `gift_categories` VALUES ('31','饮料');
INSERT INTO `gift_categories` VALUES ('32','带微笑的礼物');
INSERT INTO `gift_categories` VALUES ('33','节日的');
INSERT INTO `gift_categories` VALUES ('34','杂项');
INSERT INTO `gift_categories` VALUES ('35','生日快乐');
INSERT INTO `gift_categories` VALUES ('36','技术');
INSERT INTO `gift_categories` VALUES ('37','装饰');
INSERT INTO `gift_categories` VALUES ('38','旗帜');
INSERT INTO `gift_categories` VALUES ('39','花朵');

-- --------------------------------------------------------
-- 
-- Table structure for table `gift_list`
-- 

CREATE TABLE `gift_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `money` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1106 DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------
-- 
-- Dumping data for table `gift_list`
-- 

INSERT INTO `gift_list` VALUES ('3','1','LoveTap.Ru','50000');
INSERT INTO `gift_list` VALUES ('998','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('997','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('996','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('995','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('994','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('993','35','生日快乐','150');
INSERT INTO `gift_list` VALUES ('991','36','技术','100');
INSERT INTO `gift_list` VALUES ('990','31','饮料','100');
INSERT INTO `gift_list` VALUES ('989','31','饮料','100');
INSERT INTO `gift_list` VALUES ('988','31','饮料','100');
INSERT INTO `gift_list` VALUES ('987','31','饮料','100');
INSERT INTO `gift_list` VALUES ('986','31','饮料','100');
INSERT INTO `gift_list` VALUES ('985','31','饮料','100');
INSERT INTO `gift_list` VALUES ('984','31','饮料','100');
INSERT INTO `gift_list` VALUES ('983','29','卡斯梅季卡','150');
INSERT INTO `gift_list` VALUES ('982','28','亲密','100');
INSERT INTO `gift_list` VALUES ('981','28','亲密','100');
INSERT INTO `gift_list` VALUES ('980','39','花朵','100');
INSERT INTO `gift_list` VALUES ('979','39','花朵','100');
INSERT INTO `gift_list` VALUES ('978','39','花朵','100');
INSERT INTO `gift_list` VALUES ('977','39','花朵','100');
INSERT INTO `gift_list` VALUES ('976','39','花朵','100');
INSERT INTO `gift_list` VALUES ('975','39','花朵','100');
INSERT INTO `gift_list` VALUES ('974','39','花朵','100');
INSERT INTO `gift_list` VALUES ('973','39','花朵','100');
INSERT INTO `gift_list` VALUES ('972','39','花朵','100');
INSERT INTO `gift_list` VALUES ('971','39','花朵','100');
INSERT INTO `gift_list` VALUES ('970','39','花朵','1000');
INSERT INTO `gift_list` VALUES ('969','39','花朵','100');
INSERT INTO `gift_list` VALUES ('968','39','花朵','100');
INSERT INTO `gift_list` VALUES ('967','39','花朵','100');
INSERT INTO `gift_list` VALUES ('966','39','花朵','100');
INSERT INTO `gift_list` VALUES ('965','39','花朵','100');
INSERT INTO `gift_list` VALUES ('964','39','花朵','100');
INSERT INTO `gift_list` VALUES ('963','39','花朵','100');
INSERT INTO `gift_list` VALUES ('962','39','花朵','100');
INSERT INTO `gift_list` VALUES ('961','39','花朵','100');
INSERT INTO `gift_list` VALUES ('960','39','花朵','100');
INSERT INTO `gift_list` VALUES ('959','39','花朵','100');
INSERT INTO `gift_list` VALUES ('958','39','花朵','100');
INSERT INTO `gift_list` VALUES ('957','25','食物','100');
INSERT INTO `gift_list` VALUES ('956','25','食物','100');
INSERT INTO `gift_list` VALUES ('955','25','食物','100');
INSERT INTO `gift_list` VALUES ('954','25','食物','100');
INSERT INTO `gift_list` VALUES ('953','25','食物','100');
INSERT INTO `gift_list` VALUES ('952','25','食物','100');
INSERT INTO `gift_list` VALUES ('951','25','食物','100');
INSERT INTO `gift_list` VALUES ('950','25','食物','100');
INSERT INTO `gift_list` VALUES ('949','25','食物','100');
INSERT INTO `gift_list` VALUES ('948','26','野兽','100');
INSERT INTO `gift_list` VALUES ('946','26','野兽','100');
INSERT INTO `gift_list` VALUES ('945','26','野兽','100');
INSERT INTO `gift_list` VALUES ('944','26','野兽','100');
INSERT INTO `gift_list` VALUES ('943','26','野兽','100');
INSERT INTO `gift_list` VALUES ('942','26','野兽','100');
INSERT INTO `gift_list` VALUES ('941','26','野兽','100');
INSERT INTO `gift_list` VALUES ('940','26','野兽','100');
INSERT INTO `gift_list` VALUES ('939','26','野兽','100');
INSERT INTO `gift_list` VALUES ('938','26','野兽','100');
INSERT INTO `gift_list` VALUES ('937','26','野兽','100');
INSERT INTO `gift_list` VALUES ('936','26','野兽','100');
INSERT INTO `gift_list` VALUES ('935','26','野兽','100');
INSERT INTO `gift_list` VALUES ('934','26','野兽','100');
INSERT INTO `gift_list` VALUES ('933','26','野兽','100');
INSERT INTO `gift_list` VALUES ('932','26','野兽','100');
INSERT INTO `gift_list` VALUES ('931','26','野兽','100');
INSERT INTO `gift_list` VALUES ('930','26','野兽','100');
INSERT INTO `gift_list` VALUES ('929','26','野兽','100');
INSERT INTO `gift_list` VALUES ('928','26','野兽','100');
INSERT INTO `gift_list` VALUES ('927','26','野兽','100');
INSERT INTO `gift_list` VALUES ('926','26','野兽','100');
INSERT INTO `gift_list` VALUES ('925','26','野兽','100');
INSERT INTO `gift_list` VALUES ('924','26','野兽','100');
INSERT INTO `gift_list` VALUES ('923','26','野兽','100');
INSERT INTO `gift_list` VALUES ('922','26','野兽','100');
INSERT INTO `gift_list` VALUES ('921','26','野兽','100');
INSERT INTO `gift_list` VALUES ('920','26','野兽','100');
INSERT INTO `gift_list` VALUES ('919','26','野兽','100');
INSERT INTO `gift_list` VALUES ('918','26','野兽','100');
INSERT INTO `gift_list` VALUES ('917','26','野兽','100');
INSERT INTO `gift_list` VALUES ('916','26','野兽','100');
INSERT INTO `gift_list` VALUES ('915','26','野兽','100');
INSERT INTO `gift_list` VALUES ('914','26','野兽','100');
INSERT INTO `gift_list` VALUES ('913','26','野兽','100');
INSERT INTO `gift_list` VALUES ('912','26','野兽','100');
INSERT INTO `gift_list` VALUES ('911','26','野兽','100');
INSERT INTO `gift_list` VALUES ('910','26','野兽','100');
INSERT INTO `gift_list` VALUES ('909','26','野兽','100');
INSERT INTO `gift_list` VALUES ('908','26','野兽','100');
INSERT INTO `gift_list` VALUES ('907','26','野兽','100');
INSERT INTO `gift_list` VALUES ('906','26','野兽','100');
INSERT INTO `gift_list` VALUES ('905','26','野兽','100');
INSERT INTO `gift_list` VALUES ('903','26','野兽','100');
INSERT INTO `gift_list` VALUES ('902','26','野兽','100');
INSERT INTO `gift_list` VALUES ('901','26','野兽','100');
INSERT INTO `gift_list` VALUES ('898','21','汽车和摩托','100');
INSERT INTO `gift_list` VALUES ('897','21','汽车和摩托','100');
INSERT INTO `gift_list` VALUES ('1068','30','爱','100');
INSERT INTO `gift_list` VALUES ('1067','30','爱','100');
INSERT INTO `gift_list` VALUES ('1066','30','爱','100');
INSERT INTO `gift_list` VALUES ('1065','30','爱','100');
INSERT INTO `gift_list` VALUES ('1064','30','爱','100');
INSERT INTO `gift_list` VALUES ('1063','30','爱','100');
INSERT INTO `gift_list` VALUES ('1062','30','爱','100');
INSERT INTO `gift_list` VALUES ('1061','30','爱','100');
INSERT INTO `gift_list` VALUES ('1060','30','爱','100');
INSERT INTO `gift_list` VALUES ('1059','30','爱','100');
INSERT INTO `gift_list` VALUES ('1058','30','爱','100');
INSERT INTO `gift_list` VALUES ('1057','30','爱','100');
INSERT INTO `gift_list` VALUES ('1056','30','爱','100');
INSERT INTO `gift_list` VALUES ('1055','30','爱','100');
INSERT INTO `gift_list` VALUES ('1054','30','爱','100');
INSERT INTO `gift_list` VALUES ('1053','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1052','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1051','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1050','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1049','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1048','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1047','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1046','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1045','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1044','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1043','32','带微笑的礼物','100');
INSERT INTO `gift_list` VALUES ('1042','37','装饰','240');
INSERT INTO `gift_list` VALUES ('1041','37','装饰','240');
INSERT INTO `gift_list` VALUES ('1040','37','装饰','699');
INSERT INTO `gift_list` VALUES ('1039','37','装饰','788');
INSERT INTO `gift_list` VALUES ('1038','37','装饰','299');
INSERT INTO `gift_list` VALUES ('1037','37','装饰','999');
INSERT INTO `gift_list` VALUES ('1036','37','装饰','799');
INSERT INTO `gift_list` VALUES ('1035','37','装饰','350');
INSERT INTO `gift_list` VALUES ('1034','37','装饰','500');
INSERT INTO `gift_list` VALUES ('1033','37','装饰','600');
INSERT INTO `gift_list` VALUES ('1032','37','装饰','899');
INSERT INTO `gift_list` VALUES ('1031','37','装饰','500');
INSERT INTO `gift_list` VALUES ('1030','37','装饰','50');
INSERT INTO `gift_list` VALUES ('1029','37','装饰','769');
INSERT INTO `gift_list` VALUES ('1028','37','装饰','170');
INSERT INTO `gift_list` VALUES ('1027','37','装饰','50');
INSERT INTO `gift_list` VALUES ('1026','37','装饰','500');
INSERT INTO `gift_list` VALUES ('1025','37','装饰','650');
INSERT INTO `gift_list` VALUES ('1024','37','装饰','600');
INSERT INTO `gift_list` VALUES ('1023','37','装饰','50');
INSERT INTO `gift_list` VALUES ('1022','37','装饰','500');
INSERT INTO `gift_list` VALUES ('1021','37','装饰','460');
INSERT INTO `gift_list` VALUES ('1020','37','装饰','400');
INSERT INTO `gift_list` VALUES ('1019','37','装饰','350');
INSERT INTO `gift_list` VALUES ('1018','37','装饰','250');
INSERT INTO `gift_list` VALUES ('1017','37','装饰','300');
INSERT INTO `gift_list` VALUES ('1016','37','装饰','50');
INSERT INTO `gift_list` VALUES ('1015','37','装饰','350');
INSERT INTO `gift_list` VALUES ('1014','37','装饰','50');
INSERT INTO `gift_list` VALUES ('1013','37','装饰','300');
INSERT INTO `gift_list` VALUES ('1012','37','装饰','160');
INSERT INTO `gift_list` VALUES ('1011','37','装饰','240');
INSERT INTO `gift_list` VALUES ('1010','37','装饰','200');
INSERT INTO `gift_list` VALUES ('1009','37','装饰','250');
INSERT INTO `gift_list` VALUES ('1008','38','旗帜','150');
INSERT INTO `gift_list` VALUES ('1007','38','旗帜','160');
INSERT INTO `gift_list` VALUES ('1006','38','旗帜','150');
INSERT INTO `gift_list` VALUES ('1005','38','旗帜','150');
INSERT INTO `gift_list` VALUES ('1004','38','旗帜','150');
INSERT INTO `gift_list` VALUES ('1003','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('1002','35','生日快乐','150');
INSERT INTO `gift_list` VALUES ('1001','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('1000','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('999','35','生日快乐','100');
INSERT INTO `gift_list` VALUES ('1105','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1104','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1103','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1102','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1101','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1100','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1099','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1098','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1097','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1096','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1095','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1094','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1093','34','杂项','100');
INSERT INTO `gift_list` VALUES ('1092','1','LoveTap.Ru','50000');
INSERT INTO `gift_list` VALUES ('1091','1','LoveTap.Ru','100');
INSERT INTO `gift_list` VALUES ('1090','30','爱','100');
INSERT INTO `gift_list` VALUES ('1089','30','爱','100');
INSERT INTO `gift_list` VALUES ('1088','30','爱','100');
INSERT INTO `gift_list` VALUES ('1087','30','爱','100');
INSERT INTO `gift_list` VALUES ('1086','30','爱','100');
INSERT INTO `gift_list` VALUES ('1085','30','爱','100');
INSERT INTO `gift_list` VALUES ('1084','30','爱','100');
INSERT INTO `gift_list` VALUES ('1083','30','爱','100');
INSERT INTO `gift_list` VALUES ('1082','30','爱','100');
INSERT INTO `gift_list` VALUES ('1081','30','爱','100');
INSERT INTO `gift_list` VALUES ('1080','30','爱','100');
INSERT INTO `gift_list` VALUES ('1079','30','爱','100');
INSERT INTO `gift_list` VALUES ('1078','30','爱','100');
INSERT INTO `gift_list` VALUES ('1077','30','爱','100');
INSERT INTO `gift_list` VALUES ('1076','30','爱','100');
INSERT INTO `gift_list` VALUES ('1075','30','爱','100');
INSERT INTO `gift_list` VALUES ('1074','30','爱','100');
INSERT INTO `gift_list` VALUES ('1073','30','爱','100');
INSERT INTO `gift_list` VALUES ('1072','30','爱','100');
INSERT INTO `gift_list` VALUES ('1071','30','爱','100');
INSERT INTO `gift_list` VALUES ('1070','30','爱','100');
INSERT INTO `gift_list` VALUES ('1069','30','爱','100');
