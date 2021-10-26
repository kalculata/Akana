<?php
    define('ENDPOINTS', [
        '/' => 'UsersController',
        "/login/" => "LoginController",
        '/(user_id:int)/' => 'ManageUserController',
    ]);