#include <iostream>

#include "src/assistant.cpp"
#include "src/commandManager.cpp"

using namespace std;

int main(int argc, char *argv[])
{
    if(argc > 1){
        string command = argv[1];

        if(CommandManager::check_command(command)){
            cout << "commande correct";
        }
        else{
            Assistant::command_not_valid(command);
        }
    }
    else
        Assistant::akana_presentation();
}