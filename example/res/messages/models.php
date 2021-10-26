<?php
    namespace messages\Models;

    use Akana\Model;
    use users\Models\User;

    class Message extends Model{
        public $sender;
        public $receiver;
        public $content;
        public $sent_at;

        public static $params = [
            'sender' => ['type'=>User, 'relation'=> 'one2many', 'nullable'=>true],
            'receiver' => ['type'=>User, 'relation'=> 'one2many', 'nullable'=>true],
            'content' => ['type'=>'str', 'max_length'=>1000],
            'sent_at' => ['type'=> 'datetime', 'default'=> 'now'],
        ];
    }
