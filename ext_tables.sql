#
# Table structure for table 'tx_generic_gallery_pictures'
#
CREATE TABLE tx_generic_gallery_pictures (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	tt_content_id int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	link tinytext,
	images blob,
	contents int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_generic_gallery_content'
#
CREATE TABLE tx_generic_gallery_content (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	pictures_id int(11) DEFAULT '0' NOT NULL,
	bodytext mediumtext,
	position tinytext,
	width tinytext,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'pages'
#
CREATE TABLE tt_content (
	tx_generic_gallery_items int(11) DEFAULT '0' NOT NULL,
	tx_generic_gallery_predefined tinytext,
	tx_generic_gallery_images blob,
	tx_generic_gallery_collection int(11) DEFAULT '0' NOT NULL,
);
