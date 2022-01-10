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
    const url = '/tags/'+ id + '/reject';
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

    const tagContainer = document.createElement('div');
    tagContainer.classList.add('mt-5');
    tagContainer.classList.add('pb-3');
    tagContainer.classList.add('pt-5');
    tagContainer.classList.add('bg-light');
    tagContainer.classList.add('mb-5');
    tagContainer.classList.add('manageTagContainer');

    const subdiv = document.createElement('div');
    subdiv.id = "stateButton";
    subdiv.classList.add("d-flex");
    subdiv.classList.add("align-items-center");
    
    const tag = document.createElement("h5");
    tag.classList.add("mx-3");
    tag.classList.add("my-0");
    tag.classList.add("py-0");
    tag.classList.add("w-75");
    tag.innerHTML = JSON.parse(this.responseText).tag_name;

    const btn = document.createElement("button");
    btn.type = "button";
    btn.onclick = "removeTag(this, {{ $tag['id'] }})";
    btn.classList.add("my-0");
    btn.classList.add("py-0");
    btn.classList.add("btn");
    btn.classList.add("btn-lg");
    btn.classList.add("btn-transparent");

    const iter = document.createElement("i");
    iter.classList.add("fas");
    iter.classList.add("fa-trash");
    iter.classList.add("fa-2x");
    iter.classList.add("mb-2");
    iter.classList.add("text-danger");

    btn.appendChild(iter);
    subdiv.appendChild(tag);
    subdiv.appendChild(btn);
    tagContainer.appendChild(subdiv);

    const container = select(`#acceptedTagsContainer`);
    container.appendChild(tagContainer);

    if (previousError) previousError.remove();

    const confirmation = document.createElement('h4');
    confirmation.classList.add('mb-0');
    confirmation.innerHTML = JSON.parse(this.responseText).msg;

    elem.parentElement.replaceWith(confirmation);
}