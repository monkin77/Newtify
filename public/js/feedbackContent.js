likeContent = (elem, id, like) => {
    const url = '/content/' + id;
    sendAjaxRequest('put', url, null, contentHandler(elem, id, like));
}


const contentHandler = (elem, is_like) => function() {
    if (this.status == 403) {
        window.location = '/';
        return;
    }

    const previousError = elem.querySelector('.error');

    if (this.status == 400) {
        const error = createErrorMessage(JSON.parse(this.responseText).errors);

        if (previousError)
            previousError.replaceWith(error);
        else
            elem.appendChild(error);

        return;
    }

    elem.innerHTML = is_like ? JSON.parse(response).likes : JSON.parse(response.dislikes);

    if (previousError) previousError.remove();

    const confirmation = document.createElement('h4');
    confirmation.classList.add('mb-0');
    confirmation.innerHTML = JSON.parse(this.responseText).msg;

    elem.parentElement.replaceWith(confirmation);
}

