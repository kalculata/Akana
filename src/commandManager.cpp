#include "headers/commandManager.h"

#include "commands.cpp"

bool CommandManager::check_command(string command){
    vector<string> commands = CommandManager::get_commands();
    
    for(int i=0; i<commands.size(); i++){
        if(command == commands[i])
            return true;
    }
    return false;
}

vector<string> CommandManager::get_commands(){
    vector<string> commands;

    commands.push_back("create-project");
    commands.push_back("add-resource");
    commands.push_back("runserver");
    commands.push_back("help");

    return commands;
}

void CommandManager::execute_command(string command, int arguments_length, char* arguments[]){
    if(command == "create-project"){
        if(arguments_length > 2){
            Commands::create_project();
        }
        else{
            cout << "La commande 'create-project' requis au une option correspondant au nom du project";
        }
        
    }

    else if(command == "add-resource"){
       if(arguments_length > 2){
            Commands::add_resource();
        }
        else{
            cout << "La commande 'add-resource' requis au une option correspondant au nom du resource";
        }
    }
    else if(command == "runserver"){
        Commands::runserver();
    }
    else if(command == "help"){
        Commands::help();
    }
}
