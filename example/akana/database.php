<?php
    namespace Akana;

    use PDO;
    use Exception;
    use Akana\Exceptions\DatabaseException;
    use \ErrorException;

    class Database{
        private $_database_con;

        public function __construct(){
            $this->_database_con = $this->get_database_con();
        }

        // get database connection instance
        public function get_database_con(){
            $database = DATABASE['type'].':host='.DATABASE['host'].':'.DATABASE['port'].'; dbname='.DATABASE['name'];
            
            try{
                return new PDO($database, DATABASE['login'], DATABASE['password'], array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ));
            }
            catch(Exception $e){
                throw new DatabaseException($e->getMessage());
            }
        }

        // get one object in database using id or other column
        public function get($table, $col, $val){
            try{
                $q = NULL;
                $output_data = NULL;

                $q = $this->_database_con->prepare('SELECT * FROM '.$table.' WHERE '.$col.'=:val');
                $q->execute([':val'=> $val]);
                
                if($q->rowCount() == 0){
                    return false;
                }

                while($data = $q->fetch())
                    $output_data = $data;
                    
                $q->closeCursor();

                return $output_data;
                
            }
            catch(Exception $e){
                throw new DatabaseException($e->getMessage());
            }
        }
        // get all objets in table
        public function get_all($table){
            try{
                $q = NULL;
                $output_data = [];

                $q = $this->_database_con->query('SELECT * FROM '.$table);
                
                if($q->rowCount() == 0){
                    return $output_data;
                }

                while($data = $q->fetch())
                    array_push($output_data, $data);
                    
                $q->closeCursor();

                return $output_data;
            }
            catch(Exception $e){
                throw new DatabaseException($e->getMessage());
            }
        }

        public function delete($table, $pk): bool{
            try {
                return ($this->_database_con->exec('DELETE FROM '.$table.' WHERE pk='.$pk))? true : false;
            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }

        public function empty($table){
            $query = 'TRUNCATE TABLE '.$table;
            return $this->_database_con->exec($query)? true: false;
        }

        public function save($table, $data, $params){
            $keys = "";
            $values = "";
            $counter = 0;
            
            foreach($data as $k => $v){
                if($v != NULL){

                    if($params[$k]['type'] == "str"){
                        $v = '"'.$v.'"';
                    }

                    if($counter == 0){
                        $keys = $k;
                        $values = $v;
                    }
                    else{
                        $keys .= ",".$k;
                        $values .= ",".$v;
                    }
                }
                $counter++;
            }

            try {
                $query = 'INSERT INTO '.$table.'('.$keys.') VALUES('.$values.')';

                if($this->_database_con->exec($query)){
                    return intval($this->_database_con->lastInsertId());
                }
                else{
                    throw new DatabaseException("they was an issue while saving this object, try again");
                } 

            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }

        public function update($table, $pk, $data, $params){
            $keys = "";
            $values = "";
            $counter = 0;
            $keys_values = "";
            
            foreach($data as $k => $v){
                if($v != NULL){
                    if($params[$k]['type'] == "str"){
                        $v = '"'.$v.'"';
                    }

                    if($counter == 0){
                        $keys_values = $k."=".$v;
                    }
                    else{
                        $keys_values .= ",".$k."=".$v;
                    }
                }
                $counter++;
            }

            try {
                $query = 'UPDATE '.$table.' SET '.$keys_values.' WHERE pk='.$pk;
                $result = $this->_database_con->exec($query);
                
                if($result >= 0){
                    return $pk;
                }
                else{
                    throw new DatabaseException("they was an issue while updating this object, try again");
                } 

            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }
        
    }