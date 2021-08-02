# Description
Akana is a PHP framework used to create restful APIs. This tool will help you to generate files for a empty project, add resources in project, make database migrations, etc...

# Installation
- Create a folder 'akana/' folder in computer.
- Compile the project by following instructions given in 'Commands for compilation' section (use command 1).
- Copy the executable file (.exe) and the folder 'bin/' into your folder.
- Create a new environment variables 'akana' with value of path to your folder.
- Add also in "PATH" the path to your folder.
- Then restart your computer (if you don't, akana might not work good).
- To test if akana has been installed in your computer, open cmd and tap:

    `akana`


# Commands for compilation
*  To run those commands and you must have g++ install in your computer.

* Command 1: Run this command to compile the whole project and generate executable file (akana.exe):

     `g++ -c akana.cpp src/assistant.cpp src/commandManager.cpp src/commands.cpp src/utils.cpp ; g++ akana.o assistant.o commandManager.o commands.o utils.o -o akana.exe`

Run this command when you want to re-compile all source files .cpp.

* Command 2: Run this command to generate executable file (kana.exe) after any change:

    `g++ akana.o assistant.o commandManager.o commands.o utils.o -o akana.exe`

This command must be runned to everytime you will want generate a executable file

* Command 3: Run this command to compile a source file (*.cpp), this will generate a object file (.o):

    `g++ -c source_file_name.cpp`

This command is necessary, when you made some changes in specific source file and you want to generate a new executable file (.exe) with changes you made, you will just compile the file so that chnages can migrate in object file (.o) and then run the command 2 to generate .exe file.
