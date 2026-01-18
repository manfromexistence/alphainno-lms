<?php
// Quick test to verify the runtime fix is working
header('Content-Type: text/plain');

echo "Quick Upload Configuration Test\n";
echo "================================\n\n";

echo "1. upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: '(not set)') . "\n";
echo "2. System temp: " . sys_get_temp_dir() . "\n";
echo "3. Temp dir exists: " . (is_dir(sys_get_temp_dir()) ? 'Yes' : 'No') . "\n";
echo "4. Temp dir writable: " . (is_writable(sys_get_temp_dir()) ? 'Yes' : 'No') . "\n\n";

if (ini_get('upload_tmp_dir')) {
    echo "✓ SUCCESS! upload_tmp_dir is now set!\n";
    echo "The runtime fix is working.\n\n";
    echo "Next: Visit /test-upload-simple to test actual file upload\n";
} else {
    echo "✗ FAILED: upload_tmp_dir is still not set\n";
    echo "The runtime fix didn't work.\n\n";
    echo "Please share this output for further diagnosis.\n";
}
