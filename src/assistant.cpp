#include <iostream>

#include "headers/assistant.h"

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

void Assistant::help_menu(){
    cout << "Usage: akana <command>" << endl;
    cout << endl << "Commands: " << endl;
    cout << "   create-project <project_name>   : Create a new project." << endl;
    cout << "   add-resource <project_name>     : Add a resource in project." << endl;
    cout << "   runserver                       : run the server." << endl;
    cout << "   help                            : Print help menu." << endl;
    cout << endl;
}

void Assistant::name_rules(){
    cout << "- It must start with a letter." << endl;
    cout << "- It must end with a letter or a number." << endl;
    cout << "- It must contain only lowercase letters." << endl;
    cout << "- All special characters are not allowed except underscore '_'." << endl;
    cout << "- Maximum length is 50 characters." << endl;
}