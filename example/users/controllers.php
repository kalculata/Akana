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
        static function post($request){
            $data = new User($request['data']);
            echo $data->first_name;
            //$data->save();
            
            //$serializer = UserSerializer::serialize($data);
            return new Response(['test']);
        }

        static function get($request){
            $data = User::get_all();

            if(empty($data)){
                return new Response([
                    'message' => 'not data found'],
                    Status::HTTP_404_NOT_FOUND
                );
            }

            $serializer = UserSerializer::serialize($data);
            return new Response($serializer['data'], Status::HTTP_200_OK);
        }
    }
    
    // users/<user_id>/ 
    class ManageUserController{
        static function get($request, $id){
            // get user from database using his id
            $data = User::get($id);

            if($data == NULL)
                return new Response(['message' => 'user with id "'.$id.'" do not exist'], 
                status::HTTP_404_NOT_FOUND);

            $serializer = UserSerializer::serialize($data);
            return new Response($serializer['data'], status::HTTP_200_OK);
        }

        static function patch($request, $user_id){
            return new Response(
                [
                    'message' => 'modify a specific user'
                ]
            );
        }

        static function delete($request, $user_id){
            // get user from database using his id
            $data = User::get($user_id);

            if($data == NULL)
                return new Response([
                    'message' => 'user with id "'.$user_id.'" do not exist'], 
                    status::HTTP_404_NOT_FOUND);
            
            $data->delete();
            
            return new Response([
                'message' => 'user with id "'.$user_id.'" has been deleted'],
                status::HTTP_200_OK
            );
        }
    }