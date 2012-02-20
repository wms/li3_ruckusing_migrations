<?php

namespace li3_migration\extensions\command;

use \lithium\data\Connections;
use \lithium\core\Environment;

require RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_Logger.php';
require RUCKUSING_BASE . '/lib/classes/class.Ruckusing_FrameworkRunner.php';

/**
 * Acts as a wrapper for ruckusing-migrations.
 */
class Migration extends \lithium\console\Command {

  private $ruckusing_db_config;
  public $connection = 'default';

  public function _init() {
    parent::_init();
    if($connection = Connections::get($this->connection)) {
      $this->ruckusing_db_config = array(
        $this->connection => array(
          'type'      => strtolower($connection->_config['adapter']),
          'host'      => $connection->_config['host'],
          'port'      => 3306,
          'database'  => $connection->_config['database'],
          'user'      => $connection->_config['login'],
          'password'  => $connection->_config['password'],
        )
      );
    }
  }

  public function run($command = null) {
    $env = Environment::get();
    $this->out("Using Environment: '$env' and Connection: '{$this->connection}'.");

    if(!$this->ruckusing_db_config) {
      $this->error('Error: cannot cannot connect to database');
      return;
    }
    if (is_null($command)) {
      $this->help();
      return;
    }
    $args = $this->_config['request']->args;
    $this->execute($command, $args);
  }

  public function help () {
    $message = "Usage: li3 migration [command] [args]
Commands:
  help: Displays this text.
  generate: Generates migration file. Requires a migration description argument
            either as a an underscore seperated words or quoted words.
  other migration commands: All ruckusing-migrations' tasks. Listed in
                            https://github.com/ruckus/ruckusing-migrations/wiki/Available-Tasks
";
    $this->out($message);
    return true;
  }

  public function generate($description=null) {
    if (is_null($description)) {
      $this->error("Please Specify a migration description.\nThe description can either be a set of underscore_seperated_words or \"quoted words\".");
      return false;
    }
    $argv = array('', $description);
    include RUCKUSING_BASE . '/generate.php';
  }

  private function execute($command, $args) {
    $argv = array_merge(array(''), array($command), $args, array("ENV=$this->connection"));
    $main = new \Ruckusing_FrameworkRunner($this->ruckusing_db_config, $argv);
    $main->execute();
  }

}
?>
