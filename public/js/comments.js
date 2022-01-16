const createNewComment = (article_id, parent_comment_id) => {
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest('POST', '/comment', { body, article_id }, newCommentHandler);
}

function newCommentHandler() {
    const container = select('#comments');
    const json = JSON.parse(this.responseText);

    const previousError = select(`#comments .error`);

    if (this.status != 200) {
        const error = createErrorMessage(json.errors);
        error.classList.add('mb-2');

        if (previousError)
            previousError.replaceWith(error);
        else
            container.appendChild(error);

        return;
    }

    if (previousError) previousError.remove();

    container.insertAdjacentHTML('afterbegin', json.html);
    select('#commentTextArea').value = '';
}
