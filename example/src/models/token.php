<?php
    namespace Akana\Models;

    use Akana\Model;

    class Token extends Model{
        public $token;
        public $update_at;

        static public $params = [
            'token' => ['type'=> 'str', 'lenght'=>50, 'unique'=>true],
            'update_at' => ['type'=> 'datetime', 'unique'=>true],
        ];
    }