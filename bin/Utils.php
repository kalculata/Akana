<?php
namespace Akana;


use Akana\Database\Connectivity;


class Utils {
	private $_settings;
	private $_resources;
	private $_db_credintial;
	public $dev_mod;

	public function __construct() {
		$resources     = __DIR__.'/../config/resources.yaml';
		$db_credintial = __DIR__.'/../config/db.yaml';
		$settings 		 = __DIR__.'/../config/settings.yaml';

		$this->_db_credintial = !file_exists($db_credintial)? NULL : spyc_load_file($db_credintial);
		$this->_resources = !file_exists($resources)? NULL : spyc_load_file($resources);
		$this->_settings = !file_exists($settings)? NULL : spyc_load_file($settings);
		$this->dev_mod = $this->_settings['dev_mod'];
	}

	public function getResources() { return $this->_resources; }

	public function getDBCredintial() {
		return $this->_db_credintial;
	}

	public function getSettings() { return $this->_settings; }

	public function checkDBConnectivity() {
		if($this->_db_credintial == NULL) {
      echo "[WARNING] config/db.yaml file not found.\n";
    } 
		else {
			$vars = $this->_db_credintial;

      if(!empty($vars)) {
        if(key_exists("type", $vars) && key_exists("host", $vars) && key_exists("port", $vars) && key_exists("name", $vars) && key_exists("login", $vars) && key_exists("password", $vars)) {
            Connectivity::get();
        } else {
          echo "[WARNING] A connection with database fail because some database variables are missed in env.yaml.\n";
        }
      }
    }
	}
		// static function remove_char(string $word, $index=0): string{
		// 	$output = "";
		// 	$word_length = strlen($word);
		// 	$last_index = $word_length - 1;
			
		// 	if(is_numeric($index)){
		// 			if($index == -1) $index = $last_index;

		// 			for($i=0; $i<$word_length; $i++)
		// 					if($i != $index) $output .= $word[$i];
					
		// 	}

		// 	else if(is_array($index)){
		// 			if(in_array(-1, $index))
		// 					$index[array_search(-1, $index)] = $last_index;

		// 			for($i=0; $i<$word_length; $i++){
		// 					if(!in_array($i, $index)) $output .= $word[$i];
		// 			}
		// 	}

		// 	return $output;
		// }

		// static function get_args($argv) {
		// 	$my_args = array();
		// 	for ($i = 1; $i < count($argv); $i++) {
		// 		if (preg_match('/^--([^=]+)=(.*)/', $argv[$i], $match)) {
		// 			$my_args[$match[1]] = $match[2];
		// 		}
		// 	}
		// 	return $my_args;
		// }

		// static function get_classes_in_file($file) {
		// 	$classes = get_declared_classes();
		// 	require_once $file;
		// 	return array_diff(get_declared_classes(), $classes);
		// }
	}