<?php

namespace Blaze\AdminCore\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesMedia
{
    /**
     * Boot the trait to hook into Eloquent events.
     */
    public static function bootHandlesMedia()
    {
        static::saved(function ($model) {
            $model->processRichTextMedia();
        });

        static::deleted(function ($model) {
            $model->cleanupAllMedia();
        });
    }

    /**
     * Process rich text fields to extract Base64 images and sync with storage.
     */
    public function processRichTextMedia()
    {
        $richTextFields = $this->richTextFields ?? ['content'];
        $changesMade = false;
        $disk = Storage::disk('public');
        $directory = $this->getMediaDirectory() . '/' . $this->getKey() . '/editor-media';
        
        $validFileUrls = [];

        foreach ($richTextFields as $field) {
            $html = $this->{$field};
            
            if (empty($html)) {
                continue;
            }

            // Find all image tags in the HTML
            preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
            $sources = $matches[1] ?? [];

            foreach ($sources as $src) {
                // If the image is a Base64 string, upload it
                if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                    $data = substr($src, strpos($src, ',') + 1);
                    $type = strtolower($type[1]);
                    $data = base64_decode($data);

                    if ($data === false) {
                        continue;
                    }

                    $extension = $type == 'jpeg' ? 'jpg' : $type;
                    $filename = Str::uuid() . '.' . $extension;
                    $path = $directory . '/' . $filename;
                    
                    $disk->put($path, $data);
                    $url = Storage::url($path);

                    // Replace Base64 string with URL in the HTML
                    $html = str_replace($src, $url, $html);
                    $changesMade = true;
                    $validFileUrls[] = $url;
                } 
                // If it's already an uploaded file in our directory, keep track of it
                elseif (str_contains($src, Storage::url($directory))) {
                    $validFileUrls[] = $src;
                }
            }

            if ($changesMade) {
                $this->{$field} = $html;
            }
        }

        // Cleanup orphaned files
        if ($disk->exists($directory)) {
            $existingFiles = $disk->files($directory);
            foreach ($existingFiles as $file) {
                $fileUrl = Storage::url($file);
                // If this file is no longer in the HTML across any rich text field, delete it
                if (!in_array($fileUrl, $validFileUrls)) {
                    $disk->delete($file);
                }
            }
        }

        if ($changesMade) {
            $this->saveQuietly();
        }
    }

    /**
     * Delete all media directories associated with this record.
     */
    public function cleanupAllMedia()
    {
        $directory = $this->getMediaDirectory() . '/' . $this->getKey();
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }

    /**
     * Get the base directory for this model's media files.
     */
    public function getMediaDirectory()
    {
        return $this->mediaDirectory ?? Str::snake(class_basename($this));
    }
}
