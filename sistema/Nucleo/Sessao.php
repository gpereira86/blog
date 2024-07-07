<?php

namespace sistema\Nucleo;

/**
 * Classe responsável por manipular a sessão de usuário.
 *
 * Esta classe oferece métodos para gerenciar sessões de usuário no contexto de uma aplicação web.
 * Ela permite criar, carregar, checar, limpar e deletar variáveis de sessão, além de fornecer um
 * mecanismo de flash message para comunicação temporária com o usuário.
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Sessao
{

    /**
     * Construtor da classe Sessao.
     *
     * Inicia a sessão se ainda não estiver iniciada.
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Cria uma variável de sessão com a chave e valor fornecidos.
     *
     * @param string $chave A chave da variável de sessão.
     * @param mixed $valor O valor da variável de sessão, que pode ser qualquer tipo de dado.
     * @return Sessao Retorna a própria instância da classe Sessao para encadeamento de métodos.
     */
    public function criar(string $chave, mixed $valor): Sessao
    {
        $_SESSION[$chave] = (is_array($valor) ? (Object) $valor : $valor);

        return $this;
    }

    /**
     * Carrega todas as variáveis de sessão como um objeto.
     *
     * @return object|null Retorna um objeto contendo todas as variáveis de sessão, ou null se não houver sessão iniciada.
     */
    public function carregar(): ?object
    {
        return (Object) $_SESSION;
    }

    /**
     * Verifica se uma variável de sessão com a chave fornecida existe.
     *
     * @param string $chave A chave da variável de sessão a ser verificada.
     * @return bool Retorna true se a variável de sessão existe, false caso contrário.
     */
    public function checar(string $chave): bool
    {
        return isset($_SESSION[$chave]);
    }

    /**
     * Remove uma variável de sessão com a chave fornecida.
     *
     * @param string $chave A chave da variável de sessão a ser removida.
     * @return Sessao Retorna a própria instância da classe Sessao para encadeamento de métodos.
     */
    public function limpar(string $chave): Sessao
    {
        unset($_SESSION[$chave]);
        return $this;
    }

    /**
     * Destrói completamente a sessão atual.
     *
     * @return Sessao Retorna a própria instância da classe Sessao para encadeamento de métodos.
     */
    public function deletar(): Sessao
    {
        session_destroy();
        return $this;
    }

    /**
     * Método mágico para acessar variáveis de sessão como propriedades da classe.
     *
     * @param string $atributo O nome da variável de sessão a ser acessada.
     * @return mixed|null Retorna o valor da variável de sessão se existir, ou null caso contrário.
     */
    public function __get($atributo)
    {
        if (!empty($_SESSION[$atributo])) {
            return $_SESSION[$atributo];
        }
    }

    /**
     * Retorna e remove a mensagem flash da sessão, se existir.
     *
     * Uma mensagem flash é uma mensagem temporária usada para comunicar informações
     * entre requisições, geralmente para mostrar feedback ao usuário após uma ação.
     *
     * @return Mensagem|null Retorna a mensagem flash se existir, ou null caso contrário.
     */
    public function flash(): ?Mensagem
    {
        if ($this->checar('flash')) {
            $flash = $this->flash;
            $this->limpar('flash');

            return $flash;
        }
        return null;
    }
}
