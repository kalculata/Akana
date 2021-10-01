<?php
    namespace orders\Controllers;

    require '../res/orders/models.php';
    require '../res/orders/serializers.php';

    use Akana\Response;
    
    // orders/
    class OrdersController{
        static function post(){
            return new Response(['message' => 'make an order']);
        }

        static function get(){
            return new Response(['message' => 'get list of all orders']);
        }
    }

    // orders/<order_id>/
    class ManageOrderController{
        static function get($order_id){
            return new Response(['message' => 'get a specific order']);
        }

        static function patch($order_id){
            return new Response(['message' => 'modify a specific order']);
        }
    }
