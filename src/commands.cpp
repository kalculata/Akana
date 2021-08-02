#include <iostream>
#include <regex>

#include "../headers/commands.h"
#include "../headers/utils.h"
#include "../headers/assistant.h"

using namespace std;

// this method contain all instructions to create of a new project
void Commands::create_project(string project_name){
    if(Utils::name_is_valid(project_name)){
        if(Utils::folder_exist(project_name)){
            cout << endl << "Try with another name for the project because there is already a folder "
                            "with the name '" << project_name << "/' in the current directory." << endl;
            cout << endl;
        }
        
        else{
            // --- create a folder with the project name ---
            system(string("mkdir " + project_name).c_str());

            // --- generate all files for new akana project ---
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
                cout << endl << "An error occurred while creating the project, please delete it and try to create it again." << endl;
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
    std::cout << "Not available for moment wait for the version 1.3.0" << std::endl;
}

// this method contain all instructions to runserver with the current project
void Commands::runserver(){
    std::cout << "Not available for moment wait for the version 1.3.0" << std::endl;
}

void Commands::about(){
    Assistant::about_menu();
}

// this method open help menu
void Commands::help(){
    Assistant::help_menu();
}

