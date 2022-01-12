<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    namespace Akana\Exceptions;

    use Exception;

    interface ExceptionInterface{
        public function getMessage();
        public function getCode();
        public function getFile();
        public function getLine();
        public function getTrace();
        public function getTraceAsString();

        public function __toString();
        public function __construct($message=null, $code=0);
    }

    abstract class CustomException extends Exception implements ExceptionInterface{
        protected $message;
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
        protected $file;
        protected $line;
        protected $trace;

        public function __construct($message=null, $code=0){
            parent::__construct($message, $code);
        }
    }

    class NoRootEndpointException extends CustomException{
        protected $code = STATUS_404_NOT_FOUND;
    }

    class EmptyAppResourcesException extends CustomException{
        protected $code = STATUS_404_NOT_FOUND;
    }

    class ResourceNotFoundException extends CustomException{
        protected $code = STATUS_404_NOT_FOUND;
    }

    class EndpointNotFoundException extends CustomException{
        protected $code = STATUS_404_NOT_FOUND;
    }

    class HttpVerbNotAuthorizedException extends CustomException{
        protected $code = STATUS_401_UNAUTHORIZED;
    }
    class SerializerException extends CustomException{
        protected $code = STATUS_400_BAD_REQUEST;
    }  
    class JSONException extends CustomException{
        protected $code = STATUS_400_BAD_REQUEST;
    } 

    class ControllerNotFoundException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

    class AkanaException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

    class DatabaseException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

    class ModelizationException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

    class SerializationException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

    class AuthentificationException extends CustomException{
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
    }

 
