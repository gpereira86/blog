<?php

namespace sistema\Controlador\Admin;

use sistema\Modelo\PostModelo;
use sistema\Modelo\CategoriaModelo;
use sistema\Nucleo\Helpers;
use sistema\Biblioteca\Upload;

/**
 * AdminPosts define as funcionalidades de renderização possíveis em posts dentro do painel administrativo
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AdminPosts extends AdminControlador
{

    private ?string $capa;

    /**
     * API solicita registros em método POST ao BD dos posts | prepara para Datatable
     * 
     * @return void
     */
    public function datatable(): void
    {

        $datatable = $_REQUEST;
        $datatable = filter_var_array($datatable, FILTER_SANITIZE_SPECIAL_CHARS);

        $limite = $datatable['length'];
        $offset = $datatable['start'];
        $busca = $datatable['search']['value'];

        $colunas = [
            0 => 'id',
            2 => 'titulo',
            3 => 'categoria_id',
            4 => 'visitas',
            5 => 'status',
            
        ];

        $ordem = " " . $colunas[$datatable['order'][0]['column']] . " ";
        $ordem .= " " . $datatable['order'][0]['dir'] . " ";

        $post = (new PostModelo());

        if (empty($busca)) {
            $posts = (new PostModelo())->busca()->ordem($ordem)->limite($limite)->offset($offset);
            $total = (new PostModelo())->busca(null, 'COUNT(id)', 'id');
            $total = (int) $total->total();
        } else {
            $posts = (new PostModelo())->busca("id LIKE '%{$busca}%' OR titulo LIKE '%{$busca}%'")->limite($limite)->offset($offset);
            $total = $posts->total();
        }

        $dados = [];

        foreach ($posts->resultado(true) as $post) {
            $dados[] = [
                $post->id,
                $post->capa,
                $post->titulo,
                $post->categoria()->titulo ?? '------',
                Helpers::formatarNumero($post->visitas),
                $post->status,
//                '<a class="btn" href=" '.Helpers::url('admin/posts/editar/'.$post->id).' "><i class="fa-regular fa-pen-to-square"></i></a> <a class="btn" href=" '.Helpers::url('admin/posts/deletar/'.$post->id).' "><i class="fa-regular fa-trash-can"></i></a>'
            ];
        }

        $retorno = [
            "draw" => $datatable['draw'],
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $dados,
        ];

        echo json_encode($retorno);
    }

    /**
     * Renderizar itens para página de posts do painel (soliciita consulta ao BD)
     * 
     * @return void
     */
    public function listar(): void
    {
        $post = (new PostModelo());
        echo $this->template->renderizar('posts/listar.html', [
            //'posts' => (new PostModelo())->busca(null, 'COUNT(id)', 'id'),
            'total' => [
                'total' => (int) ($post->busca(null, 'COUNT(id)', 'id')->total()),
                'ativos' => (int) ($post->busca("status = :s", "s=1 COUNT(status)", 'status')->total()),
                'inativos' => (int) ($post->busca("status = :s", "s=0 COUNT(status)", 'status')->total()),
            ]
        ]);
    }

    /**
     * Recebe os dados do Form de post e prepara para incluir novo registro no banco

     * 
     * @return void
     */
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            if ($this->validarDados($dados)) {

                $post = new PostModelo();

                $post->usuario_id = $this->usuario->id;
                $post->categoria_id = $dados['categoria-form'];
                $post->slug = Helpers::slug($dados['titulo-form']);
                $post->titulo = $dados['titulo-form'];
                $post->texto = $dados['texto-form'];
                $post->status = $dados['status-form'];
                $post->capa = $this->capa;

                if ($post->salvar()) {
                    $this->mensagem->sucesso('Post Cadastrado com Sucesso')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }
        echo $this->template->renderizar('posts/formulario.html', [
            'categorias' => (new CategoriaModelo())->busca()->resultado(true),
            'post' => $dados
        ]);
    }

    /**
     * Recebe o id vindo como parâmetro da rota, depois recebe dados do Form editado
     * de posts e prepara para fazer Update no registro do banco
     * 
     * @param int $id
     * @return void
     */
    public function editar(int $id): void
    {
        $post = (new PostModelo())->buscaPorId($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            if ($this->validarDados($dados)) {

                $post = (new PostModelo())->buscaPorId($id);

                $post->usuario_id = $this->usuario->id;
                $post->categoria_id = $dados['categoria-form'];
                $post->slug = Helpers::slug($dados['titulo-form']);
                $post->titulo = $dados['titulo-form'];
                $post->texto = $dados['texto-form'];
                $post->status = $dados['status-form'];
                $post->atualizado_em = date('Y-m-d H:i:s');

                if (is_null($this->capa)) {
                    if (!is_null($post->capa)) {
                        $post->capa = $post->capa;
                    } else {
                        $post->capa = null;
                    }
                } elseif (!empty($_FILES['capa'])) {

                    if ($post->capa && file_exists("uploads/imagens/{$post->capa}")) {
                        unlink("uploads/imagens/{$post->capa}");
                    }
                    $post->capa = $this->capa;
                }

                if ($post->salvar()) {
                    $this->mensagem->sucesso('Post atualizado com Sucesso')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }

        echo $this->template->renderizar('posts/formulario.html', [
            'post' => $post,
            'categorias' => (new CategoriaModelo())->busca()->resultado(true),
        ]);
    }

    /**
     * Checa os dados do formulário
     * 
     * @param array $dados
     * @return bool
     */
    private function validarDados(array $dados): bool
    {



        if (!empty($_FILES['capa'])) {

            if ($_FILES['capa']['name'] == null) {
                $this->capa = null;
            } else {

                $upload = new Upload();

                $upload->arquivo($_FILES['capa'], Helpers::slug($dados['titulo-form']), 'imagens');

                if ($upload->getResultado()) {
                    $this->capa = $upload->getResultado();
                } else {
                    $this->mensagem->alerta($upload->getErro())->flash();
                    return false;
                }
            }
        }

        if (empty($dados['titulo-form'])) {
            $this->mensagem->alerta('Escreve um título para o Post!')->flash();
            return false;
        }
        if (empty($dados['texto-form'])) {
            $this->mensagem->alerta('Escreve um texto para o Post!')->flash();
            return false;
        }
        return true;
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
            $post = (new PostModelo())->buscaPorId($id);

            if (!$post) {
                $this->mensagem->alerta('O post que você está tentando deletar não existe!')->flash();
                Helpers::redirecionar('admin/posts/listar');
            } else {
                if ($post->apagar("id = {$id}")) {

                    if ($post->capa && file_exists("uploads/imagens/{$post->capa}")) {
                        unlink("uploads/imagens/{$post->capa}");
                    }

                    $this->mensagem->sucesso('Post deletado com sucesso!')->flash();
                    Helpers::redirecionar('admin/posts/listar');
                } else {
                    $this->mensagem->erro($post->erro())->flash();
                    Helpers::redirecionar('admin/posts/listar');
                }
            }
        }
    }
}
