<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
#[Title('Users')]
class UserManager extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'author';
    public ?int $editingId = null;
    public bool $showForm = false;

    public function mount(): void
    {
        if (!auth()->user()->hasRole('super_admin')) {
            abort(403);
        }
    }

    protected function rules(): array
    {
        $emailRule = $this->editingId
            ? 'unique:users,email,' . $this->editingId
            : 'unique:users,email';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|' . $emailRule,
            'role' => 'required|in:super_admin,admin,editor,author',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function openForm(?int $id = null): void
    {
        if (!auth()->user()->hasRole('super_admin')) abort(403);
        if ($id) {
            $user = User::findOrFail($id);
            $this->editingId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles->first()?->name ?? 'author';
            $this->password = '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        if (!auth()->user()->hasRole('super_admin')) abort(403);
        $this->validate();

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->password = $this->password;
                $user->save();
            }

            $user->syncRoles([$this->role]);
            $this->dispatch('notify', type: 'success', message: 'User updated.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'email_verified_at' => now(),
            ]);

            $user->assignRole($this->role);
            $this->dispatch('notify', type: 'success', message: 'User created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function deleteUser(int $id): void
    {
        if (!auth()->user()->hasRole('super_admin')) abort(403);
        if ($id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'You cannot delete your own account.');
            return;
        }

        User::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'User deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'author';
    }

    public function render(): View
    {
        return view('livewire.settings.user-manager', [
            'users' => User::with('roles')->orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
