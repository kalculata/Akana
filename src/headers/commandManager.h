#include <vector>

using namespace std;

class CommandManager{
    public:
        static vector<string> get_commands();

        static bool check_command(string command);

        static void execute_command(string command, int arguments_length, char *arguments[]);
};