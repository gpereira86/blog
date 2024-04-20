<?php

namespace sistema\Controlador\Admin;
use \sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;

/**
 * Description of AdminControlador
 *
 * @author Glauco Pereira
 */
class AdminControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/admin/views');
                
        // Bloqueia o acesso ao painel admin somente para usuários logados (início) --> 
        $usuario = false;
        
        if(!$usuario){
            $this->mensagem->erro('Faça login para acessar o painel de controle!')->flash();
            
            Helpers::redirecionar('admin/login');
        }
        // <-- (fim) Bloqueia o acesso ao painel admin somente para usuários logados
        
    }
}
