<?php
    namespace orders\Models;

    use Akana\Model;

    class Order extends Model{
        public $user;
        public $product;
        public $quantity;
        public $amount;
        public $delivery_address;
        public $delivery_time;
        public $ordered_at;

        public static $params = [
            'user' => ['type'=>User, 'relation'=>'one2one'],
            'product' => ['type'=>Product, 'relation'=>'many2many'],
            'quantity' => ['type'=>'int', 'default'=>1],
            'amount' => ['type'=>'int'],
            'delivery_address' => ['type'=>'json'],
            'delivery_time' => ['type'=> 'datetime', 'default'=> 'now'],
            'created_at' => ['type'=> 'datetime', 'default'=> 'now'],
        ];

    }
