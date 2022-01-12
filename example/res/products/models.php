<?php
    namespace products\Models;

    use Akana\Model;
    use users\Models\User;

    class Product extends Model{
        public $name;
        public $size;
        public $price;
        public $likes;
        public $views;
        public $created_at;
        public $description;

        public static $params = [
            'description' => ['type'=>'str', 'min_length'=> 30, 'max_length'=>100], 
            'name' => ['type'=>'str', 'min_length'=> 3, 'max_length'=>50],
            'size' => ['type'=>'str', 'min_length'=> 3, 'max_length'=>50],
            'price' => ['type'=>'int'],
            'likes' => ['type'=>'int', 'nullable' => true],
            'views' => ['type'=>'int', 'nullable' => true],
            'created_at' => ['type'=> 'datetime', 'default'=> 'now'],
        ];
    }

    class Comment extends Model{
        public $user;
        public $product;
        public $comment;
        public $is_reply;
        public $content;
        public $like;
        public $created_at;

        public static $params = [
            'user' => ['type'=>User, 'relation'=>'one2one'],
            'product' => ['type'=>Product, 'relation'=>'one2many'],
            'comment' => ['type'=>Comment, 'relation'=>'one2many'],
            'is_reply' => ['type'=>'bool', 'default'=>false],
            'content' => ['type'=>'str', 'max_length'=>'1000'],
            'like' => ['type'=>'int', 'default'=>0],
            'created_at' => ['type'=> 'datetime', 'default'=> 'now'],
        ];
    }
