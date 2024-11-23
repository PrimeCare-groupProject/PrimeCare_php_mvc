const toggleButton = document.getElementById('toggleFilters');
const filters = document.getElementById('PL_form_filters');

toggleButton.addEventListener('click', function() {
    if(filters.style.display === 'none' || filters.style.display === '') {
        filters.style.display = 'block';
    } else {
        filters.style.display = 'none';
    }
});
