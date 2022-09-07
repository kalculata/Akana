<?php
  namespace Akana\ORM;

  class Column {
    private $_type;
    private $_limit;
    private $_default;
    private $_nullable;

    public function __construct(string $type, mixed $limit, mixed $default, bool $nullable) {
      $this->_type = $type;
      $this->_limit = $limit;
      $this->_default = $default;
      $this->_nullable = $nullable;
    }

    public function getType() { return $this->_type; }
    public function getLimit() { return $this->_limit; }
    public function getDefault() { return $this->_default; }
    public function getNullable() { return $this->_nullable; }

    public function get_sql() {
      switch ($this->_type) {
        case "integer":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "INTEGER($this->_limit)".$nullable.$default;
          break;

        case "string":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "VARCHAR($this->_limit)".$nullable.$default;
          break;

        case "text":
          $default = ($this->_default != NULL)? " DEFAULT '".$this->_default."'" : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "TEXT".$nullable.$default;
          break;

        case "boolean":
          $default = ($this->_default != NULL)? " DEFAULT ".$this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "BOOLEAN".$nullable.$default;
          break;

        case "date":
          $default = ($this->_default != NULL)? $this->_default : "";
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "DATETIME".$nullable.$default;
          break;

        case "datetime":
          $default = ($this->_default != NULL)? $this->_default : "";;
          $nullable = ($this->_nullable)? " NULL" : " NOT NULL";
          return "DATETIME".$nullable.$default;
          break;
       
      }
    }

    static public function integer(int $limit=11, int $default=null, bool $nullable=false) {
      return new Column("integer", $limit, $default, $nullable);
    }

    static public function string(int $limit=255, string $default=null, bool $nullable=false) {
      return new Column("string", $limit, $default, $nullable);
    }

    static public function text(string $default=null, bool $nullable=false) {
      return new Column("text", null, $default, $nullable);
    }

    static public function boolean(bool $default=null, bool $nullable=false) {
      return new Column("boolean", null, $default, $nullable);
    }

    static public function date(bool $now_as_default=false, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column("date", null, $default, $nullable);
    }

    static public function datetime(bool $now_as_default=false, bool $nullable=false) {
      $default = ($now_as_default)? " DEFAULT CURRENT_TIMESTAMP" : NULL;
      return new Column("datetime", null, $default, $nullable);
    }
  }