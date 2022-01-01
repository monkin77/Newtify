const followUser = (id) => {
    sendAjaxRequest('post', `/user/${id}/follow`, null, followUserHandler);
}

const unfollowUser = (id) => {
    sendAjaxRequest('post', `/user/${id}/unfollow`, null, unfollowUserHandler);
}

function followUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        const button = select('#followBtn');
        button.className = "btn btn-secondary px-5 my-0 py-0 me-3";
        button.innerHTML = 'Unfollow';
        button.onclick = () => unfollowUser(res.id);
        select('#followersCount').innerHTML++;
    }
}

function unfollowUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        const button = select('#followBtn');
            button.className = "btn btn-primary px-5 my-0 py-0 me-3";
            button.innerHTML = 'Follow';
            button.onclick = () => followUser(res.id);
            select('#followersCount').innerHTML--;
    }
}

const toggleReportPopup = () => {
    const reportContainer = select('#reportElement');
    
    if (!reportContainer.classList.contains('d-none')) {
        select('textarea[id=reason]').value = '';
        const errorContainer = select('#reportError');
        errorContainer.classList.add('d-none');
    }

    toggleElem(reportContainer);
}

const reportUser = (id) => {
    const reportReason = select('textarea[id=reason]').value;
    sendAjaxRequest('post', `/user/${id}/report`, {reason: reportReason}, reportUserHandler);
}

function reportUserHandler() {
    const res = JSON.parse(this.responseText);
    if (res.status == 'OK') {
        toggleReportPopup();
    } else if (res.msg){
        const errorContainer = select('#reportError');
        select('#reportErrorText').innerHTML = res.msg;
        errorContainer.classList.remove('d-none');

    } else {
        const errorContainer = select('#reportError');
        select('#reportErrorText').innerHTML = 'Failed to report user. Invalid request.';
        errorContainer.classList.remove('d-none');
    }
}

const imgInput = select('#imgInput');
if (imgInput) {
    imgInput.onchange = evt => {
        const [file] = imgInput.files
        if (file) {
            select('#avatarPreview').src = URL.createObjectURL(file)
        }
    }
}

const checkPass = () => {
    const matchingMsg = select('#matchingPass');
    if (select('#newPassInput').value == select('#newPassConfirmInput').value) {
        matchingMsg.style.color = 'green';
        matchingMsg.innerHTML = 'matching';
    } else {
        matchingMsg.style.color = 'red';
        matchingMsg.innerHTML = 'not matching'
    }
}