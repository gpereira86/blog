<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Modelo;



/**
 * Classe PostModelo
 * 
 * @author Glauco Pereira
 */
class AuditorUserModelo extends Modelo
{
    
    public function __construct()
    {
        parent::__construct('auditoruser');
    }
        
}
