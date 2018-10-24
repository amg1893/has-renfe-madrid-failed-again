/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

//require('offline-plugin/runtime').install();

require('purecss');
require('purecss/build/grids-responsive-min.css');
require('font-awesome/css/font-awesome.css');

// any CSS you require will output into a single css file (app.css in this case)
require('../scss/base.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');

window.axios = require('axios');
var TwitterWidgetsLoader = require('twitter-widgets');

if (process.env.APP_ENV === 'prod') {
  let gtm = require('googletagmanager');
  gtm(process.env.GOOGLE_ANALYTICS);
}

TwitterWidgetsLoader.load(function(){
  console.log('Twitter loaded');
  document.init();
});

//This is the service worker with the Cache-first network

//Add this below content to your HTML page, or add the js file to your page at the very top to register service worker
if (navigator.serviceWorker.controller) {
  console.log('[PWA Builder] active service worker found, no need to register')
} else {
//Register the ServiceWorker
  navigator.serviceWorker.register('sw.js', {
    scope: './'
  }).then(function(reg) {
    console.log('Service worker has been registered for scope:'+ reg.scope);
  });
}