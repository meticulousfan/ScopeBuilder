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
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';

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

Vue.use(ElementUI, { locale })
Vue.use(FormFilePlugin)
Vue.use(VueIziToast);


// register globally
Vue.component('questionnaires-list', require('./views/client/QuestionnairesList.vue').default)
Vue.component('questionnaire-show', require('./views/client/Questionnaire.vue').default)

Vue.component('questionnaire-questions', require('./views/client/project/Questions.vue').default)
Vue.component('anonymous-questionnaire-questions', require('./views/client/project/AnonymousProject.vue').default)

var app = new Vue({

    el: '#questionnairesclient'
});