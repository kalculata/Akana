#ifndef DEF_UTILS
#define DEF_UTILS

#include <iostream>
#include <string>

using namespace std;

class Utils{
    public:
        // this method check if the given respect project and resource name
        static bool name_is_valid(const string &name);
        
        // this method check if given folder exist
        static bool folder_exist(const string &folder);

        // this method generate the project structure
        static bool create_project(const string &project_name);

        // this method create file
        static void create_file(const string &name);
};

#endif