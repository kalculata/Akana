#include "headers/errors.h"

// notice user that the command he entrered is not valid
void Errors::command_not_valid(string command){
    cout << endl << "Command: '" << command << "' doesn't exist." << endl;
    cout << endl << "Try: 'akana help' for details" << endl;
    cout << endl;
}