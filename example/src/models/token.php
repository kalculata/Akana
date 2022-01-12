<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
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