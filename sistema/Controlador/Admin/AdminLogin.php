<?php

namespace sistema\Controlador\Admin;

use \sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelo\UsuarioModelo;
use sistema\Controlador\UsuarioControlador;

/**
 * AdminLogin define as funcionalidades de renderização possíveis na tela de login
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AdminLogin extends Controlador
{
    
    /**
     * Construtor chama o contrutor do controlador (parente) passando um parâmetro 
     * 
     */
    public function __construct()
    {
        parent::__construct('templates/admin/views');
    }

    /**
     * Renderizar itens para o login | Checa se o usuário (solicita consulta ao BD)
     * 
     * @return void
     */
    public function login(): void
    {
        
        $usuario = UsuarioControlador::usuario();
        if($usuario && $usuario->level == 3){
            Helpers::redirecionar('admin/dashboard');
        }
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Método de checagem 2
        if (isset($dados)) {
            if (in_array('', $dados)) {
                $this->mensagem->alerta('Todos os campos são obrigatórios!')->flash();
            } else {
                $usuario = (new UsuarioModelo())->login($dados, 3);
                if ($usuario){
                    Helpers::redirecionar('admin/login');
                }
            }
        }

//        // Método de checagem 1
//        if (isset($dados)) {
//            if ($this->checarDados($dados)) {
//                $this->mensagem->sucesso('Dados válidos')->flash();
//            }
//        }
//        
        echo $this->template->renderizar('login.html', []);
    }

//    // Método de checagem 1
//    private function checarDados(array $dados):bool
//    {
//        if (empty($dados['email'])) {
//            $this->mensagem->erro('Campo e-mail é obrigatório!')->flash();
//            return false;
//        }
//        if (empty($dados['senha'])) {
//            $this->mensagem->erro('Campo senha é obrigatório!')->flash();
//            return false;
//        }
//        
//        return true;
//    }
}
