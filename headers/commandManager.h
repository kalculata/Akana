#ifndef COMMANDMANAGER_H
#define COMMANDMANAGER_H

#include <vector>
#include <string>

using namespace std;

class CommandManager{
    public:
        // get all valid commands used in the framework
        static vector<string> get_commands();

        // check if the given command exist in valid commands
        static bool check_command(string command);

        // execute a command, this method will always called after the method 'check_command'
        static void execute_command(string command, int arguments_length, char *arguments[]);
};

#endif // !COMMANDMANAGER_H
