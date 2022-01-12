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
    use Akana\Exceptions\AkanaException;
    use Akana\Exceptions\ModelizationException;
    use Akana\Exceptions\SerializerException;
    use Akana\Models\AkanaUser;
    use Akana\Models\Token;
    use Akana\Utils;
    use ErrorException;

    abstract class Model{
        public $pk;

        public const ONE2ONE = 1;
        public const ONE2MANY = 2;
        public const MANY2MANY = 3;

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
                    if($model['params'][$field]['type'] == "int" || (is_subclass_of($model['class'], AkanaUser::class) && $field == 'token')) 
                        $value = intval($value);

                $this->$field = $value;      
            }  
        }
    
        // save data in REQUEST['data']
        public function save(): void{
            $data = REQUEST['data'];
            $model = ModelUtils::get_model(get_called_class());
            $token = null;

            ModelUtils::data_validation($model, $data);

            if(is_subclass_of($model['class'], AkanaUser::class)){
                $token = Utils::generate_token($model['table']);
            }

            $database_con = new DataBase();
            $pk = $database_con->save($model['table'], Database::prepare_insertion($data, $model, $token));
            
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
            $database_con->update($model['table'], $this->pk, Database::prepare_update($data, $model['params']));
            
            $data =  $database_con->get($model['table'], "pk", $this->pk);
            call_user_func_array([$this, 'hydrate_object'], [$model, $data]);
        }

        public function delete(): bool{
            $model = ModelUtils::get_model(get_called_class());
            $database_con = new DataBase();

            return $database_con->delete($model['table'], $this->pk);
        }

        static public function get($value, string $table = null){
            $model = ModelUtils::get_model(get_called_class());

            if($table != null) 
                $model['table'] = $table;

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

        static public function filter(array $filters): array{
            $output_data = [];
            $model = ModelUtils::get_model(get_called_class());

            $database_con = new DataBase();
            $data =  $database_con->filter($model['table'], $filters);
            
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

        // method to authenticate user
        static public function authenticate(){
            $model = ModelUtils::get_model(get_called_class());

            if(!is_subclass_of($model['class'], AkanaUser::class)){
                $message = "class '".$model['class']."' can't use method 'authenticate' because it is not a subclass of '".AkanaUser::class."'.";
                throw new ModelizationException($message);
            }

            if(!isset(REQUEST['data']['username']) || !isset(REQUEST['data']['password']))
                throw new SerializerException("you must provide your username and password.");
            
            $filters = ['username'=>REQUEST['data']['username'], 'password'=>REQUEST['data']['password']];
            $user = call_user_func_array([$model['class'], 'filter'], array($filters));

            if(empty($user))
                throw new SerializerException("your credintials are not correct.");
            
            if(count($user) > 1)
                throw new AkanaException("there some duplicated accounts.");

            $user = $user[0];
            $token_class = $model['table'].'__token';
            $token = call_user_func_array([Token::class, 'get'], [$user->token, $token_class]);

            if($token != NULL){
                return $token->token;
            }
            else{
                return "";
            }

        }

        static public function exec_sql(string $query){
            $model = ModelUtils::get_model(get_called_class());
            $database_con = new DataBase();
            $data =  $database_con->exec_sql($query);

            if($data){
                $object = new $model['class'];
                call_user_func_array([$object, 'hydrate_object'], [$model, $data]);

                return $object;
            }
        }
    }

    abstract class ModelUtils{
        static public function get_model_fields(array $model_vars): array{
            $model_fields = [];

            foreach($model_vars as $var){
                if($var != 'params' && $var != 'akana_user_model_params')
                    array_push($model_fields, $var);  
            }

            return $model_fields;
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

            if(is_subclass_of($model_class, AkanaUser::class)){
                $model_params += $model_vars['akana_user_model_params'] + $model_params;
            }

            $model_fields = self::get_model_fields(Utils::get_keys($model_vars));
            $model += ['fields' => $model_fields]; 
            $model += ['params' => $model_params]; 
  
            self::validate_model($model);

            return $model;
        }

        static public function get_table_name(String $class): String{
            $t = explode('\\', $class);
            return strtolower($t[0]).'__'.strtolower($t[count($t)-1]);
        }

        static public function validate_model(array &$model): void{
            // remove pk field because it has been added automatically
            unset($model['fields'][count($model['fields'])-1]);

            $valid_types = ['int', 'str', 'datetime', 'email'];
            
            // every model field must have params
            foreach($model['fields'] as $field){
                if(!key_exists($field, $model['params'])){
                    $message = "in model '".$model['class']."' field '".$field."' doesn't have parameters.";
                    throw new ModelizationException($message);
                }
            }
            
            // validate type of fields
            foreach($model['params'] as $field => $params){
                // type parameter
                if(!key_exists('type', $params)){
                    $message = "in model '".$model['class']."' field '".$field."' doesn't have 'type' parameter.";
                    throw new ModelizationException($message);
                }
                
                // type parameter must be a string
                if(!is_string($params['type'])){
                    $message = "in model '".$model['class']."' at field '".$field."' parameter 'type' must be a string.";
                    throw new ModelizationException($message);
                }
                
                // a type must be valid
                if(!in_array($params['type'], $valid_types)){
                    // check if the type is not a relation
                    if(strpos($params['type'], '\\')){
                        try{
                            new $params['type']();
                        } catch(\Error $e){
                            $message = " class '".$params['type']."' used for relation in model '".$model['class']."' at field '".$field."' not found.";
                            throw new ModelizationException($message);  
                        }  
                    }
                    // type is not a relation
                    else{                
                        $message = "in model '".$model['class']."' type '".$params['type']."' of field '".$field."' is not valid, use one of those: ".json_encode($valid_types);
                            throw new ModelizationException($message);  
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
                if(!isset($params['default'])) $model['params'][$field]['default'] = NULL; 
                else{
                    if($params['default'] == null){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'default' can't be null.";
                        throw new ModelizationException($message);
                    }
                }

                // unique parameter
                if(!isset($params['unique'])) $model['params'][$field]['unique'] = false;
                else{
                    if(!is_bool($params['unique'])){
                        $message = "in model '".$model['class']."' at field '".$field."' parameter 'unique' must be a boolean.";
                        throw new ModelizationException($message);
                    }
                }
            }

            // re-add field pk after validate the model
            array_unshift($model['fields'], 'pk');
        }

        // check if given data respect the syntax specify in the model
        // throw serializerException if there is an error
        static public function data_validation(array &$model, array &$data): void{
            $serializer_errors = [];

            unset($model['fields'][0]);

            foreach($model['fields'] as $field){
                $type = $model['params'][$field]['type'];
                $nullable = $model['params'][$field]['nullable'];
                $default = $model['params'][$field]['default'];
                $unique = $model['params'][$field]['unique'];

                try{
                    $value = $data[$field];
                }
                catch(ErrorException $e){
                    $value = NULL;
                }
                
                // check if a value doesn't exist already in the table
                if($unique){
                    $user = call_user_func_array(array($model['class'], 'get'), array([$field => $value]));
                    if($user != null){
                        if(!key_exists($field, $serializer_errors))
                            $serializer_errors[$field] = [];
                    
                        array_push($serializer_errors[$field], "'".$value."' already exist.");
                    }
                }

                // check type
                if($type == "email" && !Validator::email($value)){
                    if(!key_exists($field, $serializer_errors))
                        $serializer_errors[$field] = [];
                    
                    array_push($serializer_errors[$field], "'".$value."' is not valid email.");
                }
                
                // check if non-nullable field are null
                if(!$nullable && $value == NULL && $default == NULL){
                    if($field != 'token' || !is_subclass_of($model['class'], AkanaUser::class)){
                        if(!key_exists($field, $serializer_errors))
                            $serializer_errors[$field] = [];
                        
                        array_push($serializer_errors[$field], "can't be null.");
                    }
                }
            }

            if(!empty($serializer_errors))
                throw new SerializerException(json_encode($serializer_errors));
            
            array_unshift($model['fields'], 'pk');
        }  
    }
