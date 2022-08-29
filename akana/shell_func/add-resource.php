<?php
function add_resource($args) {
  if(!file_exists(__DIR__.'/../../settings.yaml')) {
    echo "[ERROR] settings.yaml file not found\n";
    return;
  } 

  $settings = spyc_load_file(__DIR__.'/../../settings.yaml');
  // $resources = $settings["resources"];

  // // check if name option has been provided
  // if(!key_exists("name", $args)) {
  //   echo "[ERROR] name option is required\n";
  //   return;
  // }

  // // check if the given resource exist
  // if(!in_array($args["name"], $resources)) {
  //   echo "[ERRO] resource '".$args["resource"]."' not found in settings";
  //   return;
  // }
  echo "register resource\n";
  echo "create folder for resource\n";
  echo "create files for resource: controller.php, routers.yaml, models.php\n";
}