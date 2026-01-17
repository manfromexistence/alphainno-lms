<!DOCTYPE html>
<html>
<head>
    <title>Debug File Upload</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="file"] { padding: 10px; border: 2px dashed #ccc; width: 100%; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        pre { background: #f4f4f4; padding: 15px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Debug File Upload</h1>
    <p>This page tests if file uploads are working correctly on your server.</p>
    
    <form action="{{ route('debug-upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="test_file">Select an image file:</label>
            <input type="file" name="test_file" id="test_file" accept="image/*">
        </div>
        <button type="submit">Test Upload</button>
    </form>
    
    <h2>PHP Configuration</h2>
    <pre>
upload_max_filesize: {{ ini_get('upload_max_filesize') }}
post_max_size: {{ ini_get('post_max_size') }}
file_uploads: {{ ini_get('file_uploads') ? 'On' : 'Off' }}
max_file_uploads: {{ ini_get('max_file_uploads') }}
upload_tmp_dir: {{ ini_get('upload_tmp_dir') ?: '(system default)' }}
sys_temp_dir: {{ sys_get_temp_dir() }}
    </pre>
    
    <h2>Storage Paths</h2>
    <pre>
storage_path: {{ storage_path() }}
public_path: {{ public_path() }}
storage/app/public exists: {{ is_dir(storage_path('app/public')) ? 'Yes' : 'No' }}
storage/app/public writable: {{ is_writable(storage_path('app/public')) ? 'Yes' : 'No' }}
public/storage symlink exists: {{ is_link(public_path('storage')) ? 'Yes' : 'No' }}
    </pre>
</body>
</html>
