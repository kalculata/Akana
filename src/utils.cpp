#include <regex>
#include <sys/stat.h>
#include "headers/utils.h"

bool Utils::name_is_valid(string name){
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

// void Utils::create_folder(string name){

// }

// void Utils::create_file(string name){

// }
