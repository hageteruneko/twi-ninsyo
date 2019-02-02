<?php

$router = $di->getRouter();

$router->add(
    "/login",
    array(
        "controller" => "index",
        "action"     => "login",
    )
);
$router->add(
    "/callback.phtml",
    array(
        "controller" => "index",
        "action"     => "callback",
    )
);
$router->add(
    "/products",
    array(
        "controller" => "products",
        "action"     => "index",
    )
);
$router->add(
    "/logout",
    array(
        "controller" => "index",
        "action"     => "logout",
    )
);
$router->add(
    "/error.phtml",
    array(
        "controller" => "index",
        "action"     => "error",
    )
);
// Define your routes here

$router->handle();
