<?php
    use Akana\Response;
    
    $trace = $e->getTrace();
    $first_trace = $trace[0];

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
        
        ul{
            list-style: none;
        }
        h1, h2{
            font-weight: lighter;
            margin-bottom: 0.8em;
        }
        h1{
            font-size: 1em;
        }
        h1 strong{
            font-size: 1.2em;
        }
        h2{
            font-size: 1em;
            border-bottom: 1px solid;
        }
        header{
            background-color: var(--main_color);
            color: #fff;
            padding: 20px 50px;
            margin-bottom: 20px;
        }
        header ul{
            font-size: .9em;
            color: var(--third_color);
        }
        main{
            margin: 0px 50px
        }
        main h1{
            font-size: 1.2em;
            font-weight: bolder;
        }
        
    </style>
</head>
<body>
    <header>
        <h1><strong><?= $e->getName() ?></strong>: <?= $e->getMessage() ?></h1>
        <ul>
            <?php
                if(isset($first_trace['file']))
                    echo "<li><strong>file</strong>: ".$first_trace['file']."</li>";
                if(isset($first_trace['class']))
                    echo "<li><strong>class </strong>: ".$first_trace['class']."</li>";
                if(isset($first_trace['function']))
                    echo "<li><strong>function </strong>: ".$first_trace['function']."</li>";
                if(isset($first_trace['line']))
                    echo "<li><strong>line </strong>: ".$first_trace['line']."</li>";
            ?>
        </ul>
    </header>
    <main>
        <h2>exception throw on line: <strong><?= $e->getLine() ?></strong>, in file <?= $e->getFile() ?></h2>

        <div class="trace">
            <h1>Exception trace</h1>
       
                <?= $e->getTraceAsString(); ?>
      
            
        </div>
        <div class="hint"></div>

    </main>
</body>
</html>
<?php
    }
?>

