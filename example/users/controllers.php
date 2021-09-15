<?php
    namespace users\Controllers;

    require '../users/models.php';
    require '../users/serializers.php';

    use Akana\Response;
    use Akana\Response\Status;

    use users\Models\User;
    use users\Serializers\UserSerializer;

    // users/
    class UsersController{
        static function post(){
            return new Response(
                [
                    'message' => 'create an account'
                ]
            );
        }

        static function get(){
            return new Response(
                [
                    'message' => 'get list of all users'
                ]
            );
        }
    }
    
    // users/<user_id>/ 
    class ManageUserController{
        static function get($id){
            // get user from database using his id
            $data = User::get($id);

            if($data == NULL)
                return new Response(['message' => 'user with id "'.$id.'" do not exist'], status::HTTP_404_NOT_FOUND);

            $serializer = UserSerializer::serialize($data);
                          
            return new Response($serializer['data'], status::HTTP_200_OK);
        }

        static function patch($user_id){
            return new Response(
                [
                    'message' => 'modify a specific user'
                ]
            );
        }

        static function delete($user_id){
            return new Response(
                [
                    'message' => 'delete a specific user'
                ]
            );
        }
    }