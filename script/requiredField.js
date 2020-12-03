window.addEventListener('load', () => {
    Array.from(document.getElementsByClassName('requiredField')).forEach((e) => {
        e.innerHTML = '* ' + e.innerHTML;
    });
});