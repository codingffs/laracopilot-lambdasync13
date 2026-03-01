<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Pro — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">CRM Pro</h1>
            <p class="text-slate-400 mt-1">Customer Relationship Management</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back</h2>
            <p class="text-sm text-gray-500 mb-6">Sign in to your account to continue</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                        <input type="email" name="email" value="{{ old('email', 'superadmin@crm.com') }}" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                        <input type="password" name="password" value="password" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>
        </div>

        <!-- Test Credentials -->
        <div class="mt-6 bg-slate-800 border border-slate-700 rounded-xl p-4">
            <p class="text-slate-300 text-xs font-semibold uppercase tracking-wider mb-3"><i class="fas fa-key mr-1"></i> Test Credentials (Password: password)</p>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-slate-700 rounded-lg p-2">
                    <span class="text-red-400 text-xs font-bold block">Super Admin</span>
                    <span class="text-slate-300 text-xs">superadmin@crm.com</span>
                </div>
                <div class="bg-slate-700 rounded-lg p-2">
                    <span class="text-orange-400 text-xs font-bold block">Admin</span>
                    <span class="text-slate-300 text-xs">admin@crm.com</span>
                </div>
                <div class="bg-slate-700 rounded-lg p-2">
                    <span class="text-blue-400 text-xs font-bold block">Manager</span>
                    <span class="text-slate-300 text-xs">manager@crm.com</span>
                </div>
                <div class="bg-slate-700 rounded-lg p-2">
                    <span class="text-green-400 text-xs font-bold block">Executive</span>
                    <span class="text-slate-300 text-xs">executive@crm.com</span>
                </div>
            </div>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">Made with ❤️ by <a href="https://laracopilot.com/" class="hover:text-slate-300">LaraCopilot</a></p>
    </div>
</body>
</html>
