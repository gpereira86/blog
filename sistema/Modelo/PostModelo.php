<?php

namespace sistema\Modelo;

//use sistema\Nucleo\Conexao;
use sistema\Nucleo\Modelo;

/**
 * Classe PostModelo: Define tabela do banco para posts
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class PostModelo extends Modelo
{
    /**
     * Envia ao construtor (super classe) a tabela de consulta de banco para posts
     */
    public function __construct()
    {
        parent::__construct('posts_fake');
    }

    /**
     * Busca de categoria pelo id cadastrado no post
     * 
     * @return CategoriaModelo|null
     */
    public function categoria(): ?CategoriaModelo
    {
        if($this->categoria_id){
            return (new CategoriaModelo())->buscaPorId($this->categoria_id);
        }
        return null;
    }
    
    /**
     * Busca de usuario pelo id cadastrado no post
     * 
     * @return UsuarioModelo|null
     */
    public function usuario(): ?UsuarioModelo
    {
        if($this->usuario_id){
            return (new UsuarioModelo())->buscaPorId($this->usuario_id);
        }
        return null;
    }
    
    /**
     * Verifica o slug e salva na função do parente
     * 
     * @return UsuarioModelo|null
     */
    public function salvar():bool
    {
        $this->slug();
        return parent::salvar();
    }
    
}
