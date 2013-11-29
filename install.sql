CREATE TABLE wp_vspostman_clients_comments (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  contact_id int(11) NOT NULL,
  created timestamp DEFAULT CURRENT_TIMESTAMP,
  content varchar(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_clients_contacts (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(50) NOT NULL,
  first_name varchar(50) DEFAULT NULL,
  created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  country varchar(255) DEFAULT NULL,
  city varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  phone varchar(255) DEFAULT NULL,
  skype varchar(255) DEFAULT NULL,
  icq varchar(255) DEFAULT NULL,
  facebook varchar(255) DEFAULT NULL,
  vk varchar(255) DEFAULT NULL,
  google varchar(255) DEFAULT NULL,
  web varchar(255) DEFAULT NULL,
  birthdate varchar(10) DEFAULT NULL,
  information text DEFAULT NULL,
  deleted tinyint(1) DEFAULT 0,
  deleted_at timestamp NULL DEFAULT '0000-00-00 00:00:00',
  conversion_page varchar(500) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX email (email)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_clients_custom_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  field_label varchar(255) DEFAULT NULL,
  field_name varchar(50) DEFAULT NULL,
  field_value text DEFAULT NULL,
  field_type varchar(20) DEFAULT NULL,
  sort int(3) DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_clients_custom_fields_values (
  contact_id int(11) DEFAULT NULL,
  field_id int(11) DEFAULT NULL,
  value text DEFAULT NULL,
  UNIQUE INDEX UK_wp_vspostman_clients_custom (contact_id, field_id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_clients_filters (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  data text DEFAULT NULL,
  created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_contacts_funnels (
  contact_id int(11) NOT NULL,
  funnel_id int(11) NOT NULL,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP,
  is_removal tinyint(1) DEFAULT 0,
  removal_at timestamp NULL DEFAULT '0000-00-00 00:00:00',
  removal_reason varchar(255) DEFAULT NULL,
  removal_type tinyint(1) DEFAULT 0,
  in_blacklist tinyint(1) DEFAULT 0,
  blacklist_at timestamp NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE INDEX UK_wp_vspostman_contacts_funne (contact_id, funnel_id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_funnels (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  created timestamp DEFAULT CURRENT_TIMESTAMP,
  updated timestamp NULL DEFAULT '0000-00-00 00:00:00',
  active tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE INDEX name (name)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_mail_links (
  id int(11) NOT NULL AUTO_INCREMENT,
  mail_id int(11) DEFAULT NULL,
  link varchar(255) DEFAULT NULL,
  created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE wp_vspostman_mails (
  id int(11) NOT NULL AUTO_INCREMENT,
  funnel_id int(11) NOT NULL,
  level int(11) NOT NULL DEFAULT 0,
  is_root int(1) NOT NULL DEFAULT 0,
  bound_id int(11) DEFAULT 0,
  title varchar(255) NOT NULL,
  subject varchar(255) NOT NULL,
  content text DEFAULT NULL,
  `left` int(11) DEFAULT 0,
  mail_type varchar(25) DEFAULT NULL,
  mail_link_id int(11) DEFAULT 0,
  order_id int(11) DEFAULT 0,
  data_modified_type int(11) DEFAULT 0,
  data_modified_field varchar(255) DEFAULT NULL,
  date_field datetime DEFAULT NULL,
  time_mailing_type int(11) DEFAULT NULL,
  time_mailing_delay_days int(11) DEFAULT 0,
  time_mailing_delay_hours int(11) DEFAULT 0,
  time_mailing_hour int(11) DEFAULT 0,
  time_mailing_weekdays varchar(20) DEFAULT NULL,
  created timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  active tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 0
CHARACTER SET utf8
COLLATE utf8_general_ci;