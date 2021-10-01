<?php
    define('ENDPOINTS', [
       '/' => 'OrdersController',
       '/(order_id:int)/' => 'ManageOrderController' 
    ]);
