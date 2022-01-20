const userId = select("meta[name='user-id']").getAttribute('content');

if (userId) {

    const pusher = new Pusher('26ec0b6978ee54d90d56', {
        encrypted: true,
        cluster: 'eu'
    });

    // TODO: Notification snackbars and page
    const notificationChannel = pusher.subscribe(`notifications.${userId}`);
    notificationChannel.bind('article-like', (data) => console.log("Like no artigo bro", data));
    notificationChannel.bind('comment-like', (data) => console.log("Like no comment bro", data));
    notificationChannel.bind('message', (data) => console.log("Ganda mensagem bro", data));
    notificationChannel.bind('comment', (data) => console.log("Ganda comment bro", data));
    notificationChannel.bind('comment-reply', (data) => console.log("Ganda reply bro", data));
}
