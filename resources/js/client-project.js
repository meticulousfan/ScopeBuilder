require('./vue-asset');
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import BootstrapVue from "bootstrap-vue";
import { FormFilePlugin } from 'bootstrap-vue'
import {
  ValidationObserver,
  ValidationProvider,
  extend,
  localize
} from "vee-validate";
import en from "vee-validate/dist/locale/en.json";
import * as rules from "vee-validate/dist/rules";

import Vue from 'vue'
window.Vue = require('vue');

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
  });

localize("en", en);

// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

Vue.use(BootstrapVue);
Vue.use(FormFilePlugin)
Vue.use(VueIziToast);

//Bank accounts Components
Vue.component('create-project', require('./components/client/projects/create.vue').default)
Vue.component('edit-project', require('./components/client/projects/edit/edit.vue').default )

var app = new Vue({
    el: '#clientproject'
});


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
      onlineUsers = users;
      changeList();
  })
  .joining((user) => {
      console.log("joining"+user);   
      onlineUsers.push(user);
      changeList();
  })
  .leaving((user) => {
      console.log("leaving"+user);
      onlineUsers.pop(user);
      changeList();
  })

