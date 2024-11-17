<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>Requested Tasks</h2>
</div>
<div>
    <div class="date-filter-container">
      <div class="input-group-aligned width-100">
        <label for="year-sort" class="date-label">Year:</label>
        <select id="year-filter" class="date-input-field-small">
          <option value="all">All</option>
          <?php
          $currentYear = date('Y');
          for($year = $currentYear; $year >= $currentYear - 5; $year--) {
            echo "<option value='$year'>$year</option>";
          }
          ?>
        </select>
      </div>
      <div class="input-group-aligned width-100">
        <label for="month-filter" class="date-label">Month:</label>
        <select id="month-filter" class="date-input-field-small">
          <option value="all">All</option>
          <?php
          $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
          ];
          foreach($months as $month) {
            echo "<option value='$month'>$month</option>";
          }
          ?>
        </select>
      </div>
      <div class="input-group-aligned width-100">
        <button id="sort-time-button" class="sort-time-button">Sort</button>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const sortButton = document.getElementById('sort-time-button');
        const yearFilter = document.getElementById('year-filter');
        const monthFilter = document.getElementById('month-filter');
        const container = document.querySelector('.repair-cards-container');

        function filterAndSortCards() {
          const selectedYear = yearFilter.value;
          const selectedMonth = monthFilter.value;
          
          const cards = Array.from(container.getElementsByClassName('repair-card-box'));
          
          cards.forEach(card => {
            const cardDate = new Date(card.dataset.date);
            let showCard = true;

            if (selectedYear !== 'all') {
              showCard = cardDate.getFullYear().toString() === selectedYear;
            }

            if (showCard && selectedMonth !== 'all') {
              const monthName = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(cardDate);
              showCard = monthName === selectedMonth;
            }

            card.style.display = showCard ? 'block' : 'none';
          });

          // Sort visible cards by date
          const visibleCards = cards.filter(card => card.style.display !== 'none');
          visibleCards.sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return dateB - dateA; // Newest first
          });

          // Reorder cards in DOM
          visibleCards.forEach(card => container.appendChild(card));
        }

        // Initial filter on page load
        filterAndSortCards();

        // Add event listeners
        sortButton.addEventListener('click', filterAndSortCards);
        yearFilter.addEventListener('change', filterAndSortCards);
        monthFilter.addEventListener('change', filterAndSortCards);
      });
    </script>

    <div class="repair-cards-container">
        <?php
        // Get services data from controller
        $services = $data['services'] ?? [];
        
        if (empty($services)) {
            echo "<p>No service requests found</p>";
        } else {
            foreach ($services as $service) {
                ?>
                <div class="repair-card-box" data-date="<?= $service->date ?>">
                    <div class="repair-card">
                        <div class="repair-left-content">
                            <h3><?= $service->service_type ?></h3>
                            <div class="detail-fields">
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Property ID:</strong></span>
                                    <span class="details-field-small"><?= $service->property_id ?></span>
                                </div>
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Service Provider:</strong></span>
                                    <div class="details-field-small" style="display: flex; align-items: center; gap: 10px;">
                                        <select name="service_provider" class="details-field-small" style="border: none;" onchange="updateProviderImage(this)">
                                            <?php foreach($data['service_providers'] as $provider): ?>
                                                <option value="<?= $provider->pid ?>" 
                                                    data-image="<?= ROOT ?>/assets/images/<?= $provider->image_url ?>"
                                                    <?= ($service->service_provider_id == $provider->pid) ? 'selected' : '' ?> 
                                                    style="padding: 5px; cursor: hand;" 
                                                    onmouseover="this.style.backgroundColor='#f0f0f0'" 
                                                    onmouseout="this.style.backgroundColor='transparent'">
                                                    <?= $provider->fname . ' ' . $provider->lname ?> (ID: <?= $provider->pid ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <!-- Provider image thumbnail
                                        <img id="providerImage" 
                                             src="<?= ROOT ?>/assets/images/<?= $data['service_providers'][0]->image_url ?>" 
                                             alt="Service Provider" 
                                             style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"> -->
                                    </div>
                                </div>
                                <script>
                                function updateProviderImage(select) {
                                    const selectedOption = select.options[select.selectedIndex];
                                    const imageUrl = selectedOption.getAttribute('data-image');
                                    document.getElementById('providerImage').src = imageUrl;
                                }
                                </script>
                            </div>
                            <div class="detail-fields">
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Date:</strong></span>
                                    <span class="details-field-small"><?= date('Y/m/d', strtotime($service->date)) ?></span>
                                </div>
                            </div>
                            <div class="detail-fields">
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Property Name:</strong></span>
                                    <span class="details-field-small"><?= $service->property_name ?></span>
                                </div>
                            </div>
                            <div class="detail-fields">
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Service Type:</strong></span>
                                    <span class="details-field-small"><?= $service->service_type ?></span>
                                </div>
                            </div>
                            <div class="detail-fields">
                                <div class="detail-fields-aligned">
                                    <span class="details-labels-aligend"><strong>Description:</strong></span>
                                    <span class="details-field-small"><?= $service->service_description ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="approval-right-content">
                            <form method="POST" style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                                <input type="hidden" name="service_id" value="<?= $service->service_id ?>">
                                <input type="hidden" name="service_provider_select" value="">
                                <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 10px;">
                                    <button type="submit" class="accept-btn" onclick="submitForm(this.form)" style="width: 215px;">Accept</button>
                                </div>
                            </form>

                            <form method="POST" style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                                <input type="hidden" name="delete_service_id" value="<?=$service->service_id?>">
                                <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 10px;">
                                    <button type="submit" class="decline-btn" style="width: 215px;">Decline</button>
                                </div>
                            </form>
                            <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="property" class="repair-prop-img">

                        </div>
                        <script>
                            function submitForm(form) {
                                // Get selected service provider ID from the select element
                                const providerSelect = document.querySelector('select[name="service_provider"]');
                                form.querySelector('input[name="service_provider_select"]').value = providerSelect.value;
                            }
                        </script>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>