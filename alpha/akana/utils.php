<?php
    namespace Akana\Utils;

    use Akana\Controller\Controller;

    class Response{
        private $_data;
        private $_code;

        public function __construct($data, $code){
            $this->_data = $data;
            $this->_code = $code;
        }

        public function __toString() : string{
            Controller::set_content_to_json($this->_code);

            return json_encode($this->_data);
        }
    }
