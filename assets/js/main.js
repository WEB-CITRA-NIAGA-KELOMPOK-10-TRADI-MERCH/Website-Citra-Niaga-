document.addEventListener("DOMContentLoaded", function() {
    
    window.refreshIcons = function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };
    window.refreshIcons();

    const navbar = document.querySelector('nav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15 
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    window.observeAnimations = function() {
        const animatedElements = document.querySelectorAll('.fade-in-up:not(.is-visible)');
        animatedElements.forEach(el => observer.observe(el));
    };

    window.observeAnimations();

    const mutationObserver = new MutationObserver(() => {
        window.refreshIcons();
        window.observeAnimations();
    });
    
    mutationObserver.observe(document.body, { 
        childList: true, 
        subtree: true 
    });

    const mainImage = document.getElementById('main-gallery-image'); 
    const thumbnails = document.querySelectorAll('.gallery-thumbnail'); 

    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    }
});