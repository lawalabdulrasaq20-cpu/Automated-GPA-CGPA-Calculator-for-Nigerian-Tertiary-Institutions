<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                <i class="fas fa-graduation-cap text-2xl text-purple-600"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">GPA & CGPA Calculator</h1>
            <p class="text-purple-200">For Nigerian Tertiary Institutions</p>
        </div>

        <!-- Error/Success Messages -->
        <?php if ($error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
                <p><?php echo e($error); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-check-circle mt-1 mr-2"></i>
                <p><?php echo e($success); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <?php if (!$showRegister): ?>
        <div class="bg-white rounded-lg card-shadow p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Welcome Back</h2>
            
            <form method="POST" action="http://localhost/gpa_system/public/
/login.php">
                <input type="hidden" name="login" value="1">
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Enter your email">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Enter your password">
                    </div>
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                    <a href="http://localhost/gpa_system/public/
/login.php?register=1" class="text-purple-600 font-medium hover:underline">Register here</a>
                </p>
            </div>

            <!-- Demo Credentials 
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-2"><strong>Demo Account:</strong></p>
                <p class="text-xs text-gray-600">Email: <span class="font-mono">admin@example.com</span></p>
                <p class="text-xs text-gray-600">Password: <span class="font-mono">password</span></p>
            </div>
        </div>-->

        <!-- Registration Form -->
        <?php else: ?>
        <div class="bg-white rounded-lg card-shadow p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create Account</h2>
            
            <form method="POST" action="/login.php?register=1">
                <input type="hidden" name="register" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Enter your full name">
                    </div>
                    <div>
                        <label for="matric_number" class="block text-gray-700 text-sm font-medium mb-2">Matric Number</label>
                        <input type="text" id="matric_number" name="matric_number"
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="e.g., 201901234">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Enter your email">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="department" class="block text-gray-700 text-sm font-medium mb-2">Department</label>
                        <input type="text" id="department" name="department"
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="e.g., Computer Science">
                    </div>
                    <div>
                        <label for="level" class="block text-gray-700 text-sm font-medium mb-2">Level</label>
                        <select id="level" name="level" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select Level</option>
                            <option value="100">100 Level</option>
                            <option value="200">200 Level</option>
                            <option value="300">300 Level</option>
                            <option value="400">400 Level</option>
                            <option value="500">500 Level</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Create password">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Confirm password">
                    </div>
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Already have an account?
                    <a href="http://localhost/gpa_system/public/
/login.php" class="text-purple-600 font-medium hover:underline">Login here</a>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Show/hide password
        document.querySelectorAll('input[type="password"]').forEach(input => {
            input.addEventListener('mouseenter', function() {
                this.type = 'text';
            });
            input.addEventListener('mouseleave', function() {
                this.type = 'password';
            });
        });
    </script>
</body>
</html>