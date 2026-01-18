<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestUploadController extends Controller
{
    public function index()
    {
        return view('test-upload');
    }

    public function simple()
    {
        return view('test-upload-simple');
    }

    public function simpleSubmit(Request $request)
    {
        $debugInfo = [
            'has_file' => $request->hasFile('upload_file'),
            'all_files' => $request->allFiles(),
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'method' => $request->method(),
            'php_files' => $_FILES,
            'php_post' => $_POST,
        ];

        Log::info('Simple upload attempt', $debugInfo);

        if (!$request->hasFile('upload_file')) {
            return redirect()->route('test-upload-simple.index')
                ->with('error', 'No file detected in request!')
                ->with('debug', json_encode($debugInfo, JSON_PRETTY_PRINT));
        }

        try {
            $file = $request->file('upload_file');
            
            if (!$file->isValid()) {
                $error = $file->getError();
                $errorMessages = [
                    1 => 'File exceeds upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
                    2 => 'File exceeds MAX_FILE_SIZE in form',
                    3 => 'File was only partially uploaded',
                    4 => 'No file was uploaded',
                    6 => 'Missing temporary folder',
                    7 => 'Failed to write file to disk',
                    8 => 'PHP extension stopped upload',
                ];
                
                return redirect()->route('test-upload-simple.index')
                    ->with('error', 'Upload Error #' . $error . ': ' . ($errorMessages[$error] ?? 'Unknown error'))
                    ->with('debug', json_encode($debugInfo, JSON_PRETTY_PRINT));
            }

            $path = $file->store('test-uploads', 'public');
            
            $successInfo = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => round($file->getSize() / 1024, 2) . ' KB',
                'mime' => $file->getMimeType(),
            ];

            Log::info('File uploaded successfully', $successInfo);

            return redirect()->route('test-upload-simple.index')
                ->with('success', 'File uploaded successfully!')
                ->with('debug', json_encode($successInfo, JSON_PRETTY_PRINT));

        } catch (\Exception $e) {
            Log::error('Upload exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('test-upload-simple.index')
                ->with('error', 'Exception: ' . $e->getMessage())
                ->with('debug', json_encode($debugInfo, JSON_PRETTY_PRINT));
        }
    }

    public function submit(Request $request)
    {
        Log::info('Test upload form submitted', [
            'has_file' => $request->hasFile('test_file'),
            'all_input' => $request->all(),
            'files' => $request->allFiles(),
            'content_length' => $request->header('Content-Length'),
            'content_type' => $request->header('Content-Type'),
        ]);

        // Check if file was uploaded
        if (!$request->hasFile('test_file')) {
            $error = 'No file detected in request';
            Log::error($error, [
                'post_data' => $_POST,
                'files_data' => $_FILES,
                'upload_errors' => [
                    'UPLOAD_ERR_OK' => UPLOAD_ERR_OK,
                    'UPLOAD_ERR_INI_SIZE' => UPLOAD_ERR_INI_SIZE,
                    'UPLOAD_ERR_FORM_SIZE' => UPLOAD_ERR_FORM_SIZE,
                    'UPLOAD_ERR_PARTIAL' => UPLOAD_ERR_PARTIAL,
                    'UPLOAD_ERR_NO_FILE' => UPLOAD_ERR_NO_FILE,
                ],
            ]);
            return redirect()->route('test-upload.index')
                ->with('error', $error . ' - Check if file size exceeds limits or browser blocked upload.');
        }

        try {
            $validated = $request->validate([
                'test_file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:30720',
                'test_text' => 'nullable|string|max:255',
            ], [
                'test_file.required' => 'Please select a file to upload.',
                'test_file.file' => 'The uploaded file is not valid.',
                'test_file.mimes' => 'File must be an image (jpeg, png, jpg, gif, svg, webp, ico).',
                'test_file.max' => 'File size must not exceed 30MB.',
            ]);

            if ($request->hasFile('test_file')) {
                $file = $request->file('test_file');
                
                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                ]);

                // Check if file is valid
                if (!$file->isValid()) {
                    $errorCode = $file->getError();
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
                        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                    ];
                    
                    $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error';
                    Log::error('File upload error', ['code' => $errorCode, 'message' => $errorMsg]);
                    
                    return redirect()->route('test-upload.index')
                        ->with('error', "Upload Error #{$errorCode}: {$errorMsg}");
                }

                // Try to store the file
                $path = $file->store('test-uploads', 'public');
                
                Log::info('File stored successfully', ['path' => $path]);

                return redirect()->route('test-upload.index')
                    ->with('success', "✓ File uploaded successfully! Path: {$path} | Size: " . round($file->getSize() / 1024, 2) . " KB | Type: " . $file->getMimeType());
            }

            return redirect()->route('test-upload.index')
                ->with('error', 'No file was uploaded.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
            ]);
            
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = implode(' ', $messages);
            }
            
            return redirect()->route('test-upload.index')
                ->with('error', 'Validation Error: ' . implode(' | ', $errorMessages));
                
        } catch (\Exception $e) {
            Log::error('Upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('test-upload.index')
                ->with('error', 'Upload failed: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')');
        }
    }
}
