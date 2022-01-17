const createNewComment = (article_id) => {
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest(
        'POST', '/comment',
        { body, article_id },
        newCommentHandler('comment_form', 'commentTextArea', select('#comments'), 'afterbegin')
    );
}

const createNewReply = (parent, article_id, parent_comment_id) => {
    const body = select(`#reply_textarea_${parent_comment_id}`).value;
    if (!body) return;

    sendAjaxRequest(
        'POST', '/comment',
        { body, article_id, parent_comment_id },
        newCommentHandler(
            `reply_form_${parent_comment_id}`,
            `reply_textarea_${parent_comment_id}`,
            parent,
            'afterend',
            `reply_${parent_comment_id}`
        )
    );
}

const deleteComment = (comment_id) =>
    sendAjaxRequest('DELETE', `/comment/${comment_id}`, null, deleteCommentHandler(comment_id));

const newCommentHandler = (formId, textareaId, parent, position, removeId) => function () {
    const json = JSON.parse(this.responseText);

    const previousError = select(`#${formId} .error`);

    if (this.status != 200) {
        const error = createErrorMessage(json.errors);
        error.classList.add('mt-2');

        if (previousError)
            previousError.replaceWith(error);
        else
            select(`#${textareaId}`).insertAdjacentElement('afterend', error);

        return;
    }

    if (previousError) previousError.remove();

    parent.insertAdjacentHTML(position, json.html);
    select(`#${textareaId}`).value = '';

    if (removeId) select(`#${removeId}`).remove();
}

const deleteCommentHandler = (comment_id) => function() {
    const json = JSON.parse(this.responseText);
    const previousError = select(`#comment_${comment_id} .error`);

    if (this.status != 200) {
        const error = createErrorMessage(json.errors);
        error.classList.remove('text-center');
        error.classList.add('ms-5');

        if (previousError)
            previousError.replaceWith(error);
        else
            select(`#comment_${comment_id}`).appendChild(error);

        return;
    }

    if (previousError) previousError.remove();
    select(`#comment_${comment_id}`).remove();
}

const openReplyBox = (articleId, parentCommentId) => {
    const previousReply = select(`#reply_${parentCommentId}`)
    if (previousReply) {
        previousReply.remove();
        return;
    }

    const parentComment = select(`#comment_${parentCommentId}`);
    const { mainDiv, commentForm, textArea, button } = getCommentTextarea();
    
    commentForm.id = `reply_form_${parentCommentId}`;
    textArea.id = `reply_textarea_${parentCommentId}`;

    button.onclick = () => createNewReply(parentComment, articleId, parentCommentId);
    button.innerText = 'Reply';

    const wrapperDiv = getReplyBox(mainDiv);
    wrapperDiv.id = `reply_${parentCommentId}`;

    parentComment.insertAdjacentElement('afterend', wrapperDiv);
}

const openEditBox = (commentId, isReply) => {
    const comment = select(`#comment_${commentId}`);
    comment.setAttribute('style', 'display:none !important');

    const { mainDiv, commentForm, textArea, button } = getCommentTextarea();
    textArea.id = `edit_textarea_${commentId}`;
    textArea.value = select(`#comment_${commentId} .commentTextContainer`).innerText;

    button.onclick = () => editComment(commentId);
    button.innerText = 'Save';

    if (!isReply) {
        comment.insertAdjacentElement('afterend', mainDiv);
        return;
    }

    const wrapperDiv = getReplyBox(mainDiv);
    comment.insertAdjacentElement('afterend', wrapperDiv);
}

const getCommentTextarea = () => {
    const mainDiv = document.createElement('div');
    mainDiv.classList.add('d-flex', 'flex-row', 'my-3', 'w-75');

    const headerDiv = document.createElement('div');
    headerDiv.classList.add('flex-column', 'h-100', 'commentHeader', 'mx-5');

    const img = select('.commentHeader img').cloneNode();
    headerDiv.appendChild(img);

    const imgText = document.createElement('p');
    imgText.innerText = 'You';
    headerDiv.appendChild(imgText);
    mainDiv.appendChild(headerDiv);

    const commentForm = document.createElement('div');
    commentForm.classList.add('flex-column', 'w-100', 'mb-0');

    const textArea = document.createElement('textarea');
    textArea.classList.add('flex-column', 'border-light', 'm-0', 'p-2');
    textArea.placeholder = 'Type here';
    commentForm.appendChild(textArea);

    const button = document.createElement('button');
    button.classList.add('btn', 'btn-primary', 'px-4');
    commentForm.appendChild(button);
    mainDiv.appendChild(commentForm);

    return { mainDiv, commentForm, textArea, button };
}

const getReplyBox = (mainDiv) => {
    mainDiv.classList.remove('w-75');

    const childDiv = document.createElement('div');
    childDiv.classList.add('child-comment');
    childDiv.appendChild(mainDiv);

    const wrapperDiv = document.createElement('div');
    wrapperDiv.classList.add('d-flex', 'justify-content-end', 'w-75');
    wrapperDiv.appendChild(childDiv);

    return wrapperDiv;
}
