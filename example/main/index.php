<?php
    require '../config.php';
    require '../akana/main.php';
    require '../akana/database.php';
    require '../akana/status.php';  
    require '../akana/response.php';
    require '../akana/utils.php';
    require '../akana/exceptions.php';
    require '../akana/orm.php';

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
        include_once('../akana/pages/error.php');
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
        include_once('../akana/pages/error.php');
    }
    