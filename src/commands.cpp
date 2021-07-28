#include "headers/commands.h"

#include <iostream>
#include "assistant.cpp"

using namespace std;

// this method contain all instructions to create of a new project
void Commands::create_project(){
    std::cout << "command to create project" << std::endl;
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

