<?php

namespace App\Http\Controllers;

use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    public function index()
    {
        $files = FileManager::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('boilerplate::file-manager.index', compact('files'));
    }

    public function create()
    {
        return view('boilerplate::file-manager.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // Max 100MB
            'description' => 'nullable|string|max:500'
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize();
        $mimeType = $uploadedFile->getMimeType();
        $fileType = $this->getFileCategory($mimeType);
        
        // Generate unique filename
        $filename = Str::uuid() . '_' . time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $uploadedFile->getClientOriginalExtension();
        
        // Store file
        $path = $uploadedFile->storeAs('uploads/files', $filename, 'public');
        
        // Create database record
        $file = FileManager::create([
            'name' => $filename,
            'original_name' => $originalName,
            'file_path' => $path,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'mime_type' => $mimeType,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('boilerplate.file-manager.index')
            ->with('growl', [
                'type' => 'success',
                'message' => 'File uploaded successfully!',
                'title' => 'Success'
            ]);
    }

    public function preview(FileManager $fileManager)
    {
        if (!$fileManager->isImage()) {
            abort(404, 'Preview not available for this file type.');
        }
        
        $filePath = storage_path('app/public/' . $fileManager->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        return response()->file($filePath, [
            'Content-Type' => $fileManager->mime_type
        ]);
    }

    public function download(FileManager $fileManager)
    {
        $filePath = storage_path('app/public/' . $fileManager->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        return response()->download($filePath, $fileManager->original_name, [
            'Content-Type' => $fileManager->mime_type
        ]);
    }

    public function destroy(FileManager $fileManager)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($fileManager->file_path)) {
            Storage::disk('public')->delete($fileManager->file_path);
        }
        
        // Delete database record
        $fileManager->delete();
        
        return redirect()->route('boilerplate.file-manager.index')
            ->with('growl', [
                'type' => 'success',
                'message' => 'File deleted successfully!',
                'title' => 'Success'
            ]);
    }

    private function getFileCategory($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (str_starts_with($mimeType, 'audio/')) return 'audio';
        if ($mimeType === 'application/pdf') return 'pdf';
        if (str_contains($mimeType, 'word')) return 'document';
        if (str_contains($mimeType, 'excel')) return 'spreadsheet';
        if (str_contains($mimeType, 'presentation')) return 'presentation';
        if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar')) return 'archive';
        return 'other';
    }
}