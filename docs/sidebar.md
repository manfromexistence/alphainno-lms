Don't change anything else - just update the first blade file to have all the sidebar items of the second blade file with links as /[slug] instead of just # links and make sure to don't change anything else!!!
1st blade file:
```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Alpha LMS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
        }


        .bengali-text {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .submenu.open {
            max-height: 500px;
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
            height: 100%;
        }

        .sidebar-header {
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-footer {
            flex-shrink: 0;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50">
        <div class="sidebar-container">
            <!-- Logo -->
            <div class="sidebar-header flex items-center justify-center px-6 py-5 border-b border-gray-200">
                <img src="{{ asset('Alphainno LMS.png') }}" alt="Alpha LMS" class="h-12 w-auto object-contain">
            </div>

            <!-- Navigation -->
            <nav id="sidebarNav" class="sidebar-nav px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'bg-emerald-50 text-bd-green' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Student Menu with Submenu -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('studentSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span class="truncate">Student</span>
                        </div>
                        <svg id="studentSubmenuIcon" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="studentSubmenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('dashboard.students.index') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.students.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Manage Students</span>
                        </a>
                        <a href="{{ route('dashboard.students.create') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.students.create') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Student</span>
                        </a>
                    </div>
                </div>

                <!-- Teacher Menu with Submenu -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('teacherSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Teacher</span>
                        </div>
                        <svg id="teacherSubmenuIcon" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="teacherSubmenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('dashboard.teachers.index') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.teachers.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Manage Teachers</span>
                        </a>
                        <a href="{{ route('dashboard.teachers.create') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.teachers.create') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Teacher</span>
                        </a>
                    </div>
                </div>

                <!-- Course & Class Management -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('courseSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="truncate">Course & Class</span>
                        </div>
                        <svg id="courseSubmenuIcon" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="courseSubmenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('dashboard.courses.index') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.courses.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Manage Courses</span>
                        </a>
                        <a href="{{ route('dashboard.batches.index') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.batches.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Manage Batches</span>
                        </a>
                    </div>

                    <!-- Online Exam Menu -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('examSubmenu')"
                            class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="truncate">Online Exam</span>
                            </div>
                            <svg id="examSubmenuIcon" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="examSubmenu" class="submenu pl-4 space-y-1">
                            <a href="{{ route('dashboard.exams.index') }}"
                                class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.exams.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span>Exams</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment & Finance -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('paymentSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="truncate">Payments</span>
                        </div>
                        <svg id="paymentSubmenuIcon" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="paymentSubmenu" class="submenu pl-4 space-y-1">
                        <a href="{{ route('dashboard.payments.index') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.payments.index') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Manage Payments</span>
                        </a>
                        <a href="{{ route('dashboard.payments.create') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.payments.create') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Payment</span>
                        </a>
                    </div>
                </div>


                <!-- Accounts Module -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('accountsSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Accounts</span>
                        </div>
                        <svg id="accountsSubmenuIcon"
                            class="w-4 h-4 transition-transform {{ request()->routeIs('dashboard.accounts.*') ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="accountsSubmenu"
                        class="submenu pl-4 space-y-1 {{ request()->routeIs('dashboard.accounts.*') ? 'open' : '' }}">
                        <a href="{{ route('dashboard.accounts.income') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.accounts.income') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <span>Income Reports</span>
                        </a>
                        <a href="{{ route('dashboard.accounts.expenses') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.accounts.expenses') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <span>Expense Registry</span>
                        </a>
                        <a href="{{ route('dashboard.accounts.reports') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.accounts.reports') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <span>Financial Analytics</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('dashboard.communication.index') }}"
                    class="flex items-center space-x-3 px-4 py-2.5 {{ request()->routeIs('dashboard.communication.*') ? 'bg-emerald-50 text-bd-green' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Communication</span>
                </a>

                <!-- Reports Module -->
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('reportSubmenu')"
                        class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="truncate">Reports</span>
                        </div>
                        <svg id="reportSubmenuIcon"
                            class="w-4 h-4 transition-transform {{ request()->routeIs('dashboard.reports.*') ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="reportSubmenu"
                        class="submenu pl-4 space-y-1 {{ request()->routeIs('dashboard.reports.*') ? 'open' : '' }}">
                        <a href="{{ route('dashboard.reports.attendance') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.reports.attendance') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <span>Attendance Analysis</span>
                        </a>
                        <a href="{{ route('dashboard.reports.performance') }}"
                            class="flex items-center space-x-3 px-4 py-2 text-sm {{ request()->routeIs('dashboard.reports.performance') ? 'text-bd-green bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg">
                            <span>Performance Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="{{ route('dashboard.settings.index') }}"
                    class="flex items-center space-x-2 px-3 py-2.5 {{ request()->routeIs('dashboard.settings.*') ? 'text-bd-green bg-emerald-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">Settings</span>
                </a>
            </nav>

            <!-- Logout Button (Fixed at bottom) -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100 bg-white">
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

            function toggleSubmenu(submenuId) {
                const submenu = document.getElementById(submenuId);
                const icon = document.getElementById(submenuId + 'Icon');

                submenu.classList.toggle('open');
                icon.classList.toggle('rotate-180');

                // Save submenu states to localStorage
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
                                if (submenu && icon) {
                                    submenu.classList.add('open');
                                    icon.classList.add('rotate-180');
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error restoring submenu states:', e);
                    }
                }
            }

            function saveSidebarScroll() {
                const sidebarNav = document.getElementById('sidebarNav');
                if (sidebarNav) {
                    localStorage.setItem(SIDEBAR_SCROLL_KEY, sidebarNav.scrollTop);
                }
            }

            function restoreSidebarScroll() {
                const sidebarNav = document.getElementById('sidebarNav');
                const savedScroll = localStorage.getItem(SIDEBAR_SCROLL_KEY);
                if (sidebarNav && savedScroll) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                }
            }

            function initSidebarScrollPersistence() {
                const sidebarNav = document.getElementById('sidebarNav');
                if (sidebarNav) {
                    let scrollTimeout;
                    sidebarNav.addEventListener('scroll', function () {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(saveSidebarScroll, 100);
                    });
                }
            }

            window.addEventListener('DOMContentLoaded', function () {
                restoreSidebarScroll();
                initSidebarScrollPersistence();
                restoreSubmenuStates();
            });
        </script>
        @stack('scripts')
    </div>

    <!-- Main Content -->
    <div class="pl-64 min-h-screen flex flex-col fixed top-0 left-0 min-w-full">
        <!-- Site Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-8 py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')
                    </h1>
                        <p class="text-sm text-gray-500 mt-1">@yield('page-description', '')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->roles->first()->name ?? 'User' }}
                            </p>
                        </div>
                        <div
                            class="w-10 h-10 bg-bd-green rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="relative bg-linear-to-r from-bd-green to-emerald-600 pt-32 pb-20 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="bg-linear-to-br from-bd-green to-emerald-600 text-white px-3 py-1 rounded-full text-xs font-semibold mb-3 inline-block">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
    </div>
</body>
</html>
```
2nd blade file:
```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Alpha LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
        }

        .bg-bd-green {
            background-color: #006A4E;
        }

        .text-bd-green {
            color: #006A4E;
        }

        .border-bd-green {
            border-color: #006A4E;
        }

        .hover\:bg-bd-green-dark:hover {
            background-color: #005840;
        }

        .bengali-text {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .submenu.open {
            max-height: 500px;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .transition-transform {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-red-500 overflow-y-auto overflow-x-hidden pb-24">
        <!-- Logo -->
        <div class="flex items-center justify-center px-6 py-5 border-b border-gray-200">
            <img src="{{ asset('Alphainno LMS.png') }}" alt="Alpha LMS" class="h-12 w-auto object-contain">
        </div>

        <!-- Navigation -->
        <nav class="px-4 py-6 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-bd-green rounded-lg font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Student Menu with Submenu -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('studentSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="truncate">Student</span>
                    </div>
                    <svg id="studentSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Submenu -->
                <div id="studentSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Add/Edit Student Profile</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Digital Admission Form</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Batch & Class Assignment</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Attendance Tracking</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>SMS Notification</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Exam Routine</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Result & Mark Sheets</span>
                    </a>
                </div>
            </div>

            <!-- Teacher Menu with Submenu -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('teacherSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Teacher</span>
                    </div>
                    <svg id="teacherSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="teacherSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Add/Edit Teacher Info</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Assign to Subject/Class</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Department Categorization</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Salary/Payment Records</span>
                    </a>
                </div>
            </div>

            <!-- Course & Class Management -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('courseSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="truncate">Course & Class</span>
                    </div>
                    <svg id="courseSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="courseSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Course & Module Creation</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Class & Exam Routine</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Material Upload (PDF/Video)</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Attendance Per Class</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Telegram/FB Group Links</span>
                    </a>
                </div>
            </div>

            <!-- Online Exam System -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('examSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate">Online Exam</span>
                    </div>
                    <svg id="examSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="examSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>MCQ Exam</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>CQ Exam</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Live Exam</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Result & Mark Upload</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Exam Leaderboard</span>
                    </a>
                </div>
            </div>

            <!-- Payment & Receipt -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('paymentSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="truncate">Payment</span>
                    </div>
                    <svg id="paymentSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="paymentSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Online/Offline Payment</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Receipt Generation</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Auto Invoice</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>SMS/Email Confirmation</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Due/Advance Tracking</span>
                    </a>
                </div>
            </div>

            <!-- Accounts & Finance -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('accountsSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Accounts</span>
                    </div>
                    <svg id="accountsSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="accountsSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        <span>Income Reports</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                        <span>Expense Categories</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Financial Reports</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export to Excel/PDF</span>
                    </a>
                </div>
            </div>

            <!-- Communication -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('commSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="truncate">Communication</span>
                    </div>
                    <svg id="commSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="commSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" onclick="openSendNoticePanel(); return false;" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span>Send Notice</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Send Result</span>
                    </a>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="space-y-1">
                <button onclick="toggleSubmenu('reportsSubmenu')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="truncate">Reports</span>
                    </div>
                    <svg id="reportsSubmenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="reportsSubmenu" class="submenu pl-4 space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Attendance Reports</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Performance Analytics</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Payment Summary</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <span>Income/Expense Charts</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export Reports</span>
                    </a>
                </div>
            </div>

            <a href="#"
                class="flex items-center space-x-2 px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="truncate">User & Role</span>
            </a>

            <a href="#"
                class="flex items-center space-x-2 px-3 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="truncate">Settings</span>
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="fixed lg:max-w-[250px] bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 bengali-text">ড্যাশবোর্ড</h2>
                    <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center space-x-3 cursor-pointer hover:opacity-80 transition-opacity">
                            <div id="userAvatarContainer" class="w-10 h-10 bg-bd-green rounded-full flex items-center justify-center text-white font-bold relative overflow-hidden">
                                <img id="userAvatarImage" src="" alt="User Avatar" class="w-10 h-10 rounded-full object-cover hidden">
                                <span id="userInitials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-2">
                                <a href="#" onclick="openEditProfile(); toggleUserMenu(); return false;" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-gray-700">Edit Profile</span>
                                </a>
                                <a href="/" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span class="text-gray-700">View Site</span>
                                </a>
                            </div>
                        </div>
                        <input type="file" id="userAvatarUpload" accept="image/*" class="hidden" onchange="handleProfileImageChange(event)">
                    </div>
                </div>
            </div>
        </div>

        <!-- Send Notice Section -->
        <div id="sendNoticeSection" class="hidden mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Send Notice</h3>
                    <button onclick="closeSendNoticeSection()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="sendNoticeForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Notice Title -->
                        <div>
                            <label for="noticeTitle" class="block text-sm font-semibold text-gray-700 mb-2">
                                Notice Title <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="noticeTitle" 
                                name="noticeTitle"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                placeholder="Enter notice title"
                                required
                            >
                        </div>

                        <!-- Priority Level -->
                        <div>
                            <label for="noticePriority" class="block text-sm font-semibold text-gray-700 mb-2">
                                Priority Level
                            </label>
                            <select 
                                id="noticePriority" 
                                name="priority"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                            >
                                <option value="normal">Normal</option>
                                <option value="important">Important</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notice Message -->
                    <div>
                        <label for="noticeMessage" class="block text-sm font-semibold text-gray-700 mb-2">
                            Notice Message <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="noticeMessage" 
                            name="noticeMessage"
                            rows="5"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"
                            placeholder="Enter your notice message here..."
                            required
                        ></textarea>
                    </div>

                    <!-- Send To -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Send To <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="sendTo" value="all" class="w-4 h-4 text-bd-green focus:ring-emerald-500" checked>
                                <span class="text-gray-700">All Students</span>
                            </label>
                            <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="sendTo" value="batch" class="w-4 h-4 text-bd-green focus:ring-emerald-500">
                                <span class="text-gray-700">Specific Batch</span>
                            </label>
                            <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="sendTo" value="individual" class="w-4 h-4 text-bd-green focus:ring-emerald-500">
                                <span class="text-gray-700">Individual Students</span>
                            </label>
                        </div>
                    </div>

                    <!-- Batch Selection (Hidden by default) -->
                    <div id="batchSelection" class="hidden">
                        <label for="noticeBatch" class="block text-sm font-semibold text-gray-700 mb-2">
                            Select Batch
                        </label>
                        <select 
                            id="noticeBatch" 
                            name="batch"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                        >
                            <option value="">Choose a batch...</option>
                            <option value="batch1">Batch 2024-A</option>
                            <option value="batch2">Batch 2024-B</option>
                            <option value="batch3">Batch 2025-A</option>
                        </select>
                    </div>

                    <!-- Student Selection (Hidden by default) -->
                    <div id="studentSelection" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Select Students
                        </label>
                        
                        <!-- Search by Student ID -->
                        <div class="mb-3">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="studentSearchInput"
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                    placeholder="Search by Student ID or Name..."
                                    oninput="filterStudents()"
                                >
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Student List -->
                        <div id="studentList" class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto space-y-2">
                            <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded student-item" data-id="STD001" data-name="Rahul Ahmed">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="students[]" value="student1" class="w-4 h-4 text-bd-green focus:ring-emerald-500 rounded">
                                    <div>
                                        <p class="text-gray-900 font-medium">Rahul Ahmed</p>
                                        <p class="text-sm text-gray-500">ID: STD001</p>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded student-item" data-id="STD002" data-name="Priya Sharma">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="students[]" value="student2" class="w-4 h-4 text-bd-green focus:ring-emerald-500 rounded">
                                    <div>
                                        <p class="text-gray-900 font-medium">Priya Sharma</p>
                                        <p class="text-sm text-gray-500">ID: STD002</p>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded student-item" data-id="STD003" data-name="Arjun Patel">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="students[]" value="student3" class="w-4 h-4 text-bd-green focus:ring-emerald-500 rounded">
                                    <div>
                                        <p class="text-gray-900 font-medium">Arjun Patel</p>
                                        <p class="text-sm text-gray-500">ID: STD003</p>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded student-item" data-id="STD004" data-name="Mehedi Hasan">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="students[]" value="student4" class="w-4 h-4 text-bd-green focus:ring-emerald-500 rounded">
                                    <div>
                                        <p class="text-gray-900 font-medium">Mehedi Hasan</p>
                                        <p class="text-sm text-gray-500">ID: STD004</p>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded student-item" data-id="STD005" data-name="Sadia Rahman">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="students[]" value="student5" class="w-4 h-4 text-bd-green focus:ring-emerald-500 rounded">
                                    <div>
                                        <p class="text-gray-900 font-medium">Sadia Rahman</p>
                                        <p class="text-sm text-gray-500">ID: STD005</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Selected Count -->
                        <div class="mt-2 text-sm text-gray-600">
                            <span id="selectedCount">0</span> student(s) selected
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            onclick="closeSendNoticeSection()" 
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium"
                        >
                            Send Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div id="editProfileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Profile</h3>
                    <button onclick="closeEditProfile()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <form id="editProfileForm">
                        <!-- Profile Picture Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Profile Picture</label>
                            <div class="flex items-center space-x-6">
                                <div class="relative">
                                    <div id="profilePreviewContainer" class="w-24 h-24 bg-bd-green rounded-full flex items-center justify-center text-white font-bold text-3xl overflow-hidden">
                                        <img id="profilePreviewImage" src="" alt="Profile Preview" class="w-24 h-24 rounded-full object-cover hidden">
                                        <span id="profilePreviewInitials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <button type="button" onclick="document.getElementById('userAvatarUpload').click()" class="px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                                        Change Photo
                                    </button>
                                    <p class="text-sm text-gray-500 mt-2">JPG, PNG or GIF. Max size 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="profileName" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input 
                                type="text" 
                                id="profileName" 
                                value="{{ Auth::user()->name }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>

                        <!-- Email Field (Read-only) -->
                        <div class="mb-6">
                            <label for="profileEmail" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                id="profileEmail" 
                                value="{{ Auth::user()->email }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                readonly
                            >
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeEditProfile()" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div id="dashboardContent">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text">মোট শিক্ষার্থী</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalStudents) }}</p>
                        <p class="text-sm text-green-600 mt-2">↑ 12% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Courses -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text">মোট কোর্স</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalCourses) }}</p>
                        <p class="text-sm text-green-600 mt-2">↑ 8% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text">মোট আয়</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">৳{{ number_format($totalRevenue) }}</p>
                        <p class="text-sm text-green-600 mt-2">↑ 15% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 bengali-text">সক্রিয় ব্যবহারকারী</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalUsers) }}</p>
                        <p class="text-sm text-green-600 mt-2">↑ 20% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Students -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 bengali-text">সাম্প্রতিক শিক্ষার্থী</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                R</div>
                            <div>
                                <p class="font-semibold text-gray-900">Rahul Ahmed</p>
                                <p class="text-sm text-gray-600">Enrolled in Web Development</p>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">2 mins ago</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                S</div>
                            <div>
                                <p class="font-semibold text-gray-900">Sadia Rahman</p>
                                <p class="text-sm text-gray-600">Enrolled in Python Programming</p>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">5 mins ago</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                                M</div>
                            <div>
                                <p class="font-semibold text-gray-900">Mehedi Hasan</p>
                                <p class="text-sm text-gray-600">Enrolled in Data Science</p>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">10 mins ago</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 bengali-text">দ্রুত কাজ</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all text-left">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900">Add Course</p>
                        <p class="text-sm text-gray-600">Create new course</p>
                    </button>

                    <button
                        class="p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 rounded-xl transition-all text-left">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900">Add Student</p>
                        <p class="text-sm text-gray-600">Register student</p>
                    </button>

                    <button
                        class="p-4 bg-gradient-to-br from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 rounded-xl transition-all text-left">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900">View Reports</p>
                        <p class="text-sm text-gray-600">Analytics & stats</p>
                    </button>

                    <button
                        class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all text-left">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900">Settings</p>
                        <p class="text-sm text-gray-600">Manage platform</p>
                    </button>
                </div>
            </div>
        </div>
        </div>
        <!-- End Dashboard Content -->
    </div>

    <script>
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + 'Icon');
            
            submenu.classList.toggle('open');
            icon.classList.toggle('rotate-180');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('userDropdownMenu');
            menu.classList.toggle('hidden');
        }

        function openEditProfile() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.remove('hidden');
            
            // Load current profile data
            const savedAvatar = localStorage.getItem('userAvatar');
            const savedName = localStorage.getItem('userName');
            
            if (savedAvatar) {
                document.getElementById('profilePreviewImage').src = savedAvatar;
                document.getElementById('profilePreviewImage').classList.remove('hidden');
                document.getElementById('profilePreviewInitials').classList.add('hidden');
            }
            
            if (savedName) {
                document.getElementById('profileName').value = savedName;
            }
        }

        function closeEditProfile() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.add('hidden');
        }

        // Handle profile form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editProfileForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const name = document.getElementById('profileName').value;
                    
                    // Save to localStorage
                    localStorage.setItem('userName', name);
                    
                    // Update UI
                    updateUserName(name);
                    
                    // Close modal
                    closeEditProfile();
                    
                    // Show success message
                    showNotification('Profile updated successfully!');
                });
            }
        });

        function updateUserName(name) {
            // Update welcome message
            const welcomeText = document.querySelector('p.text-gray-600');
            if (welcomeText) {
                welcomeText.textContent = `Welcome back, ${name}!`;
            }
            
            // Update initials
            const initial = name.charAt(0).toUpperCase();
            const userInitials = document.getElementById('userInitials');
            if (userInitials) {
                userInitials.textContent = initial;
            }
            
            const profilePreviewInitials = document.getElementById('profilePreviewInitials');
            if (profilePreviewInitials) {
                profilePreviewInitials.textContent = initial;
            }
        }

        function handleProfileImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageData = e.target.result;
                    
                    // Update profile preview in modal
                    const profilePreviewImage = document.getElementById('profilePreviewImage');
                    const profilePreviewInitials = document.getElementById('profilePreviewInitials');
                    profilePreviewImage.src = imageData;
                    profilePreviewImage.classList.remove('hidden');
                    profilePreviewInitials.classList.add('hidden');
                    
                    // Update header avatar
                    const userAvatarImage = document.getElementById('userAvatarImage');
                    const userInitials = document.getElementById('userInitials');
                    userAvatarImage.src = imageData;
                    userAvatarImage.classList.remove('hidden');
                    userInitials.classList.add('hidden');
                    
                    // Store in localStorage
                    localStorage.setItem('userAvatar', imageData);
                };
                reader.readAsDataURL(file);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userDropdownMenu');
            const button = event.target.closest('button');
            
            if (menu && !menu.contains(event.target) && (!button || !button.onclick || button.onclick.toString().indexOf('toggleUserMenu') === -1)) {
                menu.classList.add('hidden');
            }
        });

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-bd-green text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        function handleUserAvatarUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const userAvatarImage = document.getElementById('userAvatarImage');
                    const userInitials = document.getElementById('userInitials');
                    
                    // Set the image source
                    userAvatarImage.src = e.target.result;
                    userAvatarImage.classList.remove('hidden');
                    userInitials.classList.add('hidden');
                    
                    // Store in localStorage
                    localStorage.setItem('userAvatar', e.target.result);
                    
                    // Show success message
                    showNotification('Profile picture updated successfully!');
                };
                reader.readAsDataURL(file);
            }
        }

        // Load saved data on page load
        window.addEventListener('DOMContentLoaded', function() {
            // Load saved user avatar
            const savedAvatar = localStorage.getItem('userAvatar');
            if (savedAvatar) {
                const userAvatarImage = document.getElementById('userAvatarImage');
                const userInitials = document.getElementById('userInitials');
                
                userAvatarImage.src = savedAvatar;
                userAvatarImage.classList.remove('hidden');
                userInitials.classList.add('hidden');
            }

            // Load saved user name
            const savedName = localStorage.getItem('userName');
            if (savedName) {
                updateUserName(savedName);
            }
        });

        // Send Notice Section Functions
        function openSendNoticePanel() {
            const section = document.getElementById('sendNoticeSection');
            const dashboardContent = document.getElementById('dashboardContent');
            
            section.classList.remove('hidden');
            dashboardContent.classList.add('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function closeSendNoticeSection() {
            const section = document.getElementById('sendNoticeSection');
            const dashboardContent = document.getElementById('dashboardContent');
            
            section.classList.add('hidden');
            dashboardContent.classList.remove('hidden');
            document.getElementById('sendNoticeForm').reset();
            document.getElementById('studentSearchInput').value = '';
            filterStudents();
            updateSelectedCount();
            // Hide conditional fields
            document.getElementById('batchSelection').classList.add('hidden');
            document.getElementById('studentSelection').classList.add('hidden');
        }

        // Filter students by ID or Name
        function filterStudents() {
            const searchInput = document.getElementById('studentSearchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const studentItems = document.querySelectorAll('.student-item');
            
            studentItems.forEach(item => {
                const studentId = item.getAttribute('data-id').toLowerCase();
                const studentName = item.getAttribute('data-name').toLowerCase();
                
                if (studentId.includes(searchTerm) || studentName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Update selected students count
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('input[name="students[]"]:checked');
            const countElement = document.getElementById('selectedCount');
            if (countElement) {
                countElement.textContent = checkboxes.length;
            }
        }

        // Handle Send To radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const sendToRadios = document.querySelectorAll('input[name="sendTo"]');
            const batchSelection = document.getElementById('batchSelection');
            const studentSelection = document.getElementById('studentSelection');

            sendToRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    batchSelection.classList.add('hidden');
                    studentSelection.classList.add('hidden');

                    if (this.value === 'batch') {
                        batchSelection.classList.remove('hidden');
                    } else if (this.value === 'individual') {
                        studentSelection.classList.remove('hidden');
                    }
                });
            });

            // Add event listeners to student checkboxes
            const studentCheckboxes = document.querySelectorAll('input[name="students[]"]');
            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Handle form submission
            const sendNoticeForm = document.getElementById('sendNoticeForm');
            sendNoticeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const noticeData = {
                    title: formData.get('noticeTitle'),
                    message: formData.get('noticeMessage'),
                    sendTo: formData.get('sendTo'),
                    priority: formData.get('priority')
                };

                // Here you would send the data to your backend
                console.log('Notice Data:', noticeData);
                
                showNotification('Notice sent successfully!');
                closeSendNoticeSection();
                this.reset();
            });
        });
    </script>

</body>

</html>
```
