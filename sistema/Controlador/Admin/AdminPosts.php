<?php

namespace sistema\Controlador\Admin;

use sistema\Modelo\PostModelo;
use sistema\Modelo\CategoriaModelo;
use sistema\Nucleo\Helpers;

/**
 * Description of AdminPosts
 *
 * @author glauc
 */
class AdminPosts extends AdminControlador
{

    public function listar(): void
    {
        $post = new PostModelo();

        echo $this->template->renderizar('posts/listar.html', [
            'posts' => $post->busca()->ordem('status Asc, id DESC')->resultado(true),
            'total' => [
                'total' => $post->total(),
                'ativos' => $post->busca('status = 1')->total(),
                'inativos' => $post->busca('status = 0')->total(),
            ]
        ]);
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {


            $post = new PostModelo();
            $post->titulo = $dados['titulo-form'];
            $post->categoria_id = $dados['categoria-form'];
            $post->texto = $dados['texto-form'];
            $post->status = $dados['status-form'];

            if ($post->salvar()) {
                $this->mensagem->sucesso('Post Cadastrado com Sucesso')->flash();
                Helpers::redirecionar('admin/posts/listar');
            }
        }
        echo $this->template->renderizar('posts/formulario.html', [
            'categorias' => (new CategoriaModelo())->busca()
        ]);
    }

    public function editar(int $id): void
    {
        $post = (new PostModelo())->buscaPorId($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {


            $post = (new PostModelo())->buscaPorId($id);

            $post->titulo = $dados['titulo-form'];
            $post->categoria_id = $dados['categoria-form'];
            $post->texto = $dados['texto-form'];
            $post->status = $dados['status-form'];

            if ($post->salvar()) {
                $this->mensagem->sucesso('Post atualizado com Sucesso')->flash();
                Helpers::redirecionar('admin/posts/listar');
            }
        }

        echo $this->template->renderizar('posts/formulario.html', [
            'post' => $post,
            'categorias' => (new CategoriaModelo())->busca()
        ]);
    }

    public function deletar(int $id): void
    {
//        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (is_int($id)) {
            $post = (new PostModelo())->buscaPorId($id);

            if (!$post) {
                $this->mensagem->alerta('O post que você está tentando deletar não existe!')->flash();
                Helpers::redirecionar('admin/posts/listar');
            } else {
                if($post->apagar("id = {$id}")){
                    $this->mensagem->sucesso('Post deletado com sucesso!')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }else{
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
                
                
            }
        }
    }
}
