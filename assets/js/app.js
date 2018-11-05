/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import * as OfflinePluginRuntime from 'offline-plugin/runtime';
OfflinePluginRuntime.install({
  onUpdateReady: () => {
    window._showUpdateButton();
  }
});

require('purecss');
require('purecss/build/grids-responsive-min.css');
require('font-awesome/css/font-awesome.css');

// any CSS you require will output into a single css file (app.css in this case)
require('../scss/base.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');

window.axios = require('axios');
var TwitterWidgetsLoader = require('twitter-widgets');

let deferredPrompt;
let installButton;
let updateButton;

if (process.env.APP_ENV === 'prod') {
  let ga = require('universal-ga');
  ga.initialize(process.env.GOOGLE_ANALYTICS);
}

TwitterWidgetsLoader.load(function(){
  console.log('Twitter loaded');
  document.init();
});

window._installApp = function () {
  console.log('install app');
  deferredPrompt.prompt();
  deferredPrompt.userChoice
    .then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        installButton.style.display = 'none';
        deferredPrompt = null;
      }
    });
};

window._updateApp = function () {
  console.log('update app');
  OfflinePluginRuntime.applyUpdate();
};

window._showInstallButton = function () {
  console.log('show install button');
  installButton = document.getElementById('installButton');
  installButton.style.display = 'inline-block';

  installButton.addEventListener('click', window._installApp);
};

window._showUpdateButton = function () {
  console.log('show update button');
  updateButton = document.getElementById('updateAppButton');
  updateButton.style.display = 'inline-block';
};

self.addEventListener('beforeinstallprompt', (e) => {
  console.log('beforeinstallprompt triggered');
  e.preventDefault();
  deferredPrompt = e;

  window._showInstallButton();
});

// make the whole serviceworker process into a promise so later on we can
// listen to it and in case new content is available a toast will be shown
window.isUpdateAvailable = new Promise(function(resolve, reject) {
  // lazy way of disabling service workers while developing
  if ('serviceWorker' in navigator && ['localhost', '127'].indexOf(location.hostname) === -1) {
    // register service worker file
    navigator.serviceWorker.register('service-worker.js')
      .then(reg => {
        reg.onupdatefound = () => {
          const installingWorker = reg.installing;
          installingWorker.onstatechange = () => {
            switch (installingWorker.state) {
              case 'installed':
                if (navigator.serviceWorker.controller) {
                  // new update available
                  resolve(true);
                } else {
                  // no update available
                  resolve(false);
                }
                break;
            }
          };
        };
      })
      .catch(err => console.error('[SW ERROR]', err));
  }
});

// Update:
// this also can be incorporated right into e.g. your run() function in angular,
// to avoid using the global namespace for such a thing.
// because the registering of a service worker doesn't need to be executed on the first load of the page.