<?php

namespace Redmine;

require_once __DIR__ . "/../vendor/autoload.php";

$route = new Route($_SERVER["REQUEST_URI"]);
$route->urlRoute();
?>