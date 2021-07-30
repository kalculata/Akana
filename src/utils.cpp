#include <regex>
#include <map>
#include <sys/stat.h>
#include "headers/utils.h"

bool Utils::name_is_valid(const string &name){
    if(regex_match(name, regex("^[a-z]*[a-z0-9_]*[a-z0-9]+$")) && name.length() <= 50){
        return true;
    }
    else{
        return false;
    }
}

// string Utils::to_camel_case(string value){

// }

bool Utils::folder_exist(const string &file){
    struct stat info;

    return (stat(file.c_str(), &info) == 0);
}

bool Utils::create_project(const string &project_name){
    map<string, string> project_structure = {
        {"akana/pages/error.php", "bin/p_1"},
        {"akana/pages/home.php", "bin/p_2"},

        {"akana/controller.php", "bin/p_3"},
        {"akana/exceptions.php", "bin/p_4"},
        {"akana/status.php", "bin/p_5"},
        {"akana/utils.php", "bin/p_6"},

        {"main/index.php", "bin/p_7"},

        {"config.php", "bin/p_8"},
        {"root_controller.php", "bin/p_9"},
        
    };
}

void Utils::create_folder(const string &name){
    string command = "mkdir " + name;
    system(command.c_str());
}

void Utils::create_file(const string &name){
    void print_map(std::string_view comment, const std::map<std::string, int>& m)
{
    std::cout << comment;
    for (const auto& [key, value] : m) {
        std::cout << key << " = " << value << "; ";
    }
    std::cout << "\n";
}
}
