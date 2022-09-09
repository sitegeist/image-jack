#
# Table structure for table 'tx_imagejack_domain_model_queue'
#
CREATE TABLE tx_imagejack_domain_model_queue
(
	storage int(11) DEFAULT '0' NOT NULL,
	identifier varchar(512) DEFAULT '' NOT NULL,
	KEY storage_identifier (storage, identifier)
);
