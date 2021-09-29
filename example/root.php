<?php
    namespace App;

    use Akana\Response;

    class RootController{
        static function get(){
            return new Response([[
                "Framework" => "Akana",
                "Creaded by" => "Kubwacu Entreprise",
                "Version" => "1.2.4",
                "Release at" => "1/10/2021"]], 
            STATUS_200_OK);
        }
    }
