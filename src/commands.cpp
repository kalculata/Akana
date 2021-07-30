#include "headers/commands.h"

#include <iostream>
#include <regex>
#include "assistant.cpp"
#include "utils.cpp"

using namespace std;

// this method contain all instructions to create of a new project
void Commands::create_project(string project_name){
    // verifier s'il y a pas de dossier avec le meme nom
    // creer le fichier du project
    
    if(Utils::name_is_valid(project_name)){
        if(Utils::folder_exist(project_name)){
            cout << "Le dossier existe deja";
        }
        else{
            cout << "Le dossier n'existe pas deja";
        }

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

