document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.section-header, .stat-card, .credit-card, .lyrics-wrapper');
    
    fadeElements.forEach(el => el.classList.add('fade-in'));

    function handleScroll() {
        fadeElements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            
            if (rect.top < windowHeight * 0.85) {
                el.classList.add('visible');
            }
        });
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll();

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    const albumArt = document.querySelector('.album-art img');
    if (albumArt) {
        albumArt.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02) rotate(1deg)';
            this.style.transition = 'transform 0.5s ease';
        });
        
        albumArt.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    const lyricsLines = document.querySelectorAll('.lyrics-text p:not(.verse-label)');
    lyricsLines.forEach((line, index) => {
        line.style.animationDelay = `${index * 0.05}s`;
    });

    const creditCards = document.querySelectorAll('.credit-card');
    creditCards.forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.1}s`;
    });

    function createParticle() {
        const hero = document.querySelector('.hero');
        if (!hero) return;
        
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(212, 175, 55, 0.3);
            border-radius: 50%;
            pointer-events: none;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation: floatUp 8s linear forwards;
        `;
        hero.appendChild(particle);
        
        setTimeout(() => particle.remove(), 8000);
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translateY(0) scale(1);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) scale(0.5);
            }
        }
    `;
    document.head.appendChild(style);

    setInterval(createParticle, 500);

    const title = document.querySelector('.song-title');
    if (title) {
        const text = title.textContent;
        title.innerHTML = '';
        text.split('').forEach((char, i) => {
            const span = document.createElement('span');
            span.textContent = char;
            span.style.cssText = `
                display: inline-block;
                opacity: 0;
                animation: fadeInChar 0.5s forwards;
                animation-delay: ${i * 0.05}s;
            `;
            title.appendChild(span);
        });
    }

    const charStyle = document.createElement('style');
    charStyle.textContent = `
        @keyframes fadeInChar {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(charStyle);
});
