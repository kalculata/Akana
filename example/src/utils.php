<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    namespace Akana;

    use Akana\Exceptions\JSONException;

    abstract class Utils{
        static public function stop_error_handler($errno, $errstr, $errfile, $errline){
            if (0 === error_reporting()) return false; 

            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }

        static private function json_valid($data=NULL): bool{
            if(!empty($data)) {
                json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
            }
            return true;
        }

        static public function get_request_data(): array{
            $json_data = file_get_contents('php://input');

            if(self::json_valid($json_data) == false)
                throw new JsonException("your json request data contain errors");
        
            $request_data = json_decode($json_data, true);

            if(empty($request_data) && !empty($_POST))
                $request_data = $_POST;  

            return $request_data;
        }

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

        static function get_keys($array){
            $keys = [];

            foreach($array as $k => $v)
                array_push($keys, $k);

            return $keys;
        }
        
        static function get_values($array){
            $values = [];

            foreach($array as $k => $v)
                array_push($values, $v);

            return $values;
        }
    }

    abstract class URI{
        static function extract_resource(string $uri): string{
            return explode('/', $uri)[1];
        }

        static function extract_endpoint(string $resource, string $uri): string{
            $uri_in_part = explode('/', $uri);
            $endpoint = '';
            
            foreach($uri_in_part as $value){
                if($value != $resource AND !empty($value))
                    $endpoint .= '/' . $value;
                
            }

            return $endpoint . '/';
        }
    }

    abstract class Resource{
        static function is_exist(string $resource_name): bool{
            return in_array($resource_name, APP_RESOURCES);
        }
    }

    abstract class Endpoint{
        static function details(string $resource, string $endpoint): array{
            require '../res/'. $resource . '/endpoints.php';
            
            foreach(ENDPOINTS as $ep => $controller){
                if(self::is_dynamic($ep) == true){
                    if(preg_match_all('#^'. self::to_regex($ep) .'$#', $endpoint, $data))
                        return ["controller" => $controller, "args" => self::get_args($ep, $data)];   
                }

                else{
                    if($ep == $endpoint){
                        return ["controller" => $controller, "args" =>  []];
                    }
                }
            }

            return [];
        }
        static function is_dynamic(string $endpoint): bool{
            return (preg_match('#\([a-zA-Z0-9_]+:int\)|\([a-zA-Z0-9_]+:str\)+#', $endpoint))? true: false;
        }
        static function get_args($native_endpoint, $pattern_matches): array{
            $pattern = "#\([a-zA-Z0-9_]+:#";
            $data = [];
            $args = [];

            if(preg_match_all($pattern, $native_endpoint, $data)) 
                $data = $data[0];

            for($i = 0; $i<count($data); $i++) 
                $data[$i] = Utils::remove_char($data[$i],[0,-1]);

            for($i = 0; $i<count($data); $i++){
                $args[$i] = $pattern_matches[$data[$i]][0];
                $args[$i] = (is_numeric($args[$i]))? intval($args[$i]) : $args[$i];
            }

            return $args;
        }
        static function to_regex(string $dynamic_endpoint): string{
            $regex = $dynamic_endpoint;
            
            $regex = preg_replace('#\/#', '\/', $regex);
            $regex = preg_replace('#\(([a-zA-Z0-9_]+):int\)#', '(?<$1>[0-9]+)', $regex);
            $regex = preg_replace('#\(([a-zA-Z0-9_]+):str\)#', '(?<$1>[a-zA-Z0-9_-]+)', $regex);

            return $regex;
        }
    }
