#ifndef COMMANDS_H
#define COMMANDS_H

#include <string>

using namespace std;

class Commands{
    public:
        static void create_project(string project_name);

        static void add_resource(string resource_name);

        static void about();

        static void version();

        static void help();

    private:
        static string getVersion(){
            return "Akana 1.0.0 (release at : 12/01/2022)";
        }
};

#endif // !COMMANDS_H
