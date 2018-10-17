/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('purecss');
require('font-awesome');

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');

window.axios = require('axios');
var TwitterWidgetsLoader = require('twitter-widgets');

TwitterWidgetsLoader.load(function(){
  console.log('Twitter loaded');
  document.init();
});
