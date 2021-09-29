#include <iostream>
#include <vector>
#include "src/utils.h"
#include "src/commands.h"

using namespace std;


int main(int arguments_length, char *arguments[]){
    // check if the program has been runned with no command 
    if(arguments_length == 1) Commands::help();
    

    else{
        // get the command to execute, the element at index 0 in arguments array correspond to the program name 
        string command = arguments[1];

        if(Utils::command_isvalid(command)) 
            Utils::execute_command(command, arguments_length, arguments);
        
        else{
            cout << "Command: '" << command << "' doesn't exist." << endl;
            cout << endl << "Try command: 'akana help' to see list of commands." << endl << endl;
        }   
    }
}