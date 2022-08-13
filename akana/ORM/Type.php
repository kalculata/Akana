<?php
  namespace Akana\ORM;

  class Type {
    private $_type;
    private $_limit;
    private $_default;
    private $_nullable;

    public function __construct(string $type, int $limit, mixed $default, bool $nullable) {
      $this->_type = $type;
      $this->_limit = $limit;
      $this->_default = $default;
      $this->_nullable = $nullabe;
    }

    static public function integer(int $limit=11, int $default=null, bool $nullable=false) {
      return new Type("integer", $limit, $default, $nullable);
    }

    static public function string(int $limit=255, string $default=null, bool $nullabe=false) {
      return new Type("string", $limit, $default, $nullable);
    }

    static public function text(string $default=null, bool $nullabe=false) {
      return new Type("text", null, $default, $nullable);
    }

    static public function boolean(bool $default=null, bool $nullabe=false) {
      return new Type("boolean", null, $default, $nullable);
    }

    static public function date(bool $now_as_default=false, bool $nullabe=false) {
      $default = ($now_as_default)? "NOW" : "";
      return new Type("date", null, $default, $nullable);
    }

    static public function datetime(bool $now_as_default=false, bool $nullabe=false) {
      $default = ($now_as_default)? "NOW" : "";
      return new Type("datetime", null, $default, $nullable);
    }
  }