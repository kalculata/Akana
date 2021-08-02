#include <iostream>

#include "../headers/assistant.h"

using namespace std;

// this method give a bref presentation of the framework
void Assistant::akana_presentation(){
    cout << endl << 
            "Akana is a small framework of PHP, used to create only simple RestFul APIs. The main philosophy\n" 
            "of the framework is that an application made by it will be made up of resources that each organizes\n" 
            "in its own folder and to get data, the resources will use their endpoints and each endpoint will be\n"
            "associated with a controller that has the role of managing the generation and access to data." << endl;

    cout << endl << "Try command 'akana help' to see list of commands." << endl; 
    cout << endl;
}

// this method print the help menu
void Assistant::help_menu(){
    cout << "Usage: akana <command>" << endl;
    cout << endl << "Commands: " << endl;
    cout << "   create-project <project_name>   : Create a new project." << endl;
    cout << "   add-resource <project_name>     : Add a resource in project." << endl;
    cout << "   runserver                       : run the server." << endl;
    cout << "   about                           : Display the version of the framework." << endl;
    cout << "   help                            : Print help menu." << endl;
    cout << endl;
}

// this method print only about menu
void Assistant::about_menu(){
    cout << endl << "Version    : 1.2.3 (Akana 0)" << endl;
    cout << "Release at : 02/08/2021" << endl;
    cout << "Author     : Kubwacu Entreprise" << endl;
    cout << "GitHub     : http://www.github.com/kubwacu-entreprise/akana_framework/" << endl;
    cout << endl;
}

// this method print project and resource name rules
void Assistant::name_rules(){
    cout << "- It must start with a letter." << endl;
    cout << "- It must end with a letter or a number." << endl;
    cout << "- It must contain only lowercase letters." << endl;
    cout << "- All special characters are not allowed except underscore '_'." << endl;
    cout << "- Maximum length is 50 characters." << endl;
}