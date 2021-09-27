<?php
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

        public function getName();
        public function getLevel();
    }

    abstract class CustomException extends Exception implements ExceptionInterface{
        protected $message;
        protected $code = STATUS_500_INTERNAL_SERVER_ERROR;
        protected $file;
        protected $line;
        protected $trace;
        protected $name = 'CustomException';
        protected $level = 'low';

        public function __construct($message=null, $code=0){
            parent::__construct($message, $code);
        }

        public function getName(){
            return $this->name;
        }
        public function getLevel(){
            return $this->level;
        }
    }

    class NoRootEndpointException extends CustomException{
        protected $name = 'NoRootEndpointException';
        protected $code = STATUS_404_NOT_FOUND;
    }

    class EmptyAppResourcesException extends CustomException{
        protected $name = 'EmptyAppResourcesException';
        protected $code = STATUS_404_NOT_FOUND;
    }

    class ResourceNotFoundException extends CustomException{
        protected $name = 'ResourceNotFoundException';
        protected $code = STATUS_404_NOT_FOUND;
    }

    class EndpointNotFoundException extends CustomException{
        protected $name = 'EndpointNotFoundException';
        protected $code = STATUS_404_NOT_FOUND;
    }

    class HttpVerbNotAuthorizedException extends CustomException{
        protected $name = 'HttpVerbNotAuthorizedException';
        protected $code = STATUS_401_UNAUTHORIZED;
    }
    class SerializerException extends CustomException{
        protected $name = 'SerializerException';
        protected $code = STATUS_400_BAD_REQUEST;
    }  
    class JSONException extends CustomException{
        protected $name = 'JSONException';
        protected $code = STATUS_400_BAD_REQUEST;
    } 

    class ControllerNotFoundException extends CustomException{
        protected $name = 'ControllerNotFoundException';
        protected $level = 'hight';
    }

    class MethodNotStaticException extends CustomException{
        protected $name = 'MethodNotStaticException';
        protected $level = 'hight';
    }

    class DatabaseException extends CustomException{
        protected $name = 'DatabaseException';
        protected $level = 'hight';
    }

    class ORMException extends CustomException{
        protected $name = 'ORMException';
        protected $level = 'hight';
    }

    class ModelizationException extends CustomException{
        protected $name = 'ModelizationException';
        protected $level = 'hight';
    }

    class NotSerializableException extends CustomException{
        protected $name = 'NotSerializableException';
        protected $level = 'hight';
    }
 