<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use App\Entities\User;

class UserViewModel extends ViewModel
{
    public $user;

    public function __construct(User $user = null) {
        $this->user = $user;
    }

    public function administradores() {
        return User::role('administrador')->get();
    }

    public function contabilidad() {
        return User::role('contabilidad')->get();
    }

    public function logistica() {
        return User::role('logistica')->get();
    }
}
