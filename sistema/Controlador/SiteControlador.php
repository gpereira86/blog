<?php

namespace sistema\Controlador;

use sistema\Nucleo\Helpers;
use sistema\Nucleo\Controlador;
use sistema\Modelo\PostModelo;
use sistema\Modelo\CategoriaModelo;
use sistema\Biblioteca\Paginar;

/**
 * Controle Geral: Renderizações de HTML
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class SiteControlador extends Controlador
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
     * Renderizar itens para página index (solicita consulta ao BD) 
     * 
     * @return void
     */
    public function index(): void
    {
        $posts = (new PostModelo())->busca("status = 1");

        echo $this->template->renderizar('index.html', [
            'slides' => $posts->ordem('id DESC')->limite(3)->resultado(true),
            'posts' => $posts->ordem('id DESC')->limite(10)->offset(3)->resultado(true),
            'maisLidos' => (new PostModelo())->busca("status = 1")->ordem('visitas DESC')->limite(5)->resultado(true),
            'categorias' => $this->categorias()
        ]);
    }

    /**
     * Consulta de BD para pesquisa no campo do header
     * 
     * @return void
     */
    public function buscar(): void
    {
        $busca = filter_input(INPUT_POST, 'busca', FILTER_DEFAULT);
        if (isset($busca)) {
            $posts = (new PostModelo())->busca("status = 1 AND titulo LIKE '%{$busca}%'")->resultado(true);

            if ($posts) {
                foreach ($posts as $post) {
                    echo "<li class='list-group-item fw-bold'><a href=" . Helpers::url('post/') . $post->slug . " class='text-dark text-decoration-none'>$post->titulo</a></li>";
                }
            }
        }
    }

    /**
     * Renderizar itens para página post (solicita consulta ao BD por slug) 
     * 
     * @param string $slug
     * @return void
     */
    public function post(string $slug): void
    {
        $post = (new PostModelo())->buscaPorSlug($slug);

        if (!$post) {
            Helpers::redirecionar('404');
        }

        $post->salvarVisitas();

        echo $this->template->renderizar('post.html', [
            'post' => $post,
            'categorias' => $this->categorias()
        ]);
    }

    /**
     * Consulta de BD para filtro por categoria
     * 
     * @return void
     */
    public function categorias()
    {
        return (new CategoriaModelo())->busca('status = 1')->resultado(true);
    }

    /**
     * Renderizar itens para página categorias (solicita consulta ao BD por slug) 
     * 
     * @return void
     */
    public function categoria(string $slug, int $pagina = null): void
    {
        $categoria = (new CategoriaModelo())->buscaPorSlug($slug);

        if (!$categoria) {
            Helpers::redirecionar('404');
        }

        $categoria->salvarVisitas();

        $posts = (new PostModelo());
        $total = $posts->busca('categoria_id = :c', "c={$categoria->id} COUNT(id)", 'id')->total();

        $paginar = new Paginar(Helpers::url('categoria/' . $slug), ($pagina ?? 1), 4, 3, $total);

        echo $this->template->renderizar('categoria.html', [
            'posts' => $posts->busca("categoria_id = {$categoria->id}")->limite($paginar->limite())->offset($paginar->offset())->resultado(true),
            'paginacao' => $paginar->renderizar(),
            'infoPaginacao' => $paginar->info(),        
            //'posts' => (new CategoriaModelo())->posts($categoria->id),
            'categorias' => $this->categorias(),
        ]);
    }

    /**
     * Renderizar itens para página sobre (solicita consulta ao BD por slug) 
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
     * Renderizar página de Erro 404
     * @return void
     */
    public function erro404(): void
    {
        echo $this->template->renderizar('404.html', [
            'titulo' => 'Página Não Encontrada'
        ]);
    }
}
