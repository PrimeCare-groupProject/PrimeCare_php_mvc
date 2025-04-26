<?php require_once 'ownerHeader.view.php'; ?>

<div class="reviews-dashboard">
    <!-- Page Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-comment-alt"></i> Guest Reviews</h1>
        <p>See what your guests are saying about your properties.</p>
    </div>

    <!-- Review List -->
    <div class="review-list">
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card <?= $review->rating >= 4 ? 'positive' : ($review->rating >= 3 ? 'neutral' : 'negative') ?>">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="avatar" style="background-color: <?= generatePastelColor($review->person_id) ?>">
                                <?= getInitials($review->person_id) ?>
                            </div>
                            <div class="reviewer-details">
                                <div class="name">Reviewer ID: <?= htmlspecialchars($review->person_id) ?></div>
                                <div class="property">Property ID: <?= htmlspecialchars($review->property_id) ?></div>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="rating">
                                <?= str_repeat('★', $review->rating) ?><?= str_repeat('☆', 5 - $review->rating) ?>
                                <span class="rating-value"><?= $review->rating ?>.0</span>
                            </div>
                            <div class="date"><?= date('M j, Y', strtotime($review->created_at)) ?></div>
                        </div>
                    </div>
                    <div class="review-content">
                        <p><?= nl2br(htmlspecialchars($review->message)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-reviews">
                <img src="<?= ROOT ?>/assets/images/no-reviews.svg" alt="No reviews">
                <h3>No reviews yet</h3>
                <p>Guest feedback will appear here once they review your properties.</p>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php require_once 'ownerFooter.view.php'; ?>

<!-- Helper Functions -->
<?php
function getInitials($id) {
    return strtoupper(substr(md5($id), 0, 2)); // Generate initials based on ID
}

function generatePastelColor($seed) {
    $hash = md5($seed);
    return sprintf(
        'hsl(%d, %d%%, %d%%)',
        hexdec(substr($hash, 0, 2)) % 360,
        70, // saturation
        80 // lightness
    );
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const reviewsPerPage = 4;
        const reviews = document.querySelectorAll(".review-card");
        const paginationContainer = document.querySelector(".pagination");
        let currentPage = 1;

        // Clear existing pagination buttons
        paginationContainer.innerHTML = '';

        // Calculate total pages
        const totalPages = Math.ceil(reviews.length / reviewsPerPage);

        function showPage(page) {
            currentPage = page;
            const start = (page - 1) * reviewsPerPage;
            const end = start + reviewsPerPage;

            reviews.forEach((review, index) => {
                review.style.display = (index >= start && index < end) ? "block" : "none";
            });

            updatePaginationButtons();
        }

        function createPagination() {
            // Previous Button
            const prevButton = document.createElement("button");
            prevButton.className = "pagination-btn prev-page";
            prevButton.innerHTML = `<img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous">`;
            prevButton.addEventListener("click", () => {
                if (currentPage > 1) showPage(currentPage - 1);
            });

            // Page Numbers
            const pageNumbers = document.createElement("div");
            pageNumbers.className = "page-numbers";
            
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement("button");
                pageButton.className = `pagination-btn ${i === 1 ? "active" : ""}`;
                pageButton.textContent = i;
                pageButton.addEventListener("click", () => showPage(i));
                pageNumbers.appendChild(pageButton);
            }

            // Next Button
            const nextButton = document.createElement("button");
            nextButton.className = "pagination-btn next-page";
            nextButton.innerHTML = `<img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next">`;
            nextButton.addEventListener("click", () => {
                if (currentPage < totalPages) showPage(currentPage + 1);
            });

            // Append elements
            paginationContainer.appendChild(prevButton);
            paginationContainer.appendChild(pageNumbers);
            paginationContainer.appendChild(nextButton);

            // Disable buttons initially if needed
            updatePaginationButtons();
        }

        function updatePaginationButtons() {
            const buttons = paginationContainer.querySelectorAll(".pagination-btn");
            buttons.forEach(button => {
                button.classList.remove("active");
                if (button.textContent == currentPage) {
                    button.classList.add("active");
                }
            });

            // Disable prev/next buttons when appropriate
            const prevButton = paginationContainer.querySelector(".prev-page");
            const nextButton = paginationContainer.querySelector(".next-page");
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
        }

        if (reviews.length > 0) {
            createPagination();
            showPage(1);
        }
    });
</script>

<style>
    :root {
    --primary: #4361ee;
    --positive: #4cc9f0;
    --neutral: #7209b7;
    --negative: #f72585;
    --text-dark: #2b2d42;
    --text-medium: #8d99ae;
    --bg-light: #f8f9fa;
    --border-radius: 12px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* Reviews Dashboard */
.reviews-dashboard {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    color: var(--text-dark);
}

.dashboard-header {
    text-align: center;
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    font-size: 2rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.dashboard-header p {
    font-size: 1rem;
    color: var(--text-medium);
}

/* Review List */
.review-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.review-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    border-left: 4px solid var(--positive);
    transition: var(--transition);
}

.review-card.positive {
    border-left-color: var(--positive);
}

.review-card.neutral {
    border-left-color: var(--neutral);
}

.review-card.negative {
    border-left-color: var(--negative);
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.reviewer-details {
    line-height: 1.4;
}

.reviewer-details .name {
    font-weight: 600;
}

.reviewer-details .property {
    font-size: 0.85rem;
    color: var(--text-medium);
}

.review-meta {
    text-align: right;
}

.rating {
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: flex-end;
}

.rating-value {
    font-weight: 600;
    color: var(--text-dark);
}

.date {
    font-size: 0.8rem;
    color: var(--text-medium);
}

.review-content {
    line-height: 1.6;
    color: var(--text-dark);
}

/* No Reviews */
.no-reviews {
    text-align: center;
    padding: 3rem 1rem;
}

.no-reviews img {
    max-width: 200px;
    opacity: 0.7;
    margin-bottom: 1.5rem;
}

.no-reviews h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.no-reviews p {
    color: var(--text-medium);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    align-items: center;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #4361ee;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn:hover {
    background: #4361ee;
    color: white;
}

.pagination-btn.active {
    background: #4361ee;
    color: white;
    font-weight: bold;
}

.pagination-btn[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn[disabled]:hover {
    background: white;
    color: inherit;
}

.page-numbers {
    display: flex;
    gap: 0.5rem;
}

.pagination-btn img {
    height: 1.2rem;
}

</style>