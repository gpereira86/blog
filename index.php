<?php
// Arquivo index responsável pela inicialização do sistema
require 'vendor/autoload.php';

require 'rotas.php';

//echo $pagina = (filter_input(INPUT_GET,'pagina',FILTER_VALIDATE_INT) ?? 1);
//$limite = 5;
//$offset = ($pagina-1) * $limite;
//echo '<hr>';
//
//$posts = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21];
//
//$paginar = array_slice($posts, $offset, $limite);
//
//$total = ceil(count($posts)/$limite);
//
//foreach ($paginar as $post){
//    echo $post.'<br>';
//}
//echo '<hr>';
//echo "Página {$pagina} de {$total}";
//echo '<hr>';
//
//if($pagina > 1){
//    echo '<a href="?pagina='.($pagina-1).'">Anterior</a>';    
//}
//
//$inicio = max(1,$pagina - 3);
//$fim = min($total, $pagina +3);
//
//echo ' ... ';
//
//for($i = $inicio; $i <= $fim; $i++){
//    if($i == $pagina){
//        echo $i.' ';
//    }   else{
//        echo ' <a href="?pagina='.$i.'">'.$i.'</a> ';
//    } 
//}
//
//echo ' ... ';
//
//if($pagina < $total){
//    echo ' <a href="?pagina='.($pagina+1).'"> Próximo</a>';    
//}
