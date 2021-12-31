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
    const reportContainer = $('#reportElement');
    
    if (!reportContainer.classList.contains('d-none')) {
        $('textarea[id=reason]').value = '';
        const errorContainer = $('#reportError');
        errorContainer.classList.add('d-none');
    }

    toggleElem(reportContainer);
}

const reportUser = (id) => {
    const reportReason = $('textarea[id=reason]').value;
    sendAjaxRequest('post', `/user/${id}/report`, {reason: reportReason}, reportUserHandler);
}

function reportUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        toggleReportPopup();
    } else if (res.msg){
        const errorContainer = $('#reportError');
        $('#reportErrorText').innerHTML = res.msg;
        errorContainer.classList.remove('d-none');

    } else {
        const errorContainer = $('#reportError');
        $('#reportErrorText').innerHTML = 'Failed to report user. Invalid request.';
        errorContainer.classList.remove('d-none');
    }
}

const imgInput = $('#imgInput');
if (imgInput) {
    imgInput.onchange = evt => {
        const [file] = imgInput.files
        if (file) {
            $('#avatarPreview').src = URL.createObjectURL(file)
        }
    }
}
