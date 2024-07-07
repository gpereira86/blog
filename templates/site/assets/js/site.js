Fancybox.bind("[data-fancybox]", {
    hideScrollbar: false,
});

function adjustCarouselHeight() {
    var footerHeight = document.querySelector('.custom-footer').offsetHeight;
    var windowHeight = window.innerHeight;
    var carouselContainer = document.getElementById('carouselContainer');
    carouselContainer.style.height = (windowHeight - footerHeight) + 'px';
}

window.addEventListener('load', adjustCarouselHeight);
window.addEventListener('resize', adjustCarouselHeight);