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

    protected $contadorArray;

    /**
     * Construtor da classe Sessao.
     *
     * Inicia a sessão se ainda não estiver iniciada e armazena o IP do usuário na sessão.
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        // Armazena o IP do usuário na sessão se ainda não estiver definido
        if (!$this->checar('ip')) {
            $this->criar('ip', $this->obterIpUsuario());
        }

        // Inicializa o contador de ações se ainda não estiver definido como um array
        if (!$this->checar('acao_contadores')) {
            $_SESSION['acao_contadores'] = [];
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
        $_SESSION[$chave] = $valor;
        return $this;
    }

    /**
     * Carrega todas as variáveis de sessão como um objeto.
     *
     * @return object|null Retorna um objeto contendo todas as variáveis de sessão, ou null se não houver sessão iniciada.
     */
    public function carregar(): ?object
    {
        return (object) $_SESSION;
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
        return $this->checar($atributo) ? $_SESSION[$atributo] : null;
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

    /**
     * Obtém o IP do usuário.
     *
     * @return string Retorna o IP do usuário.
     */
    public function obterIpUsuario(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Obtém o IP armazenado na sessão.
     *
     * @return string|null Retorna o IP da sessão ou null se não estiver definido.
     */
    public function obterIpSessao(): ?string
    {
        return $this->checar('ip') ? $_SESSION['ip'] : null;
    }

    /**
     * Incrementa o contador de ações para uma ação específica.
     *
     * @param string $acao O nome da ação.
     * @return void
     */
    public function incrementarAcao(string $acao): void
    {
        if (!isset($_SESSION['acao_contadores'][$acao])) {
            $_SESSION['acao_contadores'][$acao] = 0;
        }

        $_SESSION['acao_contadores'][$acao]++;
    }

    /**
     * Decrementa o contador de ações para uma ação específica.
     *
     * @param string $acao O nome da ação.
     * @return void
     */
    public function decrementarAcao(string $acao): void
    {
        if (isset($_SESSION['acao_contadores'][$acao]) && $_SESSION['acao_contadores'][$acao] >= 1) {
            $_SESSION['acao_contadores'][$acao]--;
        } else {
            $_SESSION['acao_contadores'][$acao] = 0;
        }
    }

    /**
     * Verifica se o limite de ações foi atingido para uma ação específica.
     *
     * @param string $acao O nome da ação.
     * @return bool Retorna true se o limite de ações foi atingido, false caso contrário.
     */
    public function limiteAcoesAtingido(string $acao): bool
    {
        // Verifica se a ação existe no array de contadores antes de acessá-la
        return isset($_SESSION['acao_contadores'][$acao]) && $_SESSION['acao_contadores'][$acao] >= QTDE_PERMITIDA;
    }

    /*     * `
     * Obtém o contador de ações para uma ação específica.
     *
     * @param string $acao O nome da ação.
     * @return int Retorna o contador de ações para a ação.
     */

    public function obterContadorAcao(string $acao): int
    {
        // Retorna 0 se a ação não estiver definida no array de contadores
        return $_SESSION['acao_contadores'][$acao] ?? 0;
    }
}
