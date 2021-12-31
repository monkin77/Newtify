const $ = (selector) => document.querySelector(selector);

function addEventListeners() {

  const filterButtons = document.querySelectorAll('input[name=filterType]');
  [].forEach.call(filterButtons, function(checker) {
    checker.addEventListener('change', filterArticles);
  });
  
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  const request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

const createErrorMessage = (errors) => {
  const msg = document.createElement('div');
  msg.classList.add('error');

  for (let error of Object.values(errors)) {
    const errorMsg = document.createElement('span');
    errorMsg.classList.add('text-danger');
    errorMsg.innerHTML = error;
    msg.appendChild(errorMsg);
  }

  return msg;
}

function replaceArticles() {
  const json = JSON.parse(this.responseText);
  const html = json.html;
  const canLoadMore = json.canLoadMore;

  const section = $('#articles');
  while (section.firstChild)
    section.removeChild(section.firstChild);

  section.insertAdjacentHTML('afterbegin', html);

  loadMoreButton = $('#load-more');
  if (loadMoreButton.style.display === "none") {
    if (canLoadMore) loadMoreButton.style.display = "block";
  } else {
    if (!canLoadMore) loadMoreButton.style.display = "none";
  }
}

function filterArticles () {
  // TODO: Pass filter parameters when filter is implemented in interface
  // Do common function to return URL and data, and use it for loadMore too

  const type = this.id;
  const url = `/api/article/filter?type=${type}&limit=5`;
  sendAjaxRequest('get', url, null, replaceArticles);
}

addEventListeners();
