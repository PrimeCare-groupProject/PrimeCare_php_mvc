const toggleButton = document.getElementById('toggleFilters');
const filters = document.getElementById('PL_form_filters');

toggleButton.addEventListener('click', function() {
    if(filters.style.display === 'none' || filters.style.display === '') {
        filters.style.display = 'block';
    } else {
        filters.style.display = 'none';
    }
});

let currentPage = 1;
const listingsPerPage = 9;
const listings = document.querySelectorAll('.property-listing-grid .property-component');
const totalPages = Math.ceil(listings.length / listingsPerPage);

function showPage(page) {
    // Hide all listings
    listings.forEach((listing, index) => {
        listing.style.display = 'none';
    });

    // Show listings for the current page
    const start = (page - 1) * listingsPerPage;
    const end = start + listingsPerPage;

    for (let i = start; i < end && i < listings.length; i++) {
        listings[i].style.display = 'block';
    }

    // Update pagination display
    document.querySelector('.current-page').textContent = page;
}

document.querySelector('.next-page').addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
    }
});

document.querySelector('.prev-page').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
    }
});

// Initial page load
showPage(currentPage);
function resetFilters() {
    // Reset dropdown selections
    document.getElementById('sort_by').selectedIndex = 0;
    
    // Reset price range inputs
    document.getElementById('min_price').value = '';
    document.getElementById('max_price').value = '';
    
    // Reset date inputs
    document.getElementById('check_in_date').value = '';
    document.getElementById('check_out_date').value = '';
    
    // Reset location inputs
    document.getElementById('city').value = '';
    document.getElementById('state').value = '';
    
    // Reset search term
    document.getElementById('searchTerm').value = '';
    
    // Optionally refresh the listing
    // applyFilters(); // Uncomment this if you want to refresh listings after reset
}