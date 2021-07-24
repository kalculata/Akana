#include <vector>

using namespace std;

class CommandManager{
    public:
        static bool check_command(string command);

        static void execute_command(string command);

        static vector<string> get_commands();
};