<?php

namespace Blaze\AdminCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MediaUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            ]);

            $file = $request->file('file');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->extension();
            $path = $file->storeAs('editor-media', $filename, 'public');
            
            return response()->json([
                'url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function delete(Request $request)
    {
        try {
            $url = $request->input('url');
            if (!$url) {
                return response()->json(['success' => false], 400);
            }

            // Extract the relative path safely from an absolute URL
            $parsedUrl = parse_url($url, PHP_URL_PATH);
            $storagePrefix = Storage::url('');
            
            if (str_starts_with($parsedUrl, $storagePrefix)) {
                $path = substr($parsedUrl, strlen($storagePrefix));
            } else {
                $path = $parsedUrl;
            }

            // Ensure we are only deleting from the editor-media directory
            if (!str_starts_with($path, 'editor-media/')) {
                return response()->json(['error' => 'Invalid path'], 403);
            }
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
