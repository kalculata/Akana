#include "headers/commandManager.h"

#include "commands.cpp"

// get all valid commands used in the framework
vector<string> CommandManager::get_commands(){
    vector<string> commands;

    commands.push_back("create-project");
    commands.push_back("add-resource");
    commands.push_back("runserver");
    commands.push_back("help");

    return commands;
}

// check if the given command exist in valid commands
bool CommandManager::check_command(string command){
    // --- get all commands used in the framework ---
    vector<string> valid_commands = CommandManager::get_commands();
    
    for(int i=0; i<valid_commands.size(); i++){
        if(command == valid_commands[i])
            return true;
    }

    return false;
}


// execute a command, this method will always called after the method 'check_command'
void CommandManager::execute_command(string command, int arguments_length, char* arguments[]){
    if(command == "create-project"){

        // --- check if the command has been executed at least with one option ---
        if(arguments_length > 2){
            string project_name = arguments[2];
            Commands::create_project(project_name);
        }

        // --- if the command has been executed with no option ---
        else{
            cout << "Command 'create-project' requires a parameter for the project name." << endl;
            cout << endl << "Usage: akana create-project <project_name>." << endl;
            cout << endl;
        }
        
    }

    else if(command == "add-resource"){

        // --- check if the command has been executed at least with one option ---
        if(arguments_length > 2){
            Commands::add_resource();
        }
        
        // --- if the command has been executed with no option ---
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
