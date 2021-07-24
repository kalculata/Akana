#include "headers/errors.h"

// notice user that the command he entrered is not valid
void Errors::command_not_valid(string command){
    cout << "La commande '" << command << "' que vous avez entre n'est pas valide";
}