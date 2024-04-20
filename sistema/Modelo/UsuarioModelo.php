<?php

namespace sistema\Modelo;
use sistema\Nucleo\Modelo;

/**
 * Description of UsuarioModelo
 *
 * @author glauc
 */
class UsuarioModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct('usuarios');
    }
    
    public function buscaPorEmail(string $email): ?UsuarioModelo
    {
        $busca = $this->busca("email = :e","e={$email}");
        return $busca->resultado();
    } 
    
    public function login(array $dados, int $level = 1)
    {
        $usuario = (new UsuarioModelo())->buscaPorEmail($dados['email']);
        
        if(!$usuario){
            $this->mensagem->alerta("Dados incorretos")->flash();
            return false;
        }
        
        if($dados['senha'] != $usuario->senha){
            $this->mensagem->alerta("Senha incorreta")->flash();
            return false;
        }
        
        if($usuario->status != 1){
            $this->mensagem->alerta("Ative sua conta")->flash();
            return false;
        }
        
        if($usuario->level < $level){
            $this->mensagem->alerta("Usuário sem permissão")->flash();
            return false;
        }
        
        
        
        $this->mensagem->sucesso("{$usuario->nome}, seja bem vindo ao painel de controle")->flash();
        return true;
        
    }
}
