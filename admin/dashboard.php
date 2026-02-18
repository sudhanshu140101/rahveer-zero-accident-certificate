<?php
session_start();
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Session timeout (30 minutes)
$timeout_duration = 1800;
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['login_time'] = time();

// Get database connection
try {
    $conn = getDBConnection();
    
    // Fetch all pledges
    $stmt = $conn->query("
        SELECT 
            id, full_name, mobile, profession, location, 
            photo_path, certificate_number, submission_date, ip_address
        FROM pledges 
        ORDER BY submission_date DESC
    ");
    $pledges = $stmt->fetchAll();
    
    // Get statistics
    $statsStmt = $conn->query("
        SELECT 
            COUNT(*) as total_pledges,
            COUNT(DISTINCT DATE(submission_date)) as active_days,
            COUNT(CASE WHEN DATE(submission_date) = CURDATE() THEN 1 END) as today_pledges
        FROM pledges
    ");
    $stats = $statsStmt->fetch();
    
} catch (Exception $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $error = "Failed to load data. Please try again.";
    $pledges = [];
    $stats = ['total_pledges' => 0, 'active_days' => 0, 'today_pledges' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RAHVEER Certificate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-container {
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            position: sticky;
            top: 0;
            background: #1e40af;
            z-index: 10;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-700 to-blue-900 text-white shadow-lg no-print">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">RAHVEER Admin Dashboard</h1>
                    <p class="text-blue-200 text-sm">Certificate Management System</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition-colors">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 py-6 no-print">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Total Pledges</p>
                        <p class="text-3xl font-bold text-blue-700"><?php echo number_format($stats['total_pledges']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Today's Pledges</p>
                        <p class="text-3xl font-bold text-green-700"><?php echo number_format($stats['today_pledges']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Active Days</p>
                        <p class="text-3xl font-bold text-orange-700"><?php echo number_format($stats['active_days']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="max-w-7xl mx-auto px-4 pb-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center no-print">
                <h2 class="text-xl font-bold text-gray-800">All Pledge Submissions</h2>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print / Export
                </button>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="px-6 py-4 bg-red-100 border-b border-red-200">
                    <p class="text-red-700 font-semibold"><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="table-container" style="max-height: 600px; overflow-y: auto;">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-white text-left text-sm font-semibold">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Full Name</th>
                            <th class="px-4 py-3">Mobile</th>
                            <th class="px-4 py-3">Profession</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Certificate No.</th>
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">Submission Date</th>
                            <th class="px-4 py-3 no-print">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if (empty($pledges)): ?>
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    No pledges submitted yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pledges as $index => $pledge): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 <?php echo $index % 2 === 0 ? 'bg-white' : 'bg-gray-50'; ?>">
                                    <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($pledge['id']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($pledge['full_name']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($pledge['mobile']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($pledge['profession']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($pledge['location']); ?></td>
                                    <td class="px-4 py-3 font-mono text-xs text-blue-700"><?php echo htmlspecialchars($pledge['certificate_number']); ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($pledge['photo_path']): ?>
                                            <a href="../<?php echo UPLOAD_DIR . htmlspecialchars($pledge['photo_path']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold">View</a>
                                        <?php else: ?>
                                            <span class="text-gray-400">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs"><?php echo date('d M Y, h:i A', strtotime($pledge['submission_date'])); ?></td>
                                    <td class="px-4 py-3 text-xs text-gray-500 no-print"><?php echo htmlspecialchars($pledge['ip_address'] ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 no-print">
                <p class="text-sm text-gray-600">
                    Showing <strong><?php echo number_format(count($pledges)); ?></strong> total records
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8 no-print">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">© <?php echo date('Y'); ?> RAHVEER - Zero Accident Bharat Mission</p>
        </div>
    </footer>
</body>
</html>
