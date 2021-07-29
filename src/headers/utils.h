#ifndef DEF_UTILS
#define DEF_UTILS

#include <iostream>
#include <string>

using namespace std;

class Utils{
    public:
        static bool name_is_valid(string name);

        static string to_camel_case(string value);

        static bool file_exist(string file);

        static void create_folder(string name);

        static void create_file(string name);
};

#endif