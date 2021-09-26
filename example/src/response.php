<?php
    namespace Akana;

    use Akana\Utils;

    class Response{
        private $_data;
        private $_code;

        public function __construct(array $data, int $code=200){
            $this->_data = $data;
            $this->_code = $code;
        }

        public function __toString() : string{
            Utils::set_content_to_json($this->_code);
            return json_encode($this->_data);
        }
    }