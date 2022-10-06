<?php
  require_once __DIR__.'/../database/sql_generator.php';

  use Akana\Database\SqlGenerator;
  use Akana\Utils;
  use Akana\ORM\Migration;

  function setup() {
    $resources = RESOURCES;
    
    $query = "START TRANSACTION;\n\n";
    foreach($resources as $resource) {
      $classes = Utils::get_classes_in_file(__DIR__.'/../../App/'.$resource.'/tables.php');
      $models = Migration::class_to_tables($classes, $resource);

      foreach($models as $class => $table_name) {
        $query .= SqlGenerator::table_creation($table_name, $class)."\n\n";
      }
    }
    $query .= "COMMIT;";
    
    file_put_contents(__DIR__.'/../../database.sql', $query);
    echo "OK";

  } setup();
