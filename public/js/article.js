deleteArticle = (e, id) => {
    e.preventDefault();
    
    sendAjaxRequest('delete', '/article/id', null, deleteArticleHandler);
}

function deleteArticleHandler() {
    if (this.status = 403){
        window.location = '';
        return;
    }
}