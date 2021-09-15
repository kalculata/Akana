<?php
    namespace orders;

    require '../orders/models.php';

    use Akana\Response;
    use Akana\Response\Status;

    use orders\Models\Order;
    
    // orders/
    class OrdersController{
        static function post(){
            return new Response(
                [
                    'message' => 'make an order'
                ]
            );
        }

        static function get(){
            return new Response(
                [
                    'message' => 'get list of all orders'
                ]
            );
        }
    }

    // orders/<order_id>/
    class ManageOrderController{
        static function get($order_id){
            return new Response(
                [
                    'message' => 'get a specific order'
                ]
            );
        }

        static function patch($order_id){
            return new Response(
                [
                    'message' => 'modify a specific order'
                ]
            );
        }
    }
