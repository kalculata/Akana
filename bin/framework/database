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

    use PDO;
    use Exception;
    use Akana\Exceptions\DatabaseException;

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

        static public function insert_query_data(array $data, array $params): array{
            $query_data = [];
            $columns = NULL;
            $values = NULL;
            $counter = 0;
            
            foreach($data as $k => $v){
                if($v != NULL){

                    if($params[$k]['type'] == "str") $v = '"'.$v.'"';

                    if($counter == 0){$columns = $k; $values = $v;}
                    
                    else{$columns .= ",".$k; $values .= ",".$v;}
                    
                }
                $counter++;
            }
            $query_data['columns'] = $columns;
            $query_data['values'] = $values;

            return $query_data;
        }
        static public function update_query_data(array $data, array $params): string{
            $query_data = NULL;
            $counter = 0;
            
            foreach($data as $k => $v){
                if($v != NULL){
                    if($params[$k]['type'] == "str"){
                        $v = '"'.$v.'"';
                    }

                    if($counter == 0){
                        $query_data = $k."=".$v;
                    }
                    else{
                        $query_data .= ",".$k."=".$v;
                    }
                }
                $counter++;
            }

            return $query_data;
        }

        public function save(string $table, array $query_data): int{
            try {
                $query = 'INSERT INTO '.$table.'('.$query_data['columns'].') VALUES('.$query_data['values'].')';
                $result = $this->_database_con->exec($query);

                if($result)
                    return intval($this->_database_con->lastInsertId());
                
                else
                    throw new DatabaseException("they was an issue while saving this object, try again");

            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }

        // get one object in database using id or other column
        public function get($table, $col, $val){
            try{
                $query = 'SELECT * FROM '.$table.' WHERE '.$col.'=:val';
                $output_data = NULL;

                $q = $this->_database_con->prepare($query);
                $q->execute([':val'=> $val]);
                
                if($q->rowCount() > 0){
                    while($data = $q->fetch())
                        $output_data = $data;
                        
                    $q->closeCursor();

                    return $output_data;
                }
                
            }
            catch(Exception $e){
                throw new DatabaseException($e->getMessage());
            }
        }
        // get all objets in table
        public function get_all(string $table){
            try{
                $query = 'SELECT * FROM '.$table;
                $output_data = [];

                $q = $this->_database_con->query($query);
                
                if($q->rowCount() > 0){
                    while($data = $q->fetch())
                        array_push($output_data, $data);
                        
                    $q->closeCursor();
                }

                return $output_data;
            }
            catch(Exception $e){
                throw new DatabaseException($e->getMessage());
            }
        }

        public function delete(string $table, int $pk): bool{
            try {
                $query = 'DELETE FROM '.$table.' WHERE pk='.$pk;
                return ($this->_database_con->exec($query))? true : false;
            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }

        public function empty(string $table): bool{
            $query = 'TRUNCATE TABLE '.$table;
            return $this->_database_con->exec($query)? true: false;
        }

        public function update(string $table, $pk, string $query_data): int{
            try {
                $query = 'UPDATE '.$table.' SET '.$query_data.' WHERE pk='.$pk;
                $result = $this->_database_con->exec($query);
                
                if($result >= 0) 
                    return $pk;
                
                else
                    throw new DatabaseException("they was an issue while updating this object, try again");

            } 
            catch (Exception $e) {
                throw new DatabaseException($e->getMessage());
            }
        }
        
    }
