<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Pro — Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-200 text-sm font-medium; }
        .sidebar-link.active { @apply bg-blue-600 text-white; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 flex flex-col flex-shrink-0 h-full overflow-y-auto">
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">CRM Pro</p>
                    <p class="text-slate-400 text-xs">{{ session('crm_user_role') }}</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1">
            <p class="text-slate-500 text-xs uppercase tracking-wider px-4 mb-2 font-semibold">Main</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-4"></i> Dashboard
            </a>

            <p class="text-slate-500 text-xs uppercase tracking-wider px-4 mt-4 mb-2 font-semibold">Sales</p>
            <a href="{{ route('admin.leads.index') }}" class="sidebar-link {{ request()->routeIs('admin.leads*') ? 'active' : '' }}">
                <i class="fas fa-funnel-dollar w-4"></i> Leads
            </a>
            <a href="{{ route('admin.leads.pipeline') }}" class="sidebar-link {{ request()->routeIs('admin.leads.pipeline') ? 'active' : '' }}">
                <i class="fas fa-columns w-4"></i> Pipeline
            </a>
            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <i class="fas fa-users w-4"></i> Customers
            </a>
            <a href="{{ route('admin.deals.index') }}" class="sidebar-link {{ request()->routeIs('admin.deals*') ? 'active' : '' }}">
                <i class="fas fa-handshake w-4"></i> Deals
            </a>

            <p class="text-slate-500 text-xs uppercase tracking-wider px-4 mt-4 mb-2 font-semibold">Productivity</p>
            <a href="{{ route('admin.tasks.index') }}" class="sidebar-link {{ request()->routeIs('admin.tasks*') ? 'active' : '' }}">
                <i class="fas fa-tasks w-4"></i> Tasks
            </a>

            @if(in_array(session('crm_user_role'), ['Super Admin', 'Admin', 'Manager']))
            <p class="text-slate-500 text-xs uppercase tracking-wider px-4 mt-4 mb-2 font-semibold">Analytics</p>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-4"></i> Reports
            </a>
            @endif

            @if(in_array(session('crm_user_role'), ['Super Admin', 'Admin']))
            <p class="text-slate-500 text-xs uppercase tracking-wider px-4 mt-4 mb-2 font-semibold">Admin</p>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-user-shield w-4"></i> Users
            </a>
            <a href="{{ route('admin.activity.index') }}" class="sidebar-link {{ request()->routeIs('admin.activity*') ? 'active' : '' }}">
                <i class="fas fa-history w-4"></i> Audit Logs
            </a>
            @endif
        </nav>

        <!-- User Footer -->
        <div class="px-3 py-4 border-t border-slate-700">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(session('crm_user_name', 'U'), 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-medium truncate">{{ session('crm_user_name') }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ session('crm_user_email') }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-2">
                @csrf
                <button class="sidebar-link w-full text-left">
                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                <p class="text-xs text-gray-500">@yield('page-subtitle', 'CRM Management System')</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ now()->format('D, M d Y') }}</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">{{ session('crm_user_role') }}</span>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto px-6 py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
