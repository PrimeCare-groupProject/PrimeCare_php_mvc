document.addEventListener("DOMContentLoaded", function () {
    const reviewContainer = document.getElementById('review-container');
    const seeMoreBtn = document.getElementById('see-more-btn');
    let offset = 0;
    const limit = 10;
    let totalReviews = 0;

    function loadReviews() {
        fetch(`<?= ROOT ?>/propertyListing/getReviewsPaginated/<?= $property_id ?>?offset=${offset}`)
            .then(res => res.json())
            .then(data => {
                totalReviews = data.total;

                if (data.reviews.length > 0) {
                    data.reviews.forEach(review => {
                        const reviewDiv = document.createElement('div');
                        reviewDiv.classList.add('review');
                        reviewDiv.innerHTML = `
                            <div class="review-header">
                                <div class="user-info">
                                    <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/${review.user_image}" alt="User Image" class="user-img" />
                                    <div>
                                        <h3 class="user-name">${review.user_name}</h3>
                                        <p class="review-date">${review.created_at}</p>
                                    </div>
                                </div>
                                <div class="rating">
                                    <span class="rating-score">${review.rating}</span>
                                    <span class="stars">★★★★★</span>
                                </div>
                            </div>
                            <p class="review-text">${review.message}</p>
                        `;
                        reviewContainer.appendChild(reviewDiv);
                    });

                    offset += limit;
                    if (offset >= totalReviews) {
                        seeMoreBtn.style.display = 'none';
                    } else {
                        seeMoreBtn.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error("Review loading error:", error);
                alert("An error occurred while loading reviews.");
            });
    }

    seeMoreBtn.addEventListener('click', loadReviews);

    // Load the first batch
    loadReviews();
});


console.log("Review AJAX script loaded successfully.");