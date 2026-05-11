function initMobileMenu() {
    const btn = document.getElementById('mobile-menu-btn');
    const mobileNav = document.getElementById('mobile-nav');
    const iconMenu = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    if (btn) {
        btn.addEventListener('click', () => {
            mobileNav.classList.toggle('hidden');
            mobileNav.classList.toggle('flex');
            iconMenu.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    }
}

function initSlider() {
    const track = document.getElementById('hero-slider-track');
    if (!track) return;
    const slides = Array.from(track.children);
    const nextBtn = document.getElementById('slider-next');
    const prevBtn = document.getElementById('slider-prev');

    let currentIndex = 0;
    let autolayInterval = null;

    function updateSlider() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider();
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoplay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoplay();
        });
    }

    function resetAutoplay() {
        if (autolayInterval) clearInterval(autolayInterval);
        autolayInterval = setInterval(nextSlide, 5000);
    }

    // Initialize autoplay
    resetAutoplay();
}

function initToc() {
    const tocContainer = document.getElementById('toc-content');
    const articleContent = document.querySelector('.prose');
    
    if (tocContainer && articleContent) {
        // Find all h2, h3, h4 headings inside the prose content
        const headings = articleContent.querySelectorAll('h2, h3, h4');
        
        if (headings.length > 0) {
            // Create a list container
            const tocList = document.createElement('ul');
            tocList.className = 'space-y-3 text-sm text-gray-600';
            
            // Array to hold the TOC links for the observer
            const tocLinks = [];
            
            headings.forEach((heading, index) => {
                // Ensure heading has an ID
                if (!heading.id) {
                    // Create slug from text: "WAF Evasion Comparison" -> "waf-evasion-comparison"
                    heading.id = heading.textContent.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '') || `heading-${index}`;
                }
                
                // Add scroll margin to prevent sticky header from covering the heading
                heading.classList.add('scroll-mt-24');
                
                const listItem = document.createElement('li');
                
                // Add some indentation based on heading level
                if (heading.tagName === 'H3') {
                    listItem.className = 'ml-4';
                } else if (heading.tagName === 'H4') {
                    listItem.className = 'ml-8';
                }
                
                const link = document.createElement('a');
                link.href = `#${heading.id}`;
                link.className = 'toc-link hover:text-defenxor-red transition-colors block';
                link.textContent = heading.textContent;
                
                // Smooth scroll behavior
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Update URL without jump
                    history.pushState(null, null, `#${heading.id}`);
                });
                
                listItem.appendChild(link);
                tocList.appendChild(listItem);
                tocLinks.push(link);
            });
            
            // Clear the "No headings found" text and append our list
            tocContainer.innerHTML = '';
            tocContainer.appendChild(tocList);
            
            // Set up Intersection Observer for active state highlighting
            const observerOptions = {
                root: null,
                rootMargin: '-100px 0px -60% 0px', // Trigger when heading is near the top
                threshold: 0
            };
            
            const headingObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Remove active class from all links
                        tocLinks.forEach(link => {
                            link.classList.remove('font-bold', 'text-defenxor-red');
                            link.classList.add('text-gray-600');
                        });
                        
                        // Add active class to the currently intersecting heading's link
                        const activeLink = tocList.querySelector(`a[href="#${entry.target.id}"]`);
                        if (activeLink) {
                            activeLink.classList.remove('text-gray-600');
                            activeLink.classList.add('font-bold', 'text-defenxor-red');
                        }
                    }
                });
            }, observerOptions);
            
            // Observe all headings
            headings.forEach(heading => headingObserver.observe(heading));
        }
    }
}

function initVideoPlayer() {
    const video = document.getElementById('siapaKamiVideo');
    const overlay = document.getElementById('videoOverlay');

    if (video && overlay) {
        overlay.addEventListener('click', () => {
            video.play();
        });

        video.addEventListener('play', () => {
            overlay.style.display = 'none';
        });
    }
}

// Run initializers safely since this script is dynamically injected
function runInitializers() {
    initMobileMenu();
    initSlider();
    initToc();
    initVideoPlayer();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runInitializers);
} else {
    runInitializers();
}
