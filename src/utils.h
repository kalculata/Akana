#ifndef UTILS_H
#define UTILS_H

#include <iostream>
#include <string>

using namespace std;

class Utils{
    public:
        // check if a given name respect project and resource name rules
        static bool name_isvalid(const string &name);
        
        // check if a given folder exist
        static bool folder_exist(const string &folder);

        // generate project structure
        static bool gen_project_struct(const string &project_name);

        // generate resource structure
        static bool gen_resource_struct(const string &resource_name);

        // get all commands used in the framework
        static vector<string> get_commands();

        // check if the given command exist in valid commands
        static bool command_isvalid(string command);

        // execute a command, it's good to always call this method after the method 'check_command'
        static void execute_command(string command, int arguments_length, char *arguments[]);

        static void get_name_rules();
};

#endif // !UTILS_H