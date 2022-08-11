<?php
	namespace Akana;


	class Utils {
		// static function read($file): string {
		// 	$myfile = fopen($file, "r") or die("Unable to open file!");
		// 	$contents = fread($myfile, filesize($file));
		// 	fclose($myfile);
		// 	return $contents;
		// }

		static function remove_char(string $word, $index=0): string{
			$output = "";
			$word_length = strlen($word);
			$last_index = $word_length - 1;
			
			if(is_numeric($index)){
					if($index == -1) $index = $last_index;

					for($i=0; $i<$word_length; $i++)
							if($i != $index) $output .= $word[$i];
					
			}

			else if(is_array($index)){
					if(in_array(-1, $index))
							$index[array_search(-1, $index)] = $last_index;

					for($i=0; $i<$word_length; $i++){
							if(!in_array($i, $index)) $output .= $word[$i];
					}
			}

			return $output;
		}
	}