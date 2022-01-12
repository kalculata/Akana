<?php
    namespace App;

    use Akana\Response;

    class RootController{
        static function get(): Response{
            return new Response([[
                "Framework" => "Akana",
                "Creaded by" => "Kubwacu Entreprise",
                "Version" => "1.0.0",
                "Release at" => "12/01/2022"]], 
            STATUS_200_OK);
        }
    }
