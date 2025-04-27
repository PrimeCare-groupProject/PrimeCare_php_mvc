<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
  <a href='<?= ROOT ?>/dashboard/serviceRequests/'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
  <!-- <div class="gap"></div> -->
    <h2>External Service Requests</h2>
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
          
          const cards = Array.from(container.getElementsByClassName('external-service'));
          
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
        // Get external services data from controller
        $services = $data['services'] ?? [];
        
        if (empty($services)) {
            ?>
            <div class="no-services-container">
          <div class="no-services-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#c8c8c8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                <path d="M9 9h6"></path>
                <path d="M9 12h6"></path>
                <path d="M9 15h4"></path>
              </svg>
          </div>
          <h3>No External Service Requests Found</h3>
          <p>When customers request external services, they will appear here.</p>
            </div>
            <style>
          .no-services-container {
              display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
              padding: 60px 20px;
              background-color: white;
              border-radius: 8px;
              box-shadow: 0 2px 5px rgba(0,0,0,0.1);
              margin: 20px 0;
              text-align: center;
              min-height: 300px;
          }
          .no-services-icon {
              margin-bottom: 20px;
          }
          .no-services-container h3 {
              margin: 0 0 10px 0;
              color: #333;
              font-size: 24px;
          }
          .no-services-container p {
              margin: 0;
              color: #777;
              font-size: 16px;
          }
            </style>
            <?php
        } else {
            $serviceCount = 1; // Initialize service counter
            foreach ($services as $service) {
                // Get filtered providers specific to this service
                $filteredProviders = $data['providers_by_service'][$service->id] ?? [];
                
                // Find the current provider image if a provider is selected
                $currentProviderImage = null;
                if (!empty($service->service_provider_id)) {
                    foreach ($filteredProviders as $provider) {
                        if ($provider->pid == $service->service_provider_id) {
                            $currentProviderImage = $provider->image_url;
                            break;
                        }
                    }
                }
                // Default to first provider's image if no match found
                if (empty($currentProviderImage) && !empty($filteredProviders)) {
                    $currentProviderImage = $filteredProviders[0]->image_url;
                }
                
                // Get property images (decode JSON)
                $propertyImage = $service->property_image ?? 'listing_alt.jpg';
                ?>
                <div class="external-service" data-date="<?= $service->date ?>">
                    <div class="preInspection-header">
                        <h3><?= $service->service_type ?></h3>
                        <div style="display: flex; gap: 10px;">
                            <form method="POST">
                                <input type="hidden" name="service_id" value="<?= $service->id ?>">
                                <input type="hidden" name="service_provider_select" value="">
                                <button type="submit" class="accept-btn" onclick="submitForm(this.form)" <?= empty($filteredProviders) ? 'disabled' : '' ?>>Accept</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="delete_service_id" value="<?= $service->id ?>">
                                <button type="submit" class="decline-btn">Decline</button>
                            </form>
                        </div>
                    </div>
                    <div class="input-group1">
                        <div class="input-group2">
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Request ID:</strong></span>
                                    <input class="input-field2" value="<?= $service->id ?>" readonly>
                                </div>
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Date:</strong></span>
                                    <input class="input-field2" value="<?= date('Y/m/d', strtotime($service->date)) ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Property Address:</strong></span>
                                    <input class="input-field2" value="<?= $service->property_address ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Service Type:</strong></span>
                                    <input class="input-field2" value="<?= $service->service_type ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Description:</strong></span>
                                    <input class="input-field2" value="<?= $service->property_description ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Cost Per Hour:</strong></span>
                                    <input class="input-field2" value="<?= $service->cost_per_hour ?> LKR" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Service Provider:</strong></span>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if (empty($filteredProviders)): ?>
                                            <div class="no-provider-message">
                                                <p>No approved service providers available for this service type.</p>
                                            </div>
                                        <?php else: ?>
                                            <select name="service_provider" class="input-field2" onchange="updateProviderImage(this, <?= $service->id ?>)">
                                                <?php foreach($filteredProviders as $provider): ?>
                                                    <option value="<?= $provider->pid ?>" 
                                                        data-image="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $provider->image_url ?>"
                                                        <?= ($service->service_provider_id == $provider->pid) ? 'selected' : '' ?>>
                                                        <?= $provider->fname . ' ' . $provider->lname ?> (ID: <?= $provider->pid ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <img id="providerImage_<?= $service->id ?>" 
                                                 src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $currentProviderImage ?? 'Agent.png' ?>" 
                                                 alt="Service Provider" 
                                                 class="provider-image"
                                                 onerror="this.src='<?= ROOT ?>/assets/images/Agent.png'">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image-area">
                            <div class="property-image-container">
                                <img class="property-image zoom-on-hover" 
                                     src="<?= ROOT ?>/assets/images/<?= $propertyImage ?>" 
                                     alt="property" 
                                     onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $serviceCount++; // Increment service counter
            }
        }
        ?>
    </div>
</div>

<script>
function updateProviderImage(selectElement, serviceId) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const imageUrl = selectedOption.getAttribute('data-image');
    const imageElement = document.getElementById('providerImage_' + serviceId);
    
    // Set new image source but maintain the error handling
    imageElement.src = imageUrl;
    
    // Add a small animation to indicate the change
    imageElement.style.transform = 'scale(1.1)';
    setTimeout(() => {
        imageElement.style.transform = 'scale(1)';
    }, 300);
}

