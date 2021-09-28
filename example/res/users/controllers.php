<?php
    namespace users\Controllers;

    require '../res/users/models.php';
    require '../res/users/serializers.php';

    use Akana\Response;

    use users\Models\User;
    use users\Serializers\UserSerializer;

    // users/
    class UsersController{
        static function post(){
            $data = new User(REQUEST['data']);
            $data->save();

            $serializer = UserSerializer::serialize($data);
            return new Response($serializer['data']);
        }

        static function get(){
            User::get_all();
            return new Response(["the model is correct"]);
            // $data = User::get_all();

            // if(empty($data)){
            //     return new Response([
            //         'message' => 'not data found'],
            //         STATUS_404_NOT_FOUND
            //     );
            // }

            // $serializer = UserSerializer::serialize($data);
            // return new Response($serializer['data'], STATUS_200_OK);
        }
    }
    
    // users/<user_id>/ 
    class ManageUserController{
        static function get($id){
            // get user from database using his id
            $data = User::get($id);

            if($data == NULL)
                return new Response(['message' => 'user with id "'.$id.'" do not exist'], 
                STATUS_404_NOT_FOUND);

            $serializer = UserSerializer::serialize($data);
            return new Response($serializer['data'], STATUS_200_OK);
        }

        static function patch($user_id){
            $user_modify = User::get($user_id);

            if(!$user_modify){
                return new Response([
                    "message" => "user with id '".$user_id."' do not exist"], 
                    STATUS_404_NOT_FOUND);
            }

            $user_modify->update(REQUEST['data']);
            $serializer = UserSerializer::serialize($user_modify);
            return new Response(
                [
                    'message' => 'user "'.$user_id.'" have been modified',
                    'data' => $serializer['data']
                ]
            );
        }

        static function delete($user_id){
            // get user from database using his id
            $data = User::get($user_id);

            if($data == NULL)
                return new Response([
                    'message' => 'user with id "'.$user_id.'" do not exist'], 
                    STATUS_404_NOT_FOUND);
            
            $data->delete();
            
            return new Response([
                'message' => 'user with id "'.$user_id.'" has been deleted'],
                STATUS_200_OK
            );
        }
    }