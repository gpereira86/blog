<?php

use sistema\Nucleo\Helpers;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Cria o dispatcher de rotas
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Define a rota para a página inicial
    $r->addRoute('GET', '/blog/', 'sistema\Controlador\SiteControlador@index');

    // Define a rota para a página sobre    
    $r->addRoute('GET', '/blog/sobre-nos', 'sistema\Controlador\SiteControlador@sobre');

    // Define a rota para a página post  
    $r->addRoute('GET', '/blog/post/{id:\d+}', 'sistema\Controlador\SiteControlador@post');
    
    // Define a rota para a página categoria  
    $r->addRoute('GET', '/blog/categoria/{id:\d+}', 'sistema\Controlador\SiteControlador@categoria');
    
    // Define a rota para a página buscar  
    $r->addRoute('POST', '/blog/buscar', 'sistema\Controlador\SiteControlador@buscar');
    
    // Define a rota para ERRO    
    $r->addRoute('GET', '/blog/404', 'sistema\Controlador\SiteControlador@erro404');
    
    
    $r->addGroup('/blog/admin/', function (FastRoute\ConfigureRoutes $r) {
        $r->addRoute('GET', 'dashboard', 'sistema\Controlador\Admin\AdminDashboard@dashboard');
        
        //admin posts
        $r->addRoute('GET', 'posts/listar', 'sistema\Controlador\Admin\AdminPosts@listar');
        
        //admin categorias
        $r->addRoute('GET', 'categorias/listar', 'sistema\Controlador\Admin\AdminCategorias@listar');
        
        //admin cadastrar categorias
        $r->addRoute(['GET', 'POST'], 'categorias/cadastrar', 'sistema\Controlador\Admin\AdminCategorias@cadastrar');
    });
    
});

// Obtém o método HTTP e o caminho da URI atual
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Remove os parâmetros da URI, se houverem
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Faz a correspondência da rota com o dispatcher
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);



// Trata o resultado da correspondência da rota
switch ($routeInfo[0]) {
//    case \FastRoute\Dispatcher::NOT_FOUND:
//        Helpers::redirecionar('404');
//        break;
//    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//        echo "405 - Method Not Allowed";
//        break;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $id = isset($vars['id']) ? $vars['id'] : null;
        
        // Chama o controlador associado à rota
        $handlerParts = explode('@', $handler);
        $controllerClass = $handlerParts[0];
        $controllerMethod = $handlerParts[1];
        
        // Cria uma instância do controlador e chama o método associado, passando o ID como parâmetro
        $siteController = new $controllerClass();
        $siteController->$controllerMethod($id); // Passa o ID do post como parâmetro
        break;
}
