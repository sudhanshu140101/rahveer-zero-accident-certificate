<?php
session_start();
require_once '../config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        
        // Log admin login
        try {
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO admin_logs (action, description, ip_address) VALUES (?, ?, ?)");
            $stmt->execute(['LOGIN', 'Admin logged in', $_SERVER['REMOTE_ADDR']]);
        } catch (Exception $e) {
            error_log("Admin log error: " . $e->getMessage());
        }
        
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
        sleep(2); // Prevent brute force
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RAHVEER Certificate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a365d 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Login</h1>
            <p class="text-gray-600">RAHVEER Certificate Dashboard</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <p class="text-sm font-semibold"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                    Username
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition-colors"
                    placeholder="Enter username"
                    autocomplete="username"
                >
            </div>
            
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition-colors"
                    placeholder="Enter password"
                    autocomplete="current-password"
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg"
            >
                Login to Dashboard
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="../index.html" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Home
            </a>
        </div>
    </div>
</body>
</html>
