<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
class UsersIndex extends Component
{

// Dans UserTable.php
#[Url]
public $filterStatus = ''; // Pour filtrer les actifs/inactifs
public $search;
public function render()
{
    $users = User::query()
    // Left join ensures users without a subscription still show up in the list
    ->leftJoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')
    ->select('users.*', 'subscriptions.stripe_status') 
    ->when($this->search, fn($q) => $q->where('users.name', 'like', "%{$this->search}%"))
    ->when($this->filterStatus, function($q) {
        $q->where('subscriptions.stripe_status', $this->filterStatus);
    })
    ->latest('users.created_at') // Explicitly specify table to avoid column ambiguity
    ->paginate(10);


    return view('livewire.admin.users.users-index', ['users' => $users]);
}

    
}
