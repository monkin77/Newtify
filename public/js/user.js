const followUser = (id) => {
    fetch(`/user/${id}/follow`, {
        method: 'POST'
    }).then(data => data.json()).then(res => {
        if (res.status == 'OK') {
            const button = $('#followBtn');
            button.className = "btn btn-secondary px-5 my-0 py-0 me-3";
            button.innerHTML = 'Unfollow';
            button.onclick = () => unfollowUser(id);
            $('#followersCount').innerHTML++;
        }
    });
}

const unfollowUser = (id) => {
    fetch(`/user/${id}/unfollow`, {
        method: 'POST'
    }).then(data => data.json()).then(res => {
        if (res.status == 'OK') {
            const button = $('#followBtn');
            button.className = "btn btn-primary px-5 my-0 py-0 me-3";
            button.innerHTML = 'Follow';
            button.onclick = () => followUser(id);
            $('#followersCount').innerHTML--;
        }
    });
}
