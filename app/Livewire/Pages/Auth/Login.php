<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.auth')] // Atribut ini memaksa layout auth.blade.php
class Login extends Component
{
    // Nanti, semua logika form PHP Anda akan dipindahkan ke sini
    // Untuk sekarang, biarkan kosong.

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}