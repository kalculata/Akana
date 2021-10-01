<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    namespace Akana;

    class Response{
        private $_data;
        private $_code;

        public function __construct(array $data, int $code=200){
            $this->_data = $data;
            $this->_code = $code;
        }

        public function __toString() : string{
            $this->set_content_to_json($this->_code);
            return json_encode($this->_data);
        }

        private function set_content_to_json(int $status = STATUS_200_OK){
            http_response_code($status);
            header('Content-Type: application/json');
        }
    }
