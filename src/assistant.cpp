#include <iostream>

#include "headers/assistant.h"

using namespace std;

// this method give a bref presentation of the framework
void Assistant::akana_presentation(){
    cout << "Akana est un framework PHP, permettant de creer des API restful facilement.";
}

void Assistant::help_menu(){
    cout << "Usage: akana <command>" << endl;
    cout << endl << "Commands: " << endl;
    cout << "   create-project <project_name>   : create a new project" << endl;
    cout << "   add-resource <project_name>     : add a resource in project" << endl;
    cout << "   help                            : print help menu" << endl;
    cout << endl;
}