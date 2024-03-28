<?php

namespace sistema\Controlador;

use sistema\Nucleo\Helpers;
use sistema\Nucleo\Controlador;
use sistema\Modelo\PostModelo;
use sistema\Modelo\CategoriaModelo;

/**
 * Controle Geral
 * 
 * @author Glauco Pereira
 */
class SiteControlador extends Controlador
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
    }
    
    /**
     * 'POST'
     * @return void
     */
    public function index(): void
    {
        $posts = (new PostModelo())->busca();

        echo $this->template->renderizar('index.html', [
            'posts' => $posts,
            'categorias' => $this->categorias()
        ]);
    }
    
    /**
     * 'buscar'
     * @return void
     */
    public function buscar(): void
    {
        $buscar = filter_input(INPUT_POST, 'busca', FILTER_DEFAULT);
        if (isset($buscar)){
            $posts = (new PostModelo())->pesquisa($buscar);
            
            foreach ($posts as $post) {
                echo "<li class='list-group-item fw-bold'><a href=".Helpers::url('post/').$post->id." class='text-dark text-decoration-none'>$post->titulo</a></li>";                                
            }            
        }   
    }
    
    /**
     * View Post
     * @param int $id
     * @return void
     */
    public function post(int $id): void
    {
        $post = (new PostModelo())->buscaPorId($id);
        if (!$post) {
            Helpers::redirecionar('404');
        }

        echo $this->template->renderizar('post.html', [
            'post' => $post,
            'categorias' => $this->categorias()
        ]);
    }
    
     /**
     * Categorias
     * @return void
     */
    public function categorias()
    {
        return (new CategoriaModelo())->busca();
    }
    
    /**
    * View categoria
    * @return void
    */
    public function categoria(int $id): void
    {
        $posts = (new CategoriaModelo())->posts($id);
        
        echo $this->template->renderizar('categoria.html', [
            'posts' => $posts,
            'categorias' => $this->categorias()
        ]);
    }
    
    /**
     * View Sobre
     * @return void
     */
    public function sobre(): void
    {
        echo $this->template->renderizar('sobre.html', [
            'titulo' => 'Sobre Nós',
            'subtitulo' => 'teste de subtitulo'
        ]);
    }

     /**
     * View Erro404
     * @return void
     */
    public function erro404(): void
    {
        echo $this->template->renderizar('404.html', [
            'titulo' => 'Página Não Encontrada'
        ]);
    }
}
