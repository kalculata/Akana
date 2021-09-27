<?php
    namespace Akana;

    use Akana\Exceptions\NoRootEndpointException;
    use Akana\Exceptions\HttpVerbNotAuthorizedException;
    use Akana\Exceptions\EmptyAppResourcesException;
    use Akana\Exceptions\ResourceNotFoundException;
    use Akana\Exceptions\EndpointNotFoundException;
    use Akana\Exceptions\MethodNotStaticException;
    use Akana\Response;
    use Akana\Utils;
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
                    $resource = Utils::get_resource($uri);

                    if(!Utils::resource_exist($resource)){
                        $message = "resource '".$resource."' not found.";
                        throw new ResourceNotFoundException($message);
                    }

                    else{
                        $endpoint = Utils::get_endpoint($resource, $uri); 
                        $t = Utils::endpoint_exist($resource, $endpoint);
                        $controller = empty($t) ? "" : '\\'.$resource.'\\Controllers\\'.$t['method'];
                        
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
                                    return call_user_func_array(array($controller, HTTP_VERB), $t['args']);
                                }
                                catch(ErrorException $e){
                                    $message = "method '".HTTP_VERB."' in controller '".$controller."' is not static.";
                                    throw new MethodNotStaticException($message);
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