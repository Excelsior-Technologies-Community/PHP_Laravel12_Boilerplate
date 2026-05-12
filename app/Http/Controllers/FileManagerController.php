<?php

namespace App\Http\Controllers;

use App\Models\FileManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends Controller
{
    public function index()
    {
        $files = FileManager::query()
            ->with('uploader')
            ->latest()
            ->paginate(15);

        return view('boilerplate::file-manager.index', compact('files'));
    }

    public function create()
    {
        return view('boilerplate::file-manager.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200',
            ],
        ]);

        $uploadedFile = $validated['file'];
        $disk = 'public';
        $directory = 'file-manager/'.now()->format('Y/m');
        $fileName = (string) Str::uuid();
        $path = $uploadedFile->storeAs($directory, $fileName, $disk);

        FileManager::create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'file_name' => $fileName,
            'path' => $path,
            'disk' => $disk,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'uploaded_by' => $request->user()?->id,
        ]);

        return redirect()
            ->route('boilerplate.file-manager.index')
            ->with('growl', [__('File uploaded successfully'), 'success']);
    }

    public function preview(FileManager $fileManager)
    {
        abort_unless(Storage::disk($fileManager->disk)->exists($fileManager->path), 404);

        $headers = [];

        if ($fileManager->mime_type) {
            $headers['Content-Type'] = $fileManager->mime_type;
        }

        return response()->file(Storage::disk($fileManager->disk)->path($fileManager->path), $headers);
    }

    public function download(FileManager $fileManager): StreamedResponse
    {
        abort_unless(Storage::disk($fileManager->disk)->exists($fileManager->path), 404);

        return Storage::disk($fileManager->disk)->download($fileManager->path, $fileManager->original_name);
    }

    public function destroy(FileManager $fileManager): RedirectResponse
    {
        if (Storage::disk($fileManager->disk)->exists($fileManager->path)) {
            Storage::disk($fileManager->disk)->delete($fileManager->path);
        }

        $fileManager->delete();

        return back()->with('growl', [__('File deleted successfully'), 'success']);
    }
}
