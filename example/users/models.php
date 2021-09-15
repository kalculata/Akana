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
    }