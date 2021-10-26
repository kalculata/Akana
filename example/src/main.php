<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    namespace Akana;

    use Akana\Exceptions\AkanaException;
    use Akana\Exceptions\NoRootEndpointException;
    use Akana\Exceptions\HttpVerbNotAuthorizedException;
    use Akana\Exceptions\EmptyAppResourcesException;
    use Akana\Exceptions\ResourceNotFoundException;
    use Akana\Exceptions\EndpointNotFoundException;
    use Akana\Response;
    use ErrorException;


    class Main{
        static public function execute(string $uri): Response{
            if($uri == '/'){
                if(!self::root_isset()){
                    $message = "resource '/' not found.";
                    throw new NoRootEndPointException($message); 
                }
                
                else{
                    $root_controller_file =  ROOT_CONTROLLER['file'];
                    $root_controller_class = ROOT_CONTROLLER['class'];
                    
                    require '../'.$root_controller_file;
                    
                    if(method_exists($root_controller_class, HTTP_VERB))
                        return call_user_func(array($root_controller_class, HTTP_VERB));
                    else
                        throw new HttpVerbNotAuthorizedException();
                }
            }

            else if($uri != '/'){
                if(count(APP_RESOURCES) == 0){
                    $message = "this application doesn't have any resource registred in config.php.";
                    throw new EmptyAppResourcesException($message);
                }

                else{
                    $resource = URI::extract_resource($uri);

                    if(!Resource::is_exist($resource)){
                        $message = "resource '".$resource."' not found.";
                        throw new ResourceNotFoundException($message);
                    }

                    else{
                        $endpoint = URI::extract_endpoint($resource, $uri); 
                        $endpoint_details = Endpoint::details($resource, $endpoint);
                        $controller = empty($endpoint_details) ? 
                            "" : 
                            '\\'.$resource.'\\Controllers\\'.$endpoint_details['controller'];
                        
                        if(empty($controller)){
                            $message = "endpoint '".$endpoint."' not found in resource '".$resource.".";
                            throw new EndpointNotFoundException($message);
                        }

                        else{
                            require '../res/'. $resource .'/controllers.php';
                            
                            if(!method_exists($controller, HTTP_VERB)){
                                $message = "method '".HTTP_VERB."' is not authorized to this uri '".URI."'.";
                                throw new HttpVerbNotAuthorizedException($message);
                            }

                            else{
                                try{
                                    return call_user_func_array(array($controller, HTTP_VERB), $endpoint_details['args']);
                                }
                                catch(ErrorException $e){
                                    $message = str_replace("call_user_func_array() expects parameter 1 to be a valid callback, ", "", $e->getMessage());
                                    throw new AkanaException($message);
                                }
                            } 
                        }
                    }
                }
            }
        }

        static public function root_isset(){
            return ROOT == true && ROOT_CONTROLLER != NULL;
        }
    }
