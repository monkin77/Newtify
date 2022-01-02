const select = (selector) => document.querySelector(selector);
const selectAll = (selector) => document.querySelectorAll(selector);

const toggleElem = (elem) => {
  elem.classList.toggle('d-none');
}

function addEventListeners() {

  const filterButtons = document.querySelectorAll('input[name=filterType]');
  [].forEach.call(filterButtons, function(checker) {
    checker.addEventListener('change', filterArticles);
  });

  const submitButtons = document.querySelectorAll('.submit');
  [].forEach.call(submitButtons, function(checker) {
    checker.addEventListener('click', function() {
      this.parentNode.submit();
    });
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
  msg.classList.add('text-center');

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
  const previousError = select('#filterError');

  if (this.status == 400) {
    const error = createErrorMessage(json.errors);
    error.id = 'filterError';
    error.classList.add('mb-2');

    if (previousError)
        previousError.replaceWith(error);
    else
        select('#filterSection').after(error);

    return;
  }

  if (previousError) previousError.remove();

  const html = json.html;
  const canLoadMore = json.canLoadMore;

  const section = select('#articles');
  while (section.firstChild)
    section.removeChild(section.firstChild);

  section.insertAdjacentHTML('afterbegin', html);

  loadMoreButton = select('#load-more');
  if (loadMoreButton.style.display === "none") {
    if (canLoadMore) loadMoreButton.style.display = "block";
  } else {
    if (!canLoadMore) loadMoreButton.style.display = "none";
  }
}

function filterArticles() {
  // TODO: Pass filter parameters when filter is implemented in interface
  // Do common function to return URL and data, and use it for loadMore too

  const type = this.id;
  const url = `/api/article/filter?type=${type}&limit=5`;
  sendAjaxRequest('get', url, null, replaceArticles);
}

const checkPass = (id) => {
  const matchingMsg = select('#matchingPass');
  const confirmId = `${id}-confirm`;

  if (select(id).value == select(confirmId).value) {
      matchingMsg.style.color = 'green';
      matchingMsg.innerHTML = 'matching';
  } else {
      matchingMsg.style.color = 'red';
      matchingMsg.innerHTML = 'not matching'
  }
}

setSearchType = (item) => {
  select('#searchDropdownButton').innerHTML = item.innerText;
  select("#searchForm input[name='type']").value = item.innerText.toLowerCase();
}

addEventListeners();
