<?php

namespace sistema\Controlador;

use sistema\Nucleo\Helpers;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Sessao;
use sistema\Modelo\UsuarioModelo;
use sistema\Controlador\UsuarioControlador;


/**
 * Controle Usuario
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class UsuarioControlador extends Controlador
{
    
    /**
     * Construtor chama o contrutor do controlador (parente) passando um parâmetro 
     * 
     */
    public function __construct()
    {
        parent::__construct('templates/site/views');
    }
    
    /**
     * Cria sessão de usuário
     * 
     * @return UsuarioModelo|null
     */
    public static function usuario(): ?UsuarioModelo
    {
        $sessao = new Sessao();
        if(!$sessao->checar('usuarioId')){
            return null;
        }
        
        return (new UsuarioModelo())->buscaPorId($sessao->usuarioId);
        
    }

}
