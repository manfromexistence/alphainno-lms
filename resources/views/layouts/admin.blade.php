<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Alpha LMS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* 1. Standard approach for Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #2e7d32 #e8f5e9;
        }

        /* 2. WebKit approach for Chrome, Edge, and Safari */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #e8f5e9;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #2e7d32;
            border-radius: 10px;
            border: 3px solid #e8f5e9;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #1b5e20;
        }

        body {
            font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
        }

        .bengali-text {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .no-transition {
            transition: none !important;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            will-change: max-height;
        }

        .submenu.open {
            max-height: 3000px;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .transition-transform {
            transition: transform 0.3s ease-in-out;
        }

        /* Custom Scrollbar Styling */
        .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: #006A4E #e5e7eb;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #006A4E;
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #005840;
        }

        /* Sidebar Layout */
        .sidebar-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            width: 16rem;
        }

        .sidebar-header {
            flex-shrink: 0;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            position: fixed;
            bottom: 0;
            width: 16rem;
            background: white;
            z-index: 10;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-white">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50">
        <div class="sidebar-container">
            <!-- Logo -->
            <div class="sidebar-header flex items-center justify-between px-4 py-4 border-b border-gray-200">
                <img src="{{ asset('Alphainno LMS.png') }}" alt="Alpha LMS" class="h-10 w-auto object-contain">
                
                <!-- Collapse/Expand All Buttons -->
                <div class="flex items-center space-x-1">
                    <button type="button" onclick="collapseAllSubmenus()" 
                            class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" 
                            title="Collapse All">
<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-down-up-icon lucide-chevrons-down-up"><path d="m7 20 5-5 5 5"/><path d="m7 4 5 5 5-5"/></svg>
                    </button>
                    <button type="button" onclick="expandAllSubmenus()" 
                            class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" 
                            title="Expand All">
<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-expand-icon lucide-expand"><path d="m15 15 6 6"/><path d="m15 9 6-6"/><path d="M21 16v5h-5"/><path d="M21 8V3h-5"/><path d="M3 16v5h5"/><path d="m3 21 6-6"/><path d="M3 8V3h5"/><path d="M9 9 3 3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Dynamic Navigation based on user role -->
            <div class="sidebar-nav">
                <x-sidebar.navigation :menuItems="$sidebarMenuItems ?? []" />
            </div>

            <!-- Logout Button (Fixed at bottom) -->
            <div class="sidebar-footer p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-3 px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-lg font-medium text-sm w-full transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <script>
            // Sidebar localStorage keys
            const SIDEBAR_SCROLL_KEY = 'adminSidebarScroll';
            const SIDEBAR_SUBMENUS_KEY = 'adminSidebarSubmenus';

            // Active link styling classes
            const ACTIVE_CLASSES = ['bg-emerald-50', 'text-bd-green', 'font-medium'];
            const INACTIVE_CLASSES = ['text-gray-600', 'hover:bg-gray-100'];

            function toggleSubmenu(submenuId) {
                const submenu = document.getElementById(submenuId);
                const icon = document.getElementById(submenuId + 'Icon');

                if (submenu) {
                    submenu.classList.toggle('open');
                    if (icon) icon.classList.toggle('rotate-180');
                    saveSubmenuStates();
                }
            }

            // Expand all submenus
            function expandAllSubmenus() {
                const submenus = document.querySelectorAll('.submenu');
                submenus.forEach(submenu => {
                    submenu.classList.add('open');
                    const icon = document.getElementById(submenu.id + 'Icon');
                    if (icon) icon.classList.add('rotate-180');
                });
                saveSubmenuStates();
            }

            // Collapse all submenus
            function collapseAllSubmenus() {
                const submenus = document.querySelectorAll('.submenu');
                submenus.forEach(submenu => {
                    submenu.classList.remove('open');
                    const icon = document.getElementById(submenu.id + 'Icon');
                    if (icon) icon.classList.remove('rotate-180');
                });
                saveSubmenuStates();
            }

            function saveSubmenuStates() {
                const submenus = document.querySelectorAll('.submenu');
                const states = {};
                submenus.forEach(submenu => {
                    states[submenu.id] = submenu.classList.contains('open');
                });
                localStorage.setItem(SIDEBAR_SUBMENUS_KEY, JSON.stringify(states));
            }

            function restoreSubmenuStates() {
                const saved = localStorage.getItem(SIDEBAR_SUBMENUS_KEY);
                if (saved) {
                    try {
                        const states = JSON.parse(saved);
                        Object.keys(states).forEach(submenuId => {
                            if (states[submenuId]) {
                                const submenu = document.getElementById(submenuId);
                                const icon = document.getElementById(submenuId + 'Icon');
                                if (submenu) {
                                    submenu.classList.add('open');
                                    if (icon) icon.classList.add('rotate-180');
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error restoring submenu states:', e);
                    }
                }
            }

            function saveSidebarScroll() {
                const scroller = document.querySelector('.sidebar-nav');
                if (scroller) {
                    localStorage.setItem(SIDEBAR_SCROLL_KEY, scroller.scrollTop);
                }
            }

            function restoreSidebarScroll() {
                const scroller = document.querySelector('.sidebar-nav');
                const savedScroll = localStorage.getItem(SIDEBAR_SCROLL_KEY);
                if (scroller && savedScroll) {
                    scroller.scrollTop = parseInt(savedScroll, 10);
                }
            }

            function highlightActiveLink() {
                const currentUrl = window.location.href.split('?')[0].split('#')[0];
                const links = document.querySelectorAll('.sidebar-nav a');

                // First, remove active classes from all links
                links.forEach(link => {
                    link.classList.remove(...ACTIVE_CLASSES);
                    link.classList.add(...INACTIVE_CLASSES);
                });

                // Then apply active class only to exact match
                links.forEach(link => {
                    const linkUrl = link.href.split('?')[0].split('#')[0];
                    if (linkUrl === currentUrl) {
                        link.classList.remove(...INACTIVE_CLASSES);
                        link.classList.add(...ACTIVE_CLASSES);

                        // Open parent submenu if exists
                        const parentSubmenu = link.closest('.submenu');
                        if (parentSubmenu) {
                            parentSubmenu.classList.add('open');
                            const submenuId = parentSubmenu.id;
                            const icon = document.getElementById(submenuId + 'Icon');
                            if (icon) icon.classList.add('rotate-180');
                            saveSubmenuStates();
                        }
                    }
                });
            }

            function toggleProfileDropdown() {
                const menu = document.getElementById('profileDropdownMenu');
                if (menu) {
                    if (menu.classList.contains('hidden')) {
                        menu.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            menu.classList.remove('opacity-0', 'scale-95');
                            menu.classList.add('opacity-100', 'scale-100');
                        });
                    } else {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            menu.classList.add('hidden');
                        }, 100);
                    }
                }
            }

            window.addEventListener('click', function (e) {
                const btn = document.getElementById('profileDropdownBtn');
                const menu = document.getElementById('profileDropdownMenu');
                if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target) && !menu.classList.contains('hidden')) {
                    toggleProfileDropdown();
                }
            });

            function initSidebarScrollPersistence() {
                const scroller = document.querySelector('.sidebar-nav');
                if (scroller) {
                    // Debounced save on scroll
                    let scrollTimeout;
                    scroller.addEventListener('scroll', function () {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(saveSidebarScroll, 50); // Reduced delay
                    });

                    // Immediate save on any link click in sidebar
                    const links = scroller.querySelectorAll('a');
                    links.forEach(link => {
                        link.addEventListener('click', function() {
                            saveSidebarScroll();
                        });
                    });

                    // Final backup save before leaving page
                    window.addEventListener('beforeunload', function() {
                        saveSidebarScroll();
                    });
                }
            }

            (function () {
                document.body.classList.add('no-transition');
                const style = document.createElement('style');
                style.id = 'suppress-transitions';
                style.textContent = '* { transition: none !important; }';
                document.head.appendChild(style);

                restoreSubmenuStates();
                restoreSidebarScroll();
                highlightActiveLink();

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        document.body.classList.remove('no-transition');
                        const styleEl = document.getElementById('suppress-transitions');
                        if (styleEl) styleEl.remove();
                    });
                });
            })();

            window.addEventListener('DOMContentLoaded', function () {
                initSidebarScrollPersistence();
                initSidebarTextCopy();
            });

            // Copy sidebar text to clipboard on selection attempt
            function initSidebarTextCopy() {
                const sidebar = document.querySelector('.sidebar-container');
                if (!sidebar) return;

                let selectionTimeout;
                
                sidebar.addEventListener('mouseup', function(e) {
                    clearTimeout(selectionTimeout);
                    selectionTimeout = setTimeout(() => {
                        const selection = window.getSelection();
                        const selectedText = selection.toString().trim();
                        
                        if (selectedText && selectedText.length > 0) {
                            // Copy to clipboard
                            navigator.clipboard.writeText(selectedText).then(() => {
                                // Show toast notification
                                showCopyToast('Copied to clipboard!');
                                // Clear selection
                                selection.removeAllRanges();
                            }).catch(err => {
                                console.error('Failed to copy text:', err);
                            });
                        }
                    }, 100);
                });
            }

            function showCopyToast(message) {
                // Remove existing toast if any
                const existingToast = document.getElementById('sidebar-copy-toast');
                if (existingToast) {
                    existingToast.remove();
                }

                // Create toast element
                const toast = document.createElement('div');
                toast.id = 'sidebar-copy-toast';
                toast.className = 'fixed bottom-20 left-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm flex items-center space-x-2 animate-fade-in';
                toast.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(toast);

                // Remove after 2 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px)';
                    setTimeout(() => toast.remove(), 300);
                }, 2000);
            }
        </script>
        <style>
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-fade-in {
                animation: fade-in 0.3s ease-out;
            }
            #sidebar-copy-toast {
                transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            }
        </style>
        @stack('scripts')
    </div>

    <!-- Main Content -->
    <div class="pl-64 min-h-screen flex flex-col top-0 left-0 min-w-full">
        <!-- Site Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-8 py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm text-gray-500 mt-1">@yield('page-description', '')</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ Auth::user()->roles->first()->name ?? 'User' }}
                        </p>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileDropdownBtn" onclick="toggleProfileDropdown()"
                            class="w-10 h-10 bg-bd-green rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bd-green">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdownMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-50 origin-top-right transition-all duration-100 ease-out transform scale-95 opacity-0">
                            <a href="{{ route('dashboard.settings.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-4 min-h-screen overflow-y-auto overflow-x-hidden">
            <!-- Global Toast Notifications -->
            @if (session('success'))
                <x-ui.toast type="success" :message="session('success')" />
            @endif

            @if (session('error'))
                <x-ui.toast type="error" :message="session('error')" />
            @endif
            
            @if (session('warning'))
                <x-ui.toast type="warning" :message="session('warning')" />
            @endif

            @if (session('info'))
                <x-ui.toast type="info" :message="session('info')" />
            @endif

            @yield('content')
        </div>
    </div>

    {{-- Custom Confirmation Dialog --}}
    <x-ui.confirm-dialog />
</body>

</html>
