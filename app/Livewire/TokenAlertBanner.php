<?php

namespace App\Livewire;

use Livewire\Component;

class TokenAlertBanner extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        $notifications = $user 
            ? $user->unreadNotifications
                ->where('type', \App\Notifications\TokenThresholdReachedNotification::class)
            : collect();

        return view('livewire.token-alert-banner', [
            'notifications' => $notifications,
        ]);
    }

    public function dismissNotification(string $notificationId): void
    {
        auth()->user()?->unreadNotifications->where('id', $notificationId)->first()?->markAsRead();
    }
}
