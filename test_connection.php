<?php
/**
 * Database Connection Test Script
 * Use this to verify your database connection after deployment
 * DELETE THIS FILE after testing for security
 */

require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #10b981;
            padding: 15px;
            background: #d1fae5;
            border-left: 4px solid #10b981;
            margin: 20px 0;
        }
        .error {
            color: #ef4444;
            padding: 15px;
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            margin: 20px 0;
        }
        .info {
            color: #3b82f6;
            padding: 15px;
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
        }
        h1 {
            color: #1e40af;
        }
        .warning {
            color: #f59e0b;
            padding: 15px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            margin: 20px 0;
            font-weight: bold;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔧 Database Connection Test</h1>
        
        <?php
        try {
            // Test database connection
            $conn = getDBConnection();
            
            echo '<div class="success">';
            echo '<strong>✅ SUCCESS!</strong> Database connection established successfully.';
            echo '</div>';
            
            // Test if tables exist
            $tables = ['pledges', 'admin_logs'];
            echo '<div class="info">';
            echo '<strong>📊 Checking Database Tables:</strong><br><br>';
            
            foreach ($tables as $table) {
                $stmt = $conn->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() > 0) {
                    echo "✅ Table <code>$table</code> exists<br>";
                } else {
                    echo "❌ Table <code>$table</code> NOT found<br>";
                }
            }
            echo '</div>';
            
            // Count records
            $stmt = $conn->query("SELECT COUNT(*) as count FROM pledges");
            $count = $stmt->fetch();
            
            echo '<div class="info">';
            echo "<strong>📈 Database Statistics:</strong><br><br>";
            echo "Total Pledges: <strong>" . $count['count'] . "</strong><br>";
            echo "Database Host: <code>" . DB_HOST . "</code><br>";
            echo "Database Name: <code>" . DB_NAME . "</code><br>";
            echo "PHP Version: <code>" . phpversion() . "</code><br>";
            echo '</div>';
            
            // Test file permissions
            echo '<div class="info">';
            echo '<strong>📁 File System Check:</strong><br><br>';
            
            if (is_dir('uploads')) {
                echo '✅ uploads/ directory exists<br>';
                if (is_writable('uploads')) {
                    echo '✅ uploads/ directory is writable<br>';
                } else {
                    echo '❌ uploads/ directory is NOT writable (set to 755)<br>';
                }
            } else {
                echo '❌ uploads/ directory NOT found<br>';
            }
            echo '</div>';
            
            echo '<div class="warning">';
            echo '⚠️ <strong>SECURITY WARNING:</strong> Delete this file (test_connection.php) after testing!';
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<strong>❌ ERROR!</strong> Database connection failed.<br><br>';
            echo '<strong>Error Message:</strong> ' . htmlspecialchars($e->getMessage()) . '<br><br>';
            echo '<strong>Possible Solutions:</strong><br>';
            echo '1. Check database credentials in config.php<br>';
            echo '2. Verify database exists in cPanel<br>';
            echo '3. Check if database user has proper privileges<br>';
            echo '4. Confirm database host is correct (usually "localhost")<br>';
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center;">
            <a href="index.html" style="color: #3b82f6; text-decoration: none; font-weight: bold;">← Back to Home</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="admin/login.php" style="color: #3b82f6; text-decoration: none; font-weight: bold;">Admin Login →</a>
        </div>
    </div>
</body>
</html>
