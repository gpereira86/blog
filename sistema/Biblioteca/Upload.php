<?php

namespace sistema\Biblioteca;

/**
 * Uploads de arquivos limitados a 3 MB e nas extensões: pdf, png, jpg, jpeg, docx
 *
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Upload
{

    private ?string $diretorio;
    private ?array $arquivo;
    private ?string $nome;
    private ?string $subDiretorio;
    private ?int $tamanho;
    private ?string $resultado = null;
    private ?string $erro = null;

    /**
     * Getter resultados
     * 
     * @return string|null
     */
    public function getResultado(): ?string
    {
        return $this->resultado;
    }
    
    /**
     * Getter erros
     * 
     * @return string|null
     */
    public function getErro(): ?string
    {
        return $this->erro;
    }
    
    /**
     * Construtor chamador da função permite escolher uma pasta para salvar (cria essa pasta dentro da raiz do projeto)
     * 
     * @param string $diretorio
     */        
    public function __construct(string $diretorio = null)
    {
        $this->diretorio = $diretorio ?? 'uploads';

        if (!file_exists($this->diretorio) && !is_dir($this->diretorio)) {
            mkdir($this->diretorio, 0755);
        }
    }

    /**
     * Função de arquivo: Valida extensão e tamanho, depois cria os parâmetros personalizáveis 
     * (nome, subdiretório e tamanho) chamando suas respectivas funçÕes
     * 
     * @param array $arquivo
     * @param string $nome
     * @param string $subDiretorio
     * @param int $tamanho
     */
    public function arquivo(array $arquivo, string $nome = null, string $subDiretorio = null, int $tamanho = null)
    {
        $this->arquivo = $arquivo;
        $this->nome = $nome ?? pathinfo($this->arquivo['name'], PATHINFO_FILENAME);

        $this->subDiretorio = $subDiretorio ?? 'arquivos';

        $extensao = pathinfo($this->arquivo['name'], PATHINFO_EXTENSION);
        
        $this->tamanho = $tamanho ?? 3; 

        $extensoesValidas = [
            'pdf',
            'png',
            'jpg',
            'jpeg',
            'docx'
        ];

        $tiposValidos = [
            'application/pdf',
            //'text/plain',
            'image/png',
            'image/jpeg',
            'image/x-citrix-jpeg',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($extensao, $extensoesValidas)) {
            $this->erro = 'Extensão não permitida. Você só pode enviar aqrquivos: ' . implode(' .', $extensoesValidas);
        } elseif (!in_array($this->arquivo['type'], $tiposValidos)) {
            $this->erro = 'Tipo não permitido.';
        } elseif( $this->arquivo['size'] > $this->tamanho * (1024*1024)){
        
            $this->erro = "Arquivo muito grande, máximo permitido: {$this->tamanho} MB, Seu arquivo tem: {$this->arquivo['size']}.";
            
        }else {
            $this->criarSubDiretorio();
            $this->renomearArquivo();
            $this->moverArquivo();
        }
    }
    
    /**
     * Cria o subdiretório quando informado na função arquivo, do contrário salva no diretório criado no construtor
     * 
     * @return void
     */
    private function criarSubDiretorio(): void
    {
        if (!file_exists($this->diretorio . DIRECTORY_SEPARATOR . $this->subDiretorio) && !is_dir($this->diretorio . DIRECTORY_SEPARATOR . $this->subDiretorio)) {
            mkdir($this->diretorio . DIRECTORY_SEPARATOR . $this->subDiretorio, 0755);
        }
    }

    /**
     * Verifica se já existe um arquivo com mesmo nome no diretório especificado na função arquivo,
     * existindo ele gera um nome úniico para o arquiivo e o renomeia
     * 
     * @return void
     */
    private function renomearArquivo(): void
    {
        $arquivo = $this->nome . strrchr($this->arquivo['name'], '.');

        if (file_exists($this->diretorio . DIRECTORY_SEPARATOR . $this->subDiretorio . DIRECTORY_SEPARATOR . $arquivo)) {

            $arquivo = $this->nome . '-' . uniqid() . strrchr($this->arquivo['name'], '.');
        }
        $this->nome = $arquivo;
    }
    
    /**
     * Salva o arquivo no diretório e com nome definidos na função arquivo
     * 
     * @return void
     */
    private function moverArquivo(): void
    {
        if (move_uploaded_file($this->arquivo['tmp_name'], $this->diretorio . DIRECTORY_SEPARATOR . $this->subDiretorio . DIRECTORY_SEPARATOR . $this->nome)) {

            $this->resultado = $this->nome;
        } else {
            $this->resultado = null;
            $this->erro = 'Erro ao enviar o arquivo';
        }
    }
}
