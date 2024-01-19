<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChangePasswordComponent extends Component
{
    public $password, $verify_password;

    public function render()
    {
        return view('livewire.change-password-component');
    }

    public function updatedPassword()
    {
        $this->validate([
            'password'      => 'required|min:8|max:20|',
        ],[
            'password.required' => 'El campo contraseña es requerido',
            'password.min'      => 'La contraseña debe contener al menos 8 carácteres',
            'password.max'      => 'La contraseña no puede superar los 20 carácteres',
        ]);
    }

    public function updatedVerifyPassword()
    {
        $this->validate([
            'verify_password'       => 'required|same:password',
        ],[
            'verify_password.required'  => 'la verificación de contraseña es requerida',
            'verify_password.same'      => 'la verificación no coincide con la contraseña',
        ]);
    }

    public function save()
    {
        $this->validate([
            'password'              => 'required|min:8|max:20|',
            'verify_password'       => 'required|same:password',
        ],[
            'password.required' => 'El campo contraseña es requerido',
            'password.min'      => 'La contraseña debe contener al menos 8 carácteres',
            'password.max'      => 'La contraseña no puede superar los 20 carácteres',
            'verify_password.required'  => 'la verificación de contraseña es requerida',
            'verify_password.same'      => 'la verificación no coincide con la contraseña',
        ]);

        $user = User::find(Auth::user()->id);

        $user->update([
            'password'      => bcrypt($this->verify_password),
        ]);

        return redirect()->route('home');

    }
}
