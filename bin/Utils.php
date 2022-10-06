<?php
	namespace Akana;

	
	class Utils {
		private $_resources;

		public function __construct() {
			$resource_file = __DIR__.'/../config/resources.yaml';

			$this->_resources = !file_exists($resource_file)? NULL : spyc_load_file($resource_file);
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