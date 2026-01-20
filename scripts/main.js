/**
 * main.js by CN8VX
 * Consolidated script for ConnectLogs project
 * Handles auto-refresh and UI interactions for all pages
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        refreshInterval: 5000, // 5 seconds
        scrollThreshold: 300,  // pixels before showing back-to-top button
        animationDuration: 800, // scroll animation duration in ms
        themeStorageKey: 'connectlogs-theme' // LocalStorage key for theme
    };

    // State
    let refreshTimer = null;
    let isRefreshing = false;

    /**
     * Update action states (Connected/Disconnected) with color classes
     */
    function updateActionStates() {
        const actions = document.querySelectorAll("td.action");
        
        actions.forEach(td => {
            td.classList.remove("connected", "disconnected");
            
            const text = td.textContent.trim().toLowerCase();
            
            if (text === "connected") {
                td.classList.add("connected");
            } else if (text === "disconnected") {
                td.classList.add("disconnected");
            }
        });
    }

    /**
     * Auto-refresh page content
     * Detects current page and fetches updated content
     */
    function autoRefreshPage() {
        if (isRefreshing) return;
        
        isRefreshing = true;
        
        // Get current page filename
        const page = window.location.pathname.split('/').pop() || 'index.php';
        
        fetch(page)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                // Store scroll position before update
                const scrollPos = window.scrollY;
                
                // Store back-to-top button reference
                const backToTopBtn = document.querySelector('.back_to_top');
                
                // Create temporary container to parse new content
                const temp = document.createElement('html');
                temp.innerHTML = data;
                
                // Extract only body content
                const newBody = temp.querySelector('body');
                if (newBody) {
                    // Save back-to-top button state
                    const hadShowClass = backToTopBtn && backToTopBtn.classList.contains('show');
                    
                    // Update body content
                    document.body.innerHTML = newBody.innerHTML;
                    
                    // Restore back-to-top button state if it exists
                    const newBackToTopBtn = document.querySelector('.back_to_top');
                    if (newBackToTopBtn && hadShowClass) {
                        newBackToTopBtn.classList.add('show');
                    }
                }
                
                // Restore scroll position
                window.scrollTo(0, scrollPos);
                
                // Reapply action states
                updateActionStates();
                
                // Reinitialize theme toggle
                initThemeToggle();
                
                // Reinitialize back-to-top button with a small delay
                setTimeout(() => {
                    initBackToTop();
                }, 100);
            })
            .catch(error => {
                console.error("Error during fetch operation:", error);
            })
            .finally(() => {
                isRefreshing = false;
            });
    }

    /**
     * Start auto-refresh for specified pages
     */
    function startAutoRefresh() {
        const page = window.location.pathname.split('/').pop() || 'index.php';
        const refreshPages = ['index.php', 'allstar.php', 'echoLink.php'];
        
        // Only enable refresh on specific pages
        if (refreshPages.includes(page)) {
            console.log('Auto-refresh enabled for:', page);
            refreshTimer = setInterval(autoRefreshPage, CONFIG.refreshInterval);
        } else {
            console.log('Auto-refresh disabled for:', page);
        }
    }

    /**
     * Stop auto-refresh (useful for cleanup)
     */
    function stopAutoRefresh() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }
    }

    /**
     * Initialize back-to-top button functionality
     * Pure JavaScript implementation (no jQuery required)
     */
    function initBackToTop() {
        const backToTopBtn = document.querySelector('.back_to_top');
        
        if (!backToTopBtn) {
            return; // Button not present on this page
        }

        console.log('Initializing back-to-top button');

        // Create a single scroll handler
        if (!window._backToTopHandler) {
            window._backToTopHandler = function() {
                const btn = document.querySelector('.back_to_top');
                if (!btn) return;
                
                if (window.scrollY > CONFIG.scrollThreshold) {
                    btn.classList.add('show');
                } else {
                    btn.classList.remove('show');
                }
            };
        }

        // Smooth scroll to top animation
        function scrollToTop(e) {
            e.preventDefault();
            
            const startPosition = window.scrollY;
            const startTime = performance.now();
            
            function animation(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / CONFIG.animationDuration, 1);
                
                // Easing function (easeInOutCubic) for smooth animation
                const easing = progress < 0.5
                    ? 4 * progress * progress * progress
                    : 1 - Math.pow(-2 * progress + 2, 3) / 2;
                
                window.scrollTo(0, startPosition * (1 - easing));
                
                if (progress < 1) {
                    requestAnimationFrame(animation);
                }
            }
            
            requestAnimationFrame(animation);
            return false;
        }

        // Remove old click listener and add new one
        const newBtn = backToTopBtn.cloneNode(true);
        backToTopBtn.parentNode.replaceChild(newBtn, backToTopBtn);
        newBtn.addEventListener('click', scrollToTop);

        // Remove and re-add scroll listener
        window.removeEventListener('scroll', window._backToTopHandler);
        window.addEventListener('scroll', window._backToTopHandler, { passive: true });

        // Initial check
        window._backToTopHandler();
    }

    /**
     * Initialize Dark/Light theme toggle
     */
    function initThemeToggle() {
        // Load saved theme or default to light
        const savedTheme = localStorage.getItem(CONFIG.themeStorageKey) || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Create theme toggle button if it doesn't exist
        let themeToggle = document.querySelector('.theme-toggle');
        if (!themeToggle) {
            themeToggle = document.createElement('button');
            themeToggle.className = 'theme-toggle';
            themeToggle.setAttribute('aria-label', 'Toggle theme');
            themeToggle.innerHTML = `
                <span class="theme-icon sun-icon">🌙</span>
                <span class="theme-icon moon-icon">☀️</span>
            `;
            document.body.appendChild(themeToggle);
        }
        
        // Toggle theme function
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem(CONFIG.themeStorageKey, newTheme);
            
            console.log('Theme switched to:', newTheme);
        }
        
        // Remove old listener and add new one
        const newToggle = themeToggle.cloneNode(true);
        themeToggle.parentNode.replaceChild(newToggle, themeToggle);
        newToggle.addEventListener('click', toggleTheme);
    }

    /**
     * Initialize all features when DOM is ready
     */
    function init() {
        // Initialize theme toggle (must be first)
        initThemeToggle();
        
        // Apply action states on initial load
        updateActionStates();
        
        // Initialize back-to-top button
        initBackToTop();
        
        // Start auto-refresh
        startAutoRefresh();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // DOM already loaded
        init();
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopAutoRefresh);

    // Expose public API (optional, for manual control)
    window.ConnectLogs = {
        updateActionStates,
        startAutoRefresh,
        stopAutoRefresh,
        initBackToTop,
        initThemeToggle,
        config: CONFIG
    };


})();
