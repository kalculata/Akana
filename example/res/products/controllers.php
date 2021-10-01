<?php
    namespace products\Controllers;

    require '../res/products/models.php';
    require '../res/products/serializers.php';

    use Akana\Response;

    // products/
    class ProductsController{
        static function post(){
            return new Response(
                [
                    'message' => 'add new product'
                ]
            );
        }

        static function get(){
            return new Response(
                [
                    'message' => 'get list of all products'
                ]
            );
        }
    }

    // products/<product_id>/
    class ManageProductController{
        static function get($product_id){
            return new Response(
                [
                    'message' => 'get a specific product'
                ]
            );
        }

        static function patch($product_id){
            return new Response(
                [
                    'message' => 'modify a specific product'
                ]
            );
        }

        static function delete($product_id){
            return new Response(
                [
                    'message' => 'delete a specific product'
                ]
            );
        }
    }
