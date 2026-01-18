<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6">File Upload Test Page</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('test-upload.submit') }}" method="POST" enctype="multipart/form-data" id="testForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Test File Upload (Max 30MB)
                </label>
                <input type="file" 
                       name="test_file" 
                       id="test_file"
                       accept="image/*"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"
                       onchange="handleFileSelect(this)">
                <p class="mt-1 text-xs text-gray-500">Select any image file to test upload</p>
            </div>

            <div id="fileInfo" class="mb-6 hidden">
                <h3 class="font-semibold mb-2">Selected File Info:</h3>
                <div class="bg-gray-50 p-4 rounded">
                    <p><strong>Name:</strong> <span id="fileName"></span></p>
                    <p><strong>Size:</strong> <span id="fileSize"></span></p>
                    <p><strong>Type:</strong> <span id="fileType"></span></p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Test Text Input
                </label>
                <input type="text" 
                       name="test_text" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md"
                       placeholder="Enter some text">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-semibold">
                Submit Test Upload
            </button>
        </form>

        <div class="mt-8 p-4 bg-gray-50 rounded">
            <h3 class="font-semibold mb-2">Console Logs:</h3>
            <div id="consoleLogs" class="text-xs font-mono bg-white p-3 rounded border max-h-64 overflow-y-auto"></div>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            <h3 class="font-semibold mb-2">PHP Configuration:</h3>
            <ul class="space-y-1">
                <li><strong>upload_max_filesize:</strong> {{ ini_get('upload_max_filesize') }}</li>
                <li><strong>post_max_size:</strong> {{ ini_get('post_max_size') }}</li>
                <li><strong>memory_limit:</strong> {{ ini_get('memory_limit') }}</li>
                <li><strong>max_file_uploads:</strong> {{ ini_get('max_file_uploads') }}</li>
            </ul>
        </div>
    </div>

    <script>
        const consoleDiv = document.getElementById('consoleLogs');
        
        function log(message, data = null) {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = 'mb-1';
            logEntry.innerHTML = `<span class="text-gray-500">[${timestamp}]</span> ${message}`;
            if (data) {
                logEntry.innerHTML += `<pre class="ml-4 text-xs">${JSON.stringify(data, null, 2)}</pre>`;
            }
            consoleDiv.appendChild(logEntry);
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
            console.log(message, data);
        }

        function handleFileSelect(input) {
            const file = input.files[0];
            log('File selected', {
                name: file?.name,
                size: file?.size,
                type: file?.type
            });

            if (file) {
                document.getElementById('fileInfo').classList.remove('hidden');
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatBytes(file.size);
                document.getElementById('fileType').textContent = file.type;
            }
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        document.getElementById('testForm').addEventListener('submit', function(e) {
            log('Form submitting...');
            
            const formData = new FormData(this);
            const entries = {};
            let hasFile = false;
            
            for (let [key, value] of formData.entries()) {
                if (value instanceof File) {
                    hasFile = true;
                    entries[key] = {
                        name: value.name,
                        size: value.size,
                        type: value.type,
                        lastModified: value.lastModified
                    };
                    log(`File found: ${key}`, entries[key]);
                } else {
                    entries[key] = value;
                }
            }
            
            if (!hasFile) {
                log('WARNING: No file in FormData!', 'This means the file input is empty');
            }
            
            log('All form data:', entries);
            log('Form will now submit to server...');
        });

        log('Test page loaded');
    </script>
</body>
</html>
