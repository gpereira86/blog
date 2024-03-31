<?php

// Arquivo index responsável pela inicialização do sistema
require 'vendor/autoload.php';

//require 'rotas.php';


session_start();

echo session_id();

echo '<hr>';

$_SESSION['nome'] = 'Glauco Pereira';

if (isset($_SESSION['visitas'])) {
    $_SESSION['visitas'] += 1;
} else {
    $_SESSION['visitas'] = 1;
}

//unset($_SESSION['nome']);

//session_destroy();

echo "{$_SESSION['nome']} visitou {$_SESSION['visitas']}";
