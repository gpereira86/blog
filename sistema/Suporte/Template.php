<?php

namespace sistema\Suporte;

use Twig\Lexer;
use sistema\Nucleo\Helpers;
use sistema\Controlador\UsuarioControlador;

/**
 * Class do Twig Template: Construtor (exigida pela biblioteca) | Funções personalizadas
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Template
{

    private \Twig\Environment $twig;

    /**
     * construtor padrão Twig Template
     * 
     * @param string $diretorio
     */
    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    /**
     * Renderizar o view
     * 
     * @param string $view
     * @param array $dados
     * @return string
     */
    public function renderizar(string $view, array $dados): string
    {
        return $this->twig->render($view, $dados);
    }

    /**
     * Funções personalizadas do Twig Template
     * 
     * @return void
     */
    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    /**
                     * Consultar documentação em Helpers
                     */
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            }),
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('saudacao', function () {
                                        return Helpers::saudacao();
                                    })
                    ),
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('resumirTexto', function (string $texto, int $limite) {
                                        return Helpers::resumirTexto($texto, $limite);
                                    })
                    ),
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('flash', function () {
                                        return Helpers::flash();
                                    })
                    ),
                    /**
                     * Consultar documentação em UsuarioControlador
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('usuario', function () {
                                        return UsuarioControlador::usuario();
                                    })
                    ),
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('dataBr', function (string $data) {
                                        return Helpers::dataBr($data);
                                    })
                    ),
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('contarTempo', function (string $data) {
                                        return Helpers::contarTempo($data);
                                    })
                    ),
                    /**
                     * Calcula tempo de carregamento das páginas
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('tempoCarregamento', function () {
                                        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                                        return number_format($time, 4);
                                    })
                    ),
            )
        );
    }
}
