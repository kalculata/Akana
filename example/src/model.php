<?php
    namespace Akana;

    use Akana\Exceptions\ORMException;
    use Akana\Database;
    use Akana\Utils;
    use ErrorException;

    abstract class Model{
        public $pk;

        public function __construct($data = NULL){
            if ($data != NULL)
                $this->hydrate_object($data, true);
        }

        public function save(){
            $class_name = get_called_class();
            $table = self::get_table_name($class_name);

            $fields = get_class_vars(get_called_class());
            $fields_params = $fields["params"];
            $fields_keys = Utils::get_keys($fields);

            $data = [];

            for ($i=0; $i < count($fields_keys); $i++) { 
                if($fields_keys[$i] != "params"){
                    $t = $fields_keys[$i];
                    $data += array($t => $this->$t);
                }
            }

            //check if data are in good format
            foreach($fields_keys as $k){
                if($k != "params" && $k != "pk" && $k != "id"){
                    try{
                        $is_nullable = $fields_params[$k]['is_nullable'];
        
                    }
                    catch(ErrorException $e){
                        $is_nullable = false;
                    }
                    try{
                        $default_value = $fields_params[$k]['default'];
                    }
                    catch(ErrorException $e){
                        $default_value = NULL;
                        
                    }

                    if($default_value != NULL){
                        continue;
                    }

                    if($this->$k == NULL && $is_nullable == false){
                        throw new ORMException("field '".$k."' can not be null");
                    }  
                }
            }

            $database_con = new DataBase();
            $pk = $database_con->save($table, $data, $fields_params);
           
            $data =  $database_con->get($table, "pk", $pk);

            call_user_func_array([$this, 'hydrate_object'], [$data]);

        }
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

            if(!empty($data)){
                for($i=0; $i<count($data); $i++){
                    $object = new $class_name();
                    call_user_func_array([$object, 'hydrate_object'], [$data[$i]]);
                    array_push($output_data, $object);
                }

            }

            return $output_data;

        }

        public function update(Array $data){
            $class_name = get_called_class();
            $fields = get_class_vars($class_name);
            $fields_params = $fields['params'];
            $fields_keys = Utils::get_keys($fields);

            foreach($data as $k => $v){
                if(!in_array($k, $fields_keys)){
                    throw new ORMException("field '".$k."' do no exist in model '".$class_name."'");
                }
            }
            
            $table = self::get_table_name($class_name);
            $database_con = new DataBase();
            $database_con->update($table, $this->pk, $data, $fields_params);
            
            $data =  $database_con->get($table, "pk", $this->pk);
            call_user_func_array([$this, 'hydrate_object'], [$data]);
            
        }

        public function delete(): bool{
            $class_name = get_called_class();
            $table = self::get_table_name($class_name);
            $database_con = new DataBase();

            return $database_con->delete($table, $this->pk);
        }

        static function delete_all(): bool{
            $class_name = get_called_class();
            $table = self::get_table_name($class_name);
            $database_con = new DataBase();

            return $database_con->empty($table);
        }

        private function hydrate_object(Array $data, bool $ignore=false){
            $class = get_called_class();
            $fields = get_class_vars($class);

            try{
                $fields_params = $fields["params"];
            }
            catch(ErrorException $e){
                throw new ORMException("model '".$class."' doesn't params");
            }
       
            if(key_exists('id', $fields['params']) || key_exists('pk', $fields['params'])){      
                throw new ORMException("remove field 'pk' or 'id' in your model '".$class."' is inserted by default");
            }

            // check if each fields has params
            foreach($fields as $k => $v){
                if($k != "params" && $k != "pk"){
                    if(!key_exists($k, $fields_params)){
                        throw new ORMException("field '".$k."' do not have params");
                    }
                }
            }
            
            // hydrate the object with given data
            foreach($fields as $k => $v){
                try{
                    if($k != "params" && $k != "pk"){
                        try{
                            $is_nullable = $fields_params[$k]['nullable'];
                        }
                        catch(ErrorException $e){
                            $is_nullable = false;
                        }

                        try {
                            $default_value = $fields_params[$k]['default'];
                        } 
                        catch(ErrorException $e) {
                            $default_value = NULL;
                        }
                            
                        if($is_nullable == false || ($data[$k] == NULL && $default_value != NULL)
                            || ($is_nullable == true && $data[$k] != NULL)){
                            try {
                                $type = $fields_params[$k]['type'];
                            } 
                            catch (ErrorException $th) {
                                throw new ORMException("You did not specify type of field '".$k."' in it params");
                            }
                            
                            $value = $data[$k];
                            if($data[$k] != NULL){
                                if(strtolower($type) == "int" || strtolower($type) == "integer"){
                                    $value = intval($value);
                                }
                            }
                            $this->$k = $value;
                        }
                        
                    }
                }
                catch(ErrorException $e){
                    if($ignore == true){
                        continue;
                    }

                    throw new ORMException("field '".$k."' doesn't have releted data in given data");
                }
            }

            if($ignore != true){
                $this->pk = intval($data['pk']);
            }
        }

        private static function get_table_name(String $class): String{
            $t = explode('\\', $class);
            return strtolower($t[0]).'__'.strtolower($t[count($t)-1]);
        }
    }