function *timeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);

    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) {
        yield interval + ' years ago';
    }

    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        yield interval + ' months ago';
    }

    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        yield interval + ' days ago';
    }

    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        yield interval + ' hours ago';
    }

    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        yield interval + ' minutes ago';
    }

    if(seconds < 10) yield 'just now';

    yield Math.floor(seconds) + ' seconds ago';
};

export default function getTimeAgo(date) {
    const ago = timeAgo(date);

    return ago.next().value;
}
