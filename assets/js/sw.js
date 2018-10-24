var cacheName = 'HRMFA';

self.addEventListener('fetch', (event) => {
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