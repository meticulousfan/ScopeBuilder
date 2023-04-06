<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('common_room', function ($user) {
    return $user->id;
});
Broadcast::channel('developer_room.{id}', function ($user, $id) {
   return (int) $user->id === (int) $id;
});
Broadcast::channel('call_room.{meeting_id}', function ($user, $meeting_id) {
   return array('id'=>$user->id, 'name'=>$user->name);
});