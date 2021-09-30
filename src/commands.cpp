#include <iostream>
#include <regex>
#include "commands.h"
#include "utils.h"

using namespace std;

void Commands::create_project(string project_name){
    if(!Utils::name_isvalid(project_name)){
        cout << "Project name '" << project_name << "' is not valid." << endl;
        cout << endl << "Project name rules: " << endl;
        Utils::get_name_rules();
        return;
    }

    if(Utils::folder_exist(project_name)){
        cout << endl << "Try with another name for the project because there is already a folder "
                "with the name '" << project_name << "/' in the current directory." << endl << endl;
        return;
    }
        
    system(string("mkdir " + project_name).c_str());

    if(!Utils::gen_project_struct(project_name)){
        cout << endl << "An error occurred while creating the project, please delete it and try to create it again." << endl;
        return;
    } 

    cout << endl << "Your project has been successfully created." << endl;
    cout << endl << "To start the server" << endl;
    cout << "- cd " << project_name << "/" << endl;
    cout << "- akana runserver" << endl;
    cout << endl << "By default the server is started at the localhost address, port 1402 (127.0.0.1:1402), "
                    "\nbut nothing prevents you from running it on the address and port you want." << endl << endl;
}

void Commands::add_resource(string resource_name){
    if(!Utils::name_isvalid(resource_name)){
        cout << "Resource name '" << resource_name << "' is not valid." << endl;
        cout << endl << "Resource name rules: " << endl;
        Utils::get_name_rules();
        return;
    }

    if(Utils::folder_exist(resource_name)){
        cout << endl << "Try with another name for the resource because there is already a folder "
                "with the name '" << resource_name << "/' in the current directory." << endl << endl;
        return;
    }
        

    system(string("mkdir res\\" + resource_name).c_str());

    if(!Utils::gen_resource_struct(resource_name)){
        cout << endl << "An error occurred while adding the resource, please delete it and try to add it again." << endl;
        return;
    }    
            
    cout << endl << "Your resource has been successfully added." << endl;
    cout << endl << "Now add your resource in APP_RESOURCES a constant array that list all resources in"
                    "\nyour application, find APP_RESOURCES in (/project_name/config.php)." << endl;
    cout << endl;
}

void Commands::runserver(){
    std::cout << "Not available for moment wait for the version 1.2.5" << std::endl;
}

void Commands::about(){
    cout << endl << "Version    : " << Commands::getVersion() << endl;
    cout << "Author     : Kubwacu Entreprise" << endl;
    cout << "GitHub     : http://www.github.com/kubwacu-entreprise/akana_framework/" << endl;
    cout << endl;
}

void Commands::version(){
    cout << "Version: " << Commands::getVersion() << endl;
}

void Commands::help(){
    cout << endl << 
            "Akana is a PHP framework, used to create only simple RestFul APIs. The main philosophy\n" 
            "of the framework is that an application made by it will be made up of resources that each organizes\n" 
            "in its own folder and to get data, the resources will use their endpoints and each endpoint will be\n"
            "associated with a controller that has the role of managing the generation and access to data." << endl;

    cout << "Usage: akana <command>" << endl;
    cout << endl << "Commands: " << endl;
    cout << "   create-project <project_name>   : Create a new project." << endl;
    cout << "   add-resource <project_name>     : Add a resource in project." << endl;
    cout << "   runserver <address:port>        : run the server." << endl;
    cout << "   about                           : Display the information about the framework." << endl;
    cout << "   version                         : Show the version of the framework." << endl;
    cout << "   help                            : Print help menu." << endl;
    cout << endl;
}

