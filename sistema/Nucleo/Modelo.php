<?php

namespace sistema\Nucleo;

use sistema\Nucleo\Conexao;

/**
 * Description of Modelo
 *
 * @author glauc
 */
class Modelo
{

    protected $dados;
    protected $query;
    protected $erro;
    protected $parametros;
    protected $tabela;
    protected $ordem;
    protected $limite;
    protected $offset;

    public function __construct(string $tabela)
    {
        $this->tabela = $tabela;
    }

    public function ordem(string $ordem)
    {
        $this->ordem = " ORDER BY {$ordem}";
        return $this;
    }

    public function limite(string $limite)
    {
        $this->limite = " LIMIT {$limite}";
        return $this;
    }

    public function offset(string $offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    public function busca(?string $termos = null, ?string $parametros = null, string $colunas = '*')
    {
        if ($termos) {
            $this->query = "SELECT {$colunas} FROM " . this->tabela . " WHERE {$termos}";
            parse_str($parametros, $this->parametros);
            return $this;
        }

        $this->query = "SELECT {$colunas} FROM " . $this->tabela;
        return $this;
    }

    public function resultado(bool $todos = false)
    {
        try {

            $stmt = Conexao::getInstancia()->prepare($this->query . $this->ordem . $this->limite . $this->offset);
            $stmt->execute($this->parametros);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($todos) {
                return $stmt->fetchAll();
            }

            return $stmt->fetchObject();
        } catch (\PDOException $ex) {
            $this->erro = $ex;
            return null;
        }
    }

    protected function cadastrar(array $dados)
    {
        try {

            $colunas = implode(',', array_key($dados));
            $valores = ':' . implode(',:', array_key($dados));
            $query = "INSERT INTO " . $this->tabela . "({$colunas}) VALUES ({$valores})";

            $stmt = Conexao::getInstancia()->prepare($query);
            $stmt->execute($this->filtro($dados));

            return Conexao::getInstancia()->lastInsertId();
        } catch (\PDOException $ex) {
            $this->erro = $ex;
            return null;
        }
    }

    private function filtro(array $dados)
    {
        $filtro = [];

        foreach ($dados as $chave => $valor) {
            $filtro = [$chave] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }
    }
}
