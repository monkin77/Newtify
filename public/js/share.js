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

const showSocials = () => {
    const currentURL = window.location.href;

    const url = `/api/share_socials`;
    sendAjaxRequest('post', url, {url: currentURL}, handleShowSocials(currentURL));
}

const handleShowSocials = (currentURL) => function(){
    const socialLinks = JSON.parse(this.responseText).links;

    select('#fbIcon').href = socialLinks.facebook;
    select('#twitterIcon').href = socialLinks.twitter;
    select('#linkedInIcon').href = socialLinks.linkedin;
    select('#redditIcon').href = socialLinks.reddit;
    select('#shareLinkInput').value = currentURL;

    select('#socialsPopup').classList.remove('d-none');
}

const hideSocials = () => {
    const popup = select('#socialsPopup');
    popup.classList.add('d-none');
}

