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
            'total' =>[
                'total' => $post->total(),
                'ativos' =>  $post->total('status = 1'),              
                'inativos' =>  $post->total('status = 0'),              
            ]
        ]);
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            //var_dump($dados);
            (new PostModelo())->armazenar($dados);
            $this->mensagem->sucesso('Post Cadastrado com Sucesso')->flash();
            Helpers::redirecionar('admin/posts/listar');
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
            (new PostModelo())->atualizar($dados, $id);
            $this->mensagem->sucesso('Post editado com Sucesso')->flash();
            Helpers::redirecionar('admin/posts/listar');
        }

        echo $this->template->renderizar('posts/formulario.html', [
            'post' => $post,
            'categorias' => (new CategoriaModelo())->busca()
        ]);
    }

    public function deletar(int $id): void
    {
        (new PostModelo())->deletar($id);
        Helpers::redirecionar('admin/posts/listar');
    }
}