// Initialize provider images when page loads
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="service_provider"]');
    selects.forEach(select => {
        const serviceId = select.closest('.external-service').querySelector('input[name="service_id"]').value;
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            const imageUrl = selectedOption.getAttribute('data-image');
            if (imageUrl) {
                const imageElement = document.getElementById('providerImage_' + serviceId);
                imageElement.src = imageUrl;
                // Make sure the onerror attribute is preserved
                imageElement.onerror = function() {
                    this.src = '<?= ROOT ?>/assets/images/Agent.png';
                };
            }
        }
    });
});

function submitForm(form) {
    // Get selected service provider ID from the select element
    const providerSelect = form.closest('.external-service').querySelector('select[name="service_provider"]');
    form.querySelector('input[name="service_provider_select"]').value = providerSelect.value;
}
</script>

<style>
.provider-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ddd;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  flex-shrink: 0;
  position: relative;
  display: inline-block;
  overflow: hidden;
}

.zoom-on-hover {
  transition: transform 0.3s ease;
}

.zoom-on-hover:hover {
  transform: scale(1.1);
}

/* Fixed image container styling */
.image-area {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  padding: 0 20px 0 10px;
  box-sizing: border-box;
  margin-right: 15px;
}

.property-image-container {
  width: 170px;
  height: 170px;
  overflow: hidden;
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.property-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 6px;
}

.preInspection-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 15px;
  background-color: #E5E4E2;
  border-radius: 15px 15px 0 0;
}

.external-service {
  background-color: white;
  border-radius: 15px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  margin-bottom: 20px;
  overflow: hidden;
  margin-top: 15px;
  margin-left: 10px;
  margin-right: 10px;
}

.input-group1 {
  display: flex;
  padding: 15px;
  gap: 0; /* Removed gap to have better control of spacing */
}

.input-group2 {
  flex: 1;
  padding-right: 10px;
}

/* Button styles remain unchanged */
.accept-btn {
  max-width: 200px;
  min-width: 150px;
  padding: 10px 20px;
  background-color: #28a745;
  color: var(--white-color);
  font-size: 16px;
  font-weight: 500;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.accept-btn:hover {
  background-color: #218838;
}

.accept-btn:disabled {
  background-color: #8BC34A;
  opacity: 0.6;
  cursor: not-allowed;
}

.decline-btn {
  max-width: 200px;
  min-width: 150px;
  padding: 10px 20px;
  background-color: #D22B2B;
  color: var(--white-color);
  font-size: 16px;
  font-weight: 500;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.decline-btn:hover {
  background-color: #bd2130;
}

.no-provider-message {
  background-color: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
  border-radius: 5px;
  padding: 8px 12px;
  margin-top: 5px;
  font-size: 14px;
}

/* Status badge styling */
.status-badge {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: bold;
  text-transform: uppercase;
  margin-left: 10px;
}

.status-pending {
  background-color: #ffeeba;
  color: #856404;
}

.status-ongoing {
  background-color: #b8daff;
  color: #004085;
}

.status-done {
  background-color: #c3e6cb;
  color: #155724;
}

.status-rejected {
  background-color: #f5c6cb;
  color: #721c24;
}
</style>

<?php require_once 'agentFooter.view.php'; ?>