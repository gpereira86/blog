<?php

namespace sistema\Modelo;

use sistema\Nucleo\Modelo;
use sistema\Nucleo\Sessao;
use sistema\Nucleo\Helpers;

/**
 * Classe UsuarioModelo: Define tabela no BD para usuarios | Checagens de login
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class UsuarioModelo extends Modelo
{
    
    /**
     * Envia ao construtor (super classe) a tabela de consulta de banco para usuarios
     */
    public function __construct()
    {
        parent::__construct('usuarios');
    }

    /**
     * Consulta usuário por e-mail no BD
     * 
     * @param string $email
     * @return UsuarioModelo|null
     */
    public function buscaPorEmail(string $email): ?UsuarioModelo
    {
        $busca = $this->busca("email = :e", "e={$email}");
        return $busca->resultado();
    }

    /**
     * Checagens de login
     * 
     * @param array $dados
     * @param int $level
     * @return bool
     */
    public function login(array $dados, int $level = 1)
    {
        $usuario = (new UsuarioModelo())->buscaPorEmail($dados['email']);

        if (!$usuario) {
            $this->mensagem->alerta("Os dados informados para o login estão incorretos!")->flash();
            return false;
        }

        if (!Helpers::verificarSenha($dados['senha'], $usuario->senha)) {
            $this->mensagem->alerta("Os dados informados para o login estão incorretos!")->flash();
            return false;
        }

        if ($usuario->status != 1) {
            $this->mensagem->alerta("Para fazer o login, primeiro ative sua conta!")->flash();
            return false;
        }

        if ($usuario->level < $level) {
            $this->mensagem->alerta("Você não tem permissão para acessar essa área!")->flash();
            return false;
        }

        $usuario->ultimo_login = date('Y-m-d H:i:s');
        $usuario->salvar();

        (new Sessao())->criar('usuarioId', $usuario->id);

        $this->mensagem->sucesso("{$usuario->nome}, seja bem vindo ao painel de controle")->flash();
        return true;
    }

    /**
     * Checagem de e-mail e cadastro de usuário
     * 
     * @return bool
     */
    public function salvar(): bool
    {
        if ($this->busca("email = :e AND id != :id", "e={$this->email}&id={$this->id}")->resultado()) {
            $this->mensagem->alerta("O e-mail " . $this->dados->email . " já está em cadastrado");
            return false;
        }
                
        parent::salvar();

        return true;
    }
}
