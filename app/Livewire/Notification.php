<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $notifications = [];
    
    protected $listeners = ['notify' => 'addNotification'];
    
    public function addNotification($data)
    {
        $notification = [
            'id' => uniqid(),
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'timeout' => $data['timeout'] ?? 3000,
        ];
        
        $this->notifications[] = $notification;
        
        // Auto remove notification after timeout
        $this->dispatch('remove-notification', [
            'id' => $notification['id'],
            'timeout' => $notification['timeout'],
        ]);
    }
    
    public function remove($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }
    
    public function render()
    {
        return view('livewire.notification');
    }
}
