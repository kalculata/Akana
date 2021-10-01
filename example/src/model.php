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

    use Akana\Database;
    use Akana\Exceptions\ModelizationException;
    use Akana\Exceptions\SerializerException;
    use Akana\Utils;
    use ErrorException;

    abstract class Model{
        public $pk;

        private function hydrate_object(array $model, array $data){     
            foreach($model['fields'] as $field){
                if($field == 'pk'){
                    $this->pk = intval($data['pk']);
                    continue;
                }
                
                try{
                    $value = $data[$field];
                }
                catch(ErrorException $e){
                    $message = "in model '".$model['class']."' field '".$field."' doesn't exist in database on table '".$model['table']."'.";
                    throw new ModelizationException($message);
                }
                

                if($value != NULL)
                    if($model['params'][$field]['type'] == "int") 
                        $value = intval($value);

                $this->$field = $value;      
            }  
        }
    
        public function save(): void{
            $data = REQUEST['data'];
            $model = ModelUtils::get_model(get_called_class());

            ModelUtils::data_validation($model, $data);

            $database_con = new DataBase();
            $pk = $database_con->save($model['table'], Database::insert_query_data($data, $model['params']));
            
            $data =  $database_con->get($model['table'], "pk", $pk);
            call_user_func_array([$this, 'hydrate_object'], [$model, $data]);            
        }

        public function update(Array $data){
            $class = get_called_class();
            $model = ModelUtils::get_model($class);

            foreach($data as $k => $v){
                if(!in_array($k, $model['fields']))
                    throw new SerializerException("field '".$k."' doesn't exist in model '".$class."'");
            }
            
            $database_con = new DataBase();
            $database_con->update($model['table'], $this->pk, Database::update_query_data($data, $model['params']));
            
            $data =  $database_con->get($model['table'], "pk", $this->pk);
            call_user_func_array([$this, 'hydrate_object'], [$model, $data]);
        }

        public function delete(): bool{
            $model = ModelUtils::get_model(get_called_class());
            $database_con = new DataBase();

            return $database_con->delete($model['table'], $this->pk);
        }

        static public function get($value){
            $model = ModelUtils::get_model(get_called_class());
            $message = "method 'get' of class 'Model' can receive one parameter and it can be array with length 1 or integer.";

            if(!is_array($value) && !is_numeric($value))
                throw new ModelizationException($message);
            
            if(is_array($value) && count($value) != 1)
                throw new ModelizationException($message);
            
            $database_con = new DataBase();

            $col = '';
            $val = '';

            if(is_numeric($value)){
                $col = 'pk';
                $val = $value;
            }
            
            else{
                foreach($value as $k=>$v) 
                    $col = $k; 
                    $val = $v;
                
                if(is_numeric($col)){
                    $col = 'pk';
                    if(!is_numeric($val)) throw new ModelizationException($message);
                }

                else if($col == 'id')
                    $col = 'pk';
        
            }

            $data =  $database_con->get($model['table'], $col, $val);

            if($data){
                $object = new $model['class'];
                call_user_func_array([$object, 'hydrate_object'], [$model, $data]);

                return $object;
            }

        }

        static public function get_all(){
            $output_data = [];
            $model = ModelUtils::get_model(get_called_class());

            $database_con = new DataBase();
            $data =  $database_con->get_all($model['table']);

        
            if(!empty($data)){
                for($i=0; $i<count($data); $i++){
                    $object = new $model['class']();
                    call_user_func_array([$object, 'hydrate_object'], [$model, $data[$i]]);
                    array_push($output_data, $object);
                }
            }

            return $output_data;
        }

        static public function delete_all(): bool{
            $model = ModelUtils::get_model(get_called_class());
            $database_con = new DataBase();

            return $database_con->empty($model['table']);
        }  
    }

    abstract class ModelUtils{
        static public function get_model_fields(array $model_vars): array{
            $model_fields = [];

            foreach($model_vars as $var){
                if($var != 'params')
                    array_push($model_fields, $var);  
            }

            return $model_fields;
        }

        static public function get_table_name(String $class): String{
            $t = explode('\\', $class);
            return strtolower($t[0]).'__'.strtolower($t[count($t)-1]);
        }

        static public function validate_model(array &$model): void{
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

                // type = datetime
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

                // max_length parameter
                if(isset($params['max_length'])){
                    if(!is_int($params['max_length'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'max_length' must be an integer.";
                        throw new ModelizationException($message);
                    }
                }

                // min_length parameter
                if(isset($params['min_length'])){
                    if(!is_int($params['min_length'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'min_length' must be an integer.";
                        throw new ModelizationException($message);
                    }
                }

                // default parameter
                if(!isset($params['default']))
                    $model['params'][$field]['default'] = NULL;
                    
                else{
                    if($params['default'] == NULL){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'default' can not be null.";
                        throw new ModelizationException($message);
                    }
                }
            }

            // re-add field pk after validate the model
            array_unshift($model['fields'], 'pk');
        }

        static public function get_model(string $model_class): array{
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

        static public function data_validation(array &$model, array &$data): void{
            $serializer_errors = [];

            unset($model['fields'][0]);

            foreach($model['fields'] as $field){
                $type = $model['params'][$field]['type'];
                $nullable = $model['params'][$field]['nullable'];
                $default = $model['params'][$field]['default'];

                try{
                    $value = $data[$field];
                }
                catch(ErrorException $e){
                    $value = NULL;
                }

                if(!$nullable && $value == NULL && $default == NULL){
                    if(!key_exists($field, $serializer_errors))
                        $serializer_errors[$field] = [];
                    
                    array_push($serializer_errors[$field], "can't be null.");
                }
            }

            if(!empty($serializer_errors))
                throw new SerializerException(json_encode($serializer_errors));
            
            array_unshift($model['fields'], 'pk');
        }  
    }
