<?php

namespace App\Livewire\Media;

use App\Models\Media;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaPicker extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showModal = false;
    public string $targetField = '';
    public array $uploads = [];
    public string $search = '';
    public string $activeTab = 'library';

    #[On('open-media-picker')]
    public function openPicker(string $targetField = ''): void
    {
        $this->targetField = $targetField;
        $this->showModal = true;
        $this->search = '';
        $this->activeTab = 'library';
        $this->resetPage();
    }

    public function closePicker(): void
    {
        $this->showModal = false;
        $this->targetField = '';
    }

    public function updatedUploads(): void
    {
        $this->validate([
            'uploads.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,webm|max:' . config('static-cms.media.max_upload_size', 51200),
        ]);

        foreach ($this->uploads as $upload) {
            // Real MIME check — block SVG/XML regardless of extension
            $realMime = (new \finfo(FILEINFO_MIME_TYPE))->file($upload->getRealPath());
            if (str_contains($realMime, 'svg') || str_contains($realMime, 'xml')) {
                continue;
            }

            $filename = $upload->getClientOriginalName();
            $path = $upload->store('media/' . date('Y/m'), 'public');
            $mime = $upload->getMimeType();

            $width = null;
            $height = null;
            if (str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml') {
                try {
                    $imageSize = getimagesize($upload->getRealPath());
                    if ($imageSize) {
                        $width = $imageSize[0];
                        $height = $imageSize[1];
                    }
                } catch (\Exception $e) {
                    // Skip
                }
            }

            Media::create([
                'filename' => $filename,
                'disk' => 'public',
                'path' => $path,
                'mime_type' => $mime,
                'size' => $upload->getSize(),
                'width' => $width,
                'height' => $height,
                'uploaded_by' => auth()->id(),
            ]);
        }

        $this->uploads = [];
        $this->activeTab = 'library';
    }

    public function selectImage(int $id): void
    {
        $media = Media::findOrFail($id);
        $this->dispatch('media-selected', url: $media->url, field: $this->targetField, id: $media->id);
        $this->closePicker();
    }

    public function render(): View
    {
        $query = Media::where(function($q) {
            $q->where('mime_type', 'like', 'image/%')
              ->orWhere('mime_type', 'like', 'video/%');
        })->orderBy('created_at', 'desc');

        if ($this->search) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $this->search);
            $query->where('filename', 'like', '%' . $search . '%');
        }

        return view('livewire.media.media-picker', [
            'mediaItems' => $query->paginate(24),
        ]);
    }
}
