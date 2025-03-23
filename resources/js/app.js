import './bootstrap';

import Echo from 'laravel-echo';

let e = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ':6001'
});
e.channel('chat')
    .listen('MessageSent', (e) => {
        console.log('Nouveau message reçu :', e);
    });
