<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .good { border-left-color: #28a745; }
        .bad { border-left-color: #dc3545; }
        .warning { border-left-color: #ffc107; }
        h2 { margin-top: 0; }
        pre { background: #f8f9fa; padding: 10px; overflow-x: auto; }
        .status { font-weight: bold; }
        .status.ok { color: #28a745; }
        .status.error { color: #dc3545; }
    </style>
</head>
<body>
    <h1>🔍 PHP Upload Diagnostic Tool</h1>
    
    <div class="section <?php echo ini_get('upload_tmp_dir') ? 'good' : 'bad'; ?>">
        <h2>1. Upload Temp Directory</h2>
        <p><strong>upload_tmp_dir:</strong> 
            <span class="status <?php echo ini_get('upload_tmp_dir') ? 'ok' : 'error'; ?>">
                <?php echo ini_get('upload_tmp_dir') ?: '(NOT SET - THIS IS THE PROBLEM!)'; ?>
            </span>
        </p>
        <p><strong>System Temp:</strong> <?php echo sys_get_temp_dir(); ?></p>
        <?php if (ini_get('upload_tmp_dir')): ?>
            <p><strong>Directory Exists:</strong> 
                <?php echo is_dir(ini_get('upload_tmp_dir')) ? '✓ Yes' : '✗ No'; ?>
            </p>
            <p><strong>Directory Writable:</strong> 
                <?php echo is_writable(ini_get('upload_tmp_dir')) ? '✓ Yes' : '✗ No'; ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>2. PHP Configuration File</h2>
        <p><strong>Loaded php.ini:</strong> <?php echo php_ini_loaded_file() ?: 'None'; ?></p>
        <p><strong>Additional ini files:</strong> <?php echo php_ini_scanned_files() ?: 'None'; ?></p>
    </div>

    <div class="section <?php echo ini_get('file_uploads') ? 'good' : 'bad'; ?>">
        <h2>3. Upload Settings</h2>
        <pre><?php
echo "file_uploads:         " . (ini_get('file_uploads') ? 'Enabled ✓' : 'Disabled ✗') . "\n";
echo "upload_max_filesize:  " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size:        " . ini_get('post_max_size') . "\n";
echo "max_file_uploads:     " . ini_get('max_file_uploads') . "\n";
echo "memory_limit:         " . ini_get('memory_limit') . "\n";
echo "max_execution_time:   " . ini_get('max_execution_time') . "s\n";
        ?></pre>
    </div>

    <div class="section">
        <h2>4. Server Information</h2>
        <pre><?php
echo "PHP Version:          " . PHP_VERSION . "\n";
echo "Server API:           " . PHP_SAPI . "\n";
echo "Server Software:      " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root:        " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Current Script:       " . __FILE__ . "\n";
        ?></pre>
    </div>

    <div class="section">
        <h2>5. Test File Upload</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
            echo "<h3>Upload Attempt Result:</h3>";
            echo "<pre>";
            print_r($_FILES['test_file']);
            echo "</pre>";
            
            $error = $_FILES['test_file']['error'];
            $errorMessages = [
                UPLOAD_ERR_OK => 'Success! No error.',
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder (ERROR 6 - THIS IS YOUR ISSUE!)',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'PHP extension stopped upload',
            ];
            
            echo "<h3>Error Analysis:</h3>";
            echo "<p class='status " . ($error === UPLOAD_ERR_OK ? 'ok' : 'error') . "'>";
            echo "Error Code: $error - " . ($errorMessages[$error] ?? 'Unknown error');
            echo "</p>";
            
            if ($error === UPLOAD_ERR_NO_TMP_DIR) {
                echo "<div class='section bad'>";
                echo "<h3>⚠️ SOLUTION NEEDED</h3>";
                echo "<p>The upload_tmp_dir is still not set correctly for the web server.</p>";
                echo "<p><strong>You MUST restart Herd for the php.ini changes to take effect!</strong></p>";
                echo "<ol>";
                echo "<li>Find Herd icon in system tray</li>";
                echo "<li>Right-click → Quit</li>";
                echo "<li>Reopen Herd</li>";
                echo "<li>Refresh this page</li>";
                echo "</ol>";
                echo "</div>";
            }
        } else {
        ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="test_file" required>
            <button type="submit">Test Upload</button>
        </form>
        <p><small>Select any small file and click "Test Upload" to see what happens</small></p>
        <?php } ?>
    </div>

    <div class="section">
        <h2>6. Recommendations</h2>
        <?php
        $issues = [];
        $recommendations = [];
        
        if (!ini_get('upload_tmp_dir')) {
            $issues[] = "upload_tmp_dir is NOT SET";
            $recommendations[] = "<strong>RESTART HERD!</strong> The php.ini was updated but Herd hasn't reloaded it yet.";
        }
        
        if (!ini_get('file_uploads')) {
            $issues[] = "File uploads are disabled";
            $recommendations[] = "Enable file_uploads in php.ini";
        }
        
        if (ini_get('upload_tmp_dir') && !is_dir(ini_get('upload_tmp_dir'))) {
            $issues[] = "Upload temp directory doesn't exist";
            $recommendations[] = "Create the directory: " . ini_get('upload_tmp_dir');
        }
        
        if (ini_get('upload_tmp_dir') && is_dir(ini_get('upload_tmp_dir')) && !is_writable(ini_get('upload_tmp_dir'))) {
            $issues[] = "Upload temp directory is not writable";
            $recommendations[] = "Fix permissions on: " . ini_get('upload_tmp_dir');
        }
        
        if (empty($issues)) {
            echo "<p class='status ok'>✓ All checks passed! File uploads should work.</p>";
        } else {
            echo "<h3 class='status error'>Issues Found:</h3>";
            echo "<ul>";
            foreach ($issues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
            
            echo "<h3>What to Do:</h3>";
            echo "<ol>";
            foreach ($recommendations as $rec) {
                echo "<li>$rec</li>";
            }
            echo "</ol>";
        }
        ?>
    </div>

    <div class="section">
        <h2>7. Quick Actions</h2>
        <p><a href="?action=phpinfo" target="_blank">View Full PHP Info</a></p>
        <p><a href="/test-upload-simple">Go to Simple Upload Test</a></p>
        <p><a href="/dashboard/settings">Go to Settings Page</a></p>
    </div>

    <?php if (isset($_GET['action']) && $_GET['action'] === 'phpinfo'): ?>
    <div class="section">
        <h2>Full PHP Info</h2>
        <?php phpinfo(); ?>
    </div>
    <?php endif; ?>

</body>
</html>
