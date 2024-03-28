$(document).ready(function(){
  // Adiciona o evento de clique no corpo da página
  $('body').on('click', function(e){
    // Verifica se o clique não foi dentro do menu
    if (!$(e.target).closest('.menu').length) {
      // Desmarca o input checkbox do menu
      $('#button-menu').prop('checked', false);
    }
  });
});