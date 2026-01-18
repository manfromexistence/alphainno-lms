<!DOCTYPE html>
<html>
<head>
    <title>Simple Upload Test</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 5px; }
        input[type="file"] { margin: 10px 0; padding: 10px; border: 2px solid #ddd; width: 100%; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .info { background: #e7f3ff; padding: 10px; margin: 10px 0; border-left: 4px solid #2196F3; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Simple File Upload Test</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('debug'))
        <div class="info">
            <strong>Debug Info:</strong>
            <pre>{{ session('debug') }}</pre>
        </div>
    @endif

    <form method="POST" action="{{ route('test-upload-simple.submit') }}" enctype="multipart/form-data">
        @csrf
        
        <h3>Select a file to upload:</h3>
        <input type="file" name="upload_file" id="upload_file" accept="image/*" required>
        
        <div id="fileInfo" style="margin: 10px 0; padding: 10px; background: #f0f0f0; display: none;">
            <strong>Selected File:</strong><br>
            Name: <span id="fileName"></span><br>
            Size: <span id="fileSize"></span><br>
            Type: <span id="fileType"></span>
        </div>
        
        <button type="submit">Upload File</button>
    </form>

    <div class="info" style="margin-top: 20px;">
        <strong>PHP Settings:</strong><br>
        upload_max_filesize: {{ ini_get('upload_max_filesize') }}<br>
        post_max_size: {{ ini_get('post_max_size') }}<br>
        max_file_uploads: {{ ini_get('max_file_uploads') }}<br>
        file_uploads: {{ ini_get('file_uploads') ? 'Enabled' : 'Disabled' }}
    </div>

    <script>
        document.getElementById('upload_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileInfo').style.display = 'block';
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
                document.getElementById('fileType').textContent = file.type;
                console.log('File selected:', file);
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitting...');
            const formData = new FormData(this);
            console.log('FormData entries:');
            for (let pair of formData.entries()) {
                console.log(pair[0], pair[1]);
            }
        });
    </script>
</body>
</html>
