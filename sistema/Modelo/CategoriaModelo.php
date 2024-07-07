<?php

namespace sistema\Modelo;

//use sistema\Nucleo\Conexao;
use sistema\Nucleo\Modelo;



/**
 * Classe CategoriaModelo: Define tabela do banco para categorias
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class CategoriaModelo extends Modelo
{
    /**
     * Envia ao construtor (super classe) a tabela de consulta de banco para categorias
     */
    public function __construct()
    {
        parent::__construct('categorias');
    }
    
    /**
     * Busca posts da caegoiria no BD
     * 
     * @param int $id
     * @return array|null
     */
    public function posts(int $id): ?array
    {
        $busca = (new PostModelo())->busca("categoria_id = {$id}");
        return $busca->resultado(true);
    }
    
// ----- ANTES DE CRIAR A SUPERCLASSE -----
//
//        const TABELA = 'categorias';
//             
//    public function busca(?string $termo = null): array
//    {
//        $termo = ($termo ? "WHERE {$termo}" : '');
//        
//        $query = "SELECT * FROM categorias {$termo}";
//        $stmt = Conexao::getInstancia()->query($query);
//        $resultado = $stmt->fetchAll();
//
//        return $resultado;
//    }
//
//    public function buscaPorId(int $id): bool|object
//    {
//        $query = "SELECT * FROM categorias WHERE id = {$id} ORDER BY id desc";
//        $stmt = Conexao::getInstancia()->query($query);
//        $resultado = $stmt->fetch();
//                
//        return $resultado;
//        
//    }
//
//    public function posts(int $id): array
//    {
//        $query = "SELECT * FROM posts WHERE categoria_id = {$id} AND status = 1 ORDER BY id desc";
//        $stmt = Conexao::getInstancia()->query($query);
//        $resultado = $stmt->fetchAll();
//
//        return $resultado;
//    }
//
//    public function armazenarb(array $dados): void
//    {
//        $query = "INSERT INTO categorias (titulo, texto, status) VALUES (?, ?, ?)";
//        $stmt = Conexao::getInstancia()->prepare($query);
//        $stmt->execute([$dados['titulo-form'], $dados['texto-form'], $dados['status-form']]);
//    }
//    
//    public function atualizarb(array $dados, int $id): void
//    {
//        $query = "UPDATE categorias SET titulo = ?, texto = ?, status = ? WHERE id = ?";
//        $stmt = Conexao::getInstancia()->prepare($query);
//        $stmt->execute([$dados['titulo-form'], $dados['texto-form'], $dados['status-form'], $id]);
//    }
//    
//    public function deletar(int $id): void
//    {
//        $query = "DELETE FROM categorias WHERE id = ?";
//        $stmt = Conexao::getInstancia()->prepare($query);
//        $stmt->execute([$id]);
//    }
//    
//    public function total(?string $termo = null): int
//    {
//        $termo = ($termo ? "WHERE {$termo}" : '');
//        
//        $query = "SELECT * FROM categorias {$termo}";
//        $stmt = Conexao::getInstancia()->prepare($query);
//        $stmt->execute();
//        
//        return $stmt->rowCount();
//    }
}
