<?php
    define('ENDPOINTS', [
        '/' => ['UsersController'],
        "/login/" =>  ["LoginController", false],
        '/(user_id:int)/' => ['ManageUserController'],
    ]);