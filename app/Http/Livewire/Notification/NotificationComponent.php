<?php

namespace App\Http\Livewire\Notification;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    /*---- Variables de filtros, cantidad de registro y busqueda -----*/
    public $cantidad_registros = 5;


    public $filter_status = 'no_leidas';

    public function render()
    {
        $user = User::find(Auth::user()->id);

       /*  $notificaciones =  Notification::where('notifiable_id', Auth::user()->id)

                                    ->get(); */

        $notifications = $user->unreadNotifications;

        return view('livewire.notification.notification-component', compact('notifications'))->extends('adminlte::page');
    }



    public function marcar_leida()
    {

$this->render();
    }


}
