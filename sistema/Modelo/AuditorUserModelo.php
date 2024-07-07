<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Modelo;



/**
 * Classe AuditorUserModelo: Define tabela do banco para auditoria de logins
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AuditorUserModelo extends Modelo
{
    /**
     * Envia ao construtor (super classe) a tabela de consulta de banco para auditoria de logins usuários
     */
    public function __construct()
    {
        parent::__construct('auditoruser');
    }
        
}
