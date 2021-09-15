<?php
    use Akana\Response;
    use Akana\Status;

    if(!DEBUG){
        switch($e->getExceptionName()){
            case "NoRootEndpointException":
                echo new Response(["message" => "Root endpoint '/' is not found.",], Status::HTTP_404_NOT_FOUND);
                break;
            case "ResourceNotFoundException":
                echo new Response(["message" => "Resource '". $e->getMessage() ."' do not exist."], Status::HTTP_404_NOT_FOUND);
                break;
            
            case "EmptyAppResourcesException":
                echo new Response(["message" => "This application has no resources."], Status::HTTP_404_NOT_FOUND);
                break;
            case "EndpointNotFoundException":
                echo new Response(["message" => $e->getMessage()], Status::HTTP_404_NOT_FOUND);
                break;
            default:
                echo new Response(["message" => "Unknown error",], Status::HTTP_500_INTERNAL_SERVER_ERROR);
                break;
            
        }
    }
    else{
?>

<h1>Error: <?php echo $e->getExceptionName() . ": ".$e->getMessage(); ?></h1>

<?php
    }
?>

