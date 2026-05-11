<?php

namespace App\Livewire\Media;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Media Library')]
class MediaLibrary extends Component
{
    use WithFileUploads;

    public array $uploads = [];
    public string $viewMode = 'grid'; // grid or list
    public string $filterType = ''; // image, document, etc.
    public ?int $editingId = null;
    public string $editAltText = '';
    public bool $isEmbedMode = false; // when used as picker from PostEditor
    public array $selected = [];
    public bool $selectAll = false;

    public function updatedUploads(): void
    {
        $this->validate([
            'uploads.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,webm|max:' . config('static-cms.media.max_upload_size', 10240),
        ]);

        foreach ($this->uploads as $upload) {
            // Real MIME check — block SVG/XML regardless of extension
            $realMime = (new \finfo(FILEINFO_MIME_TYPE))->file($upload->getRealPath());
            if (str_contains($realMime, 'svg') || str_contains($realMime, 'xml')) {
                continue;
            }
            $mime = $upload->getMimeType();
            $filename = $upload->getClientOriginalName();
            $size = $upload->getSize();
            $width = null;
            $height = null;
            $path = '';

            if (str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml') {
                try {
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                    $image = $manager->decodePath($upload->getRealPath());
                    
                    $width = $image->width();
                    $height = $image->height();
                    
                    // Resize if too large (max width 1920px)
                    if ($width > 1920) {
                        $image->scaleDown(width: 1920);
                        $width = $image->width();
                        $height = $image->height();
                    }
                    
                    // Convert to WebP
                    $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 80));
                    
                    // Update metadata
                    $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                    $filename = $filenameWithoutExt . '.webp';
                    $mime = 'image/webp';
                    
                    // Save to storage
                    $path = 'media/' . date('Y/m') . '/' . uniqid() . '_' . $filename;
                    Storage::disk('public')->put($path, (string) $encoded);
                    $size = strlen((string) $encoded);
                    
                } catch (\Exception $e) {
                    // Fallback to normal upload if processing fails
                    $path = $upload->store('media/' . date('Y/m'), 'public');
                    try {
                        $imageSize = getimagesize($upload->getRealPath());
                        if ($imageSize) {
                            $width = $imageSize[0];
                            $height = $imageSize[1];
                        }
                    } catch (\Exception $ex) {}
                }
            } else {
                $path = $upload->store('media/' . date('Y/m'), 'public');
            }

            Media::create([
                'filename' => $filename,
                'disk' => 'public',
                'path' => $path,
                'mime_type' => $mime,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'uploaded_by' => auth()->id(),
            ]);
        }

        $this->uploads = [];
        $this->dispatch('notify', type: 'success', message: 'Files uploaded successfully.');
    }

    public function editMedia(int $id): void
    {
        $media = Media::findOrFail($id);
        $this->authorizeMedia($media);
        $this->editingId = $id;
        $this->editAltText = $media->alt_text ?? '';
    }

    public function updateAltText(): void
    {
        if ($this->editingId) {
            $media = Media::findOrFail($this->editingId);
            $this->authorizeMedia($media);
            $media->update(['alt_text' => $this->editAltText]);
            $this->editingId = null;
            $this->editAltText = '';
            $this->dispatch('notify', type: 'success', message: 'Alt text updated.');
        }
    }

    public function deleteMedia(int $id): void
    {
        $media = Media::findOrFail($id);
        $this->authorizeMedia($media);

        // Delete file from storage
        Storage::disk($media->disk)->delete($media->path);

        // Delete variants
        if ($media->variants) {
            foreach ($media->variants as $variantPath) {
                Storage::disk($media->disk)->delete($variantPath);
            }
        }

        $media->delete();
        $this->dispatch('notify', type: 'success', message: 'Media deleted.');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selected)) {
            return;
        }

        $mediaItems = Media::whereIn('id', $this->selected)->get();

        foreach ($mediaItems as $media) {
            Storage::disk($media->disk)->delete($media->path);
            if ($media->variants) {
                foreach ($media->variants as $variantPath) {
                    Storage::disk($media->disk)->delete($variantPath);
                }
            }
            $media->delete();
        }

        $this->selected = [];
        $this->dispatch('notify', type: 'success', message: 'Selected media deleted.');
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value
            ? $this->getMediaQuery()->pluck('id')->map(fn ($id) => (string) $id)->toArray()
            : [];
    }

    /**
     * Check if the current user is authorized to modify the given media.
     */
    protected function authorizeMedia(Media $media): void
    {
        if ($media->uploaded_by !== auth()->id()
            && !auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'You are not authorized to modify this media.');
        }
    }

    private function getMediaQuery()
    {
        $query = Media::orderBy('created_at', 'desc');

        if ($this->filterType === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($this->filterType === 'document') {
            $query->where('mime_type', 'not like', 'image/%');
        }

        return $query;
    }

    public function render(): View
    {
        return view('livewire.media.media-library', [
            'mediaItems' => $this->getMediaQuery()->paginate(24),
        ]);
    }
}
