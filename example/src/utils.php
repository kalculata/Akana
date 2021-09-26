<?php
    namespace Akana;

    use Akana\Exceptions\JSONException;

    class Utils{
        // change type as json and response code (by default: 200)
        static function set_content_to_json(int $status = 200){
            http_response_code($status);

            header('Content-Type: application/json');
        }

        // remove some characters in given string
        static function remove_char(string $word, $index=0): string{
            $output = "";
            $word_length = strlen($word);
            $last = $word_length - 1;
            
            
            if(is_numeric($index)){
                if($index == -1) $index = $last;

                for($i=0; $i<$word_length; $i++){
                    if($i != $index) $output .= $word[$i];
                }
            }

            else if(is_array($index)){
                if(in_array(-1, $index)){
                    $index[array_search(-1, $index)] = $last;
                }

                for($i=0; $i<$word_length; $i++){
                    if(!in_array($i, $index)) $output .= $word[$i];
                }
            }
 
            return $output;
        }

        static function get_args($native_endpoint, $pattern_matches): array{
            $pattern = "#\([a-zA-Z0-9_]+:#";
            $data = [];
            $args = [];

            if(preg_match_all($pattern, $native_endpoint, $data)) $data = $data[0];

            for($i = 0; $i<count($data); $i++) $data[$i] = self::remove_char($data[$i],[0,-1]);

            for($i = 0; $i<count($data); $i++){
                $args[$i] = $pattern_matches[$data[$i]][0];
                $args[$i] = (is_numeric($args[$i]))? intval($args[$i]) : $args[$i];
            }

            return $args;
        }

        static function get_resource(string $uri): string{
            return explode('/', $uri)[1];
        }

        static function get_endpoint(string $resource, string $uri): string{
            $uri_in_part = explode('/', $uri);
            $endpoint = '';
            
            foreach($uri_in_part as $value){
                if($value != $resource AND !empty($value)){
                    $endpoint .= '/' . $value;
                }
            }
            return $endpoint . '/';
        }

        static function resource_exist(string $resource_name): bool{
            foreach(APP_RESOURCES as $value){
                if($value == $resource_name){
                    return true;
                }
            }
            
            return false;
        }

        // check if a specific endpoint exist in ENDPOINTS constanr array of specific resource, and return the
        // name of the method associated with the endpoint and an array of arguments for the method ---
        static function endpoint_exist(string $resource, string $endpoint): array{
            require '../res/'. $resource . '/endpoints.php';
            
            foreach(ENDPOINTS as $ep => $controller){
                if(self::is_dynamic($ep) == true){
                    // --- check if the given endpoint is match with the dynamic endpoint and convert it to regex ---
                    if(preg_match_all('#^'. self::to_regex($ep) .'$#', $endpoint, $data)){ 
                        return [
                            "method" => $controller,
                            "args" => self::get_args($ep, $data)
                        ];
                    }
                }

                else{
                    if($ep == $endpoint){
                        return [
                            "method" => $controller,
                            "args" =>  []
                        ];
                    }
                }
            }

            return [];
        }
 
        // check if endpoint is dynamic by verify that the endpoint contain expressions: (int) or/and (str)
        static function is_dynamic(string $endpoint): bool{
            if(preg_match('#\([a-zA-Z0-9_]+:int\)|\([a-zA-Z0-9_]+:str\)+#', $endpoint)) return true;
            else return false;
        }

        // transform dynamic endpoint to regex
        static function to_regex(string $dynamic_endpoint): string{
            $regex = $dynamic_endpoint;
            
            $regex = preg_replace('#\/#', '\/', $regex);
            $regex = preg_replace('#\(([a-zA-Z0-9_]+):int\)#', '(?<$1>[0-9]+)', $regex);
            $regex = preg_replace('#\(([a-zA-Z0-9_]+):str\)#', '(?<$1>[a-zA-Z0-9_-]+)', $regex);

            return $regex;
        }

        // force php stop handle errors and throw an ErrorException instance
        // p.e: set_error_handle('stop_error_handler')
        static function stop_error_handler($errno, $errstr, $errfile, $errline, array $errcontext){
            if (0 === error_reporting()) {
                return false; 
            }
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }

        static function get_keys($array){
            $keys = [];

            foreach($array as $k => $v){
                array_push($keys, $k);
            }

            return $keys;
        }
        
        static function get_values($array){
            $values = [];

            foreach($array as $k => $v){
                array_push($values, $v);
            }

            return $values;
        }
        
        static function array_to_string($array){
            $string = "";

            foreach($array as $k){
                $string += "";
            }

            return $string;
        }

        static function json_validator($data=NULL) {
            if(!empty($data)) {
                json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
            }
            return true;
        }

        static function get_request_data(){
            $json_data = file_get_contents('php://input');

            if(Utils::json_validator($json_data) == false)
                throw new JsonException("your json content contain errors");
        
            $request_data = json_decode($json_data, true);

            if(empty($request_data) && !empty($_POST)){
                $request_data = $_POST;
            }

            return $request_data;
            
        }

    }