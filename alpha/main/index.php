<?php
    require '../config.php';
    require '../akana/controller.php';
    require '../akana/status.php';  
    require '../akana/utils.php';
    require '../akana/exceptions.php';

    use Akana\Controller\Controller;

    define('URI',  $_SERVER['REQUEST_URI']);
    define('HTTP_VERB', strtolower($_SERVER['REQUEST_METHOD']));
    
    //--- execute resource ---
    try {
        // --- print the response if execution when good ---
        echo URI == '/' ? Controller::execute('/') : Controller::execute(URI);
    } catch (Exception $e) {
        include_once('../akana/pages/error.php');
    }
    
    
