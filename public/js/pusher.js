const pusher = new Pusher('26ec0b6978ee54d90d56', {
    encrypted: true,
    cluster: 'eu'
});

const notificationChannel = pusher.subscribe('notifications');
notificationChannel.bind('article-like', (data) => console.log("Like no artigo bro"));
