<?php
    namespace Akana;

    use Akana\Exceptions\ORMException;
    use Akana\Database;
    use Akana\Exceptions\ModelizationException;
    use Akana\Utils;
    use ErrorException;

    abstract class Model{
        public $pk;

        public function __construct($data = NULL){
            if ($data != NULL)
                $this->hydrate_object($data, true);
        }

        static private function get_model_fields(array $model_vars): array{
            $model_fields = [];

            foreach($model_vars as $var){
                if($var != 'params')
                    array_push($model_fields, $var);  
            }

            return $model_fields;

        }

        static private function get_table_name(String $class): String{
            $t = explode('\\', $class);
            return strtolower($t[0]).'__'.strtolower($t[count($t)-1]);
        }

        static public function validate_model(array $model): void{
            // remove pk field because it has been added automatically
            unset($model['fields'][count($model['fields'])-1]);

            $valid_types = ['int', 'str', 'datetime'];
            
            // every model field must have params
            foreach($model['fields'] as $field){
                if(!key_exists($field, $model['params'])){
                    $message = "in model '".$model['class']."' field '".$field."' doesn't have parameters.";
                    throw new ModelizationException($message);
                }
            }

            foreach($model['params'] as $field => $params){
                // type parameter
                if(!key_exists('type', $params)){
                    $message = "in model '".$model['class']."' field '".$field."' doesn't have 'type' parameter.";
                    throw new ModelizationException($message);
                }
                else{
                    if(!is_string($params['type'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'type' must be a string.";
                        throw new ModelizationException($message);
                    }
                    else{
                        if(!in_array($params['type'], $valid_types)){
                            $message = "in model '".$model['class']."' type '".$params['type']."' of field '".$field."' is not valid, use one of those: ".json_encode($valid_types);
                            throw new ModelizationException($message);  
                        }
                    }
                }

                //type = datetime
                if($params['type'] == 'datetime'){
                    if(isset($params['default']) && $params['default'] != 'now'){
                        $message = "in model '".$model['class']."' at field '".$field."' if field type is eqaul to 'datetime' default value can be only 'now'.";
                        throw new ModelizationException($message);
                    }
                    if(isset($params['max_length']) || isset($params['min_length'])){
                        $message = "in model '".$model['class']."' at field '".$field."' if field type is equal to 'datetime' parameters 'max_length' and 'min_length' are not authorized.";
                        throw new ModelizationException($message);
                    }
                }

                // nullable parameter
                if(!key_exists('nullable', $params))
                    $model['params'][$field]['nullable'] = false;
                
                else{
                    if(!is_bool($params['nullable'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'nullable' must be a boolean.";
                        throw new ModelizationException($message);
                    }
                }

                //max_length parameter
                if(isset($params['max_length'])){
                    if(!is_int($params['max_length'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'max_length' must be an integer.";
                        throw new ModelizationException($message);
                    }
                }

                //min_length parameter
                if(isset($params['min_length'])){
                    if(!is_int($params['min_length'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'min_length' must be an integer.";
                        throw new ModelizationException($message);
                    }
                }
            }

            // re-add field pk after validate the model
            array_unshift($model['fields'], 'pk');
        }

        static private function get_model(string $model_class): array{
            $model = ['class' => $model_class, 'table' => self::get_table_name($model_class)];
            $model_vars = get_class_vars($model_class);

            try{
                $model_params = $model_vars['params'];
            }
            catch(ErrorException $e){
                throw new ModelizationException("model '".$model_class."' doesn't have parameters.");
            }

            $model_fields = self::get_model_fields(Utils::get_keys($model_vars));
            $model += ['fields' => $model_fields]; 
            $model += ['params' => $model_params]; 
  
            self::validate_model($model);

            return $model;
            
        }

        // not done
        static public function data_validation(array $model, array $data): void{
            // check data respect modelization
        } 

        public function save(){
            $data = [];
            $model = self::get_model(get_called_class());
            foreach($model['fields'] as $field) {$data += [$field => $this->$field];}

            self::data_validation($model, $data);

            // //check if data are in good format
            // foreach($fields_keys as $k){
            //     if($k != "params" && $k != "pk" && $k != "id"){
            //         try{
            //             $is_nullable = $fields_params[$k]['is_nullable'];
            //         }
            //         catch(ErrorException $e){
            //             $is_nullable = false;
            //         }
            //         try{
            //             $default_value = $fields_params[$k]['default'];
            //         }
            //         catch(ErrorException $e){
            //             $default_value = NULL;   
            //         }

            //         if($default_value != NULL){
            //             continue;
            //         }

            //         if($this->$k == NULL && $is_nullable == false){
            //             throw new ORMException("field '".$k."' can not be null");
            //         }  
            //     }
            // }

            $database_con = new DataBase();
            $pk = $database_con->save($model['table'], $data, $model['fields']);

            $data =  $database_con->get($model['table'], "pk", $pk);
            call_user_func_array([$this, 'hydrate_object'], [$data]);
        }

        public function update(Array $data){
            $class = get_called_class();
            $model = self::get_model($class);

            foreach($data as $k => $v){
                if(!in_array($k, $model['fields']))
                    throw new ORMException("field '".$k."' doesn't exist in model '".$class."'");
            }
            
            $database_con = new DataBase();
            $database_con->update($model['table'], $this->pk, $data, $model['params']);
            
            $data =  $database_con->get($model['table'], "pk", $this->pk);
            call_user_func_array([$this, 'hydrate_object'], [$data]);
            
        }

        public function delete(): bool{
            $model = self::get_model(get_called_class());
            $database_con = new DataBase();

            return $database_con->delete($$model['table'], $this->pk);
        }
        
        // not done
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

        // not done
        static public function get($value){
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

        static public function get_all(){
            $output_data = [];
            $model = self::get_model(get_called_class());

            $database_con = new DataBase();
            $data =  $database_con->get_all($model['table']);

            if(!empty($data)){
                for($i=0; $i<count($data); $i++){
                    $object = new $model['class']();
                    call_user_func_array([$object, 'hydrate_object'], [$data[$i]]);
                    array_push($output_data, $object);
                }
            }

            return $output_data;

        }

        static public function delete_all(): bool{
            $model = self::get_model(get_called_class());
            $database_con = new DataBase();

            return $database_con->empty($model['table']);
        }  
    }
