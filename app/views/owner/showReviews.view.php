<?php require_once 'ownerHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row" >
        
        <div class="left-content">
            <div class="gap"></div>
            <h2>Reviews</h2>
        </div>
        <div>
            <span class="RS__date-range" style="margin-right:20px;">March 2021 - February 2022</span>
        </div>
    </div>
</div>

<div class="RS__reviews-container">
    <div class="RS__stats-container">
        <div class="RS__stat-box">
            <div class="RS__total-number">10</div>
            <div class="RS__stat-label">Reviews on this year</div>
        </div>

        <div class="RS__stat-box">
            <div class="RS__total-number">4.0</div>
            <div class="RS__rating-stars">★★★★☆</div>
            <div class="RS__stat-label">Average rating on this year</div>
        </div>

        <div class="RS__stat-box">
            <div class="RS__rating-distribution">
                <span>5</span>
                <div class="RS__rating-bar">
                    <div class="RS__rating-bar-fill" style="width: 80%"></div>
                </div>
                <span class="RS__rating-count">2.0k</span>
            </div>
            <div class="RS__rating-distribution">
                <span>4</span>
                <div class="RS__rating-bar">
                    <div class="RS__rating-bar-fill" style="width: 60%"></div>
                </div>
                <span class="RS__rating-count">2.0k</span>
            </div>
            <div class="RS__rating-distribution">
                <span>3</span>
                <div class="RS__rating-bar">
                    <div class="RS__rating-bar-fill" style="width: 50%"></div>
                </div>
                <span class="RS__rating-count">2.0k</span>
            </div>
            <div class="RS__rating-distribution">
                <span>2</span>
                <div class="RS__rating-bar">
                    <div class="RS__rating-bar-fill" style="width: 40%"></div>
                </div>
                <span class="RS__rating-count">2.0k</span>
            </div>
            <div class="RS__rating-distribution">
                <span>1</span>
                <div class="RS__rating-bar">
                    <div class="RS__rating-bar-fill" style="width: 20%"></div>
                </div>
                <span class="RS__rating-count">2.0k</span>
            </div>
            <!-- Repeat for other ratings -->
        </div>
    </div>

    <div class="RS__review-card">
        <div class="RS__reviewer">
            <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Reviewer" class="reviewer-avatar">
            <div class="RS__reviewer-info">
                <div class="RS__reviewer-name">Towhidur Rahman</div>
                <div class="RS__reviewer-stats">
                    Property Name - <span class="RS__reviewer-property">The Grand Hotel</span>
                </div>
            </div>
            <div>
                <div class="RS__rating-stars">★★★★☆</div>
                <div class="RS__review-date">24-10-2022</div>
            </div>
        </div>
        <div class="RS__review-content">
            My first and only mala ordered on Etsy, and I'm beyond delighted! I requested a custom mala based on two stones I was called to invite together in this kind of creation. The fun and genuine joy I invite together in this kind of creation. The fun and genuine joy.
        </div>
    </div>

    <div class="RS__review-card">
        <div class="RS__reviewer">
            <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Reviewer" class="reviewer-avatar">
            <div class="RS__reviewer-info">
                <div class="RS__reviewer-name">Towhidur Rahman</div>
                <div class="RS__reviewer-stats">
                    Property Name - <span class="RS__reviewer-property">The Grand Hotel</span>
                </div>
            </div>
            <div>
                <div class="RS__rating-stars">★★★★☆</div>
                <div class="RS__review-date">24-10-2022</div>
            </div>
        </div>
        <div class="RS__review-content">
            My first and only mala ordered on Etsy, and I'm beyond delighted! I requested a custom mala based on two stones I was called to invite together in this kind of creation. The fun and genuine joy I invite together in this kind of creation. The fun and genuine joy.
        </div>
    </div>
</div>

<?php require_once 'ownerFooter.view.php'; ?>