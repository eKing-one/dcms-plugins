CREATE TABLE `farm_plant` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(1024) NOT NULL,
`opis` varchar(1024) DEFAULT NULL,
`cena` int(11) NOT NULL,
`dohod` varchar(1024) NOT NULL,
`rand1` varchar(1024) NOT NULL,
`rand2` varchar(1024) NOT NULL,
`oput` varchar(1024) NOT NULL,
`xp` float NOT NULL DEFAULT '0' COMMENT 'Сколько здоровья за единицу',
`let` int(11) NOT NULL DEFAULT '1',
`time` varchar(1024) DEFAULT NULL,
`level` int(10) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

INSERT INTO `farm_plant` VALUES (1, '豌豆', '农场最快、最简单的产品', 50, '4', '19', '20', '1', '1.8', 1, '10800', 0);
INSERT INTO `farm_plant` VALUES (2, '萝卜', '不错的开始选择', 50, '5', '20', '22', '1', '3.6', 1, '14400', 1);
INSERT INTO `farm_plant` VALUES (3, '胡萝卜', '不错的开始选择', 25, '1.8', '24', '25', '1', '5', 1, '18000', 2);
INSERT INTO `farm_plant` VALUES (4, '甜菜', '帮助快速积累经验', 30, '5', '24', '25', '1', '1.8', 1, '21600', 2);
INSERT INTO `farm_plant` VALUES (5, '黄瓜', '带来了很多经验', 50, '5', '29', '30', '1', '1.2', 1, '14400', 3);
INSERT INTO `farm_plant` VALUES (6, '地瓜', '农民的好朋友', 30, '6', '24', '25', '1', '3', 1, '21600', 3);
INSERT INTO `farm_plant` VALUES (7, '白菜', '有利可图的植物', 70, '5', '37', '38', '1', '0.6', 1, '14400', 4);
INSERT INTO `farm_plant` VALUES (8, '辣椒', '低产但经验丰富的辣椒', 22, '31', '3', '4', '6', '1.5', 1, '14400', 4);
INSERT INTO `farm_plant` VALUES (9, '大蒜', '好蔬菜发财', 30, '7', '34', '35', '1', '1.2', 1, '21600', 5);
INSERT INTO `farm_plant` VALUES (10, '秋葵', '昂贵而有利可图的蔬菜农民', 30, '15', '29', '30', '3', '1.2', 1, '50400', 5);
INSERT INTO `farm_plant` VALUES (11, '花生', '在许多方面都是好的和有利的', 30, '4', '57', '58', '1', '0.9', 1, '28800', 5);
INSERT INTO `farm_plant` VALUES (12, '洋葱', '多参数蔬菜', 115, '10', '39', '40', '2', '0.9', 1, '28800', 6);
INSERT INTO `farm_plant` VALUES (13, '豆角', '早熟不平衡', 75, '6', '29', '30', '1', '0.9', 1, '10800', 7);
INSERT INTO `farm_plant` VALUES (14, '西红柿', '多产的蔬菜', 100, '9', '64', '65', '2', '1.2', 1, '43200', 8);
INSERT INTO `farm_plant` VALUES (15, '辣椒', '在学习和学习经验方面非常有用的蔬菜', 125, '30', '20', '21', '6', '1.3', 1, '43200', 9);
INSERT INTO `farm_plant` VALUES (16, '洋葱', '增长迅速', 52, '6', '36', '37', '1', '0.3', 1, '14400', 10);
INSERT INTO `farm_plant` VALUES (17, '朝天椒', '长时间生长，产量不高，但价格昂贵', 270, '65', '20', '21', '11', '1.6', 1, '86400', 11);
INSERT INTO `farm_plant` VALUES (18, '玉米', '长期增长，但利润丰厚', 340, '31', '60', '61', '7', '0.9', 1, '115200', 12);
INSERT INTO `farm_plant` VALUES (19, '卷心菜', '增长迅速', 315, '31', '27', '28', '6', '1.5', 1, '36000', 13);
INSERT INTO `farm_plant` VALUES (20, '绿茄子', '成熟得很快，但经验教训很少', 170, '25', '25', '26', '6', '1.8', 1, '28800', 14);
INSERT INTO `farm_plant` VALUES (21, '洋葱', '成熟速度快。价格和获得的经验平均', 100, '19', '32', '33', '6', '0.9', 1, '28800', 15);
INSERT INTO `farm_plant` VALUES (22, '马铃薯', '便宜实用的蔬菜', 150, '23', '41', '42', '7', '3', 1, '43200', 16);
INSERT INTO `farm_plant` VALUES (23, '西兰花', '相当有利可图的卷心菜品种', 80, '57', '20', '21', '16', '1.8', 1, '57600', 17);
INSERT INTO `farm_plant` VALUES (24, '莳萝', '你的山脊上生长最快的居民之一', 103, '12', '31', '32', '3', '0.6', 1, '14400', 18);
INSERT INTO `farm_plant` VALUES (25, '李子', '一种多年生植物', 15000, '41', '59', '60', '7', '0.7', 15, '64800', 19);
INSERT INTO `farm_plant` VALUES (26, '野苹果树', '它生长得很快，适合许多人。', 800, '30', '44', '45', '7', '0.9', 5, '50400', 20);
INSERT INTO `farm_plant` VALUES (27, '草莓', '多年生浆果', 2850, '53', '34', '35', '7', '0.9', 3, '36000', 21);
INSERT INTO `farm_plant` VALUES (28, '野梨', '这种水果通常足够成熟。', 4900, '45', '38', '39', '8', '0.9', 8, '43200', 22);
INSERT INTO `farm_plant` VALUES (29, '富士苹果', '常见的苹果品种', 13000, '60', '50', '51', '13', '0.6', 20, '86400', 23);
INSERT INTO `farm_plant` VALUES (30, '柠檬', '增长的时间略长于其他大多数，但更有利', 40000, '152', '34', '35', '31', '0.4', 5, '86400', 24);
INSERT INTO `farm_plant` VALUES (31, '核桃', '像柠檬一样，它长得更长，但在滑翔机上很好。', 14000, '91', '44', '45', '46', '5', 15, '93600', 25);
INSERT INTO `farm_plant` VALUES (32, '橙子', '肥沃', 34000, '79', '64', '65', '30', '6', 15, '79200', 26);
INSERT INTO `farm_plant` VALUES (33, '香蕉', '不是那么有利可图，但它是有用的。', 10200, '60', '44', '45', '27', '10', 10, '43200', 27);
INSERT INTO `farm_plant` VALUES (34, '菠萝', '均衡水果', 2500, '63', '59', '60', '51', '7', 15, '86400', 28);
INSERT INTO `farm_plant` VALUES (35, '木瓜', '熟果', 14200, '120', '49', '50', '117', '12', 12, '108000', 29);
INSERT INTO `farm_plant` VALUES (36, '芒果', '久熟', 12200, '500', '14', '15', '545', '35', 8, '126000', 30);
INSERT INTO `farm_plant` VALUES (37, '樱桃', '实用便宜', 800, '60', '54', '55', '81', '10', 15, '64800', 31);
INSERT INTO `farm_plant` VALUES (38, '西瓜', '成熟得很快', 3700, '94', '19', '20', '79', '32', 5, '21600', 32);
INSERT INTO `farm_plant` VALUES (39, '石榴', '健康恢复良好，非常有益', 7500, '200', '24', '25', '153', '65', 8, '50400', 33);
INSERT INTO `farm_plant` VALUES (40, '热情果', '短暂但有益的水果', 6350, '250', '19', '20', '256', '25', 4, '61200', 34);
INSERT INTO `farm_plant` VALUES (41, '草莓', '快速增长', 2150, '200', '9', '10', '321', '15', 6, '28800', 34);
INSERT INTO `farm_plant` VALUES (42, '白梨', '经验与利润平衡', 11000, '200', '19', '20', '231', '20', 12, '54000', 35);
INSERT INTO `farm_plant` VALUES (43, '猕猴桃', '高产植物', 11300, '80', '64', '65', '98', '12', 8, '64800', 36);
INSERT INTO `farm_plant` VALUES (44, '西瓜', '成熟快，寿命短', 6100, '300', '9', '10', '267', '30', 4, '25200', 37);
INSERT INTO `farm_plant` VALUES (45, '青苹果', '成熟时间长，但树苗不长', 200, '173', '49', '50', '314', '5', 10, '144000', 38);
INSERT INTO `farm_plant` VALUES (46, '橘子', '获得大量经验并快速成长', 4600, '85', '34', '35', '136', '6', 12, '43200', 39);
INSERT INTO `farm_plant` VALUES (47, '蓝莓', '便宜有利可图的浆果', 750, '115', '19', '20', '201', '15', 8, '36000', 40);
INSERT INTO `farm_plant` VALUES (48, '开心果', '长熟植物', 13000, '550', '15', '16', '975', '20', 40, '129600', 41);
INSERT INTO `farm_plant` VALUES (49, '甜柠檬', '它是一种非常多产的植物，但不耐久。', 14400, '102', '79', '80', '139', '30', 5, '86400', 42);
INSERT INTO `farm_plant` VALUES (50, '沙棘果', '异国情调的浆果，有很好的特点', 5000, '84', '44', '45', '164', '10', 8, '50400', 43);
INSERT INTO `farm_plant` VALUES (51, '竹子', '在这种植物中，与长生不老不同的是，它的根，而不是叶子或果实。它成熟得很快，果实很好，但寿命也很短。', 4960, '70', '49', '50', '95', '5', 3, '28800', 44);
INSERT INTO `farm_plant` VALUES (52, '薄荷', '一种快速成熟的草。这片荒地不是很肥沃，但它的生长速度得到了回报。', 600, '112', '9', '10', '293', '1', 3, '14400', 45);

