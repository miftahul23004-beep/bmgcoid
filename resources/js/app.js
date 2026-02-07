import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Alpine Stores - configure before starting Alpine
Alpine.store('productView', {
    mode: 'list',
    init() {
        const saved = localStorage.getItem('productViewMode');
        if (saved) {
            this.mode = saved;
        }
    },
    toggle() {
        this.mode = this.mode === 'grid' ? 'list' : 'grid';
        localStorage.setItem('productViewMode', this.mode);
    }
});

// Start Alpine - prioritize first paint on mobile
Alpine.start();

// Defer non-critical JS to idle time (reduces TBT on mobile)
const runWhenIdle = (fn) => {
    if ('requestIdleCallback' in window) {
        requestIdleCallback(fn);
    } else {
        setTimeout(fn, 1);
    }
};

runWhenIdle(() => {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Lazy load images with IntersectionObserver (deferred)
runWhenIdle(() => {
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        }, { rootMargin: '200px' });

        lazyImages.forEach(img => imageObserver.observe(img));
    }
});

// Email Protection - Decode obfuscated emails (deferred to idle)
runWhenIdle(() => {
    const protectedEmails = document.querySelectorAll('.protected-email');
    
    protectedEmails.forEach(function(el) {
        const user = atob(el.dataset.u || '');
        const domain = atob(el.dataset.d || '');
        
        if (user && domain) {
            const email = user + '@' + domain;
            const emailText = el.querySelector('.email-text');
            
            if (emailText) {
                emailText.textContent = email;
            }
            
            el.href = 'mailto:' + email;
            el.onclick = null;
        }
    });
});
