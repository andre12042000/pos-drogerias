<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class NotificationsController extends Controller
{

    public function getNotificationsData(Request $request)
    {

        $user = User::find(Auth::user()->id);

        $notifications = [];

        foreach ($user->unreadNotifications as $notification) {

            $text = 'El producto ' . $notification->data['name'] . ' bajo';
            if (strlen($text) > 35) {

                $text = substr($text, 0, 32);
                $text = $text. '...';
            }
            $notifications[] =
                [
                    'icon' => $notification->data['icon'],
                    'text' => $text,
                    'url' =>  $notification->data['url'],
                    'time' => $notification['created_at']->diffForHumans(),
                ];
        }



        $dropdownHtml = '';

        foreach ($notifications as $key => $not) {

            $icon = "{$not['icon']}";

            $time = "<span class='float-right text-muted text-sm'>
                   {$not['time']}
                 </span>";

            $dropdownHtml .= "<a href='{$not['url']}' class='dropdown-item'>
                            {$icon} {$not['text']}{$time}
                          </a>";

            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }


        // Return the new notification data.

        return [
            'label'       => count($user->unreadNotifications),
            'label_color' => 'danger',
            'icon_color'  => 'dark',
            'dropdown'    => $dropdownHtml,
        ];
    }

     public function showNotificationsData()
    {
        $user = User::find(Auth::user()->id);

        $notificaciones =  Notification::where('notifiable_id', Auth::user()->id)->get();

        $notifications = $user->notifications;



        return view('notifications.index', compact('notifications'));

    }


    public function markNotification(Request $request)
    {
        auth()->user()->unreadNotifications
                ->when($request->input('id'), function($query) use ($request){
                    return $query->where('id', $request->input('id'));
                })->markAsRead();

               return response()->noContent();

    }
}
