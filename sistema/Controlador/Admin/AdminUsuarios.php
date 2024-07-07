<?php

namespace sistema\Controlador\Admin;

use sistema\Modelo\UsuarioModelo;
use sistema\Nucleo\Helpers;

/**
 * AdminUsuarios define as funcionalidades de renderização possíveis em usuários dentro do painel administrativo
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AdminUsuarios extends AdminControlador
{

    /**
     *  Renderizar itens para página de usuarios do painel (soliciita consulta ao BD)
     * 
     * @return void
     */
    public function listar(): void
    {
        $usuario = new UsuarioModelo();

        echo $this->template->renderizar('usuarios/listar.html', [
            'usuarios' => $usuario->busca()->ordem('level DESC,status DESC')->resultado(true),
            'total' => [
                'usuarios' => $usuario->busca('level != 3')->total(),
                'usuariosAtivo' => $usuario->busca('status = 1 AND level != 3')->total(),
                'usuariosInativo' => $usuario->busca('status = 0 AND level != 3')->total(),
                'admin' => $usuario->busca('level = 3')->total(),
                'adminAtivo' => $usuario->busca('status = 1 AND level = 3')->total(),
                'adminInativo' => $usuario->busca('status = 0 AND level = 3')->total(),
            ]
        ]);
    }

    /**
     * Recebe os dados do Form de usuários e prepara para incluir novo registro no banco
     * 
     * @return void
     */
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            //checa dados
            if ($this->validarDados($dados)) {

                if (empty($dados['senha'])) {
                    $this->mensagem->alerta('Informe uma senha para o usuário')->flash();
                } else {

                    $usuario = new UsuarioModelo();

                    $usuario->nome = $dados['nome'];
                    $usuario->email = $dados['email'];
                    $usuario->senha = Helpers::gerarSenha($dados['senha']);
                    $usuario->level = $dados['level'];
                    $usuario->status = $dados['status'];
                    $usuario->link_img = (!empty($dados['link_img']) ? $dados['link_img'] :'templates/admin/assets/img/usergen.png');

                    if ($usuario->salvar()) {
                        $this->mensagem->sucesso('Usuario Cadastrado com Sucesso')->flash();
                        Helpers::redirecionar('admin/usuarios/listar');
                    } else {
                        $usuario->mensagem()->flash();
                    }
                }
            }
        }
        echo $this->template->renderizar('usuarios/formulario.html', [
            'usuario' => $dados
        ]);
    }

    /**
     * Recebe o id vindo como parâmetro da rota, depois recebe dados do Form editado
     * de usuários e prepara para fazer Update no registro do banco
     * 
     * @param int $id
     * @return void
     */
    public function editar(int $id): void
    {
        $usuario = (new UsuarioModelo())->buscaPorId($id);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if (isset($dados)) {

            if ($this->validarDados($dados)) {

                $usuario = (new UsuarioModelo())->buscaPorId($id);

                $usuario->nome = $dados['nome'];
                $usuario->email = $dados['email'];
                $usuario->senha = (!empty($dados['senha']) ? Helpers::gerarSenha($dados['senha']) : $usuario->senha);
                $usuario->level = $dados['level'];
                $usuario->status = $dados['status'];
                $usuario->link_img = $dados['link_img'];
                $usuario->atualizado_em = date('Y-m-d H:i:s');

                if ($usuario->salvar()){
                    $this->mensagem->sucesso('Usuário atualizado com Sucesso')->flash();
                    Helpers::redirecionar('admin/usuarios/listar');
                } else {
                    $usuario->mensagem()->flash();
                }
            }
        }

        echo $this->template->renderizar('usuarios/formulario.html', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Recebe o id vindo como parâmetro da rota e deleta o registro correspondente no BD
     * 
     * @param int $id
     * @return void
     */
    public function deletar(int $id): void
    {
//        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (is_int($id)) {
            $usuario = (new UsuarioModelo())->buscaPorId($id);

            if (!$usuario) {
                $this->mensagem->alerta('O usuário que você está tentando deletar não existe!')->flash();
                Helpers::redirecionar('admin/usuarios/listar');
            } else {
                
                if ($usuario->apagar("id = {$id}")){
                    $this->mensagem->sucesso('Usuário deletado com sucesso!')->flash();
                    Helpers::redirecionar('admin/usuarios/listar');
                } else {
                    $this->mensagem->erro($usuario->erro())->flash();
                    Helpers::redirecionar('admin/usuarios/listar');
                }
            }
        }
    }

    
    /**
     * Valida os dados do Form
     * 
     * @param array $dados
     * @return bool
     */
    public function validarDados(array $dados): bool
    {
        if (empty($dados['nome'])) {
            $this->mensagem->alerta('Informe o nome do usuário')->flash();
        }
        if (empty($dados['email'])) {
            $this->mensagem->alerta('Informe o e-mail do usuário')->flash();
        }
        if (!Helpers::validarEmail($dados['email'])) {
            $this->mensagem->alerta('Informe o e-mail do válido')->flash();
            return false;
        }
        if(!empty($dados['senha'])){
            if(!Helpers::validarSenha($dados['senha'])){
                $this->mensagem->alerta('A senha deve ter entre 6 e 50 caracteres!')->flash();
                return false;
            }
        }
        
        return true;
    }
}
