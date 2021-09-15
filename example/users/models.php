<?php
    namespace users\Models;

    use Akana\ORM\Models;

    class User extends Models{
        public $pk;
        public $first_name;
        public $last_name;
        public $email;
        public $password; 
        public $created_at;

        public static $params = [
            "pk" => ["type" => "int"],
            "first_name" => ["type" => "str", "max_length" => 50],
            "last_name" => ["type" => "str", "max_length" => 50],
            "email" => ["type" => "str", "max_length" => 50],
            "password" => ["type" => "str", "max_length" => 50],
            "created_at" => ["type" => "datetime"]
        ];
    }