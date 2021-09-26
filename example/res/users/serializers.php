<?php
    namespace users\Serializers;

    use Akana\Serializer;

    class UserSerializer extends Serializer{
        // this method return fields to serializer
        public static $fields = 'all';
        
    }