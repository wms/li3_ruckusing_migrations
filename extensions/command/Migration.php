<?php

namespace li3_migration\extensions\command;

use \lithium\data\Connections;

require RUCKUSING_BASE . '/lib/classes/util/class.Ruckusing_Logger.php';
require RUCKUSING_BASE . '/lib/classes/class.Ruckusing_FrameworkRunner.php';

class Migration extends \lithium\console\Command {

  private $ruckusing_db_config;

  public function _init() {
    parent::_init();
    $args = $this->_config['request']->args;
    $env = false;
    array_walk(
      $args,
      function($e, $k) use (&$env){
        if(preg_match('/ENV=(\w+)/i', $e, $matches)) {
          $env = $matches[1];
        }
      }
    );
    if ($env) {
      $connection = Connections::get($env);
    }
    else {
      $connection = Connections::get(DEFAULT_ENV);
      $env = 'development';
    }
    if (is_null($connection)) {
      $this->error("Error: Connection {$env} not defined in your Li3 app.\n");
      exit;
    }
    $this->ruckusing_db_config = array(
      $env => array(
          'type'      => strtolower($connection->_config['adapter']),
          'host'      => $connection->_config['host'],
          'port'      => 3306,
          'database'  => $connection->_config['database'],
          'user'      => $connection->_config['login'],
          'password'  => $connection->_config['password'],
      ),
    );
  }

  public function run($command = null) {
    //var_dump ($args);
    //Connections::get('default');
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
    $argv = array_merge(array(''), array($command), $args);
    $main = new \Ruckusing_FrameworkRunner($this->ruckusing_db_config, $argv);
    $main->execute();
  }

}
?>
