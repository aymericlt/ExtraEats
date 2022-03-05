<?php

require 'Controller/Router.php';
session_start();

$router = new Router();
$router->routing();

