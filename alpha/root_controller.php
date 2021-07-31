<?php
    namespace RootController;

    use Akana\Utils\Response;

    class RootController{
        static function get(){
            return new Response([
                [
                    "message" =>"Akana Framework",
                    "data"=> [
                        "nom"=> "Huzaifa",
                        "prenom"=> "Nimushimirimana",
                        "username"=> "kalculata"
                    ],
                ]
            ], HTTP_200_OK['code']);
        }
    }
