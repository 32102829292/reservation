<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-gray-900">System Setup</h1>
            <p class="text-gray-500 mt-2">Create your first admin user</p>
        </div>

        <?php if ($userCount > 0): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
                <div class="flex items-center gap-3 text-yellow-700 mb-4">
                    <i class="fa-solid fa-exclamation-triangle text-xl"></i>
                    <span class="font-bold">Users already exist</span>
                </div>
                <p class="text-sm text-yellow-600 mb-4">There are already <?= $userCount ?> user(s) in the database.</p>
                <div class="flex gap-3">
                    <a href="/login" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold text-center hover:bg-blue-700">
                        Go to Login
                    </a>
                    <a href="/admin/emergency-fix" class="flex-1 py-3 bg-green-600 text-white rounded-xl font-bold text-center hover:bg-green-700">
                        Auto Login
                    </a>
                </div>
            </div>
        <?php else: ?>
            <form action="/admin/create-initial-admin" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Admin Name</label>
                    <input type="text" name="name" value="Administrator" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="admin@example.com" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" value="admin123" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Default: admin123</p>
                </div>
                
                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl font-bold hover:from-blue-700 hover:to-purple-700 transition shadow-lg">
                    Create Admin User
                </button>
            </form>
        <?php endif; ?>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>After setup, you'll be automatically logged in.</p>
        </div>
    </div>
</body>
</html>