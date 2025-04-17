<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
  <div class="gap"></div>
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
          
          const cards = Array.from(container.getElementsByClassName('preInspection'));
          
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
          <h3>No Service Requests Found</h3>
          <p>When new tasks are requested, they will appear here.</p>
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
                // Find the current provider image if a provider is selected
                $currentProviderImage = null;
                if (!empty($service->service_provider_id)) {
                    foreach ($data['service_providers'] as $provider) {
                        if ($provider->pid == $service->service_provider_id) {
                            $currentProviderImage = $provider->image_url;
                            break;
                        }
                    }
                }
                // Default to first provider's image if no match found
                if (empty($currentProviderImage) && !empty($data['service_providers'])) {
                    $currentProviderImage = $data['service_providers'][0]->image_url;
                }
                
                // Determine property image path
                $propertyImage = 'listing_alt.jpg'; // Default image
                
                // Check if property has an image
                if (!empty($service->property_image)) {
                    $propertyImagePath = ROOT . '/assets/images/uploads/property_images' . $service->property_image;
                    // Use the property image if it exists, otherwise use default
                    $propertyImage = $service->property_image;
                }
                ?>
                <div class="preInspection" data-date="<?= $service->date ?>">
                    <div class="preInspection-header">
                        <h3><?= $service->service_type ?> Request <?= $serviceCount ?></h3>
                        <div style="display: flex; gap: 10px;">
                            <form method="POST">
                                <input type="hidden" name="service_id" value="<?= $service->service_id ?>">
                                <input type="hidden" name="service_provider_select" value="">
                                <button type="submit" class="accept-btn" onclick="submitForm(this.form)">Accept</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="delete_service_id" value="<?=$service->service_id?>">
                                <button type="submit" class="decline-btn">Decline</button>
                            </form>
                        </div>
                    </div>
                    <div class="input-group1">
                        <div class="input-group2">
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Property ID:</strong></span>
                                    <input class="input-field2" value="<?= $service->property_id ?>" readonly>
                                </div>
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Date:</strong></span>
                                    <input class="input-field2" value="<?= date('Y/m/d', strtotime($service->date)) ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Property Name:</strong></span>
                                    <input class="input-field2" value="<?= $service->property_name ?>" readonly>
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
                                    <input class="input-field2" value="<?= $service->service_description ?>" readonly>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-aligned">
                                    <span class="input-label-aligend1"><strong>Service Provider:</strong></span>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <select name="service_provider" class="input-field2" onchange="updateProviderImage(this, <?= $service->service_id ?>)">
                                            <?php foreach($data['service_providers'] as $provider): ?>
                                                <option value="<?= $provider->pid ?>" 
                                                    data-image="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $provider->image_url ?>"
                                                    <?= ($service->service_provider_id == $provider->pid) ? 'selected' : '' ?>>
                                                    <?= $provider->fname . ' ' . $provider->lname ?> (ID: <?= $provider->pid ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <img id="providerImage_<?= $service->service_id ?>" 
                                             src="<?= ROOT ?>/assets/images/<?= $currentProviderImage ?? 'Agent.png' ?>" 
                                             alt="Service Provider" 
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image-area">
                            <div class="property-image-container">
                                <img class="property-image zoom-on-hover" 
                                     src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $propertyImage ?>" 
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
        const serviceId = select.closest('.preInspection').querySelector('input[name="service_id"]').value;
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            const imageUrl = selectedOption.getAttribute('data-image');
            if (imageUrl) {
                document.getElementById('providerImage_' + serviceId).src = imageUrl;
            }
        }
    });
});

function submitForm(form) {
    // Get selected service provider ID from the select element
    const providerSelect = form.closest('.preInspection').querySelector('select[name="service_provider"]');
    form.querySelector('input[name="service_provider_select"]').value = providerSelect.value;
}
</script>

<style>
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
  background-color: #f5f5f5;
  border-radius: 8px 8px 0 0;
}

.preInspection {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  margin-bottom: 20px;
  overflow: hidden;
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
  border: 2px solid #1e7e34;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.accept-btn:hover {
  background-color: #218838;
}

.decline-btn {
  max-width: 200px;
  min-width: 150px;
  padding: 10px 20px;
  background-color: #D22B2B;
  color: var(--white-color);
  font-size: 16px;
  font-weight: 500;
  border: 2px solid #c82333;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.decline-btn:hover {
  background-color: #bd2130;
}
</style>

<?php require_once 'agentFooter.view.php'; ?>