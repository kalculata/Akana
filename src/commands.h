#ifndef COMMANDS_H
#define COMMANDS_H

#include <string>

using namespace std;

class Commands{
    public:
        static void create_project(string project_name);

        static void add_resource(string resource_name);

        static void runserver();

        static void about();

        static void version();

        static void help();

    private:
        static string getVersion(){
            return "1.2.4 (Akana 1) (release at : 1/10/2021)";
        }
};

#endif // !COMMANDS_H
