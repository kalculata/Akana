#include "headers/commands.h"

#include <iostream>
#include <regex>
#include "assistant.cpp"
#include "utils.cpp"

using namespace std;

// this method contain all instructions to create of a new project
void Commands::create_project(string project_name){
    if(Utils::name_is_valid(project_name)){
        if(Utils::folder_exist(project_name)){
            cout << endl << "Try with another name for the project because there is already a folder "
                            "with the name '" << project_name << "/' in this directory." << endl;
            cout << endl;
        }
        
        else{
            // --- create a folder with the project name ---
            system(string("mkdir " + project_name).c_str());

            // --- create the project ---
            if(Utils::create_project(project_name) == true){
                cout << endl << "Your project has been successfully created." << endl;
                cout << endl << "To start the server" << endl;
                cout << "- cd " << project_name << "/" << endl;
                cout << "- akana runserver" << endl;
                cout << endl << "By default the server is started at the localhost address, port 1402 (127.0.0.1:1402), "
                                "\nbut nothing prevents you from running it on the address and port you want." << endl;
                cout << endl;
            }
            
            else{
                cout << endl << "An error occurred while creating the project, please try to create it again." << endl;
            }
        }

    }

    else{
        cout << "Project name '" << project_name << "' is not valid." << endl;
        cout << endl << "Project name rules: " << endl;
        Assistant::name_rules();
        cout << endl;

    }
}

// this method contain all instructions to add a resource in project project
void Commands::add_resource(){
    std::cout << "command to add resource" << std::endl;
}

// this method contain all instructions to runserver with the current project
void Commands::runserver(){
    std::cout << "command to runserver" << std::endl;
}

void Commands::about(){
    cout << endl << "Version    : 1.0.1 (Akana 0)" << endl;
    cout << "Release at : 29/07/2021" << endl;
    cout << "Author     : Kubwacu Entreprise" << endl;
    cout << "GitHub     : http://www.github.com/kubwacu-entreprise/akana-framework/" << endl;
    cout << endl;
}

// this method open help menu
void Commands::help(){
    Assistant::help_menu();
}

