<?php
    namespace users\Serializers;

    use Akana\Serializer;

    class UserSerializer extends Serializer{
        public static $rules = [
            'fields' => 'all'
        ];
    }
