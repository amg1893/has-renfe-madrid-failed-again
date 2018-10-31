/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import * as OfflinePluginRuntime from 'offline-plugin/runtime';
OfflinePluginRuntime.install();

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
  let ga = require('universal-ga');
  ga.initialize(process.env.GOOGLE_ANALYTICS);
}

TwitterWidgetsLoader.load(function(){
  console.log('Twitter loaded');
  document.init();
});