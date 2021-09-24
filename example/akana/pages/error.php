<?php
    use Akana\Response;
    use Akana\Response\Status;

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akana</title>
</head>
<body>
    <h1>You have an issue   : <?= $e->getMessage() ?></h1>
    <h2>In file             : <?= $e->getFile() ?></h2>
    <h2>On line             : <?= $e->getLine() ?></h2>
    <h2>Trace               : <?= $e->getTraceAsString() ?></h2>
</body>
</html>
<?php
    }
?>

