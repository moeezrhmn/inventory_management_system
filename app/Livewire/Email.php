<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;

class Email extends Component
{
    #[Rule('required')]
    public $email = '';

    public function render()
    {
        return view('livewire.email');
    }

    public function selectedemail(): void
    {
        $this->dispatch('email-selected', selectedemail: $this->email)
            ->to(Slug::class);
    }
}
