const notificationContainer = select("#notificationContainer");

const createNotification = (headerHTML, bodyHTML) => {
    const toast = document.createElement("div");
    toast.classList.add("toast");
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    const header = document.createElement("div");
    header.classList.add("toast-header");

    const headerText = document.createElement("strong");
    headerText.classList.add("me-auto");
    headerText.innerHTML = headerHTML;
    header.appendChild(headerText);

    const headerTime = document.createElement("small");
    headerTime.classList.add("text-muted");
    headerTime.innerText = "just now";
    header.appendChild(headerTime);

    const closeButton = document.createElement("button");
    closeButton.classList.add("btn-close");
    closeButton.setAttribute("type", "button");
    closeButton.setAttribute("data-bs-dismiss", "toast");
    closeButton.setAttribute("aria-label", "close");
    header.appendChild(closeButton);

    toast.appendChild(header);

    const body = document.createElement("div");
    body.classList.add("toast-body");
    body.innerHTML = bodyHTML;
    toast.appendChild(body);

    notificationContainer.appendChild(toast);

    bootstrap.Toast.getOrCreateInstance(toast).show();
}
