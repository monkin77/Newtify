var popupSize = {
    width: 780,
    height: 550
};

$(document).on('click', '.social-button', function (e) {
    var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
        horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

    var popup = window.open($(this).prop('href'), 'social',
        'width=' + popupSize.width + ',height=' + popupSize.height +
        ',left=' + verticalPos + ',top=' + horisontalPos +
        ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

    if (popup) {
        popup.focus();
        e.preventDefault();
    }
});

const toggleSocials = () => {
    const currentURL = window.location.href;
    console.log("Current url:", currentURL);

    const url = `/api/share_socials`;
    sendAjaxRequest('post', url, {url: currentURL}, handleToggleSocials());
}

const handleToggleSocials = () => function(){
    const socialsPopup = JSON.parse(this.responseText);
    console.log("socials popup:", socialsPopup);
    const sharePopup = select('#sharePopup');
    sharePopup.insertAdjacentHTML('afterbegin', socialsPopup.html);
    sharePopup.classList.toggle("d-none");
}

