<?php

namespace sistema\Biblioteca;

/**
 * Criia uma paginação de acordo com parâmetros de quantitativo por página 
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Paginar
{

    private String $url;
    private int $limite;
    private int $offset;
    private int $pagina;
    private int $total;
    private int $arredor;
    private int $totalRegistros;

    /**
     * Contrutor: Cria os parâmetros da paginação
     * 
     * @param String $url
     * @param int $pagina
     * @param int $limite
     * @param int $arredor
     * @param int $total
     */
    public function __construct(
            String $url,
            int $pagina = 1,
            int $limite = 10,
            int $arredor = 3,
            int $total = 0
    )
    {
        $this->url = $url;
        $this->pagina = $pagina;
        $this->limite = $limite;
        $this->offset = ($this->pagina - 1) * $this->limite;
        $this->arredor = $arredor;
        $this->total = ceil($total / $this->limite);
        $this->totalRegistros = $total;
    }

    /**
     * Limite: quantidade de Itens por página
     * 
     * @return int
     */
    public function limite(): int
    {
        return $this->limite;
    }
    
    /**
     * Offset: Define a partir de qual item a página atual iniciará
     * 
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * Renderiza a paginação para inclusão no HTML
     * 
     * @return String
     */
    public function renderizar(): String
    {
        $paginacao = '<ul class="pagination">';
        $paginacao .= '<li class="page-item">'.$this->anterior().'</li>';
        $paginacao .= '<li class="page-item">'.$this->primeira().'</li>';
        $paginacao .= '<li class="page-item">'.$this->barraPaginacao().'</li>';
        $paginacao .= '<li class="page-item">'.$this->ultima().'</li>';
        $paginacao .= '<li class="page-item">'.$this->proximo().'</li>';
        $paginacao .= '</ul>';

        return $paginacao;
    }

    /**
     * Criar lógica do item "anterior" para ser renderizado 
     * 
     * @return string|null
     */
    private function anterior(): ?string
    {
        if ($this->pagina > 1) {
            return '<a href=" ' . $this->url . '/' . ($this->pagina - 1) . '" class="page-link" style="color: inherit;">Anterior</a>';
        }elseif($this->pagina > 2){
            return '<a href=" ' . $this->url . '/' . ($this->pagina - 1) . '" class="page-link text-decoration-none" style="color: inherit;">Anterior</a>';
        }

        return null;
        
    }
    
    /**
     * Criar lógica do item "primeira" para ser renderizado
     * 
     * @return string|null
     */
    private function primeira(): ?string
    {
        if ($this->pagina > 1) {
            return ' <a href=" ' . $this->url . '/1" class="page-link text-decoration-none" style="color: inherit;">Primeira </a>';
        }

        return null;
    }

    /**
     * Criar lógica do item "barraPaginacao" para ser renderizado
     *  
     * @return string|null
     */
    private function barraPaginacao(): ?string
    {
        $inicio = max(1, $this->pagina - $this->arredor);
        $fim = min($this->total, $this->pagina + $this->arredor);
        
        $navegacao = null;

        for ($i = $inicio; $i <= $fim; $i++) {
            if ($i == $this->pagina) {
                $navegacao .= '<span class="page-link text-decoration-none" style="color: red;" >'.$i.'</span>' ;
            } else {
                $navegacao .= '<li class="page-item"><a href=" ' .$this->url. '/' . $i . '" class="page-link text-decoration-none" style="color: inherit;">' . $i . '</a></li>';
            }
            
        }

        return $navegacao;
    }

    /**
     * Criar lógica do item "ultima" para ser renderizado
     * 
     * @return string|null
     */
    private function ultima(): ?string
    {
        if ($this->pagina < $this->total) {
            return '<a href=" ' . $this->url . '/' . $this->total . '" class="page-link text-decoration-none" style="color: inherit;"> Última</a>';
        }

        return null;
    }

    /**
     * Criar lógica do item "proximo" para ser renderizado
     * 
     * @return string|null
     */
    private function proximo(): ?string
    {
        if ($this->pagina < $this->total) {
            return '<a href=" ' . $this->url . '/' . ($this->pagina + 1) . '"class="page-link text-decoration-none" style="color: inherit;">Próximo</a>';
        }

        return null;
    }
    
    /**
     * Criia o texto a ser exibido juntamente com a paginação, nele constam 
     * os valores exibidos e do total de registros daquela consulta
     * 
     * @return string
     */
    public function info(): string
    {
        $totalInicial = $this->offset + 1;
        $totalFinal = min($this->totalRegistros, $this->pagina * $this->limite);
        $totalRegistros = number_format($this->totalRegistros, 0, '.', '.');
        
        return "Mostrando {$totalInicial} até {$totalFinal} de {$totalRegistros} registros";
    }
    
}
