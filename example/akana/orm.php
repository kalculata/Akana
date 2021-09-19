<?php
    namespace Akana\ORM;

    use Akana\Exceptions\ORMException;
    use Akana\Database;
    use Akana\Response\status;
    use Akana\Exceptions\NotSerializableException;
    use ErrorException;
use Exception;

abstract class Models{
        /* 
            get one data in database using id or other column
            return false if there isn't any data correspond 
        */
        static function get($value){
            if(!is_array($value) && !is_numeric($value))
                throw new ORMException("get must be array or int");
            
            if(is_array($value) && count($value) != 1)
                throw new ORMException("get must have 1 argument");
            
            $class_name = get_called_class();
            $table = self::get_table_name($class_name);
            $database_con = new DataBase();

            $col = '';
            $val = '';

            if(is_numeric($value)){
                $col = 'pk';
                $val = $value;
            }
            
            else{
                foreach($value as $k=>$v) $col = $k; $val = $v;
                
                if(is_numeric($col)){
                    $col = 'pk';
                    if(!is_numeric($val)) throw new ORMException("get if is one is must int");
                }

                else if($col == 'id')
                    $col = 'pk';
        
            }

            // get data from database
            $data =  $database_con->get($table, $col, $val);

            // check if data retrieved are not empty
            if(!$data)
                return NULL;

            // create, hydrate and return model object
            $object = new $class_name();
            call_user_func_array([$object, 'hydrate_object'], [$data]);
            return $object;

        }

        /*
            get all data in database
        */
        static function get_all(){
            $class_name = get_called_class();
            $table = self::get_table_name($class_name);
            $database_con = new DataBase();

            $data =  $database_con->get_all($table);
            $output_data = [];

            if(empty($data)){
                echo "data is empty";
            }

            if(!empty($data)){
                for($i=0; $i<count($data); $i++){
                    $object = new $class_name();
                    call_user_func_array([$object, 'hydrate_object'], [$data[$i]]);
                    array_push($output_data, $object);
                }

            }

            return $output_data;

        }

        private function hydrate_object(Array $data){
            $fields = get_class_vars(get_called_class());
            $fields_params = $fields["params"];

            // check if each fields has params
            foreach($fields as $k => $v){
                if($k != "params"){
                    if(!key_exists($k, $fields_params)){
                        throw new ORMException("field '".$k."' do not have params");
                    }
                }
            }
            
            foreach($fields as $k => $v){
                try{
                    if($k != "params"){
                        try{
                            $type = $fields_params[$k]['type'];
                        }
                        catch(ErrorException $e){
                            throw new ORMException("You did not specify type of field '".$k."' in it params");
                        }
                        $value = $data[$k];

                        if($data[$k] != NULL){
                            if(strtolower($type) == "int" || strtolower($type) == "integer"){
                                $value = intval($data[$k]);
                            }
                        }
                        $this->$k = $value;
                    }
                }
                catch(ErrorException $e){
                    $message = "field '".$k."' do not have any related field in database in table";
                    throw new ORMException($message);
                }
            }
        }

        private static function get_table_name(String $class): String{
            $t = explode('\\', $class);
            return strtolower($t[0]).'__'.strtolower($t[count($t)-1]);
        }
    }

    abstract class Serializer{
        static public function serialize($object): Array {
            // check given value is not object, only object can be serialize
            if(!is_object($object) && !is_array($object))
                throw new NotSerializableException("Only an instance of model or an array of them can be serialized"); 

            $data = ['data' => [],'status' => ''];
            
            // get serializer class, fields to serializer and current object fields
            $serialize_class = get_called_class();

            try{
                $serialize_fields = new \ReflectionProperty($serialize_class, 'fields');
                $serialize_fields = $serialize_fields->getValue();
            }
            catch (\ReflectionException $e){
                $serialize_fields = 'all';
            }

            try{
                // get object to serialized fields
                if(is_array($object))
                    $object_fields = get_class_vars(get_class($object[0]));
                else
                    $object_fields = get_class_vars(get_class($object));
            }
            catch(ErrorException $e){
                throw new ORMException("Only an instance of model or an array of them can be serialized");
            }

            if($serialize_fields == 'all'){
                // when user is serializing many data
                if(is_array($object)){
                    for($i=0; $i<count($object); $i++){
                        array_push($data['data'], self::serializer($object_fields, $object[$i]));
                    }
                }
                elseif(is_object($object)){
                    foreach($object_fields as $k => $v){
                        try{
                            if($k != "params"){
                                $data['data'][$k] = $object->$k;
                            }
                        }
                        catch(ErrorException $e){
                            $message = "field '".$k."' do not have any related field in database in table serializer";
                            throw new ORMException($message);
                        }
                    }
                }
            }
            $data['status'] = status::HTTP_200_OK;
            return $data;
        }

        static private function serializer($object_fields, $object): Array{
            $serialized_data = [];

            foreach($object_fields as $k => $v){
                try{
                    if($k != "params"){
                        $serialized_data[$k] = $object->$k;
                    }
                }
                catch(ErrorException $e){
                    $message = "field '".$k."' do not have any related field in database in table serializer";
                    throw new ORMException($message);
                }
            }

            return $serialized_data;
        }
    }