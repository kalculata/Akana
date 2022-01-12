<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    if(isset($BRIDGE)){
        define('API_ROOT', $BRIDGE);
    }
    else{
        define('API_ROOT', '..');
    }

    require API_ROOT.'/config.php';
    require API_ROOT.'/src/status.php';  
    require_once API_ROOT.'/src/utils.php';
    require_once API_ROOT.'/src/database.php';
    require_once API_ROOT.'/src/exceptions.php';
    require_once API_ROOT.'/src/model.php';
    require_once API_ROOT.'/src/models/user.php';
    require_once API_ROOT.'/src/models/token.php';
    require_once API_ROOT.'/src/serializer.php';
    require_once API_ROOT.'/src/response.php';
    require_once API_ROOT.'/src/main.php';

    use Akana\Main;
    use Akana\Utils;

    
    if(isset($BRIDGE)){
        define('URI',  $BRIDGE_URI);
        define('HTTP_VERB', strtolower($SERVER_COPY['REQUEST_METHOD']));
        define('REQUEST', ['data'=>json_decode($BRIDGE_DATA, true)]);
            
        if(isset($SERVER_COPY['HTTP_AUTHORIZATION'])){
            define('AUTH_USER_TOKEN', $SERVER_COPY['HTTP_AUTHORIZATION']);
        }
        else{
            define('AUTH_USER_TOKEN', "");
        }
    }
    else{
        define('URI',  $_SERVER['REQUEST_URI']);
        define('HTTP_VERB', strtolower($_SERVER['REQUEST_METHOD']));
        define('REQUEST', ['data'=>Utils::get_request_data()]);
    
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            define('AUTH_USER_TOKEN', $_SERVER['HTTP_AUTHORIZATION']);
        }
        else{
            define('AUTH_USER_TOKEN', "");
        }
    }

    function main(){
        try{
            set_error_handler([Utils::class, 'stop_error_handler']);
            echo Main::execute(URI);
        } catch (Exception $e) {
            include_once(API_ROOT.'/src/errors_manager.php');
        }
    } main();
    
   