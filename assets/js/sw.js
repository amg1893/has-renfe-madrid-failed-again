var cacheName = 'HRMFA';

self.addEventListener('fetch', (event) => {
    console.log(event.request.method);
    if (event.request.method === 'GET') {
        event.respondWith(
            caches.match(event.request).then((response) => {
                if (response) {
                    return response;
                }
                return fetch(event.request).then((response) => {
                    return caches.open(cacheName).then((cache) => {
                        cache.put(event.request.url, response.clone());
                        return response;
                    });
                });
            })
        );
    }
});

self.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    let deferredPrompt = e;
    let installButton = document.getElementById('installButton');

    installButton.addEventListener('click', (e) => {
        deferredPrompt.prompt();
        deferredPrompt.userChoice
            .then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                  installButton.style.display = 'none';
                }
            });
    });

    installButton.style.display = 'inline-block;';
});