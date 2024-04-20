<?php

namespace sistema\Controlador\Admin;

use sistema\Modelo\CategoriaModelo;
use sistema\Nucleo\Helpers;

/**
 * Description of AdminCategorias
 *
 * @author glauc
 */
class AdminCategorias extends AdminControlador
{
    public function listar(): void
    {
        $categoria = new CategoriaModelo();
        
        echo  $this->template->renderizar('categorias/listar.html', [
            'categorias' => $categoria->busca()->resultado(true),
            'total' =>[
                'total' => $categoria->total(),
                'ativos' =>  $categoria->total('status=1'),              
                'inativos' =>  $categoria->total('status=0'),              
            ]
            
        ]);
    }
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)){
            
            $categoria = new $CategoriaModelo();
            $categoria->titulo = $dados['titulo-form'];
            $categoria->texto = $dados['texto-form'];
            $categoria->status = $dados['status-form'];
            
            
            if ($categoria->salvar()) {
                $this->mensagem->sucesso('Post Cadastrado com Sucesso')->flash();
                Helpers::redirecionar('admin/categorias/listar');
            }
              
        }
        echo  $this->template->renderizar('categorias/formulario.html', []);
    }
    
        public function editar(int $id):void
    {
        $categoria = (new CategoriaModelo())->buscaPorId($id);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)){
            var_dump($dados);
            (new CategoriaModelo())->atualizar($dados, $id);
            Helpers::redirecionar('admin/categorias/listar');
        }
        
        echo $this->template->renderizar('categorias/formulario.html', [
            'categoria' => $categoria
        ]);
    }
    
        public function deletar(int $id):void
    {
        var_dump($dados);
            (new CategoriaModelo())->deletar($id);
            Helpers::redirecionar('admin/categorias/listar');
    }
}
