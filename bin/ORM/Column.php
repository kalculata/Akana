<?php
  namespace Akana\ORM;

  use Akana\ORM\Migration;

  class Column {
    const ONE_TO_ONE = "ONE_TO_ONE";
    const ONE_TO_MANY = "ONE_TO_MANY";
    const MANY_TO_MANY = "MANY_TO_MANY";

    private $_name;
    private $_type;
    private $_limit;
    private $_default;
    private $_nullable;
    private $_rel_class;
    private $_unique;

    public function __construct(
      string $name, string $type, mixed $limit, mixed $default, bool $nullable, string $rel_class=NULL, bool $unique=false) {

      $this->_name = $name;
      $this->_type = $type;
      $this->_limit = $limit;
      $this->_default = $default;
      $this->_nullable = $nullable;
      $this->_rel_class = $rel_class;
      $this->_unique = $unique;
    }

    public function getName() { return $this->_name; }
    public function getType() { return $this->_type; }
    public function getLimit() { return $this->_limit; }
    public function getDefault() { return $this->_default; }
    public function getNullable() { return $this->_nullable; }
    public function getClassName() { return $this->_nullable; }
    public function getRelClass() { return $this->_rel_class; }
    public function getUnique() { return $this->_unique; }

    public function get_sql($table_name) {
      $not_empty_constraint = " CONSTRAINT $table_name"."_"."$this->_name"."_not_empty CHECK(LENGTH($this->_name) > 0)";
      $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
      $unique = ($this->_unique)? " UNIQUE" : "";

      if($this->_type == "date" || $this->_type == "datetime") {
        $default = ($this->_default != NULL)? $this->_default : "";
      } else {
        $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
      }
      
      switch ($this->_type) {
        case "integer": 
          return "`$this->_name` INTEGER($this->_limit)".$nullable.$default.$unique;
          break;

        case "string":
          if(!$this->_nullable) { $nullable .= $not_empty_constraint; }
          return "`$this->_name` VARCHAR($this->_limit)".$nullable.$default.$unique;
          break;

        case "text":
          if(!$this->_nullable) { $nullable .= $not_empty_constraint; }
          return "`$this->_name` TEXT".$nullable.$default.$unique;
          break;

        case "boolean":
          return "`$this->_name` BOOLEAN".$nullable.$default;
          break;

        case "date":
          return "`$this->_name` DATETIME".$nullable.$default;
          break;

        case "datetime":
          return "`$this->_name` DATETIME".$nullable.$default;
          break;
        case "json":
          return "`$this->_name` JSON".$nullable;
          break;
        case self::ONE_TO_ONE:
          $rel_table = Migration::class_to_table($this->_rel_class);
          return "`$this->_name` INTEGER".$nullable.$default." UNIQUE, FOREIGN KEY(`$this->_name`) REFERENCES $rel_table(id)";
          break;
        case self::ONE_TO_MANY:
          $rel_table = Migration::class_to_table($this->_rel_class);
          return "`$this->_name` INTEGER".$nullable.$default.", FOREIGN KEY(`$this->_name`) REFERENCES $rel_table(id)";
          break;
        case self::MANY_TO_MANY:
          $rel_table = Migration::class_to_table($this->_rel_class);
          $junction_tab_name = "jt_".$table_name."_".$rel_table;

          $col1 = $table_name."_id";
          $col2 = $rel_table."_id";

          $jt_query = <<<EOD
          DROP TABLE IF EXISTS $junction_tab_name;
          CREATE TABLE $junction_tab_name (
            $col1 INTEGER NOT NULL, $col2 INTEGER NOT NULL,
            FOREIGN KEY($col1) REFERENCES $table_name(id),
            FOREIGN KEY($col2) REFERENCES $rel_table(id),
            UNIQUE ($col1, $col2)
          );
          EOD;

          $jt_register_query = <<<EOD
          CREATE TABLE IF NOT EXISTS junction_tables (
            id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
            relation_name VARCHAR(100) NOT NULL UNIQUE,
            table_a VARCHAR(256) NOT NULL,
            table_b VARCHAR(256) NOT NULL
          );
          EOD;


          $jt_insert_query = <<<EOD
            INSERT INTO junction_tables(relation_name, table_a, table_b) VALUES('$this->_name', '$table_name', '$rel_table');
          EOD;
          
          ORM::query($jt_register_query);
          ORM::query($jt_query);
          ORM::exec($jt_insert_query);
          return null;
          break;
      }
    }

    static public function integer(string $name, int $limit=11, int $default=null, bool $nullable=false, bool $unique=false) {
      return new Column($name, "integer", $limit, $default, $nullable, null, $unique);
    }

    static public function string(string $name, int $limit=255, string $default=null, bool $nullable=false, bool $unique=false) {
      if($unique) { $limit = 100; }
      return new Column($name, "string", $limit, $default, $nullable, null, $unique);
    }

    static public function text(string $name, string $default=null, bool $nullable=false) {
      return new Column($name, "text", null, $default, $nullable, null, false);
    }

    static public function boolean(string $name, bool $default=null, bool $nullable=false) {
      return new Column($name, "boolean", null, $default, $nullable);
    }

    static public function date(string $name, bool $now_as_default=true, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column($name, "date", null, $default, $nullable);
    }

    static public function datetime(string $name, bool $now_as_default=true, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column($name, "datetime", null, $default, $nullable);
    }

    static public function json(string $name, bool $nullable=false) {
      return new Column($name, "json", null, null, $nullable);
    }

    // --- RELATION COLUMNS ---
    static public function oneToOne(string $name, string $rel_class, int $default=null, bool $nullable=false) {
      return new Column($name, self::ONE_TO_ONE, null, $default, $nullable, $rel_class);
    }

    static public function oneToMany(string $name, string $rel_class, int $default=null, bool $nullable=false) {
      return new Column($name, self::ONE_TO_MANY, null, $default, $nullable, $rel_class);
    }

    static public function ManyToMany(string $rel_class, string $relation_name) {
      return new Column($relation_name, self::MANY_TO_MANY, null, null, false, $rel_class, false);
    }

    // --- UTILS ---
    static public function getRequiredCols(array $columns) {
      $required_columns = [];

      foreach($columns as $col) {
        if(!$col->getNullable() && $col->getDefault() == NULL) {
          if($col->getType() != self::MANY_TO_MANY) {
            array_push($required_columns, $col->getName());
          }
        }
      }

      return $required_columns;
    }

    static public function getColsName(array $columns) {
      $cols_name = [];

      foreach($columns as $col) {
        array_push($cols_name, $col->getName());
      }

      return $cols_name;
    }

    static public function check_type($field, $value, $cols) {
      foreach($cols as $col) {
        if($field == $col->getName()) {
          if($col->getType() == "boolean" && is_bool($value)) {
            continue;
          }

          if($col->getNullable() == false && $value == null) {
            return "Field '$field' connot be null or empty $value";
          }
          else if($col->getType() == 'json') { 
            continue;
          }
          else if($col->getType() == 'date' || $col->getType() == 'datetime') {
            if(!strtotime($value)) {
              return "Field '$field' must be of type 'date'";
            }
          }
          else if(!in_array($col->getType(), [self::ONE_TO_ONE, self::ONE_TO_MANY, self::MANY_TO_MANY]) && gettype($value) != $col->getType()) {
            return "Field '$field' must be of type '".$col->getType()."'";
          } 
          else if(in_array($col->getType(), [self::ONE_TO_ONE, self::ONE_TO_MANY, self::MANY_TO_MANY])) {
            if(gettype($value) != "integer") {
              return "Field '$field' is relation field and it must be of type 'integer'";
            }
            else {
              $table_name = Migration::class_to_table($col->getRelClass());
              $tab = new Table($table_name);
              if(!$tab->get($value)) {
                return "$field with id $value not found";
              }
            }
            
          }
        }
      }
    }
  }