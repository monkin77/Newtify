loadMoreHandler = (containerId) => function () {
    const container = $(`#${containerId}`);
    const json = JSON.parse(this.responseText);
    const html = json.html;
    const canLoadMore = json.canLoadMore;
  
    container.insertAdjacentHTML('beforeend', html);
    if (!canLoadMore) $('#load-more').style.display = "none";
  };

const loadMoreSearch = (type, value) => {
    const numResults = $(`#${type}`).childElementCount;
    const url = `/api/search/${type}?value=${value}&offset=${numResults}&limit=10`;
    sendAjaxRequest('get', url, null, loadMoreHandler(type));
};

const loadMoreHome = () => {
    // TODO: Pass filter parameters when filter is implemented in interface
    const numArticles = $('#articles').childElementCount;
    const url = `/api/article/filter?offset=${numArticles}&limit=5`;
    sendAjaxRequest('get', url, null, loadMoreHandler('articles'));
};
