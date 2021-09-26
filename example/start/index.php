<?php
    require '../config.php';
    require '../src/status.php';  
    require '../src/utils.php';
    require '../src/database.php';
    require '../src/exceptions.php';
    require '../src/model.php';
    require '../src/serializer.php';
    require '../src/response.php';
    require '../src/main.php';
    
    
    

    use Akana\Main;
    use Akana\Utils;
    use Akana\Exceptions\JSONException;

    define('URI',  $_SERVER['REQUEST_URI']);
    define('HTTP_VERB', strtolower($_SERVER['REQUEST_METHOD']));

    $json_data = file_get_contents('php://input');

    try{
        if(Utils::json_validator($json_data) == false)
            throw new JsonException("your json content contain errors");
    }
    catch(Exception $e){
        include_once('../src/pages/error.php');
    }
    
    $request = [
        'data' => json_decode($json_data, true)
    ];

    
    // allow php to enable errors and handle them with try catch
    function stop_error_handler($errno, $errstr, $errfile, $errline, array $errcontext){
        if (0 === error_reporting()) {
            return false; 
        }
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    try {
        set_error_handler('stop_error_handler');

        echo Main::execute(URI, $request);
    } 
    catch (Exception $e) {
        include_once('../src/pages/error.php');
    }
    