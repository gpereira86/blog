<?php

use Dotenv\Dotenv;
use sistema\Nucleo\Helpers;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

//arquivo de configaração do sistema
//define fuso horário
date_default_timezone_set('America/Sao_Paulo');

//Informações do sistema
define('SITE_NOME', 'Projeto Blog');
define('SITE_DESCRICAO', 'Blog - Um projeto acadêmico com alguns toques pessoais');

//Urls do sistema
define('URL_PRODUCAO', 'https://blog.glaucopereira.com');
define('URL_DESENVOLVIMENTO', 'http://localhost/blog');

if (Helpers::localhost()) {
    
    define('DB_HOST', 'localhost');
    define('DB_PORTA', '3306');
    define('DB_NOME', 'blog');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '');

    define('URL_SITE', 'blog/');
    define('URL_ADMIN', 'blog/admin/');
    
} else {
    
    define('DB_HOST', $_ENV['DB_HOST']);
    define('DB_PORTA', $_ENV['DB_PORTA']);
    define('DB_NOME', $_ENV['DB_NOME']);
    define('DB_USUARIO', $_ENV['DB_USUARIO']);
    define('DB_SENHA', $_ENV['DB_SENHA']);
    
    define('URL_SITE', '/');
    define('URL_ADMIN', '/admin/');
    
}