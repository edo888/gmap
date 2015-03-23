CREATE TABLE IF NOT EXISTS `#__gmap_data` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `height` int(100) NOT NULL default 600,
  `width` int(100) NOT NULL default 1000,
  `data` text NOT NULL,
  `center` varchar(100) NOT NULL default '[40.169997,44.52]',
  `zoom` tinyint(2) NOT NULL default 10,
  `mapTypeId` ENUM('ROADMAP', 'HYBRID', 'SATELLITE', 'TERRAIN') not null default 'ROADMAP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


[40.13899044275822,44.27764892578125]