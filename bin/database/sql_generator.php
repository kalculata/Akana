<?php
  namespace Akana\Database;

  use Akana\ORM\Column;
  use Akana\ORM\Migration;

  class SqlGenerator {
    public static function table_creation($table_name, $table_class) {
      $table_obj = new $table_class();

      $query = "CREATE TABLE IF NOT EXISTS `$table_name` (`id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY";
      $many_to_many_jts = "";

      foreach($table_obj->getTableColumns() as $col) {       
        $sql = self::column_to_sql($table_name, $col);

        if($col->getType() == Column::MANY_TO_MANY) {
          $many_to_many_jts .= $sql;
        }
        else {
          $query .= ", $sql";
        }
      }
      $query .= ");";

      return $query.$many_to_many_jts;
    }

    public static function column_to_sql($table_name, Column $col) {
      $col_name = $col->getName();
      $col_limit = $col->getLimit();

      $not_empty_constraint = " CONSTRAINT $table_name"."_"."$col_name"."_not_empty CHECK(LENGTH(`$col_name`) > 0)";
      $nullable = ($col->getNullable())? " NULL" : " NOT NULL";
      $unique = ($col->getUnique())? " UNIQUE" : "";

      if($col->getType() == "date" || $col->getType() == "datetime") {
        $default = ($col->getDefault() != NULL)? $col->getDefault() : "";
      } else {
        $default = ($col->getDefault() != NULL)? " DEFAULT ".$col->getDefault() : "";
      }

      if($col->getType() == 'string' || $col->getType() == 'text') {
        if(!$col->getNullable()) {
          $nullable .= ",$not_empty_constraint";
        }
      }
      
      switch ($col->getType()) {
        case "integer": 
          return "`$col_name` INTEGER($col_limit)".$nullable.$default.$unique;
          break;

        case "string":
          return "`$col_name` VARCHAR($col_limit)".$unique.$default.$nullable;
          break;

        case "text":
          return "`$col_name` TEXT".$unique.$default.$nullable;
          break;

        case "boolean":
          return "`$col_name` BOOLEAN".$nullable.$default;
          break;

        case "date":
          return "`$col_name` DATETIME".$nullable.$default;
          break;

        case "datetime":
          return "`$col_name` DATETIME".$nullable.$default;
          break;
        case "json":
          return "`$col_name` JSON".$nullable;
          break;

        case Column::ONE_TO_ONE:
          $rel_table = Migration::class_to_table($col->getRelClass());
          return "`$col_name` INTEGER".$nullable.$default." UNIQUE, FOREIGN KEY(`$col_name`) REFERENCES $rel_table(id)";
          break;

        case Column::ONE_TO_MANY:
          $rel_table = Migration::class_to_table($col->getRelClass());
          return "`$col_name` INTEGER".$nullable.$default.", FOREIGN KEY(`$col_name`) REFERENCES $rel_table(id)";
          break;

        case Column::MANY_TO_MANY:
          $rel_table = Migration::class_to_table($col->getRelClass());
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
          INSERT INTO junction_tables(relation_name, table_a, table_b) VALUES('$col_name', '$table_name', '$rel_table');
          EOD;

          return "\n\n$jt_query \n\n$jt_register_query \n\n$jt_insert_query";
          break;
      }
    }
  }