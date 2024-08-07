<?php

namespace sistema\Controlador\Admin;

use sistema\Nucleo\Sessao;
use sistema\Nucleo\Helpers;
use sistema\Modelo\UsuarioModelo;
use sistema\Modelo\PostModelo;
use sistema\Modelo\CategoriaModelo;
use sistema\Modelo\AuditorUserModelo; 

/**
 * AdminDashboard define as funcionalidades de renderização possíveis no dashboard do painel administrativo
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AdminDashboard extends AdminControlador
{

    /**
     * Renderizar itens para o dashboard do painel (solicita consulta ao BD)
     * 
     * @return void
     */
    public function dashboard(): void
    {
        $posts = new PostModelo();
        $categorias = new CategoriaModelo();
        $usuarios = new UsuarioModelo();
        $auditor = new AuditorUserModelo();
        $user = new Sessao();
        $logins = get_object_vars($user->carregar());
        
        echo $this->template->renderizar('dashboard.html', [
            
            'posts' => [
                'posts' => $posts->busca()->Ordem('id DESC')->limite(5)->resultado(true),
                'total' => $posts->busca(null, 'COUNT(id)', 'id')->total(),
                'ativo' => $posts->busca('status = :s', 's=1 COUNT(status)', 'status' )->total(),
                'inativo' => $posts->busca('status = :s', 's=0 COUNT(status)', 'status')->total(),
            ],
            'categorias' => [
                'categorias' => $categorias->busca()->Ordem('id DESC')->limite(5)->resultado(true),
                'total' => $categorias->busca()->total(),
                'ativos' =>  $categorias->busca('status=1')->total(),              
                'inativos' =>  $categorias->busca('status=0')->total(),
            ],
            'usuarios' => [
                'total' => $usuarios->busca('level !=3')->total(),
                'ativo' => $usuarios->busca('status = 1 AND level != 3')->total(),
                'inativo' => $usuarios->busca('status = 0  AND level != 3')->total(),
            ],
            'usuariosadm' => [
                'total' => $usuarios->busca('level =3')->total(),
                'ativo' => $usuarios->busca('status = 1 AND level = 3')->total(),
                'inativo' => $usuarios->busca('status = 0  AND level = 3')->total(),
            ],
            'logins' => $usuarios->busca()->ordem('ultimo_login DESC')->limite('5')->resultado(True),
            'auditor' => $auditor->busca("id_user = {$logins['usuarioId']}")->ordem('id DESC')->limite('1')->resultado(),
            'loginsAdm' => $auditor->busca("id_user = {$logins['usuarioId']}")->ordem('id DESC')->limite('5')->resultado(true),
        ]);
    }

    /**
     * Fecha sessão e Renderiza página após sair
     * 
     * @return void
     */
    public function sair(): void
    {
        $sessao = new Sessao();
        $sessao->limpar('usuarioId');
        $sessao->limpar('ip');
        $sessao->limpar('acao_contadores');
        $sessao->deletar();
        

        $this->mensagem->informa('Você saiu do painel de controle')->flash();
        Helpers::redirecionar('admin/login');
    }
}
