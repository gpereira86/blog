<?php

namespace sistema\Nucleo;

use PDO;
use PDOException;

/**
 * Conexão com o Banco de Dados
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Conexao
{

    private static $instancia;
    
    /**
     * Conexão PDO Banco MySql usando constantes de sistema do arquivo de configurações
     * 
     * @return PDO
     */
    public static function getInstancia(): PDO
    {
        if (empty(self::$instancia)) {

            try {

                self::$instancia = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORTA . ';dbname=' . DB_NOME, DB_USUARIO, DB_SENHA, [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "set NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]);
            } catch (PDOException $ex) {

                die("Erro de Conexão >>> " . $ex->getMessage());
            }
        }
        return self::$instancia;
    }

    /**
     * Contrutor (proteção)
     */
    protected function __construct()
    {
        
    }

    /**
     * Clone (proteção)
     */
    private function __clone(): void
    {
        
    }
}
