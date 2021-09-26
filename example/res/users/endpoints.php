<?php
    define('ENDPOINTS', [
        '/' => 'UsersController',
        '/(user_id:int)/' => 'ManageUserController',
    ]);