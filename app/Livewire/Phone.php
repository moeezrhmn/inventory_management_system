<?php

Namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;

class Phone extends Component
{
    #[Rule('required')]
    public $phone = '';

    public function render()
    {
        return view('livewire.phone');
    }

    public function selectedphone(): void
    {
        $this->dispatch('phone-selected', selectedphone: $this->phone)
            ->to(Slug::class);
    }
}
