giveFeedback = (elem, content_id, is_like) => {
    const url = '/content/' + content_id;
    sendAjaxRequest('put', url, { is_like }, giveFeedbackHandler(elem, content_id, is_like));
}

removeFeedback = (elem, content_id, is_like) => {
    const url = '/content/' + content_id;
    sendAjaxRequest('delete', url, { is_like }, removeFeedbackHandler(elem, content_id, is_like));
}

const giveFeedbackHandler = (elem, content_id, is_like) => function() {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    const previousError = elem.querySelector('.error');

    if (this.status == 500) {
        const error = createErrorMessage(JSON.parse(this.responseText).errors);

        if (previousError)
            previousError.replaceWith(error);
        else
            elem.appendChild(error);

        return;
    }

    elem.classList.add("text-primary");
    elem.onclick = () => { removeFeedback(elem, content_id, is_like); };

    const inside = elem.lastElementChild;
    inside.innerHTML = is_like ? JSON.parse(this.responseText).likes : JSON.parse(this.responseText).dislikes;

    const oppositeFeedback = is_like ? select('#articleDislikes') : select('#articleLikes');
    const oppositeInside = oppositeFeedback.lastElementChild;
    oppositeInside.innerHTML = is_like ? JSON.parse(this.responseText).dislikes : JSON.parse(this.responseText).likes;

    oppositeFeedback.classList = is_like ? ["fas fa-thumbs-down ps-3"] : ["fas fa-thumbs-up ps-5"];
    oppositeFeedback.onclick = () => { giveFeedback(oppositeFeedback, content_id, !is_like); };

    if (previousError) previousError.remove();
}

const removeFeedbackHandler = (elem, content_id, is_like) => function() {
    if (this.status == 403) {
        window.location = '/ogin';
        return;
    }

    const previousError = elem.querySelector('.error');

    if (this.status == 500) {
        const error = createErrorMessage(JSON.parse(this.responseText).errors);

        if (previousError)
            previousError.replaceWith(error);
        else
            elem.appendChild(error);

        return;
    }

    elem.classList = is_like ? ["fas fa-thumbs-up ps-5"] : ["fas fa-thumbs-down ps-3"];
    elem.onclick = () => { giveFeedback(elem, content_id, is_like); };
    
    const inside = elem.lastElementChild;
    inside.innerHTML = is_like ? JSON.parse(this.responseText).likes : JSON.parse(this.responseText).dislikes;

    if (previousError) previousError.remove();
}

