<?php

//php service.php queueConfig start

error_reporting(-1);
ini_set('display_errors', 1);

require 'bootstrap.php';

$manager = new module\server\ProcessManager();
$manager->run($argv);
