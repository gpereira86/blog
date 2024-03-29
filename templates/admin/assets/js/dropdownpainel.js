const dropdownperfil = document.querySelector('.dropdown-perfil');
const dropdownmenu = document.querySelector('.dropdown-menu');
const body = document.querySelector('body');

// Função para remover a classe 'ativo' do dropdownmenu
const removerAtivo = () => {
    dropdownmenu.classList.remove('ativo');
};  

// Adiciona o event listener para clicar no body
body.addEventListener('click', (event) => {
    // Verifica se o clique não foi dentro do dropdownperfil ou dropdownmenu
    if (!dropdownperfil.contains(event.target) && !dropdownmenu.contains(event.target)) {
        removerAtivo();
    }
});

// Adiciona a função de toggle ao clicar no dropdownperfil
dropdownperfil.addEventListener('click', (event) => {
    event.stopPropagation(); // Impede a propagação do evento para o body
    dropdownmenu.classList.toggle('ativo');
});