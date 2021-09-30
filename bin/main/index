<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
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

    try{
        define('URI',  $_SERVER['REQUEST_URI']);
        define('HTTP_VERB', strtolower($_SERVER['REQUEST_METHOD']));
        define('REQUEST', ['data'=>Utils::get_request_data()]);
        
        set_error_handler([Utils::class, 'stop_error_handler']);
        echo Main::execute(URI);
    }
    catch (Exception $e) {
        include_once('../src/errors_manager.php');
    }
