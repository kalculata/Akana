#include <iostream>

#include "headers/assistant.h"

using namespace std;

void Assistant::akana_presentation(){
    cout << "Akana est un framework PHP, permettant de créer des API restful facilement.";
}

void Assistant::command_not_valid(string command){
    cout << "La commande '" << command << "' que vous avez entre n'est pas valide";
}