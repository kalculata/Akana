<?php
    /*
    * This file is part of akana framework files.
    *
    * (c) Kubwacu Entreprise
    *
    * @author (kalculata) Huzaifa Nimushimirimana <nprincehuzaifa@gmail.com>
    *
    */
    header('location: start/index.php?use_bridge=1&uri='.$_GET['resource'].'&request_method='.$_SERVER['REQUEST_METHOD'].'&data='.json_encode($_POST));