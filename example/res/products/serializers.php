<?php
    namespace products\Serializers;

    use Akana\Serializer;

    class ProductSerializer extends Serializer{
        public static $rules = [
            'fields' => 'all'
        ];
    }

