<?php
    namespace Akana\Models;

    use Akana\Model;

    class AkanaUser extends Model{
        public $username;
        public $password;
        public $token;

        public $akana_user_model_params = [
            'username' => ['type'=>'str', 'min_length'=> 3, 'max_length'=>50, 'unique'=>true],
            'password' => ['type'=> 'str', 'min_length'=> 8, 'max_length'=>50],
            'token' => ['type'=> Token::class, 'unique'=>true, 'relation'=> Model::ONE2ONE],
        ];
    }