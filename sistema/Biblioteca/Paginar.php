<?php

namespace sistema\Biblioteca;

/**
 * Description of Paginar
 *
 * @author glauc
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

    public function limite(): int
    {
        return $this->limite;
    }

    public function offset(): int
    {
        return $this->offset;
    }

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

    private function anterior(): ?string
    {
        if ($this->pagina > 1) {
            return '<a href=" ' . $this->url . '/' . ($this->pagina - 1) . '" class="page-link" style="color: inherit;">Anterior</a>';
        }elseif($this->pagina > 2){
            return '<a href=" ' . $this->url . '/' . ($this->pagina - 1) . '" class="page-link text-decoration-none" style="color: inherit;">Anterior</a>';
        }

        return null;
        
    }

    private function primeira(): ?string
    {
        if ($this->pagina > 1) {
            return ' <a href=" ' . $this->url . '/1" class="page-link text-decoration-none" style="color: inherit;">Primeira </a>';
        }

        return null;
    }

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

    private function ultima(): ?string
    {
        if ($this->pagina < $this->total) {
            return '<a href=" ' . $this->url . '/' . $this->total . '" class="page-link text-decoration-none" style="color: inherit;"> Última</a>';
        }

        return null;
    }

    private function proximo(): ?string
    {
        if ($this->pagina < $this->total) {
            return '<a href=" ' . $this->url . '/' . ($this->pagina + 1) . '"class="page-link text-decoration-none" style="color: inherit;">Próximo</a>';
        }

        return null;
    }
    
    public function info(): string
    {
        $totalInicial = $this->offset + 1;
        $totalFinal = min($this->totalRegistros, $this->pagina * $this->limite);
        $totalRegistros = number_format($this->totalRegistros, 0, '.', '.');
        
        return "Mostrando {$totalInicial} até {$totalFinal} de {$totalRegistros} registros";
    }
    
}
