<?php
// 1. DETECT ACTIVE PAGE PATH
// This grabs the current filename (e.g., 'index.php', 'archive.php') to apply styles dynamically
$active_page = basename($_SERVER['SCRIPT_NAME']);
$headerSearch = trim($_GET['search'] ?? '');
$headerCategory = trim($_GET['category'] ?? '');
$headerSort = trim($_GET['sort'] ?? '');
?>

<!-- 2. GLOBAL NAVIGATION HEADER -->
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Brand Identity Space -->
        <div class="flex-shrink-0">
            <a href="/" class="text-xl font-bold text-brand-primary tracking-tight hover:opacity-90 transition-opacity flex items-center gap-2">
                <span class="font-arial italic text-brand-accent text-2xl">NJM</span> 
                <span class="hidden sm:inline font-sans text-sm tracking-widest uppercase border-l border-gray-200 pl-2 text-gray-500">Ebook Editorial</span>
            </a>
        </div>
        
        <!-- Navigation Trail & Catalog Query Box -->
        <div class="flex items-center gap-4">
            <button id="mobileMenuButton" type="button" class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white p-2 text-gray-500 hover:text-brand-primary hover:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-accent/30 sm:hidden"
                    aria-expanded="false" aria-controls="mobileMenuPanel" aria-label="Open navigation menu">
                <svg id="mobileMenuIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <nav class="hidden md:flex space-x-8 text-sm font-medium">
                <!-- Home Link Logic -->
                <a href="/" class="<?php echo ($active_page == 'index.php' || $active_page == '') ? 'text-brand-accent border-b-2 border-brand-accent' : 'text-gray-500 hover:text-brand-primary'; ?> pb-1 transition-all">
                    Home
                </a>
                
                <!-- Our Books Link Logic -->
                <a href="/archive" class="<?php echo ($active_page == 'archive.php') ? 'text-brand-accent border-b-2 border-brand-accent' : 'text-gray-500 hover:text-brand-primary'; ?> pb-1 transition-all">
                    Our Books
                </a>
                
                <!-- About the Author Link Logic -->
                <a href="/about" class="<?php echo ($active_page == 'about.php') ? 'text-brand-accent border-b-2 border-brand-accent' : 'text-gray-500 hover:text-brand-primary'; ?> pb-1 transition-all">
                    About the Author
                </a>
                
                <!-- Contact Link Logic -->
                <!-- <a href="/contact" class="<?php echo ($active_page == 'contact.php') ? 'text-brand-accent border-b-2 border-brand-accent' : 'text-gray-500 hover:text-brand-primary'; ?> pb-1 transition-all">
                    Contact
                </a> -->
            </nav>
            
            <!-- Book Catalog Search Input -->
            <div class="relative hidden sm:block">
                <form action="/archive.php" method="GET" class="relative" autocomplete="off">
                    <?php if ($headerCategory !== ''): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($headerCategory); ?>">
                    <?php endif; ?>
                    <?php if ($headerSort !== ''): ?>
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($headerSort); ?>">
                    <?php endif; ?>
                    <input id="searchInput" type="text" name="search" placeholder="Search books or topics..." 
                           value="<?php echo htmlspecialchars($headerSearch); ?>"
                           class="w-48 lg:w-64 bg-gray-50 text-sm text-gray-800 rounded-full pl-4 pr-10 py-2 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all"
                           aria-label="Search books or topics">
                    <button id="searchButton" type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-brand-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div id="searchSuggestions" class="hidden absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden z-50" role="listbox" aria-label="Search suggestions"></div>
                </form>
            </div>
        </div>
        <div id="mobileMenuPanel" class="md:hidden hidden absolute inset-x-4 top-full mt-3 rounded-3xl border border-gray-200 bg-white shadow-xl py-4 z-40">
            <nav class="space-y-2 text-sm font-medium">
                <a href="/" class="block rounded-2xl px-4 py-3 text-gray-600 hover:bg-brand-accent/10 hover:text-brand-primary transition-all">Home</a>
                <a href="/archive" class="block rounded-2xl px-4 py-3 text-gray-600 hover:bg-brand-accent/10 hover:text-brand-primary transition-all">Our Books</a>
                <a href="/about" class="block rounded-2xl px-4 py-3 text-gray-600 hover:bg-brand-accent/10 hover:text-brand-primary transition-all">About the Author</a>
                <!-- <a href="/contact" class="block rounded-2xl px-4 py-3 text-gray-600 hover:bg-brand-accent/10 hover:text-brand-primary transition-all">Contact</a> -->
            </nav>
        </div>
            <script>
                (function() {
                    const input = document.getElementById('searchInput');
                    const suggestions = document.getElementById('searchSuggestions');
                    const button = document.getElementById('searchButton');
                    let timeoutId = null;
                    let activeIndex = -1;
                    let suggestionItems = [];
                    
                    function setButtonState() {
                        button.disabled = input.value.trim() === '';
                        button.classList.toggle('opacity-50', input.value.trim() === '');
                        button.classList.toggle('cursor-not-allowed', input.value.trim() === '');
                    }
                    
                    function clearSuggestions() {
                        suggestions.innerHTML = '';
                        suggestions.classList.add('hidden');
                        activeIndex = -1;
                        suggestionItems = [];
                        input.removeAttribute('aria-activedescendant');
                    }
                    
                    function updateActiveItem() {
                        suggestionItems.forEach((item, index) => {
                            const isActive = index === activeIndex;
                            item.classList.toggle('bg-brand-accent/10', isActive);
                            item.classList.toggle('text-brand-primary', isActive);
                            item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                            if (isActive) {
                                item.scrollIntoView({ block: 'nearest' });
                                input.setAttribute('aria-activedescendant', item.id);
                            }
                        });
                    }
                    
                    function renderSuggestions(items) {
                        if (!items.length) {
                            suggestions.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No matches found</div>';
                            suggestions.classList.remove('hidden');
                            activeIndex = -1;
                            suggestionItems = [];
                            input.removeAttribute('aria-activedescendant');
                            return;
                        }
                        suggestions.innerHTML = items.map((item, index) => {
                            return `<a id="searchSuggestion-${index}" role="option" href="/post.php?id=${encodeURIComponent(item.url_slug)}" data-index="${index}" class="block px-4 py-3 text-sm text-gray-800 hover:bg-brand-accent/10 transition-colors">
                                        <div class="font-semibold">${escapeHtml(item.title)}</div>
                                        <div class="text-[11px] text-gray-500 uppercase tracking-[0.2em] mt-1">${escapeHtml(item.category)}</div>
                                    </a>`;
                        }).join('');
                        suggestionItems = Array.from(suggestions.querySelectorAll('[data-index]'));
                        activeIndex = -1;
                        suggestions.classList.remove('hidden');
                    }
                    
                    function escapeHtml(text) {
                        return text.replace(/[&<>"'`]/g, function(match) {
                            return {
                                '&': '&amp;',
                                '<': '&lt;',
                                '>': '&gt;',
                                '"': '&quot;',
                                "'": '&#39;',
                                '`': '&#96;'
                            }[match];
                        });
                    }
                    
                    input.addEventListener('input', function() {
                        setButtonState();
                        clearTimeout(timeoutId);
                        const query = input.value.trim();
                        if (query.length < 2) {
                            clearSuggestions();
                            return;
                        }
                        timeoutId = setTimeout(function() {
                            fetch('/handlers/search_suggestions.php?q=' + encodeURIComponent(query))
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.results || !data.results.length) {
                                        renderSuggestions([]);
                                        return;
                                    }
                                    renderSuggestions(data.results);
                                })
                                .catch(() => {
                                    clearSuggestions();
                                });
                        }, 250);
                    });
                    
                    input.addEventListener('keydown', function(event) {
                        if (suggestions.classList.contains('hidden') || !suggestionItems.length) {
                            return;
                        }
                        if (event.key === 'ArrowDown') {
                            event.preventDefault();
                            activeIndex = Math.min(activeIndex + 1, suggestionItems.length - 1);
                            updateActiveItem();
                        } else if (event.key === 'ArrowUp') {
                            event.preventDefault();
                            activeIndex = Math.max(activeIndex - 1, -1);
                            updateActiveItem();
                        } else if (event.key === 'Enter') {
                            if (activeIndex >= 0 && suggestionItems[activeIndex]) {
                                event.preventDefault();
                                window.location.href = suggestionItems[activeIndex].href;
                            }
                        } else if (event.key === 'Escape') {
                            clearSuggestions();
                        }
                    });
                    
                    suggestions.addEventListener('mousemove', function(event) {
                        const link = event.target.closest('[data-index]');
                        if (!link) {
                            return;
                        }
                        const index = Number(link.getAttribute('data-index'));
                        if (!Number.isNaN(index)) {
                            activeIndex = index;
                            updateActiveItem();
                        }
                    });
                    
                    input.addEventListener('focus', function() {
                        if (suggestions.innerHTML.trim() !== '') {
                            suggestions.classList.remove('hidden');
                        }
                    });
                    
                    document.addEventListener('click', function(event) {
                        if (!suggestions.contains(event.target) && event.target !== input) {
                            clearSuggestions();
                        }
                    });
                    
                    setButtonState();
                })();
            </script>
            <script>
                (function() {
                    const menuButton = document.getElementById('mobileMenuButton');
                    const menuPanel = document.getElementById('mobileMenuPanel');
                    const menuIcon = document.getElementById('mobileMenuIcon');
                    const burgerPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                    const closePath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                    let isOpen = false;

                    if (!menuButton || !menuPanel || !menuIcon) {
                        return;
                    }

                    function updateMenu() {
                        menuPanel.classList.toggle('hidden', !isOpen);
                        menuButton.setAttribute('aria-expanded', String(isOpen));
                        menuIcon.innerHTML = isOpen ? closePath : burgerPath;
                    }

                    menuButton.addEventListener('click', function(event) {
                        event.stopPropagation();
                        isOpen = !isOpen;
                        updateMenu();
                    });

                    document.addEventListener('click', function(event) {
                        if (isOpen && !menuPanel.contains(event.target) && !menuButton.contains(event.target)) {
                            isOpen = false;
                            updateMenu();
                        }
                    });

                    menuPanel.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function() {
                            isOpen = false;
                            updateMenu();
                        });
                    });

                    updateMenu();
                })();
            </script>
        </div>

    </div>
</header>