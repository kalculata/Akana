#include <regex>
#include <map>
#include <fstream>
#include <sys/stat.h>

#include "../headers/utils.h"

// this method check if the given respect project and resource name
bool Utils::name_is_valid(const string &name){
    if(regex_match(name, regex("^[a-z]*[a-z0-9_]*[a-z0-9]+$")) && name.length() <= 50) return true;
    else return false;
    
}

// this method check if given folder exist
bool Utils::folder_exist(const string &file){
    struct stat info;

    return (stat(file.c_str(), &info) == 0);
}

// this method generate the project structure
bool Utils::create_project(const string &project_name){

    // --- this map contain all files to generate for a new project with their content ---
    map<string, string> project_structure = {
        {project_name + "/akana/pages/error.php", "bin/p_1"},
        {project_name + "/akana/pages/home.php", "bin/p_2"},

        {project_name + "/akana/main.php", "bin/p_3"},
        {project_name + "/akana/response.php", "bin/p_4"},
        {project_name + "/akana/exceptions.php", "bin/p_5"},
        {project_name + "/akana/status.php", "bin/p_6"},
        {project_name + "/akana/utils.php", "bin/p_7"},

        {project_name + "/main/index.php", "bin/p_8"},

        {project_name + "/config.php", "bin/p_9"},
        {project_name + "/root_controller.php", "bin/p_10"},
        
    };

    // --- create all folder necesarry to merge a project files ---
    system(string("mkdir " + project_name + "\\akana").c_str());
    system(string("mkdir " + project_name + "\\akana\\pages").c_str());
    system(string("mkdir " + project_name + "\\main").c_str());

    // --- get akana location in environment variables ---
    char* t = getenv("akana");
    string akana_location = t == NULL ? "" : string(t) + "/";
    
    for (pair<string, string> el: project_structure) {
        // --- create the project file in append mode ---
        ofstream file(el.first.c_str(), ios::app);
        
        // --- open thee file to copy ---
        ifstream file_copy((akana_location + el.second).c_str());
        if(file_copy){
            string line;
            while(getline(file_copy, line)){
                // --- copy the content of the framework file in the file for the project ---
                file << line + "\n";
            }
        }

        // --- if there was error occured while opening the file to copy ---
        else{
            cout << endl << "There was error while opening file '" << el.second << "' to copy it in '" << el.first << "'." << endl;
            return false;
        }

        // --- print the name of the created project file to notice the developper ---
        cout << el.first << endl;
    }

    return true;
}

// this method genereate the resource structure
bool Utils::add_resource(const string &resource_name){
    // --- this map contain all files to generate for a new project with their content ---
    map<string, string> resource_structure = {
        {resource_name + "/controller.php", "bin/r_1"},
        {resource_name + "/endpoints.php", "bin/r_2"},
    };

    // --- get akana location in environment variables ---
    char* t = getenv("akana");
    string akana_location = t == NULL ? "" : string(t) + "/";
    
    for (pair<string, string> el: resource_structure) {
        // --- create the resource file in append mode ---
        ofstream file(el.first.c_str(), ios::app);
        
        // --- open thee file to copy ---
        ifstream file_copy((akana_location + el.second).c_str());
        if(file_copy){
            string line;
            while(getline(file_copy, line)){
                // --- copy the content of the framework file in the file for the resource ---
                file << line + "\n";
            }
        }

        // --- if there was error occured while opening the file to copy ---
        else{
            cout << endl << "There was error while opening file '" << el.second << "' to copy it in '" << el.first << "'." << endl;
            return false;
        }

        // --- print the name of the created resource file to notice the developper ---
        cout << el.first << endl;
    }

    return true;
}

// this methode create file
void Utils::create_file(const string &file){
    ofstream fichier(file.c_str());
}
