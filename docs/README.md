# PROJECT DESCRIPTION
Akana is a small framework of PHP, used to create only simple RestFul APIs. The main philosophy of the framework is that an application made by it will be made up of resources that each organizes in its own folder and to get data, the resources will use their endpoints and each endpoint will be associated with a controller that has the role of managing the generation and access to data.

# PROJECT STRUCTURE
- akana/
    - pages/
        - error.php
        - home.php
    - database.php
    - exceptions.php
    - main.php
    - orm.php
    - response.php
    - status.php
    - utils.php
- main/
    - index.php
- config.php
- env.php
- root_controller.php

Here is a project main file structure created by Akana framework. The developper will create only resource and each resource it will have it own folder and files inside.

p.e if the developper decide to create 3 resource the structure will be like this:

- akana/ (...)
- main/ (...)
- resource_1/
    - controller.php
    - endpoints.php
    - models.php
    - serializers.php
- resource_2/ (...)
- resource_3/ (...)
- config.php
- env.php
- root_controller.php


# ROLE OF EACH FILE
- akana/pages/error.php : to notice the develop what when wrong
- akana/controller.php: contain all main methods of the framework
- akana/execptions.php: contain all definition of all custom exceptions
- akana/status.php: contain constants for http responses codes
- akana/utils: contain utils methods like Response
- main/index.php: the enter point of the project
- config.php: contain all configurations of the projects (configuration of resources, app name,...)
- root_controller.php: contain controller of root endpoint

# FRAMEWORK LOGIC
- the enter point of the project is in main/index.php so to run the project use the command:
`php -S 127.0.0.1:8000 -t main/`
- all configuration of the project will be in 'config.php'
- the controller of the root endpoint will be in file 'root_controller.php' inside with class RootController
- endpoint name must be between '/' p.e '/test/' expected of root endpoint '/'
- every importation is reference to 'main/index.php'
- all files in resource must be in 'namespace resource_name'
- every controller must return a Response
- a response take 2 arguments (array and int), 1 is required (for data) and the second is optional(http response code)