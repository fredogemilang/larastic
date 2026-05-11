<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pages')]
class PageList extends Component
{
    public function toggleStatus(int $id): void
    {
        $page = Page::findOrFail($id);
        $page->update([
            'status' => $page->status === 'published' ? 'draft' : 'published',
        ]);
        $this->dispatch('notify', type: 'success', message: 'Page status updated.');
    }

    public function deletePage(int $id): void
    {
        Page::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Page deleted.');
    }

    public function render(): View
    {
        return view('livewire.pages.page-list', [
            'pages' => Page::orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }
}
