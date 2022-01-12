<?php
    namespace products\Controllers;

    require API_ROOT.'/res/products/models.php';
    require API_ROOT.'/res/products/serializers.php';

    use Akana\Response;
    use products\Models\Product;
    use products\Serializers\ProductSerializer;

    class ProductsController{
        static function post(){
            $product = new Product();
            $product->save();
            $serializer = ProductSerializer::serialize($product);
            return new Response($serializer['data']);
        }
        static function get(){
            $data = Product::get_all();
            $serializer = ProductSerializer::serialize($data);

            return new Response($serializer['data'], STATUS_200_OK);
        }
    }
