#include "headers/commands.h"

#include <iostream>
#include <regex>
#include "assistant.cpp"

using namespace std;

// this method contain all instructions to create of a new project
void Commands::create_project(string project_name){
    // verifier s'il y a pas de dossier avec le meme nom
    // creer le fichier du project
    if(regex_match(project_name, regex("^[a-z]*[a-z0-9_]*[a-z0-9]+$"))){
        cout << "let create the project";
    }
    else{
        cout << "Project name: '" << project_name << "' is not valid." << endl;
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

// this method open help menu
void Commands::help(){
    Assistant::help_menu();
}

