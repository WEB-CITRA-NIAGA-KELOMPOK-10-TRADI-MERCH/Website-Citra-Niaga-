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

    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach(carousel => {
        let isDragging = false;
        let startPos = 0;

        const images = carousel.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('dragstart', (e) => {
                e.preventDefault(); 
            });
        });

        carousel.addEventListener('mousedown', (e) => {
            isDragging = true;
            startPos = e.pageX;
            carousel.classList.add('is-dragging');
        });

        carousel.addEventListener('mouseleave', () => {
            isDragging = false;
            carousel.classList.remove('is-dragging');
        });

        carousel.addEventListener('mouseup', (e) => {
            if (!isDragging) return;
            isDragging = false;
            carousel.classList.remove('is-dragging');
            
            const endPos = e.pageX;
            const distance = startPos - endPos;

            if (distance > 50) {
                const bsCarousel = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
                bsCarousel.next();
            } else if (distance < -50) {
                const bsCarousel = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
                bsCarousel.prev();
            }
        });
    });
});