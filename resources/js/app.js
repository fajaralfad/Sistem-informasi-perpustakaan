// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';

// Set Alpine as global
window.Alpine = Alpine;

// Alpine Components
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
        // Prevent body scroll when menu is open
        document.body.style.overflow = this.open ? 'hidden' : '';
    },
    close() {
        this.open = false;
        document.body.style.overflow = '';
    }
}));

// Book Search Component
Alpine.data('bookSearch', () => ({
    query: '',
    results: [],
    isLoading: false,
    showResults: false,

    init() {
        // Setup debounced search
        this.$watch('query', () => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                if (this.query.length >= 3) {
                    this.search();
                } else {
                    this.results = [];
                    this.showResults = false;
                }
            }, 300);
        });
    },

    async search() {
        this.isLoading = true;
        this.showResults = true;
        
        try {
            const response = await fetch(`/api/books?search=${encodeURIComponent(this.query)}`);
            if (response.ok) {
                this.results = await response.json();
            } else {
                this.results = [];
            }
        } catch (error) {
            console.error('Search error:', error);
            this.results = [];
        } finally {
            this.isLoading = false;
        }
    },

    selectResult(result) {
        this.query = result.title;
        this.showResults = false;
        // Handle selection logic here
    }
}));

// Theme Toggle Component
Alpine.data('themeToggle', () => ({
    darkMode: false,

    init() {
        // Check for saved theme preference or default to light mode
        this.darkMode = localStorage.getItem('theme') === 'dark' || 
                       (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        this.updateTheme();
    },

    toggle() {
        this.darkMode = !this.darkMode;
        this.updateTheme();
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    },

    updateTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}));

// Initialize Alpine
Alpine.start();

// DOM Ready Functions
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    initSmoothScroll();
    
    // Initialize animations on scroll
    initScrollAnimations();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize parallax effects
    initParallax();
    
    // Initialize lazy loading
    initLazyLoading();
});

// Smooth Scroll Implementation
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed header
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Scroll Animations
function initScrollAnimations() {
    const animateElements = document.querySelectorAll('[data-animate]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const animationType = element.dataset.animate;
                
                element.classList.add(`animate-${animationType}`);
                observer.unobserve(element);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animateElements.forEach(el => observer.observe(el));
}

// Tooltip System
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', showTooltip);
        tooltip.addEventListener('mouseleave', hideTooltip);
        tooltip.addEventListener('focus', showTooltip);
        tooltip.addEventListener('blur', hideTooltip);
    });
}

function showTooltip(e) {
    const tooltipText = this.dataset.tooltip;
    const tooltipEl = document.createElement('div');
    
    tooltipEl.className = 'tooltip fixed z-50 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 pointer-events-none transition-opacity duration-200';
    tooltipEl.textContent = tooltipText;
    tooltipEl.style.opacity = '0';
    
    document.body.appendChild(tooltipEl);
    
    // Position tooltip
    const rect = this.getBoundingClientRect();
    const tooltipRect = tooltipEl.getBoundingClientRect();
    
    tooltipEl.style.top = `${rect.top - tooltipRect.height - 8}px`;
    tooltipEl.style.left = `${rect.left + (rect.width - tooltipRect.width) / 2}px`;
    
    // Fade in
    requestAnimationFrame(() => {
        tooltipEl.style.opacity = '1';
    });
    
    this.tooltipInstance = tooltipEl;
}

function hideTooltip() {
    if (this.tooltipInstance) {
        this.tooltipInstance.style.opacity = '0';
        setTimeout(() => {
            if (this.tooltipInstance) {
                this.tooltipInstance.remove();
                this.tooltipInstance = null;
            }
        }, 200);
    }
}

// Parallax Effects
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    if (parallaxElements.length === 0) return;
    
    function updateParallax() {
        const scrollTop = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            const yPos = -(scrollTop * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }
    
    // Throttle scroll events
    let ticking = false;
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                updateParallax();
                ticking = false;
            });
            ticking = true;
        }
    });
}

// Lazy Loading for Images
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
        });
    }
}

// Utility Functions
window.utils = {
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};