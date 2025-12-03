#
# Table structure for table 'tx_generic_gallery_pictures'
#
CREATE TABLE tx_generic_gallery_pictures (
	tt_content_id int(11) DEFAULT '0' NOT NULL,
	title tinytext,
	link tinytext,
	images blob,
	contents int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_generic_gallery_content'
#
CREATE TABLE tx_generic_gallery_content (
	pictures_id int(11) DEFAULT '0' NOT NULL,
	bodytext mediumtext,
	position tinytext,
	width tinytext
);

#
# Table structure for table 'pages'
#
CREATE TABLE tt_content (
	tx_generic_gallery_predefined tinytext,
	tx_generic_gallery_items int(11) DEFAULT '0' NOT NULL,
	tx_generic_gallery_images blob,
	tx_generic_gallery_collection int(11) DEFAULT '0' NOT NULL
);
