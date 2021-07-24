#include <vector>

class CommandManager{
    private:
        static vector<string> valid_command();

        static{
            valid_command.push_back("create-project");
            valid_command.push_back("add-resource");
            valid_command.push_back("runserver");
            valid_command.push_back("help");
        }

    public:
        static bool check_command(string command);

        static void execute_command(string command);

        vector<string> getValidCommand(){
            return valid_command;
        }
}