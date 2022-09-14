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

    public function __construct(string $name, string $type, mixed $limit, mixed $default, bool $nullable, string $rel_class=NULL) {

      $this->_name = $name;
      $this->_type = $type;
      $this->_limit = $limit;
      $this->_default = $default;
      $this->_nullable = $nullable;
      $this->_rel_class = $rel_class;
    }

    public function getName() { return $this->_name; }
    public function getType() { return $this->_type; }
    public function getLimit() { return $this->_limit; }
    public function getDefault() { return $this->_default; }
    public function getNullable() { return $this->_nullable; }
    public function getClassName() { return $this->_nullable; }
    public function getRelClass() { return $this->_rel_class; }

    public function get_sql() {
      $nullable = ($this->_nullable)? " NULL" : " NOT NULL";

      switch ($this->_type) {
        case "integer":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          return "`$this->_name` INTEGER($this->_limit)".$nullable.$default;
          break;

        case "string":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          return "`$this->_name` VARCHAR($this->_limit)".$nullable.$default;
          break;

        case "text":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          return "`$this->_name` TEXT".$nullable.$default;
          break;

        case "boolean":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          return "`$this->_name` BOOLEAN".$nullable.$default;
          break;

        case "date":
          $default = ($this->_default != NULL)? $this->_default : "";
          return "`$this->_name` DATETIME".$nullable.$default;
          break;

        case "datetime":
          $default = ($this->_default != NULL)? $this->_default : "";
          return "`$this->_name` DATETIME".$nullable.$default;
          break;
        
        case self::ONE_TO_MANY:
          $rel_table = Migration::class_to_table($this->_rel_class);
          return "`$this->_name` INTEGER".$nullable.", FOREIGN KEY(`$this->_name`) REFERENCES $rel_table(id)";
          break;
      }
    }

    static public function integer(string $name, int $limit=11, int $default=null, bool $nullable=false) {
      return new Column($name, "integer", $limit, $default, $nullable);
    }

    static public function string(string $name, int $limit=255, string $default=null, bool $nullable=false) {
      return new Column($name, "string", $limit, $default, $nullable);
    }

    static public function text(string $name, string $default=null, bool $nullable=false) {
      return new Column($name, "text", null, $default, $nullable);
    }

    static public function boolean(string $name, bool $default=null, bool $nullable=false) {
      return new Column($name, "boolean", null, $default, $nullable);
    }

    static public function date(string $name, bool $now_as_default=false, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column($name, "date", null, $default, $nullable);
    }

    static public function datetime(string $name, bool $now_as_default=false, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column($name, "datetime", null, $default, $nullable);
    }

    // --- RELATION COLUMNS ---
    static public function oneToMany(string $name, string $rel_class, int $default=null, bool $nullable=false) {
      return new Column($name, self::ONE_TO_MANY, null, $default, $nullable, $rel_class);
    }
  }