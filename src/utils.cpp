#include <regex>
#include <map>
#include <fstream>
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
    if(Utils::create_folder("akana", project_name) == true)
        Utils::create_folder(("pages", project_name + "/akana"));
    Utils::create_folder("main", project_name);

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



    // créer un fichier
    for (pair<string, string> el: project_structure) {
        Utils::create_file(el.first);
    }
    // recuperer le contenu à mettre dans ce fichier
    // adapter le contenu au fichier du projet
    // le mettre dans le fichier

    return true;
}

bool Utils::create_folder(const string &name, const string &folder){
    string command;
    bool status;

    if(!folder.empty()){
        command = "mkdir -p " + folder + "\\" + name;
        status = system(command.c_str());
        system("rmdir \"-p\"");
    }
    else{
        command = "mkdir " + name;
        status = system(command.c_str());
    }

    return status;
}

void Utils::create_file(const string &file){
    ofstream fichier(file.c_str());
}
