const createNewComment = (article_id, parent_comment_id) => {
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest('POST', '/comment', { body, article_id }, newCommentHandler);
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
