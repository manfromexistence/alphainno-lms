<?php

$phpIniPath = 'C:\Users\Computer\.config\herd\bin\php84\php.ini';
$tempDir = 'C:\Users\Computer\AppData\Local\Temp';

echo "Force Update PHP.ini for Herd\n";
echo "================================\n\n";

// Read the file
$content = file_get_contents($phpIniPath);

// Remove any existing upload_tmp_dir lines (commented or not)
$content = preg_replace('/^.*upload_tmp_dir.*$/m', '', $content);

// Find the [PHP] section or create it
if (strpos($content, '[PHP]') !== false) {
    // Add after [PHP] section
    $content = preg_replace(
        '/(\[PHP\])/i',
        "[PHP]\n; Upload temporary directory\nupload_tmp_dir = \"$tempDir\"",
        $content,
        1
    );
    echo "✓ Added upload_tmp_dir after [PHP] section\n";
} else {
    // Add at the beginning of the file
    $content = "; Upload temporary directory\nupload_tmp_dir = \"$tempDir\"\n\n" . $content;
    echo "✓ Added upload_tmp_dir at beginning of file\n";
}

// Also add it in the File Uploads section if it exists
if (strpos($content, 'File Uploads') !== false || strpos($content, 'file_uploads') !== false) {
    $content = preg_replace(
        '/(file_uploads\s*=.*)/i',
        "$1\nupload_tmp_dir = \"$tempDir\"",
        $content,
        1
    );
    echo "✓ Added upload_tmp_dir in File Uploads section\n";
}

// Write back
file_put_contents($phpIniPath, $content);

echo "\n✓ PHP.ini updated successfully!\n\n";

echo "Now you MUST restart Herd:\n";
echo "1. Right-click Herd icon in system tray\n";
echo "2. Click 'Quit' or 'Stop Services'\n";
echo "3. Reopen Herd\n\n";

echo "Or run this command:\n";
echo "herd restart\n\n";

// Show what we set
echo "Setting applied:\n";
echo "upload_tmp_dir = \"$tempDir\"\n";
