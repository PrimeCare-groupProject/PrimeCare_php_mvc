const popupDiv = document.getElementById('FM__popup_menu');
const container = document.getElementById('FM__main_container');
const popupBtn = document.getElementById('give_increment');

const closeBtn = document.getElementById('close');

// popupBtn.addEventListener('click', () => {
//     popupDiv.style.display = 'inline-flex';
//     container.style.filter = 'blur(10px)!important';
// });

// closeBtn.addEventListener('click', () => {
//     popupDiv.style.display = 'none';
//     container.style.filter = 'none';
// });

popupBtn.addEventListener('click', () => {
    popupDiv.style.display = 'inline-flex';
    container.classList.add('blurred'); // Add the class
});

closeBtn.addEventListener('click', () => {
    popupDiv.style.display = 'none';
    container.classList.remove('blurred'); // Remove the class
});
