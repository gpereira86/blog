<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;
use sistema\Nucleo\Modelo;



/**
 * Classe PostModelo
 * 
 * @author Glauco Pereira
 */
class PostModelo extends Modelo
{
    const TABELA = 'posts';
    
    public function __construct()
    {
        parent::__construct('posts');
    }

    
    public function buscaPorId(int $id): bool|object
    {
        $query = "SELECT * FROM ".TABELA." WHERE id = {$id} ";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();

        return $resultado;
    }

    public function pesquisa(string $busca): array
    {
        $query = "SELECT * FROM ".TABELA." WHERE status = 1 AND titulo LIKE '%{$busca}%'";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();

        return $resultado;
    }

    public function armazenar(array $dados): void
    {
        //$query = "INSERT INTO posts (categoria_id, titulo, texto, status) VALUES (:categoraia-form, :titulo-form, :texto-form, :status-form)";
        $query = "INSERT INTO ".TABELA." (categoria_id, titulo, texto, status) VALUES (?, ?,?,?)";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([$dados['categoria-form'], $dados['titulo-form'], $dados['texto-form'], $dados['status-form']]);
    }

    public function atualizar(array $dados, int $id): void
    {
        $query = "UPDATE ".TABELA." SET categoria_id = ?, titulo = ?, texto = ?, status = ? WHERE id = ?";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([$dados['categoria-form'], $dados['titulo-form'], $dados['texto-form'], $dados['status-form'], $id]);
    }
    
    public function deletar(int $id): void
    {
        $query = "DELETE FROM ".TABELA." WHERE id = ?";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([$id]);
    }
    
    public function total(?string $termo = null): int
    {        
        $termo = ($termo ? "WHERE {$termo}" : '');
        
        $query = "SELECT * FROM posts {$termo}";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}
