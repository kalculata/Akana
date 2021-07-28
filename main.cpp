#include <iostream>

#include "src/commandManager.cpp"
#include "src/errors.cpp"

using namespace std;

int main(int arguments_length, char *arguments[])
{
    // --- check if the program has been runned with no command ---
    if(arguments_length == 1){
        Assistant::akana_presentation();
    }

    else{
        // --- get the command to execute, the element at index 0 in arguments array correspond 
        // to the program name ---
        string command = arguments[1];

        // --- check if the provided command is valid ---
        CommandManager::check_command(command) ?
            // --- execute the command if it is valid --- 
            CommandManager::execute_command(command, arguments_length, arguments) : 
            // --- if it is not notice the user ---
            Errors::command_not_valid(command);
    }
}