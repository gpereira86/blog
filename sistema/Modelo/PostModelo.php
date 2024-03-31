<?php

namespace sistema\Modelo;

use sistema\Nucleo\Conexao;

/**
 * Classe PostModelo
 * 
 * @author Glauco Pereira
 */
class PostModelo
{

    public function busca(?string $termo = null): array
    {
        $termo = ($termo ? "WHERE {$termo}" : '');
        
        $query = "SELECT * FROM posts {$termo}";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();

        return $resultado;
    }

    public function buscaPorId(int $id): bool|object
    {
        $query = "SELECT * FROM posts WHERE id = {$id} ";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetch();

        return $resultado;
    }

    public function pesquisa(string $busca): array
    {
        $query = "SELECT * FROM posts WHERE status = 1 AND titulo LIKE '%{$busca}%'";
        $stmt = Conexao::getInstancia()->query($query);
        $resultado = $stmt->fetchAll();

        return $resultado;
    }

    public function armazenar(array $dados): void
    {
        //$query = "INSERT INTO posts (categoria_id, titulo, texto, status) VALUES (:categoraia-form, :titulo-form, :texto-form, :status-form)";
        $query = "INSERT INTO posts (categoria_id, titulo, texto, status) VALUES (?, ?,?,?)";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([$dados['categoria-form'], $dados['titulo-form'], $dados['texto-form'], $dados['status-form']]);
    }

    public function atualizar(array $dados, int $id): void
    {
        $query = "UPDATE posts SET categoria_id = ?, titulo = ?, texto = ?, status = ? WHERE id = ?";
        $stmt = Conexao::getInstancia()->prepare($query);
        $stmt->execute([$dados['categoria-form'], $dados['titulo-form'], $dados['texto-form'], $dados['status-form'], $id]);
    }
    
    public function deletar(int $id): void
    {
        $query = "DELETE FROM posts WHERE id = ?";
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
