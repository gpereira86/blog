<?php

namespace sistema\Controlador\Admin;

use sistema\Modelo\CategoriaModelo;
use sistema\Nucleo\Helpers;

/**
 * AdminCategorias define as funcionalidades de renderização possíveis em categorias dentro do painel administrativo
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class AdminCategorias extends AdminControlador
{
    
    /**
     * Renderizar itens para página de categorias do painel (solicita consulta ao BD)
     * 
     * @return void
     */
    public function listar(): void
    {
        $categoria = new CategoriaModelo();

        echo $this->template->renderizar('categorias/listar.html', [
            'categorias' => $categoria->busca()->ordem('titulo Asc')->resultado(true),
            'total' => [
                'total' => $categoria->busca()->total(),
                'ativos' => $categoria->busca('status=1')->total(),
                'inativos' => $categoria->busca('status=0')->total(),
            ]
        ]);
    }

    /**
     * Recebe os dados do Form de categorias e prepara para incluir novo registro no banco
     * 
     * @return void
     */
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {

            if ($this->validarDados($dados)) {

                $categoria = new CategoriaModelo();

                $categoria->titulo = $dados['titulo-form'];
                $categoria->texto = $dados['texto-form'];
                $categoria->status = $dados['status-form'];
//                $categoria->cadastrado_em = date('Y-m-d H:i:s');

                if ($categoria->salvar()) {
                    $this->mensagem->sucesso('Categoria cadastrada com Sucesso')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }

        $dados2 = [];
        $dados2['titulo'] = $dados['titulo-form'] ?? '';
        $dados2['texto'] = $dados['texto-form'] ?? '';
        $dados2['status'] = $dados['status-form'] ?? '';

        echo $this->template->renderizar('categorias/formulario.html', [
            'categoria' => $dados2,
        ]);
    }

    /**
     * Recebe o id vindo como parâmetro da rota, depois recebe dados do Form editado
     * de categorias e prepara para fazer Update no registro do banco
     * 
     * @param int $id
     * @return void
     */
    public function editar(int $id): void
    {
        $categoria = (new CategoriaModelo())->buscaPorId($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            if ($this->validarDados($dados)) {

                $categoria = (new CategoriaModelo())->buscaPorId($categoria->id);

                $categoria->slug = Helpers::slug($dados['titulo-form']);
                $categoria->titulo = $dados['titulo-form'];
                $categoria->texto = $dados['texto-form'];
                $categoria->status = $dados['status-form'];
                $categoria->atualizado_em = date('Y-m-d H:i:s');

                if ($categoria->salvar()) {
                    $this->mensagem->sucesso('Categoria atualizada com Sucesso')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }

        echo $this->template->renderizar('categorias/formulario.html', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Valida os dados do Form, não permite cadastro sem um título apenas
     * 
     * @param array $dados
     * @return bool
     */
    private function validarDados(array $dados): bool
    {
        if (empty($dados['titulo-form'])) {
            $this->mensagem->alerta('Escreva um título para a Categoria!')->flash();
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

        if (is_int($id)) {
            $categoria = (new CategoriaModelo())->buscaPorId($id);

            if (!$categoria) {
                $this->mensagem->alerta('A categoria que você está tentando deletar não existe!')->flash();
                Helpers::redirecionar('admin/categorias/listar');
                
            } elseif ($categoria->posts($categoria->id)) {
                $this->mensagem->alerta("A categoria {$categoria->titulo} tem posts cadastrados, dele ou altere os posts antes de deletar!")->flash();
                Helpers::redirecionar('admin/categorias/listar');
                
            } else {
                if ($categoria->apagar("id = {$id}")) {
                    $this->mensagem->sucesso('Categoria deletado com sucesso!')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro($categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
            }
        }
    }
}
