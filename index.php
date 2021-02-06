<!-- Project started by consty  -->

<?php

    if(!isset($_SESSION)) { 
        session_start(); 
    }

    ob_start();

    define('main', __DIR__ . '/');
    define('structure', __DIR__ . '/structure/');
    define('views', __DIR__. '/views/');
    define('STYLE', views. '/general/');

    include main. 'structure/autoload.php';
    include_once structure. 'this.auto.php';
    include_once structure. 'connect.auto.php';
    include_once structure. 'redirect.auto.php';
    include_once structure. 'auth.auto.php';
    include_once structure. 'user.auto.php';

    this::init()->getContent();

?>