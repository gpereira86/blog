<?php

namespace sistema\Nucleo;

/**
 * Description of Sessao
 *
 * @author glauc
 */
class Sessao
{

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }
    
    public function criar(string $chave, mixed $valor): Sessao
    {
        $_SESSION[$chave] = (is_array($valor) ? (Object) $valor : $valor);
        
        return $this;
    }
    
    public function carregar():?object
    {
        return (Object) $_SESSION;
    }
    
    public function checar(string $chave):bool
    {
        return isset($_SESSION[$chave]);
    }
    
    public function limpar(string $chave): Sessao
    {
        unset($_SESSION[$chave]);
        return $this;
    }
    
    public function deletar(): Sessao
    {
        session_destroy();
        return $this;
    }
    
}  