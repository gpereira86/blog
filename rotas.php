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
    $r->addRoute('GET', '/blog/post/{slug:[^/.]+}', 'sistema\Controlador\SiteControlador@post');

    // Define a rota para a página categoria  
    $r->addRoute('GET', '/blog/categoria/{slug:[^/.]+}[/{pagina:\d+}]', 'sistema\Controlador\SiteControlador@categoria');

    // Define a rota para a página buscar  
    $r->addRoute('POST', '/blog/buscar', 'sistema\Controlador\SiteControlador@buscar');

    // Define a rota para ERRO    
    $r->addRoute('GET', '/blog/404', 'sistema\Controlador\SiteControlador@erro404');

    // Rotas admin GRUPO
    $r->addGroup('/blog/admin/', function (FastRoute\ConfigureRoutes $r) {

        //admin login
        $r->addRoute(['GET', 'POST'], 'login', 'sistema\Controlador\Admin\AdminLogin@login');

        // Dashboard
        $r->addRoute('GET', 'dashboard', 'sistema\Controlador\Admin\AdminDashboard@dashboard');
        $r->addRoute('GET', 'sair', 'sistema\Controlador\Admin\AdminDashboard@sair');

        //admin usuarios
        $r->addRoute('GET', 'usuarios/listar', 'sistema\Controlador\Admin\AdminUsuarios@listar');
        $r->addRoute(['GET', 'POST'], 'usuarios/cadastrar', 'sistema\Controlador\Admin\AdminUsuarios@cadastrar');
        $r->addRoute(['GET', 'POST'], 'usuarios/editar/{id:\d+}', 'sistema\Controlador\Admin\AdminUsuarios@editar');
        $r->addRoute(['GET', 'POST'], 'usuarios/deletar/{id:\d+}', 'sistema\Controlador\Admin\AdminUsuarios@deletar');

        //admin posts
        $r->addRoute('GET', 'posts/listar', 'sistema\Controlador\Admin\AdminPosts@listar');
        $r->addRoute(['GET', 'POST'], 'posts/cadastrar', 'sistema\Controlador\Admin\AdminPosts@cadastrar');
        $r->addRoute(['GET', 'POST'], 'posts/editar/{id:\d+}', 'sistema\Controlador\Admin\AdminPosts@editar');
        $r->addRoute(['GET', 'POST'], 'posts/deletar/{id:\d+}', 'sistema\Controlador\Admin\AdminPosts@deletar');
        $r->addRoute('POST', 'posts/datatable', 'sistema\Controlador\Admin\AdminPosts@datatable');

        //admin categorias
        $r->addRoute('GET', 'categorias/listar', 'sistema\Controlador\Admin\AdminCategorias@listar');
        $r->addRoute(['GET', 'POST'], 'categorias/cadastrar', 'sistema\Controlador\Admin\AdminCategorias@cadastrar');
        $r->addRoute(['GET', 'POST'], 'categorias/editar/{id:\d+}', 'sistema\Controlador\Admin\AdminCategorias@editar');
        $r->addRoute(['GET', 'POST'], 'categorias/deletar/{id:\d+}', 'sistema\Controlador\Admin\AdminCategorias@deletar');
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
        $slug = isset($vars['slug']) ? $vars['slug'] : null;

        if ($id !== null) {
            $variavel = $id;
        } else {
            $variavel = $slug;
        }

        
        // Chama o controlador associado à rota
        $handlerParts = explode('@', $handler);
        $controllerClass = $handlerParts[0];
        $controllerMethod = $handlerParts[1];

        // Cria uma instância do controlador e chama o método associado, passando o ID como parâmetro
        $siteController = new $controllerClass();
        $siteController->$controllerMethod($variavel, (isset($vars['pagina']) ? $vars['pagina'] : null)); // Passa o ID do post como parâmetro

        break;
}
