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

    private string $acaoCadastrar = 'cadastrarUser';
    private string $acaoEditar = 'editarUser';
    private string $acaoDeletar = 'deletarUser';

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
                $categoria->slug = Helpers::slug($dados['titulo-form']) . uniqid();
//                $categoria->cadastrado_em = date('Y-m-d H:i:s');
//
//                if (Helpers::validarAcao($acao)) {
//                    if ($categoria->salvar()) {
//                        $this->mensagem->sucesso('Categoria cadastrada com Sucesso. | ' . Helpers::contadorAcao($acao) . ' de 5 permitidos.')->flash();
//                        Helpers::redirecionar('admin/categorias/listar');
//                    } else {
//                        Helpers::decrementarAcao($acao);
//                        $this->mensagem->erro(' O Cadastro ' . (Helpers::contadorAcao($acao) + 1) . ' dos 5 permitidos apresentou o erro código: ' . $categoria->erro() . '.')->flash();
//                    }
//                } else {
//                    $this->mensagem->erro('Número de alterações excedeu o limite. Já utilizou ' . Helpers::contadorAcao($acao) . ' vezes permitidas.')->flash();
//                    Helpers::redirecionar('admin/categorias/listar');
//                }

                $acao = $this->acaoCadastrar;
                if ($categoria->salvar($acao)) {
                    $this->mensagem->sucesso('Categoria cadastrada com Sucesso | ' . (Helpers::contadorAcao($acao, 'msg')))->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro(Helpers::contadorAcao($acao, 'msg') . ' | Erro ' . $categoria->erro())->flash();
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

//                if (Helpers::validarAcao($acao)) {
//                    if ($categoria->salvar()) {
//                        $this->mensagem->sucesso('Categoria atualizada com Sucesso. | ' . Helpers::contadorAcao($acao) . ' de 5 permitidos.')->flash();
//                        Helpers::redirecionar('admin/categorias/listar');
//                    } else {
//                        Helpers::decrementarAcao($acao);
//                        $this->mensagem->erro(' A alteração ' . (Helpers::contadorAcao($acao) + 1) . ' das 5 permitidas apresentou o erro código: ' . $categoria->erro() . '.')->flash();
//                    }
//                } else {
//                    $this->mensagem->erro('Número de alterações excedeu o limite. Já utilizou ' . Helpers::contadorAcao($acao) . ' vezes permitidas.')->flash();
//                    Helpers::redirecionar('admin/categorias/listar');
//                }

                if ($id <= 5) {
                    $this->mensagem->alerta('Categoria padrão do sistema (Ids: de 1 ao 5) não podem ser alteradas/excluídas. Para editar/ecluir uma categoria, primeiramente cadastre uma nova.')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $acao = $this->acaoEditar;
                    if ($categoria->salvar($acao)) {
                        $this->mensagem->sucesso('Categoria atualizada com Sucesso | ' . Helpers::contadorAcao($acao, 'msg'))->flash();
                        Helpers::redirecionar('admin/categorias/listar');
                    } else {
                        $this->mensagem->erro(Helpers::contadorAcao($acao, 'msg') . ' |  Erro ' . $categoria->erro())->flash();
                        Helpers::redirecionar('admin/categorias/listar');
                    }
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




//                if (Helpers::validarAcao($acao)) {
//                    
//                    if ($categoria->apagar("id = {$id}")) {
//                        $this->mensagem->sucesso('Categoria deletada com sucesso. | ' . Helpers::contadorAcao($acao) . ' de 5 permitidos.')->flash();
//                        Helpers::redirecionar('admin/categorias/listar');
//                    } else {
//                        Helpers::decrementarAcao($acao);
//                        $this->mensagem->erro('A remoção de registro ' . (Helpers::contadorAcao($acao) + 1) . ' das 5 permitidas apresentou o erro código: ' . $categoria->erro() . '.')->flash();
//                    }
//                } else {
//                    $this->mensagem->erro('Número de remoções excedeu o limite. Já utilizou ' . Helpers::contadorAcao($acao) . ' vezes permitidas.')->flash();
//                    Helpers::redirecionar('admin/categorias/listar');
//                }
                
                if ($id <= 5) {
                    $this->mensagem->alerta('Categoria padrão do sistema (Ids: de 1 ao 5) não podem ser alteradas/excluídas. Para editar/ecluir uma categoria, primeiramente cadastre uma nova.')->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                $acao = $this->acaoDeletar;
                if ($categoria->apagar("id = {$id}", $acao)) {
                    $this->mensagem->sucesso('Categoria deletado com sucesso!  | ' . Helpers::contadorAcao($acao, 'msg'))->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                } else {
                    $this->mensagem->erro(Helpers::contadorAcao($acao, 'msg') . ' | Erro ' . $categoria->erro())->flash();
                    Helpers::redirecionar('admin/categorias/listar');
                }
                }
            }
        }
    }
}
