<?php

//arquivo de configaração do sistema
//define fuso horário
date_default_timezone_set('America/Sao_Paulo');

define('DB_HOST', 'localhost');
define('DB_PORTA', '3306');
define('DB_NOME', 'blog');
define('DB_USUARIO', 'root');
define('DB_SENHA', '');

//Informações do sistema
define('SITE_NOME', 'Projeto Blog');
define('SITE_DESCRICAO', 'Blog - Um projeto acadêmico com toques pessoais');

//Urls do sistema
define('URL_PRODUCAO', 'http://unset.com.br');
define('URL_DESENVOLVIMENTO', 'http://localhost/blog');

define('URL_SITE', 'blog/');
define('URL_ADMIN', 'blog/admin/');