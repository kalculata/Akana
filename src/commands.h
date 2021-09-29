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
};

#endif // !COMMANDS_H
