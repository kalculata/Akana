#ifndef DEF_UTILS
#define DEF_UTILS

#include <iostream>
#include <string>

using namespace std;

class Utils{
    public:
        static bool name_is_valid(const string &name);

        static string to_camel_case(string value);

        static bool folder_exist(const string &file);

        static bool create_project(const string &project_name);

        static bool create_folder(const string &name, const string &folder="");

        static void create_file(const string &name);
};

#endif