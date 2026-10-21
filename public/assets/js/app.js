document.addEventListener('submit', (event) => {
    const message = event.target.dataset ? event.target.dataset.confirm : null;

    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});
