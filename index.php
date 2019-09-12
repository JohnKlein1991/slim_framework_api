<?php
use Slim\Factory\AppFactory;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/autoload.php';

$app = AppFactory::create();

require ROOT . '/routes/api.php';

$dbConf = require ROOT . '/config/local_db_config.php';
$db = new DBHelper($dbConf);

$app->run();