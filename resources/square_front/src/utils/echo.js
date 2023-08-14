import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

export default new Echo({
    broadcaster: 'pusher',
    key: '47ea657a6483e185818a',
    cluster: 'us2',
    forceTLS: true
});
