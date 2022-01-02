proposeTag = (e) => {
    e.preventDefault();
    const tagName = e.target.elements.tagName.value;
    sendAjaxRequest('post', '/tags/new', { tagName }, proposeTagHandler);
}

function proposeTagHandler() {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    const previousError = select('#proposeTag .error');

    if (this.status == 400) {
        const error = createErrorMessage(JSON.parse(this.responseText).errors);

        if (previousError)
            previousError.replaceWith(error);
        else
            select('#proposeTag').appendChild(error);

        return;
    }

    if (previousError) previousError.remove();

    const confirmation = document.createElement('h4');
    confirmation.classList.add('mb-0');
    confirmation.innerHTML = 'Thank you for your contribution!';

    select('#proposeTagForm').replaceWith(confirmation);
}


acceptTag = (elem, id) => {
    const url = '/tags/'+ id + '/accept';
    sendAjaxRequest('put', url, null, tagHandler(elem));
}

rejectTag = (elem, id) =>  {
    const url = '/tags/'+ id + '/accept';
    sendAjaxRequest('put', url, null, tagHandler(elem));
}

removeTag = (elem, id) =>  {
    const url = '/tags/'+ id;
    sendAjaxRequest('delete', url, null, tagHandler(elem));
}

const tagHandler = (elem) => function(){
    if (this.status == 403) {
        window.location = '/login';
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

    if (previousError) previousError.remove();

    const confirmation = document.createElement('h4');
    confirmation.classList.add('mb-0');
    confirmation.innerHTML = JSON.parse(this.responseText).msg;

    elem.parentElement.replaceWith(confirmation);
}