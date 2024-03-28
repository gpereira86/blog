/* Hidden menu */
document.addEventListener("DOMContentLoaded", function() {
    const menuButton = document.getElementById('menu-button');
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.querySelector('.menu');

    menuButton.addEventListener('click', function() {
        menuToggle.checked = !menuToggle.checked; // Inverte o estado do input ao clicar no botão
        if (menuToggle.checked) {
            menu.classList.add('minimized'); // Adiciona a classe 'minimized' para minimizar o menu
        } else {
            menu.classList.remove('minimized'); // Remove a classe 'minimized' para restaurar o menu
        }
    });
});