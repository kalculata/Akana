<?php
    /*
    * This file is part of the akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    use Akana\Response;

    // var_dump($e->getMessage());

    // return;

    $exception = explode('\\', get_class($e))[2];
    $trace = $e->getTrace();
    $first_trace = $trace[0];

    if(!DEBUG || $exception == 'SerializerException' || $exception == 'JSONException'){
        if($exception == 'SerializerException'){
            if(json_decode($e->getMessage()) == null)
                echo new Response(["message" => $e->getMessage()], $e->getCode());
            else
                echo new Response(["message" => json_decode($e->getMessage())], $e->getCode());
            return;
        }
        else if($exception == 'JSONException'){
            echo new Response(["message" => $e->getMessage()], $e->getCode());
            return;
        }

        $general_response = new Response([
            "message" => "there is an internal server error, please contact us to report this issue"], 
            STATUS_500_INTERNAL_SERVER_ERROR
        );
        switch($exception){
            case 'ControllerNotFoundException':
                echo $general_response;
                break;
            case 'MethodNotStaticException':
                echo $general_response;
                break;
            case 'DatabaseException':
                echo $general_response;
                break;
            case 'ORMException':
                echo $general_response;
                break;
            case 'NotSerializableException':
                echo $general_response;
                break;
            
            default:
                echo new Response(["message" => $e->getMessage()], $e->getCode());
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
            --main_color: rgb(32,77,142);
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
        h2{
            font-size: 1em;
            border-bottom: 1px solid;
        }
        header{
            background-color: var(--main_color);
            color: #fff;
            padding: 20px 50px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        header ul{
            font-size: .9em;
            color: var(--third_color);
        }

        header h1{
            font-size: 1.1em;
        }
        header h1 strong{
            font-size: 1.3em;
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
        <h1><strong><?= $exception ?></strong>: <?= $e->getMessage() ?></h1>
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
