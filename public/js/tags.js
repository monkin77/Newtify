$('#tags').select2({
    multiple: true,
    maximumSelectionLength: 3,
    tokenSeparators: [',', ' ', ";"],
    theme: "classic",
});

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

    const previousError = $('#proposeTag .error');

    if (this.status == 400) {
        const error = createErrorMessage(JSON.parse(this.responseText).errors);

        if (previousError)
            previousError.replaceWith(error);
        else
            $('#proposeTag').appendChild(error);

        return;
    }

    if (previousError) previousError.remove();

    const confirmation = document.createElement('h4');
    confirmation.classList.add('mb-0');
    confirmation.innerHTML = 'Thank you for your contribution!';

    $('#proposeTagForm').replaceWith(confirmation);
}
