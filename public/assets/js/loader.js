// if (!document.head.querySelector('link[href="<?= ROOT ?>/assets/css/loader.css"]')) {
//     const link = document.createElement('link');
//     link.rel = 'stylesheet';
//     link.href = '<?= ROOT ?>/assets/css/loader.css';
//     document.head.appendChild(link);
// }

if (!document.querySelector('.loader-container')) {
    const loaderContainer = document.createElement('div');
    loaderContainer.className = 'loader-container';
    loaderContainer.style.display = 'none';

    const spinnerLoader = document.createElement('div');
    spinnerLoader.className = 'spinner-loader';

    loaderContainer.appendChild(spinnerLoader);
    document.body.appendChild(loaderContainer);
}

function displayLoader() {
    document.querySelector('.loader-container').style.display = '';
    //onclick="displayLoader()"
}
    
document.querySelectorAll('form').forEach(form => {
form.addEventListener('submit', displayLoader);
});

document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', displayLoader);
});
console.log("loader loaded");