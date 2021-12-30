const followUser = (id) => {
    sendAjaxRequest('post', `/user/${id}/follow`, null, followUserHandler);
}

const unfollowUser = (id) => {
    sendAjaxRequest('post', `/user/${id}/unfollow`, null, unfollowUserHandler);
}

function followUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        const button = $('#followBtn');
        button.className = "btn btn-secondary px-5 my-0 py-0 me-3";
        button.innerHTML = 'Unfollow';
        button.onclick = () => unfollowUser(res.id);
        $('#followersCount').innerHTML++;
    }
}

function unfollowUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        const button = $('#followBtn');
            button.className = "btn btn-primary px-5 my-0 py-0 me-3";
            button.innerHTML = 'Follow';
            button.onclick = () => followUser(res.id);
            $('#followersCount').innerHTML--;
    }
}

const toggleReportPopup = () => {
    const reportContainer = $('#reportContainer');
    if (reportContainer.classList.contains('d-none')) {
        reportContainer.classList.remove('d-none');
        reportContainer.classList.add('d-flex');
    } else {
        reportContainer.classList.remove('d-flex');
        reportContainer.classList.add('d-none');
    }
    
}

const reportUser = (id) => {
    console.log("Reporting user with id:", id);
}
