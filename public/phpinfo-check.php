<?php
echo "<h1>PHP Configuration Check</h1>";
echo "<h2>upload_tmp_dir Setting</h2>";
echo "<p><strong>Value:</strong> " . (ini_get('upload_tmp_dir') ?: '(not set)') . "</p>";
echo "<p><strong>Loaded php.ini:</strong> " . php_ini_loaded_file() . "</p>";
echo "<hr>";
echo "<h2>Upload Settings</h2>";
echo "<pre>";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "file_uploads: " . (ini_get('file_uploads') ? 'On' : 'Off') . "\n";
echo "sys_temp_dir: " . sys_get_temp_dir() . "\n";
echo "</pre>";
echo "<hr>";
echo "<h2>Full PHP Info</h2>";
phpinfo();
