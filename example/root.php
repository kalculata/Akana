<?php
    namespace App;

    use Akana\Response;
    use Akana\Response\Status;

    class RootController{
        static function get(){
            return new Response([
                [
                    "Framework" => "Akana",
                    "Creaded by" => "Kubwacu Entreprise",
                    "Version" => "1.3.1",
                    "Release at" => "08/08/2021"
                ]
            ], Status::HTTP_200_OK);
        }
    }