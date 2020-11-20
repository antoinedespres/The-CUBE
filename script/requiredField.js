window.addEventListener('load', () => {
    Array.from(document.getElementsByClassName('requiredField')).forEach((e) => { // warning: Array.from() is not compatible with IE
        e.innerHTML = '* ' + e.innerHTML;
    });
});