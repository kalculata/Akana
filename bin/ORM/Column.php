<?php
  namespace Akana\ORM;

  class Column {
    private $_name;
    private $_type;
    private $_limit;
    private $_default;
    private $_nullable;

    public function __construct(string $name, string $type, mixed $limit, mixed $default, bool $nullable) {
      $this->_name = $name;
      $this->_type = $type;
      $this->_limit = $limit;
      $this->_default = $default;
      $this->_nullable = $nullable;
    }

    public function getName() { return $this->_name; }
    public function getType() { return $this->_type; }
    public function getLimit() { return $this->_limit; }
    public function getDefault() { return $this->_default; }
    public function getNullable() { return $this->_nullable; }

    public function get_sql() {
      switch ($this->_type) {
        case "integer":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` INTEGER($this->_limit)".$nullable.$default;
          break;

        case "string":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` VARCHAR($this->_limit)".$nullable.$default;
          break;

        case "text":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` TEXT".$nullable.$default;
          break;

        case "boolean":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` BOOLEAN".$nullable.$default;
          break;

        case "date":
          $default = ($this->_default != NULL)? $this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` DATETIME".$nullable.$default;
          break;

        case "datetime":
          $default = ($this->_default != NULL)? $this->_default : "";;
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "`$this->_name` DATETIME".$nullable.$default;
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
  }