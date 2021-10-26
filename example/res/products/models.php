<?php
    namespace products\Models;

    use Akana\Model;
    use users\Models\User;

    class Product extends Model{
        public $name;
        public $type;
        public $size;
        public $price;
        public $likes;
        public $views;
        public $created_at;

        public static $params = [
            'name' => ['type'=>'str', 'min_length'=> 3, 'max_length'=>50],
            'type' => ['type'=>'str', 'choices'=>['t-shirt', 'watch', 'glass', 'shoes']],
            'size' => ['type'=>'str', 'min_length'=> 3, 'max_length'=>50, 'nullable'=>true],
            'price' => ['type'=>'int'],
            'likes' => ['type'=>'int', 'default'=>0],
            'views' => ['type'=>'int', 'default'=>0],
            'created_at' => ['type'=> 'datetime', 'deafult'=> 'now'],
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
            'created_at' => ['type'=> 'datetime', 'deafult'=> 'now'],
        ];
    }
