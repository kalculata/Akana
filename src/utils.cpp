#include <regex>
#include "headers/utils.h"

bool Utils::name_is_valid(string name){
    if(regex_match(name, regex("^[a-z]*[a-z0-9_]*[a-z0-9]+$"))){
        return true;
    }
    else{
        return false;
    }
}

// string Utils::to_camel_case(string value){

// }

// bool Utils::file_exist(string file){
    
// }

// void Utils::create_folder(string name){

// }

// void Utils::create_file(string name){

// }
