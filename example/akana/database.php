<?php
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

        
    }