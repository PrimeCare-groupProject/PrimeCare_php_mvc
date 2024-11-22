const toggleButton = document.getElementById('toggleFilters');
const filters = document.getElementById('PL_form_filters');
const contentSection = document.getElementById('content-section');

toggleButton.addEventListener('click', function() {
    if(filters.style.display === 'none' || filters.style.display === '') {
        filters.style.display = 'block';
        contentSection.style.top = '200px';
    } else {
        filters.style.display = 'none';
        contentSection.style.top = '100px';
    }
});
