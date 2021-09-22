<?php
    namespace Akana;

    use Exception;
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
        // this method help to run the request
        static function execute(string $uri, $request): Response{
            $resource = '';
            $endpoint = '';
            
            // --- if the uri is pointed to root endpoint '/' ---
            if($uri == '/'){
                // --- check if the root endpoint is set in 'config.php' ---
                if(ROOT_ENDPOINT == true && ROOT_ENDPOINT_CONTROLLER != NULL){
                    $root_endpoint_controller_path =  ROOT_ENDPOINT_CONTROLLER['file'];
                    $root_endpoint_controller = ROOT_ENDPOINT_CONTROLLER['controller'];
                    
                    // --- import file of root endpoint controller ---
                    require $root_endpoint_controller_path;
                    
                    // --- check if there is a method with 'http_verb' in root enpoint controller ---
                    if(method_exists($root_endpoint_controller, HTTP_VERB)){
                        
                        // --- execute method with verb name in root endpoint controller ---
                        return call_user_func(array($root_endpoint_controller, HTTP_VERB));
                    }
                    // -- throw HttpVerbNotAuthorizedException if there is not method with 'http_verb' in root 
                    // enpoint controller ---
                    else{
                        throw new HttpVerbNotAuthorizedException();
                    }
                    
                }
                else{
                    throw new NoRootEndPointException("Your application do have root endpoint.");
                }
            }

            // --- if the uri do not pointed to root endpoint ---
            else{
                // --- check if there is not resouce in APP_RESOURCES array set in config.php ---
                if(count(APP_RESOURCES) == 0){
                    throw new EmptyAppResourcesException();
                }

                else{
                    $resource = Utils::get_resource($uri);
                    
                    // --- check if the given resource exist in APP_RESOURCES array set in config.php ---
                    if(Utils::resource_exist($resource)){

                        // --- remove resource in the uri and get only endpoint ---
                        $endpoint = Utils::get_endpoint($resource, $uri);
                        
                        // --- check if the given endpoint exist in RESOURCE_FOLDER/endpoints.php ---
                        $t = Utils::endpoint_exist($resource, $endpoint);
                        $controller = empty($t) ? "" : '\\'.$resource.'\\Controllers\\'.$t['method'];
                        
                        if(!empty($controller)){
                            require '../'. $resource .'/controllers.php';
                            
                            // --- check if controller exists and that it is static and execute it with arguments
                            // they exists ---
                            if(method_exists($controller, HTTP_VERB)){
                                try{
                                    array_unshift($t['args'], $request);
                                    return call_user_func_array(array($controller, HTTP_VERB), $t['args']);
                                }
                                catch(ErrorException $e){
                                    throw new MethodNotStaticException("method '".HTTP_VERB."' in controller '".$controller."' is not static");
                                }
                            }

                            else{
                                throw new HttpVerbNotAuthorizedException();
                            } 
                        }

                        else{
                            throw new EndpointNotFoundException("Endpoint '".$endpoint."' is not found in resource '". $resource ."'.");
                        }

                    }

                    else{
                        throw new ResourceNotFoundException("Resource '".$resource."' do not exist in your application");
                    }
                }
            }
        }
    }