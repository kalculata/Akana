<?php
    use Akana\Response;
    
    if(!DEBUG || $e->getLevel() == 'low'){
        if($e->getName() == 'NoRootEndpointException' || $e->getName() == 'NoRootEndpointException' ||
            $e->getName() == 'EmptyAppResourcesException' || $e->getName() == 'ResourceNotFoundException' ||
            $e->getName() == 'EndpointNotFoundException' || $e->getName() == 'HttpVerbNotAuthorizedException' ||
            $e->getName() == 'SerializerException' || $e->getName() == 'JSONException'){
                echo new Response(["message" => $e->getMessage()], $e->getCode());
                return;
            }
        switch($e->getName()){
            // 'NoRootEndpointException'
            // 'EmptyAppResourcesException';
            // 'ResourceNotFoundException';
            // 'EndpointNotFoundException';
            // 'HttpVerbNotAuthorizedException';
            // 'SerializerException';
            // 'JSONException';

            // 'ControllerNotFoundException'
            // 'MethodNotStaticException';
            // 'DatabaseException';
            // 'ORMException';
            // 'NotSerializableException'
            
            case 'ControllerNotFoundException':
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_501_NOT_IMPLEMENTE);
                break;
            case 'MethodNotStaticException':
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_500_INTERNAL_SERVER_ERROR);
                break;
            case 'DatabaseException':
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_500_INTERNAL_SERVER_ERROR);
                break;
            case 'ORMException':
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_500_INTERNAL_SERVER_ERROR);
                break;
            case 'NotSerializableException':
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_500_INTERNAL_SERVER_ERROR);
                break;
            
            default:
                echo new Response(["message" => "there is an internal server error, please contact us to report this issue"], STATUS_500_INTERNAL_SERVER_ERROR);
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

    <style>
        :root{
            --main_color: #d50000;
            --third_color: #eee;
        }
        *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        h1, h2{
            font-weight: lighter;
        }
        h1{
            font-size: 1em;
            margin-bottom: 0.5em;
        }
        h1 strong{
            font-size: 1.2em;
        }
        h2{
            font-size: .8em;
            color: var(--third_color);
        }
        header{
            background-color: var(--main_color);
            color: #fff;
            padding: 20px 50px;
            margin-bottom: 20px;
        }
        main{
            margin: 0px 50px
        }
        
    </style>
</head>
<body>
    <header>
        <h1><strong><?= $e->getName() ?></strong>: <?= $e->getMessage() ?></h1>
        <h2>In file <?= $e->getFile() ?> on line <strong><?= $e->getLine() ?></strong></h2>
    </header>
    <main>
        <div class="trace"><?= $e->getTraceAsString() ?></div>
        <div class="hint"></div>

    </main>
</body>
</html>
<?php
    }
?>

