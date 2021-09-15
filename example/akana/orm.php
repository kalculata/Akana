<?php
    namespace Akana\ORM;

    use Akana\Exceptions\ORMException;
    use Akana\Database;
    use Akana\Response\status;
    use Akana\Exceptions\NotSerializableException;
    use Akana\Utils;
    use ErrorException;

    abstract class Models{
        /* 
        get one data in database using id or other column
        return false if there isn't any data correspond 
        */
        static function get($value){
            // value must be array or int
            if(!is_array($value) && !is_numeric($value))
                throw new ORMException("get must be array or int");
            
            
            // if it an array it must have one element
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
                        $this->$k = $data[$k];
                    }
                }
                catch(\ErrorException $e){
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
            if(!is_object($object))
                throw new NotSerializableException("Before serialize check if object from databas is not empty"); 

            $data = [
                'data' => [],
                'status' => ''
            ];
            
            // get serializer class, fields to serializer and current object fields
            $serialize_class = get_called_class();

            try{
                $serialize_fields = new \ReflectionProperty($serialize_class, 'fields');
                $serialize_fields = $serialize_fields->getValue();
            }
            catch (\ReflectionException $e){
                $serialize_fields = 'all';
            }
            $object_fields = get_class_vars(get_class($object));

            // if fields method return all fields in serialized data
            if($serialize_fields == 'all'){
                foreach($object_fields as $k => $v){
                    try{
                        if($k != "params"){
                            $data['data'][$k] = $object->$k;
                        }
                    }
                    catch(\ErrorException $e){
                        $message = "field '".$k."' do not have any related field in database in table serializer";
                        throw new ORMException($message);
                    }
                }
            }
            $data['status'] = status::HTTP_200_OK;
            return $data;
        }
    }