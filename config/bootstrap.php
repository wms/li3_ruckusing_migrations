<?php

use lithium\core\Libraries;
use lithium\core\ConfigException;

try {
  //Define the base location of ruckusing-migration. Needs to be checked out in /path/to/your_li3_app/libraries/.
  define('RUCKUSING_BASE', LITHIUM_APP_PATH . '/libraries/ruckusing-migrations' );
  //Define the db directory (which includes the migrate directory). Both must be created in /path/to/your_li3_app/config/
  define('RUCKUSING_DB_DIR', LITHIUM_APP_PATH . '/config/db');
} catch (ConfigException $e) {
  echo ('Error: '. $e->getMessage());
}

?>
