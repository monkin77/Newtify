const createNewComment = (article_id) => {
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest('POST', '/comment', { body, article_id }, newCommentHandler);
}

const createNewReply = (parent, article_id, parent_comment_id) => {

}

function newCommentHandler() {
    const json = JSON.parse(this.responseText);

    const previousError = select(`#comment_form .error`);

    if (this.status != 200) {
        const error = createErrorMessage(json.errors);
        error.classList.add('my-2');

        if (previousError)
            previousError.replaceWith(error);
        else
            select('#comment_form').insertBefore(error, select('#newCommentButton'));

        return;
    }

    if (previousError) previousError.remove();

    select('#comments').insertAdjacentHTML('afterbegin', json.html);
    select('#commentTextArea').value = '';
}

const openReplyBox = (parentComment, articleId, parentCommentId) => {

    const previousReply = select(`#reply_${parentCommentId}`)
    if (previousReply) {
        previousReply.remove();
        return;
    }

    const mainDiv = document.createElement('div');
    mainDiv.classList.add('d-flex', 'flex-row', 'my-3');
    mainDiv.id = `reply_${parentCommentId}`;

    const headerDiv = document.createElement('div');
    headerDiv.classList.add('flex-column', 'h-100', 'commentHeader', 'mx-5');

    const img = select('.commentHeader img').cloneNode();
    headerDiv.appendChild(img);

    const imgText = document.createElement('p');
    imgText.innerText = 'You';
    headerDiv.appendChild(imgText);
    mainDiv.appendChild(headerDiv);


    const replyForm = document.createElement('div');
    replyForm.classList.add('flex-column', 'w-100', 'mb-0');
    replyForm.id = `reply_form_${parentCommentId}`;

    const textArea = document.createElement('textarea');
    textArea.classList.add('flex-column', 'border-light', 'm-0', 'p-2');
    textArea.placeholder = 'Type here';
    replyForm.appendChild(textArea);

    const button = document.createElement('button');
    button.classList.add('btn', 'btn-primary', 'px-4');
    button.onclick = () => createNewReply(parentComment, articleId, parentCommentId);
    button.innerText = 'Reply';
    replyForm.appendChild(button);
    mainDiv.appendChild(replyForm);

    const childDiv = document.createElement('div');
    childDiv.classList.add('child-comment');
    childDiv.appendChild(mainDiv);

    const wrapperDiv = document.createElement('div');
    wrapperDiv.classList.add('d-flex', 'justify-content-end', 'w-75');
    wrapperDiv.appendChild(childDiv);

    parentComment.insertAdjacentElement('afterend', wrapperDiv);
}
