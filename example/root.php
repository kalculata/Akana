<?php
    namespace App;

    use Akana\Response;

    class STATUS_{
        static function get(){
            return new Response([
                [
                    "Framework" => "Akana",
                    "Creaded by" => "Kubwacu Entreprise",
                    "Version" => "1.3.1",
                    "Release at" => "08/08/2021"
                ]
            ], STATUS_200_OK);
        }
    }