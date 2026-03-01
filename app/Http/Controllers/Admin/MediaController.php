<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class MediaController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('can:media-management-view', ['only' => ['index']]);
    //     $this->middleware('can:media-management-create', ['only' => ['upload', 'download']]);
    //     $this->middleware('can:media-management-delete', ['only' => ['destroy', 'restore']]);
    //     $this->middleware('can:media-management-update', ['only' => ['update']]);
    // }
    /**
     * Display a listing of the media.
     */
    public function index(Request $request)
    {
        $request->validate([
            'trashed' => 'sometimes',
            'type_filter' => 'sometimes|string|in:image,video,audio,document',
            'date_filter' => 'sometimes|string|in:today,week,month,year',
            'search' => 'sometimes|string|max:255',
        ]);

        $trashed = $request->boolean('trashed', false);
        $typeFilter = $request->get('type_filter');
        $dateFilter = $request->get('date_filter');
        $search = $request->get('search');

        // Build query
        if ($trashed) {
            $query = Media::onlyTrashed();
        } else {
            $query = Media::query();
        }

        // Apply type filter
        if ($typeFilter) {
            switch ($typeFilter) {
                case 'image':
                    $query->where('mime_type', 'like', 'image/%');
                    break;
                case 'video':
                    $query->where('mime_type', 'like', 'video/%');
                    break;
                case 'audio':
                    $query->where('mime_type', 'like', 'audio/%');
                    break;
                case 'document':
                    $query->where('mime_type', 'like', 'application/%');
                    break;
            }
        }

        // Apply date filter
        if ($dateFilter) {
            $now = now();
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', $now->subYear());
                    break;
            }
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', '%' . $search . '%')
                    ->orWhere('alt_text', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $medias = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($request->ajax()) {
            // Check if this is a media picker request
            if ($request->has('picker')) {
                // Return raw media data for JavaScript processing
                $mediaItems = $medias->items();
                $transformedMedia = collect($mediaItems)->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'filename' => $media->filename,
                        'url' => $media->url,
                        'alt_text' => $media->alt_text ?? '',
                        'description' => $media->description ?? '',
                        'mime_type' => $media->mime_type,
                        'filesize' => $media->filesize,
                        'extension' => $media->extension,
                        'created_at' => $media->created_at->toISOString(),
                        'formatted_date' => $media->created_at->format('M j, Y'),
                        'is_image' => $media->isImage(),
                        'is_document' => $media->isDocument(),
                        'is_video' => $media->isVideo(),
                        // Add dimensions if you have them stored or can calculate
                        'dimensions' => $media->isImage() ? '800 by 600 pixels' : '',
                    ];
                });

                return response()->json([
                    'medias' => $transformedMedia,
                    'has_more' => $medias->hasMorePages(),
                    'current_page' => $medias->currentPage(),
                    'total' => $medias->total(),
                ]);
            } else {
                // Return HTML for main page (existing functionality)
                return response()->json([
                    'html' => view('admin.media.partials.media_grid', compact('medias', 'trashed'))->render(),
                    'links' => $medias->render(),
                    'has_more' => $medias->hasMorePages(),
                ]);
            }
        }

        return view('admin.media.index', compact('medias', 'trashed'));
    }

    /**
     * Get lightbox data for image navigation.
     */
    public function getLightboxData(Request $request)
    {
        $request->validate([
            'trashed' => 'sometimes|boolean',
            'type_filter' => 'sometimes|string|in:image,video,audio,document',
            'date_filter' => 'sometimes|string|in:today,week,month,year',
            'search' => 'sometimes|string|max:255',
        ]);

        $trashed = $request->boolean('trashed', false);
        $typeFilter = $request->get('type_filter');
        $dateFilter = $request->get('date_filter');
        $search = $request->get('search');

        // Build query for images only
        if ($trashed) {
            $query = Media::onlyTrashed();
        } else {
            $query = Media::query();
        }

        // Only get images for lightbox
        $query->where('mime_type', 'like', 'image/%');

        // Apply filters (same logic as index method)
        if ($typeFilter && $typeFilter === 'image') {
            // Already filtered to images above
        }

        if ($dateFilter) {
            $now = now();
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', $now->subYear());
                    break;
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', '%' . $search . '%')
                    ->orWhere('alt_text', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $images = $query->orderBy('created_at', 'desc')->get();

        $lightboxData = $images->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->url,
                'alt_text' => $media->alt_text ?? '',
                'filename' => $media->filename,
                'description' => $media->description ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'images' => $lightboxData,
        ]);
    }
    /**
     * Handle media upload.
     */
    public function upload(Request $request)
    {
        try {
            $request->validate(
                [
                    'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,mp3,mp4|max:2048', // 20MB max
                ],
                [
                    'file.required' => 'No file was uploaded.',
                    'file.file' => 'The uploaded item is not a valid file.',
                    'file.mimes' => 'Unsupported file type. Allowed types: images, PDFs, documents, audio, video.',
                    'file.max' => 'File size cannot exceed 20MB.',
                ],
            );

            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $filesize = $file->getSize();

            // Determine storage path based on MIME type
            $directory = 'uploads/files';
            $type = 'file';
            if (str_starts_with($mimeType, 'image/')) {
                $directory = 'uploads/images';
                $type = 'images';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $directory = 'uploads/videos';
                $type = 'video';
            } elseif (str_starts_with($mimeType, 'audio/')) {
                $directory = 'uploads/audio';
                $type = 'audio';
            } elseif (str_starts_with($mimeType, 'application/pdf') || str_starts_with($mimeType, 'application/msword') || str_starts_with($mimeType, 'application/vnd.openxmlformats-officedocument')) {
                $directory = 'uploads/documents';
                $type = 'document';
            }

            $filename = time() . '_' . Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            if ($directory === 'uploads/images') {
                // Process image: reduce size to below 1MB and convert to WebP format
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Convert to WebP and get the filename with .webp extension
                $pathInfo = pathinfo($filename);
                $webpFileName = $pathInfo['filename'] . '.webp';
                $filepath = "$directory/$webpFileName";

                $maxFileSize = 1024 * 1024; // 1MB in bytes

                // Get original dimensions
                $originalWidth = $image->width();
                $originalHeight = $image->height();

                // For very large images, resize first to reasonable dimensions
                $maxDimension = 1920; // Max width or height
                if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
                    if ($originalWidth > $originalHeight) {
                        $newWidth = $maxDimension;
                        $newHeight = (int) (($originalHeight / $originalWidth) * $maxDimension);
                    } else {
                        $newHeight = $maxDimension;
                        $newWidth = (int) (($originalWidth / $originalHeight) * $maxDimension);
                    }
                    $image = $image->resize($newWidth, $newHeight);
                }

                // Try different quality levels efficiently
                $qualityLevels = [85, 70, 60, 50, 40, 30, 20];
                $encoded = null;
                $imageSize = 0;

                foreach ($qualityLevels as $quality) {
                    $encoded = $image->toWebp($quality);
                    $imageSize = strlen($encoded);

                    if ($imageSize <= $maxFileSize) {
                        break;
                    }
                }

                // If still too large, resize further
                if ($imageSize > $maxFileSize) {
                    $currentWidth = $image->width();
                    $currentHeight = $image->height();
                    $reductionFactor = 0.8; // Reduce by 20%

                    while ($imageSize > $maxFileSize && $reductionFactor > 0.3) {
                        $newWidth = (int) ($currentWidth * $reductionFactor);
                        $newHeight = (int) ($currentHeight * $reductionFactor);

                        $resizedImage = $image->resize($newWidth, $newHeight);
                        $encoded = $resizedImage->toWebp(85); // Use moderate quality
                        $imageSize = strlen($encoded);

                        if ($imageSize <= $maxFileSize) {
                            $image = $resizedImage; // Keep the successful resize
                            break;
                        }

                        $reductionFactor -= 0.1;
                    }
                }

                // Save the processed image
                Storage::put($filepath, $encoded);

                // Update variables for database storage
                $filename = $webpFileName;
                $mimeType = 'image/webp';
                $filesize = strlen($encoded);
            } else {
                // Non-image: just store the file normally
                $filepath = $file->storeAs($directory, $filename, 'local');
            }

            $media = Media::create([
                'filename' => $filename,
                'disk' => 'local',
                'filepath' => $filepath,
                'mime_type' => $mimeType,
                'filesize' => $filesize,
                'mediable_type' => $type,
                'alt_text' => Str::title(pathinfo($originalFilename, PATHINFO_FILENAME)), // Default alt text
                'description' => null,
                'uploader_id' => auth()->id(), // Assign current user as uploader
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'media' => [
                    'id' => $media->id,
                    'url' => url('/') . '/' . $media->filepath,
                    'alt_text' => $media->alt_text,
                    'filename' => $media->filename,
                    'is_image' => $media->isImage(),
                    'is_document' => $media->isDocument(),
                    'extension' => $media->extension,
                    'mime_type' => $media->mime_type,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation failed: ' . $e->getMessage(),
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'File upload failed: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Download media from URL.
     */
    public function download(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $fileUrl = $request->input('url');
        $altText = $request->input('alt_text', '');

        // Decode filename (remove query params, decode special chars)
        $originalName = urldecode(basename(parse_url($fileUrl, PHP_URL_PATH)));

        // Download the file
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; MyLaravelApp/1.0)',
            'Accept' => '*/*',
        ])
            ->withOptions([
                'allow_redirects' => true,
                'verify' => false, // ⚠️ Only disable if SSL issues occur
            ])
            ->get($fileUrl);

        if (!$response->successful()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to download file. Please check the URL and try again.',
                    'url' => $fileUrl,
                ],
                400,
            );
        }

        // Store temporarily
        $tempPath = "uploads/tmp/$originalName";
        Storage::put($tempPath, $response->body());

        // Detect mime & size
        $mimeType = Storage::mimeType($tempPath);
        $fileSize = Storage::size($tempPath);

        // Decide final directory
        $directory = match (true) {
            str_starts_with($mimeType, 'image/') => 'uploads/images',
            str_starts_with($mimeType, 'video/') => 'uploads/videos',
            str_starts_with($mimeType, 'audio/') => 'uploads/audio',
            str_starts_with($mimeType, 'application/pdf'), str_starts_with($mimeType, 'application/msword'), str_starts_with($mimeType, 'application/vnd.openxmlformats-officedocument') => 'uploads/documents',
            default => 'uploads/files',
        };

        $fileName = $originalName;
        $finalPath = "$directory/$fileName";

        if ($directory === 'uploads/images') {
            // Process image: reduce size to below 1MB and convert to WebP format
            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::path($tempPath));

            // Convert to WebP and get the filename with .webp extension
            $pathInfo = pathinfo($fileName);
            $webpFileName = $pathInfo['filename'] . '.webp';
            $finalPath = "$directory/$webpFileName";

            $maxFileSize = 1024 * 1024; // 1MB in bytes

            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // For very large images, resize first to reasonable dimensions
            $maxDimension = 1920; // Max width or height
            if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
                if ($originalWidth > $originalHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) (($originalHeight / $originalWidth) * $maxDimension);
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) (($originalWidth / $originalHeight) * $maxDimension);
                }
                $image = $image->resize($newWidth, $newHeight);
            }

            // Try different quality levels efficiently
            $qualityLevels = [85, 70, 60, 50, 40, 30, 20];
            $encoded = null;
            $imageSize = 0;

            foreach ($qualityLevels as $quality) {
                $encoded = $image->toWebp($quality);
                $imageSize = strlen($encoded);

                if ($imageSize <= $maxFileSize) {
                    break;
                }
            }

            // If still too large, resize further
            if ($imageSize > $maxFileSize) {
                $currentWidth = $image->width();
                $currentHeight = $image->height();
                $reductionFactor = 0.8; // Reduce by 20%

                while ($imageSize > $maxFileSize && $reductionFactor > 0.3) {
                    $newWidth = (int) ($currentWidth * $reductionFactor);
                    $newHeight = (int) ($currentHeight * $reductionFactor);

                    $resizedImage = $image->resize($newWidth, $newHeight);
                    $encoded = $resizedImage->toWebp(85); // Use moderate quality
                    $imageSize = strlen($encoded);

                    if ($imageSize <= $maxFileSize) {
                        $image = $resizedImage; // Keep the successful resize
                        break;
                    }

                    $reductionFactor -= 0.1;
                }
            }

            // Save the processed image
            Storage::put($finalPath, $encoded);

            // Update variables for database storage
            $fileName = $webpFileName;
            $mimeType = 'image/webp';
            $fileSize = strlen($encoded);
        } else {
            // Non-image: just move the file
            Storage::move($tempPath, $finalPath);
        }

        // Clean up temporary file if it still exists
        if (Storage::exists($tempPath)) {
            Storage::delete($tempPath);
        }

        // Save in DB
        $media = Media::create([
            'filename' => $fileName,
            'disk' => 'local',
            'filepath' => $finalPath,
            'mime_type' => $mimeType,
            'filesize' => $fileSize,
            'alt_text' => $altText,
            'uploader_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File downloaded successfully.',
            'media' => $media,
        ]);
    }

    /**
     * Update media details (alt text, description, filename).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'filename' => 'required|string|max:255',
        ]);

        $media = Media::findOrFail($id);

        $media->update([
            'alt_text' => $request->input('alt_text'),
            'description' => $request->input('description'),
            'filename' => $request->input('filename'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully.',
            'media' => $media,
        ]);
    }

    /**
     * Remove the specified media from storage.
     */
    public function destroy(Request $request, $id)
    {
        $media = Media::withTrashed()->findOrFail($id);

        if ($media->deleted_at) {
            //delete permanently
            if (Storage::exists($media->filepath)) {
                Storage::delete($media->filepath);
            }
            $media->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Media permanently deleted successfully.',
            ]);
        }

        // Delete the database record (soft delete)
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media trashed successfully.',
        ]);
    }

    /**
     * Restore the specified media from trash.
     */
    public function restore($id)
    {
        $media = Media::onlyTrashed()->findOrFail($id);
        $media->restore();

        return response()->json([
            'success' => true,
            'message' => 'Media restored successfully.',
        ]);
    }
}
