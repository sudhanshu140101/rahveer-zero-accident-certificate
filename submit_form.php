<?php
// Form Submission Handler
require_once 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get form data
    $fullName = trim($_POST['fullName'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
    $location = trim($_POST['location'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($fullName) || strlen($fullName) < 2) {
        $errors[] = 'Valid full name is required';
    }
    
    if (empty($mobile) || !preg_match('/^[6-9]\d{9}$/', $mobile)) {
        $errors[] = 'Valid 10-digit mobile number is required';
    }
    
    if (empty($profession)) {
        $errors[] = 'Profession is required';
    }
    
    if (empty($location)) {
        $errors[] = 'Location is required';
    }
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit;
    }
    
    // Handle photo upload (optional)
    $photoPath = null;
    if (isset($_FILES['photoUpload']) && $_FILES['photoUpload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = UPLOAD_DIR;
        
        // Create upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $fileType = $_FILES['photoUpload']['type'];
        $fileSize = $_FILES['photoUpload']['size'];
        
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG and PNG images are allowed']);
            exit;
        }
        
        if ($fileSize > 5 * 1024 * 1024) { // 5MB max
            echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB']);
            exit;
        }
        
        // Generate unique filename
        $extension = pathinfo($_FILES['photoUpload']['name'], PATHINFO_EXTENSION);
        $filename = 'photo_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['photoUpload']['tmp_name'], $targetPath)) {
            $photoPath = $filename;
        }
    }
    
    // Generate certificate number
    $prefix = strtoupper(substr($fullName, 0, 3));
    $prefix = str_pad($prefix, 3, 'X');
    $randomNum = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $certificateNumber = 'RAHV-' . date('Y') . '-' . $prefix . $randomNum;
    
    // Get client information
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    // Insert into database
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        INSERT INTO pledges 
        (full_name, mobile, profession, location, photo_path, certificate_number, ip_address, user_agent) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $fullName,
        $mobile,
        $profession,
        $location,
        $photoPath,
        $certificateNumber,
        $ipAddress,
        $userAgent
    ]);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Pledge submitted successfully',
        'certificate_number' => $certificateNumber,
        'data' => [
            'name' => $fullName,
            'mobile' => $mobile,
            'profession' => $profession,
            'location' => $location,
            'photo_path' => $photoPath ? (UPLOAD_DIR . $photoPath) : null
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Form Submission Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while submitting the form. Please try again.'
    ]);
}
?>
