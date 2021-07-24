#include "headers/commandManager.h"

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