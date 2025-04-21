<?php require_once 'ownerHeader.view.php'; ?>

<div class="reviews-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-comment-alt"></i> Guest Feedback</h1>
            <p>Understand your property performance through guest reviews</p>
        </div>
        <div class="header-actions">
            <div class="date-filter">
                <i class="fas fa-calendar-alt"></i>
                <select class="period-select">
                    <option>Last 30 Days</option>
                    <option selected>Last Year</option>
                    <option>All Time</option>
                </select>
            </div>
            <button class="export-btn">
                <i class="fas fa-file-export"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card sentiment-card">
            <div class="card-icon">
                <i class="fas fa-smile"></i>
            </div>
            <div class="card-content">
                <div class="card-value">87%</div>
                <div class="card-label">Positive Sentiment</div>
                <div class="card-trend up">
                    <i class="fas fa-arrow-up"></i> 12% from last period
                </div>
            </div>
        </div>

        <div class="summary-card rating-card">
            <div class="card-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="card-content">
                <div class="card-value">4.7</div>
                <div class="card-label">Average Rating</div>
                <div class="card-trend up">
                    <i class="fas fa-arrow-up"></i> 0.3 from last period
                </div>
            </div>
        </div>

        <div class="summary-card reviews-card">
            <div class="card-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="card-content">
                <div class="card-value">142</div>
                <div class="card-label">Total Reviews</div>
                <div class="card-trend down">
                    <i class="fas fa-arrow-down"></i> 8% from last period
                </div>
            </div>
        </div>

        <div class="summary-card response-card">
            <div class="card-icon">
                <i class="fas fa-reply"></i>
            </div>
            <div class="card-content">
                <div class="card-value">92%</div>
                <div class="card-label">Response Rate</div>
                <div class="card-trend up">
                    <i class="fas fa-arrow-up"></i> 5% from last period
                </div>
            </div>
        </div>
    </div>

    <!-- Data Visualization Row -->
    <div class="data-viz-row">
        <div class="rating-trend-card">
            <div class="card-header">
                <h3>Rating Trend</h3>
                <div class="time-filter">
                    <button class="time-btn active">M</button>
                    <button class="time-btn">Q</button>
                    <button class="time-btn">Y</button>
                </div>
            </div>
            <div class="trend-chart">
                <canvas id="ratingTrendChart"></canvas>
            </div>
        </div>

        <div class="word-cloud-card">
            <div class="card-header">
                <h3>Common Themes</h3>
                <div class="legend">
                    <span class="positive"><i class="fas fa-circle"></i> Positive</span>
                    <span class="negative"><i class="fas fa-circle"></i> Negative</span>
                </div>
            </div>
            <div class="word-cloud">
                <span class="word positive" style="--size: 3">Cleanliness</span>
                <span class="word positive" style="--size: 4">Comfort</span>
                <span class="word positive" style="--size: 2.5">Location</span>
                <span class="word negative" style="--size: 1.8">Noise</span>
                <span class="word positive" style="--size: 3.2">Staff</span>
                <span class="word negative" style="--size: 2">WiFi</span>
                <span class="word positive" style="--size: 1.5">Breakfast</span>
                <span class="word positive" style="--size: 2.8">View</span>
            </div>
        </div>
    </div>

    <!-- Review Management Section -->
    <div class="review-management">
        <div class="section-header">
            <h2>Recent Guest Reviews</h2>
            <div class="filter-options">
                <div class="filter-group">
                    <label>Filter by:</label>
                    <select class="filter-select">
                        <option>All Properties</option>
                        <option>The Grand Hotel</option>
                        <option>Beachside Villa</option>
                        <option>Mountain Lodge</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Rating:</label>
                    <select class="filter-select">
                        <option>All Ratings</option>
                        <option>5 Stars</option>
                        <option>4 Stars</option>
                        <option>3 Stars</option>
                        <option>2 Stars</option>
                        <option>1 Star</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Review List -->
        <div class="review-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card <?= $review->rating >= 4 ? 'positive' : ($review->rating >= 3 ? 'neutral' : 'negative') ?>">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="avatar" style="background-color: <?= generatePastelColor($review->reviewer_name) ?>">
                                    <?= getInitials($review->reviewer_name) ?>
                                </div>
                                <div class="reviewer-details">
                                    <div class="name"><?= htmlspecialchars($review->reviewer_name) ?></div>
                                    <div class="property"><?= htmlspecialchars($review->property_name) ?></div>
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
                            <p><?= nl2br(htmlspecialchars($review->content)) ?></p>
                        </div>
                        <div class="review-actions">
                            <button class="action-btn reply-btn">
                                <i class="fas fa-reply"></i> Respond
                            </button>
                            <button class="action-btn highlight-btn">
                                <i class="fas fa-highlighter"></i> Highlight
                            </button>
                            <button class="action-btn flag-btn">
                                <i class="fas fa-flag"></i> Flag
                            </button>
                        </div>
                        
                        <?php if (!empty($review->response)): ?>
                            <div class="owner-response">
                                <div class="response-header">
                                    <i class="fas fa-check-circle"></i> Your Response
                                    <span class="response-date"><?= date('M j, Y', strtotime($review->response_date)) ?></span>
                                </div>
                                <div class="response-content">
                                    <?= nl2br(htmlspecialchars($review->response)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reviews">
                    <img src="<?= ROOT ?>/assets/images/no-reviews.svg" alt="No reviews">
                    <h3>No reviews yet</h3>
                    <p>Guest feedback will appear here once they review your properties</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="pagination">
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn next-btn">Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<?php require_once 'ownerFooter.view.php'; ?>

<!-- Helper functions (would normally be in a separate file) -->
<?php
function getInitials($name) {
    $names = explode(' ', $name);
    $initials = '';
    foreach ($names as $n) {
        $initials .= strtoupper(substr($n, 0, 1));
    }
    return substr($initials, 0, 2);
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

<style>
    :root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --accent: #4895ef;
    --positive: #4cc9f0;
    --negative: #f72585;
    --neutral: #7209b7;
    --warning: #f8961e;
    --success: #43aa8b;
    --text-dark: #2b2d42;
    --text-medium: #8d99ae;
    --text-light: #edf2f4;
    --bg-light: #f8f9fa;
    --border-radius: 12px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* Dashboard Layout */
.reviews-dashboard {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    color: var(--text-dark);
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-content h1 {
    font-size: 2rem;
    margin: 0;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content p {
    margin: 0.5rem 0 0;
    color: var(--text-medium);
    font-size: 1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.date-filter {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    padding: 0.6rem 1rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.period-select {
    border: none;
    background: transparent;
    font-weight: 500;
    color: var(--text-dark);
}

.export-btn {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.7rem 1.2rem;
    border-radius: var(--border-radius);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.export-btn:hover {
    background: var(--secondary);
    transform: translateY(-2px);
}

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    display: flex;
    gap: 1rem;
    transition: var(--transition);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.sentiment-card .card-icon { background: var(--positive); }
.rating-card .card-icon { background: var(--accent); }
.reviews-card .card-icon { background: var(--neutral); }
.response-card .card-icon { background: var(--success); }

.card-content {
    flex: 1;
}

.card-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.card-label {
    color: var(--text-medium);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.card-trend {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.card-trend.up {
    color: var(--success);
}

.card-trend.down {
    color: var(--negative);
}

/* Data Visualization */
.data-viz-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 900px) {
    .data-viz-row {
        grid-template-columns: 1fr;
    }
}

.rating-trend-card, .word-cloud-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.card-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.time-filter {
    display: flex;
    gap: 0.5rem;
}

.time-btn {
    border: none;
    background: var(--bg-light);
    width: 32px;
    height: 32px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
}

.time-btn.active {
    background: var(--primary);
    color: white;
}

.trend-chart {
    height: 250px;
    position: relative;
}

.word-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
    align-items: center;
    justify-content: center;
    min-height: 250px;
    padding: 1rem;
}

.word {
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-block;
    font-size: calc(var(--size) * 0.7rem);
    opacity: 0.8;
    transition: var(--transition);
}

.word:hover {
    opacity: 1;
    transform: scale(1.1);
}

.word.positive {
    background: rgba(76, 201, 240, 0.1);
    color: var(--positive);
}

.word.negative {
    background: rgba(247, 37, 133, 0.1);
    color: var(--negative);
}

.legend {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
}

.legend span {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.legend .positive i { color: var(--positive); }
.legend .negative i { color: var(--negative); }

/* Review Management */
.review-management {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.filter-options {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.9rem;
    color: var(--text-medium);
}

.filter-select {
    padding: 0.5rem;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 0.9rem;
    min-width: 120px;
}

/* Review Cards */
.review-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.review-card {
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border-left: 4px solid var(--positive);
    transition: var(--transition);
}

.review-card.positive {
    border-left-color: var(--positive);
    background: linear-gradient(to right, rgba(76, 201, 240, 0.03) 0%, rgba(255,255,255,1) 20%);
}

.review-card.neutral {
    border-left-color: var(--neutral);
    background: linear-gradient(to right, rgba(114, 9, 183, 0.03) 0%, rgba(255,255,255,1) 20%);
}

.review-card.negative {
    border-left-color: var(--negative);
    background: linear-gradient(to right, rgba(247, 37, 133, 0.03) 0%, rgba(255,255,255,1) 20%);
}

.review-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
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
    margin-bottom: 0.5rem;
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
    margin-bottom: 1rem;
}

.review-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: none;
    background: var(--bg-light);
    color: var(--text-dark);
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.action-btn:hover {
    background: #e9ecef;
}

.reply-btn {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.reply-btn:hover {
    background: rgba(67, 97, 238, 0.2);
}

.highlight-btn {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.highlight-btn:hover {
    background: rgba(248, 150, 30, 0.2);
}

.flag-btn {
    background: rgba(247, 37, 133, 0.1);
    color: var(--negative);
}

.flag-btn:hover {
    background: rgba(247, 37, 133, 0.2);
}

.owner-response {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px dashed #eee;
}

.response-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--success);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.response-date {
    margin-left: auto;
    color: var(--text-medium);
    font-size: 0.8rem;
    font-weight: normal;
}

.response-content {
    background: var(--bg-light);
    padding: 1rem;
    border-radius: 8px;
    line-height: 1.5;
}

/* No Reviews State */
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
    margin: 0;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.page-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
    transition: var(--transition);
}

.page-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-btn:hover:not(.active) {
    background: var(--bg-light);
}

.next-btn {
    width: auto;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Rating Trend Chart
    const trendCtx = document.getElementById('ratingTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Average Rating',
                data: [4.2, 4.3, 4.5, 4.6, 4.7, 4.8, 4.6, 4.7, 4.8, 4.9, 4.7, 4.8],
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rating: ' + context.raw.toFixed(1);
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 3,
                    max: 5,
                    ticks: {
                        stepSize: 0.5
                    }
                }
            }
        }
    });

    // Time filter buttons
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelector('.time-btn.active').classList.remove('active');
            this.classList.add('active');
            
            // Here you would update the chart data based on the selected time period
            // This is just a demo - in a real app you would fetch new data
            const periods = {
                'M': ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'Q': ['Month 1', 'Month 2', 'Month 3'],
                'Y': ['Q1', 'Q2', 'Q3', 'Q4']
            };
            
            trendChart.data.labels = periods[this.textContent];
            trendChart.update();
        });
    });
</script>