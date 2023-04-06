/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


 import Echo from 'laravel-echo';

 window.Pusher = require('pusher-js');
 
 window.Echo = new Echo({
     broadcaster:       'pusher',
     key:               window.PUSHER_APP_KEY,
     wsHost:            window.location.hostname,
     wsPort:            window.APP_DEBUG ? 6001 : 6002,
     wssPort:           window.APP_DEBUG ? 6001 : 6002,
     forceTLS:          !window.APP_DEBUG,
     disableStats:      true,
     enabledTransports: ['ws', 'wss'],
 });
 

window.Echo.join('common_room')
    .here((users) => {
        console.log(users);
    })
    .joining((user) => {
        console.log("joining"+user);  
    })
    .leaving((user) => {
        console.log("leaving"+user);
    }); 