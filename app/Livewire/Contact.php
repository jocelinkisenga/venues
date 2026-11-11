<?php

namespace App\Livewire;

use App\Mail\Contact as MailContact;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('layouts.guest')]
class Contact extends Component
{
    public $name, $email, $subject = '', $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function sendMessage()
    {
       
        $this->validate();

        Mail::to(env("MAIL_FROM_ADDRESS"))->send(new MailContact($this->name, $this->subject,$this->email, $this->message));

        session()->flash('message', 'Thank you. Your secure inquiry has been received.');
        
        $this->reset(['name', 'email', 'message']);
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
