<?php

use App\Config\Router\Router;
use App\Presentation\TaskController;

return function(Router $router, $container) {

    $router->addRoute("GET", "/", function () {
        echo "Please, use POST method instead GET\n";
    });

    $router->addRoute("GET", "/tasks", function () use ($container) {
        return $container->get(TaskController::class)->index();
    });

    $router->addRoute("POST", "/tasks", function () use ($container) {
        return $container->get(TaskController::class)->store();
    });

    $router->addRoute("GET", "/tasks/{id}", function ($id) use ($container) {
        return $container->get(TaskController::class)->show($id);
    });

    $router->addRoute("PUT", "/tasks/{id}", function ($id) use ($container) {
        return $container->get(TaskController::class)->update($id);
    });

    $router->addRoute("DELETE", "/tasks/{id}", function ($id) use ($container) {
        return $container->get(TaskController::class)->destroy($id);
    });

};
