const container = document.querySelector('.SL__multi_container');
const blurItems = document.querySelectorAll('.SL__content_container');
const requestForm = document.querySelector('.SL__request_container');
const activeBtn = document.querySelector('.menu-item');
const cancelBtn = document.getElementById('cancel_btn');

// Toggle form visibility and apply blur
activeBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const isFormVisible = requestForm.style.display === 'block';
    requestForm.style.display = isFormVisible ? 'none' : 'block';

    // Apply or remove blur to each item
    blurItems.forEach((item) => {
        item.style.filter = isFormVisible ? 'none' : 'blur(10px)';
    });
});

// Hide form and remove blur
cancelBtn.addEventListener('click', (e) => {
    e.preventDefault();
    requestForm.style.display = 'none';
    blurItems.forEach((item) => {
        item.style.filter = 'none';
    });
});