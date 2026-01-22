<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data;

$sql_create_module = $sql_drop_module;
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . " (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  catid int(11) unsigned NOT NULL DEFAULT 0,
  image varchar(255) DEFAULT '',
  description mediumtext,
  bodytext mediumtext NOT NULL,
  add_time int(11) unsigned NOT NULL DEFAULT 0,
  edit_time int(11) unsigned NOT NULL DEFAULT 0,
  status tinyint(4) NOT NULL DEFAULT 1,
  hitstotal int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY catid (catid),
  KEY status (status)
) ENGINE=InnoDB";