<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>Earnings</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" id="service-search" placeholder="Search services...">
            <button class="search-btn" id="search-btn">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-container">
    <h3>Filter by Timeline</h3>
    <div class="filter-options">
        <div class="quick-filters">
            <button class="filter-btn active" data-period="all">All Time</button>
            <button class="filter-btn" data-period="week">Last Week</button>
            <button class="filter-btn" data-period="month">Last Month</button>
            <button class="filter-btn" data-period="quarter">Last 3 Months</button>
            <button class="filter-btn" data-period="year">Last Year</button>
        </div>
        <div class="custom-date-range">
            <div class="date-inputs">
                <div class="date-field">
                    <label for="start-date">From:</label>
                    <input type="date" id="start-date" class="date-input">
                </div>
                <div class="date-field">
                    <label for="end-date">To:</label>
                    <input type="date" id="end-date" class="date-input">
                </div>
            </div>
            <button id="apply-filter" class="apply-btn">Apply Filter</button>
        </div>
    </div>
</div>

<!-- Total Earnings Summary Section -->
<div class="total-earnings-summary">
    <div class="total-earnings-card">
        <div class="total-earnings-icon">
            <i class="fa fa-money-bill-wave"></i>
        </div>
        <div class="total-earnings-content">
            <h3>Total Earnings</h3>
            <div class="time-period" id="earnings-time-period">All Time</div>
            <div class="total-amount" id="total-earnings-amount">LKR 0.00</div>
        </div>
    </div>
    <div class="total-earnings-stats">
        <div class="stat-item">
            <div class="stat-label">Services Completed</div>
            <div class="stat-value" id="total-services">0</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Total Hours</div>
            <div class="stat-value" id="total-hours">0</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Avg. Earnings/Hour</div>
            <div class="stat-value" id="avg-hourly-rate">LKR 0.00</div>
        </div>
    </div>
</div>

<div class="chart-container" style="width: 90%; max-width: 800px; margin: 30px auto; padding: 20px; background: #ffffff; border-radius: 10px; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; margin-bottom: 20px; font-family: 'Roboto', sans-serif; font-size: 24px; color: #444;">Earnings Overview (LKR)</h2>
    <canvas id="earningsChart" style="max-height: 400px;"></canvas>
</div>

<!-- Service Earnings Cards Section -->
<div class="earnings-cards-container" style="width: 90%; margin: 30px auto;">
    <h2 style="text-align: center; margin-bottom: 20px; font-family: 'Roboto', sans-serif; font-size: 24px; color: #444;">Completed Services</h2>
    
    <div id="filtered-results-info" class="filtered-results-info">
        Showing all completed services
    </div>
    
    <!-- Cards Grid -->
    <div class="service-cards-grid" id="service-cards-grid">
        <?php foreach($chartData as $service): ?>
            <div class="service-card" data-date="<?= htmlspecialchars($service['date'] ?? '') ?>" data-name="<?= htmlspecialchars($service['name'] ?? '') ?>" data-property="<?= htmlspecialchars($service['property_name'] ?? '') ?>">
                <div class="service-image">
                    <?php 
                    $image = isset($service['service_images']) && !empty($service['service_images']) 
                        ? json_decode($service['service_images'])[0] ?? null 
                        : null;
                    
                    if ($image && file_exists(ROOTPATH . "public/assets/images/uploads/service_logs/" . $image)): 
                    ?>
                        <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= $image ?>" alt="<?= $service['name'] ?>">
                    <?php else: ?>
                        <img src="<?= ROOT ?>/assets/images/service-placeholder.png" alt="Service">
                    <?php endif; ?>
                </div>
                
                <div class="service-details">
                    <h3><?= htmlspecialchars($service['name']) ?></h3>
                    
                    <div class="property-info">
                        <i class="fa fa-home"></i>
                        <span><?= htmlspecialchars($service['property_name'] ?? 'Property details not available') ?></span>
                    </div>
                    
                    <div class="service-date">
                        <i class="fa fa-calendar"></i>
                        <span><?= isset($service['date']) ? date('M d, Y', strtotime($service['date'])) : 'Date not available' ?></span>
                    </div>
                    
                    <div class="service-hours">
                        <i class="fa fa-clock"></i>
                        <span><?= htmlspecialchars($service['total_hours'] ?? 0) ?> hours</span>
                    </div>
                    
                    <button class="service-summary-btn" data-service-index="<?= $key ?? 0 ?>">Service Summary</button>
                </div>
                
                <div class="earnings-badge">
                    <div class="amount">LKR <?= number_format($service['totalEarnings'], 2) ?></div>
                    <div class="label">Earned</div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div id="no-filtered-services" class="no-services" style="display: none;">
            <p>No services found for the selected time period or search criteria.</p>
        </div>
        
        <?php if(empty($chartData)): ?>
            <div class="no-services">
                <p>No completed services found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Service Summary Modal - Updated structure with description section -->
<div id="service-modal" class="service-modal">
    <div class="service-modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-header">
            <h2 id="modal-service-name">Service Summary</h2>
        </div>
        <div class="modal-body">
            <div class="modal-image">
                <img id="modal-image" src="" alt="Service">
            </div>
            <div class="modal-info">
                <div class="modal-info-row">
                    <span class="info-label">Service:</span>
                    <span id="modal-service-type" class="info-value"></span>
                </div>
                <div class="modal-info-row">
                    <span class="info-label">Property:</span>
                    <span id="modal-property" class="info-value"></span>
                </div>
                <div class="modal-info-row">
                    <span class="info-label">Date:</span>
                    <span id="modal-date" class="info-value"></span>
                </div>
                <div class="modal-info-row">
                    <span class="info-label">Hours Worked:</span>
                    <span id="modal-hours" class="info-value"></span>
                </div>
                <div class="modal-info-row">
                    <span class="info-label">Earnings:</span>
                    <span id="modal-earnings" class="info-value"></span>
                </div>
            </div>
            
            <!-- Added description section -->
            <div class="modal-description">
                <h3>Service Description</h3>
                <div id="modal-description" class="description-content"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add CSS for the service cards and filter section -->
<style>
    /* Filter Container */
    .filter-container {
        width: 95%;
        max-width: none;
        margin: 20px 0;
        margin-left: auto;
        margin-right: auto;
        padding: 20px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .filter-container h3 {
        margin: 0 0 15px 0;
        font-size: 18px;
        color: #444;
        font-family: 'Roboto', sans-serif;
    }
    
    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .quick-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 20px;
        background: #f8f8f8;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
    }
    
    .filter-btn:hover {
        background: #f0f0f0;
    }
    
    .filter-btn.active {
        background: #FFEB3B;
        color: #333;
        border-color: #FFEB3B;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .custom-date-range {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .date-inputs {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .date-field {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .date-input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: 'Roboto', sans-serif;
    }
    
    .apply-btn {
        padding: 8px 16px;
        background: #FFEB3B;
        color: #333;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Roboto', sans-serif;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .apply-btn:hover {
        background: #FDD835;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .filtered-results-info {
        text-align: center;
        margin-bottom: 20px;
        color: #666;
        font-style: italic;
    }

    /* Existing service card styles */
    .service-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .service-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }
    
    .service-image {
        height: 160px;
        overflow: hidden;
        background: #f5f5f5;
    }
    
    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .service-details {
        padding: 16px;
    }
    
    .service-details h3 {
        margin: 0 0 15px 0;
        font-size: 18px;
        color: #333;
        font-family: 'Roboto', sans-serif;
    }
    
    .property-info, .service-date, .service-hours {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        color: #666;
        font-size: 14px;
    }
    
    .property-info i, .service-date i, .service-hours i {
        width: 20px;
        margin-right: 8px;
        color: #FFEB3B;
    }
    
    .earnings-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 235, 59, 0.95);
        padding: 8px 12px;
        border-radius: 6px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .earnings-badge .amount {
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }
    
    .earnings-badge .label {
        font-size: 12px;
        color: #555;
    }
    
    .no-services {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        background: #f9f9f9;
        border-radius: 10px;
        color: #666;
    }

    /* Media queries for responsive design */
    @media (max-width: 768px) {
        .quick-filters {
            justify-content: center;
        }
        
        .custom-date-range {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .date-inputs {
            width: 100%;
            justify-content: space-between;
        }
        
        .apply-btn {
            width: 100%;
        }
    }
    
    /* Add these new styles */
    .service-summary-btn {
        display: block;
        width: 100%;
        padding: 8px 0;
        margin-top: 10px;
        background-color: #FFEB3B;
        border: none;
        border-radius: 4px;
        color: #333;
        font-weight: 500;
        font-family: 'Roboto', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .service-summary-btn:hover {
        background-color: #FDD835;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Modal styles */
    .service-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    
    .service-modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        width: 80%;
        max-width: 800px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: modalFadeIn 0.3s;
        overflow: hidden;
    }
    
    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    
    .close-modal {
        float: right;
        font-size: 28px;
        font-weight: bold;
        color: #666;
        padding: 10px 15px;
        cursor: pointer;
    }
    
    .close-modal:hover {
        color: #333;
    }
    
    .modal-header {
        padding: 15px 20px;
        background-color: #FFEB3B;
        border-radius: 8px 8px 0 0;
    }
    
    .modal-header h2 {
        margin: 0;
        color: #333;
    }
    
    .modal-body {
        padding: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        align-items: flex-start; /* Align items at the top */
    }
    
    .modal-image {
        flex: 1 1 300px;
        margin-right: 0;
        margin-bottom: 0;
        height: 240px; /* Fixed height */
        overflow: hidden; /* Prevent overflow */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-image img {
        max-width: 100%;
        max-height: 100%; /* Limit height */
        object-fit: contain; /* Maintain aspect ratio */
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .modal-info {
        flex: 1 1 300px;
        padding: 0 10px;
    }
    
    .modal-info-row {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: baseline;
    }
    
    .info-label {
        font-weight: bold;
        color: #666;
        width: 140px;
        display: inline-block;
        flex-shrink: 0;
    }
    
    .info-value {
        color: #333;
        flex-grow: 1;
    }
    
    /* New styles for description section */
    .modal-description {
        flex-basis: 100%;
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    
    .modal-description h3 {
        font-size: 18px;
        margin: 0 0 15px 0;
        color: #444;
    }
    
    .description-content {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 6px;
        color: #555;
        line-height: 1.5;
        white-space: pre-line;
        min-height: 100px;
    }
    
    @media (max-width: 768px) {
        .service-modal-content {
            width: 95%;
            margin: 10% auto;
        }
        
        .modal-body {
            padding: 15px;
            gap: 15px;
        }
        
        .info-label {
            width: 120px;
        }
        
        .modal-image {
            height: 200px; /* Smaller height on mobile */
        }
    }
    
    /* Highlight search results */
    .highlight {
        background-color: #FFF9C4;
        padding: 0 2px;
    }

    /* Total Earnings Summary Styles */
    .total-earnings-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        width: 97%;
        margin: 10px 0;
        padding: 20px;
        justify-content: center;
    }

    .total-earnings-card {
        flex: 1 1 300px;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #FDD835, #FFEB3B);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .total-earnings-icon {
        font-size: 40px;
        color: rgba(255, 255, 255, 0.9);
        margin-right: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .total-earnings-content {
        flex-grow: 1;
    }

    .total-earnings-content h3 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #333;
    }

    .time-period {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
    }

    .total-amount {
        font-size: 32px;
        font-weight: 700;
        color: #333;
    }

    .total-earnings-stats {
        flex: 2 1 500px;
        display: flex;
        gap: 15px;
    }

    .stat-item {
        flex: 1;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        text-align: center;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    @media (max-width: 768px) {
        .total-earnings-summary {
            flex-direction: column;
        }
        
        .total-earnings-stats {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if chart data exists and has items
        const chartData = <?= json_encode($chartData ?? []) ?>;
        console.log('Chart data:', chartData);
        
        // References to DOM elements
        const filterButtons = document.querySelectorAll('.filter-btn');
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyFilterBtn = document.getElementById('apply-filter');
        const serviceCards = document.querySelectorAll('.service-card');
        const noFilteredServices = document.getElementById('no-filtered-services');
        const filteredResultsInfo = document.getElementById('filtered-results-info');
        const chartCanvas = document.getElementById('earningsChart');
        
        // Set today as the default end date
        const today = new Date();
        endDateInput.value = today.toISOString().split('T')[0];
        
        let earningsChart = null;
        
        // Initialize chart if we have data and a canvas element
        if (chartData.length > 0 && chartCanvas) {
            try {
                const ctx = chartCanvas.getContext('2d');
                const labels = chartData.map(data => data.name || 'Unknown');
                const values = chartData.map(data => parseFloat(data.totalEarnings) || 0);
                
                earningsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Earnings (LKR)',
                            data: values,
                            backgroundColor: 'rgba(255, 235, 59, 0.9)',
                            borderColor: 'rgba(255, 235, 59, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'LKR ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
                
                console.log('Chart initialized successfully');
            } catch (error) {
                console.error('Error initializing chart:', error);
            }
        } else {
            console.warn('No chart data available or canvas not found');
        }
        
        // Helper function to safely parse dates
        function parseDate(dateString) {
            if (!dateString) return null;
            
            try {
                const date = new Date(dateString);
                // Check if date is valid
                if (isNaN(date.getTime())) {
                    console.error('Invalid date:', dateString);
                    return null;
                }
                return date;
            } catch (e) {
                console.error('Error parsing date:', dateString, e);
                return null;
            }
        }
        
        // Add function to update earnings summary based on filtered data
        function updateEarningsSummary(filteredData) {
            // Calculate totals from filtered data
            let totalEarnings = 0;
            let totalServices = filteredData.length;
            let totalHours = 0;
            
            filteredData.forEach(service => {
                totalEarnings += parseFloat(service.totalEarnings) || 0;
                totalHours += parseFloat(service.total_hours) || 0;
            });
            
            // Calculate average hourly rate
            const avgHourlyRate = totalHours > 0 ? totalEarnings / totalHours : 0;
            
            // Update the summary sections
            document.getElementById('total-earnings-amount').textContent = 'LKR ' + totalEarnings.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('total-services').textContent = totalServices;
            document.getElementById('total-hours').textContent = totalHours.toLocaleString(undefined, {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });
            
            document.getElementById('avg-hourly-rate').textContent = 'LKR ' + avgHourlyRate.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Filter services based on date range
        function filterServices(startDate, endDate) {
            console.log('Filtering with dates:', startDate, endDate);
            
            let visibleCount = 0;
            let filteredData = [];
            
            // If no dates specified, show all
            if (!startDate && !endDate) {
                serviceCards.forEach(card => {
                    card.style.display = 'block';
                    visibleCount++;
                });
                filteredData = [...chartData];
                
                // Update time period display
                document.getElementById('earnings-time-period').textContent = 'All Time';
            } else {
                serviceCards.forEach(card => {
                    const cardDateStr = card.getAttribute('data-date');
                    const cardDate = parseDate(cardDateStr);
                    
                    console.log('Card date:', cardDateStr, cardDate);
                    
                    if (cardDate && 
                        (!startDate || cardDate >= startDate) && 
                        (!endDate || cardDate <= endDate)) {
                        card.style.display = 'block';
                        visibleCount++;
                        
                        // Find matching data for chart
                        const index = Array.from(serviceCards).indexOf(card);
                        if (chartData[index]) {
                            filteredData.push(chartData[index]);
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Update time period display with date range
                if (startDate && endDate) {
                    const startStr = startDate.toLocaleDateString();
                    const endStr = endDate.toLocaleDateString();
                    document.getElementById('earnings-time-period').textContent = `${startStr} - ${endStr}`;
                } else if (startDate) {
                    document.getElementById('earnings-time-period').textContent = `From ${startDate.toLocaleDateString()}`;
                } else if (endDate) {
                    document.getElementById('earnings-time-period').textContent = `Until ${endDate.toLocaleDateString()}`;
                }
            }
            
            // Update earnings summary with filtered data
            updateEarningsSummary(filteredData);
            
            // Update no results message visibility
            if (noFilteredServices) {
                noFilteredServices.style.display = visibleCount > 0 ? 'none' : 'block';
            }
            
            // Update chart if it exists
            if (earningsChart && filteredData.length > 0) {
                const labels = filteredData.map(data => data.name || 'Unknown');
                const values = filteredData.map(data => parseFloat(data.totalEarnings) || 0);
                
                earningsChart.data.labels = labels;
                earningsChart.data.datasets[0].data = values;
                earningsChart.update();
                console.log('Chart updated with filtered data:', filteredData);
            } else if (earningsChart) {
                earningsChart.data.labels = [];
                earningsChart.data.datasets[0].data = [];
                earningsChart.update();
                console.log('Chart cleared - no filtered data');
            }
        }
        
        // Quick filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const period = this.getAttribute('data-period');
                let startDate = null;
                let endDate = new Date(); // today
                
                // Set date range based on period
                switch(period) {
                    case 'week':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - 7);
                        filteredResultsInfo.textContent = 'Showing services from the last week';
                        break;
                    case 'month':
                        startDate = new Date();
                        startDate.setMonth(startDate.getMonth() - 1);
                        filteredResultsInfo.textContent = 'Showing services from the last month';
                        break;
                    case 'quarter':
                        startDate = new Date();
                        startDate.setMonth(startDate.getMonth() - 3);
                        filteredResultsInfo.textContent = 'Showing services from the last 3 months';
                        break;
                    case 'year':
                        startDate = new Date();
                        startDate.setFullYear(startDate.getFullYear() - 1);
                        filteredResultsInfo.textContent = 'Showing services from the last year';
                        break;
                    default:
                        // All time - no filtering
                        startDate = null;
                        endDate = null;
                        filteredResultsInfo.textContent = 'Showing all completed services';
                }
                
                // Update date inputs to reflect selected period
                if (startDate) {
                    startDateInput.value = startDate.toISOString().split('T')[0];
                } else {
                    startDateInput.value = '';
                }
                
                if (endDate) {
                    endDateInput.value = endDate.toISOString().split('T')[0];
                } else {
                    endDateInput.value = '';
                }
                
                // Apply filtering
                filterServices(startDate, endDate);
                
                // Set text for the earnings time period based on filter
                switch(period) {
                    case 'week':
                        document.getElementById('earnings-time-period').textContent = 'Last 7 Days';
                        break;
                    case 'month':
                        document.getElementById('earnings-time-period').textContent = 'Last 30 Days';
                        break;
                    case 'quarter':
                        document.getElementById('earnings-time-period').textContent = 'Last 3 Months';
                        break;
                    case 'year':
                        document.getElementById('earnings-time-period').textContent = 'Last Year';
                        break;
                    default:
                        document.getElementById('earnings-time-period').textContent = 'All Time';
                }
            });
        });
        
        // Apply custom date filter
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function() {
                if (!startDateInput.value && !endDateInput.value) {
                    // If both inputs are empty, show all
                    filterButtons.forEach(btn => {
                        if (btn.getAttribute('data-period') === 'all') {
                            btn.click();
                        }
                    });
                    return;
                }
                
                // Clear active state from quick filters
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                const startDate = startDateInput.value ? parseDate(startDateInput.value) : null;
                const endDate = endDateInput.value ? parseDate(endDateInput.value) : null;
                
                if (startDate && endDate && startDate > endDate) {
                    alert('Start date cannot be after end date');
                    return;
                }
                
                // Format dates for display
                const startStr = startDate ? startDate.toLocaleDateString() : 'earliest';
                const endStr = endDate ? endDate.toLocaleDateString() : 'latest';
                filteredResultsInfo.textContent = `Showing services from ${startStr} to ${endStr}`;
                
                // Apply filtering
                filterServices(startDate, endDate);
            });
        }
        
        // Initialize with "All Time" filter
        if (filterButtons.length > 0) {
            const allTimeButton = document.querySelector('.filter-btn[data-period="all"]');
            if (allTimeButton) {
                allTimeButton.click();
            } else {
                filterServices(null, null);
            }
        }
        
        // Add search functionality
        const searchInput = document.getElementById('service-search');
        const searchBtn = document.getElementById('search-btn');
        
        // Handle search button click
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                performSearch(searchInput.value);
            });
        }
        
        // Handle Enter key in search input
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch(searchInput.value);
                }
            });
        }
        
        // Replace the existing performSearch function with this fixed version
        function performSearch(query) {
            query = query.trim().toLowerCase();
            
            // First, reset all cards to respect only date filters
            const currentFilter = document.querySelector('.filter-btn.active');
            const startDate = startDateInput.value ? parseDate(startDateInput.value) : null;
            const endDate = endDateInput.value ? parseDate(endDateInput.value) : null;
            
            // Reset cards based on current date filter only (not previous search results)
            const allCards = document.querySelectorAll('.service-card');
            allCards.forEach(card => {
                const cardDateStr = card.getAttribute('data-date');
                const cardDate = parseDate(cardDateStr);
                
                // Apply only date filtering first
                if ((!startDate && !endDate) || 
                    (cardDate && 
                     (!startDate || cardDate >= startDate) && 
                     (!endDate || cardDate <= endDate))) {
                    card.style.display = 'block'; // Make all date-valid cards visible
                } else {
                    card.style.display = 'none';
                }
            });
            
            // If empty query, just show all cards that match date filter
            if (query === '') {
                let visibleCount = 0;
                let filteredData = [];
                
                allCards.forEach((card, index) => {
                    if (card.style.display === 'block') {
                        visibleCount++;
                        if (chartData[index]) {
                            filteredData.push(chartData[index]);
                        }
                    }
                });
                
                // Update display
                updateResultsInfo(currentFilter);
                updateEarningsSummary(filteredData);
                updateChart(filteredData);
                return;
            }
            
            // Then apply search filtering on the visible cards
            let visibleCount = 0;
            let filteredData = [];
            
            allCards.forEach((card, index) => {
                if (card.style.display === 'block') { // Only search among date-filtered cards
                    // Get searchable text from card
                    const serviceName = card.getAttribute('data-name') || '';
                    const propertyName = card.getAttribute('data-property') || '';
                    const cardText = (serviceName + ' ' + propertyName).toLowerCase();
                    
                    // Check if card matches search
                    const matches = cardText.includes(query);
                    
                    if (matches) {
                        // Keep it visible
                        visibleCount++;
                        if (chartData[index]) {
                            filteredData.push(chartData[index]);
                        }
                        
                        // Highlight matching text
                        highlightMatches(card, query);
                    } else {
                        card.style.display = 'none'; // Hide non-matching cards
                    }
                }
            });
            
            // Update results info
            document.getElementById('filtered-results-info').textContent = 
                `Found ${visibleCount} services matching "${query}"`;
            
            // Show no results message if needed
            document.getElementById('no-filtered-services').style.display = 
                visibleCount > 0 ? 'none' : 'block';
            
            // Update chart and earnings summary
            updateChart(filteredData);
            updateEarningsSummary(filteredData);
        }
        
        // Function to highlight matching text
        function highlightMatches(card, query) {
            // Remove any existing highlights first
            const highlights = card.querySelectorAll('.highlight');
            highlights.forEach(el => {
                el.outerHTML = el.innerHTML;
            });
            
            // Find text nodes that match the query
            const elements = [
                card.querySelector('h3'),
                card.querySelector('.property-info span')
            ];
            
            elements.forEach(element => {
                if (!element) return;
                
                const text = element.innerHTML;
                const regex = new RegExp('(' + query + ')', 'gi');
                
                if (text.toLowerCase().includes(query.toLowerCase())) {
                    element.innerHTML = text.replace(regex, '<span class="highlight">$1</span>');
                }
            });
        }
        
        // Add this helper function to update the chart
        function updateChart(filteredData) {
            if (earningsChart && filteredData.length > 0) {
                const labels = filteredData.map(data => data.name || 'Unknown');
                const values = filteredData.map(data => parseFloat(data.totalEarnings) || 0);
                
                earningsChart.data.labels = labels;
                earningsChart.data.datasets[0].data = values;
                earningsChart.update();
            } else if (earningsChart) {
                earningsChart.data.labels = [];
                earningsChart.data.datasets[0].data = [];
                earningsChart.update();
            }
        }
        
        // Add this helper function to update results info based on filter
        function updateResultsInfo(activeFilter) {
            if (!activeFilter) return;
            
            const period = activeFilter.getAttribute('data-period');
            switch(period) {
                case 'week':
                    filteredResultsInfo.textContent = 'Showing services from the last week';
                    break;
                case 'month':
                    filteredResultsInfo.textContent = 'Showing services from the last month';
                    break;
                case 'quarter':
                    filteredResultsInfo.textContent = 'Showing services from the last 3 months';
                    break;
                case 'year':
                    filteredResultsInfo.textContent = 'Showing services from the last year';
                    break;
                default:
                    filteredResultsInfo.textContent = 'Showing all completed services';
            }
        }
        
        // Service Summary button functionality
        const summaryButtons = document.querySelectorAll('.service-summary-btn');
        const modal = document.getElementById('service-modal');
        const closeBtn = document.querySelector('.close-modal');
        
        summaryButtons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                
                const serviceIndex = parseInt(button.getAttribute('data-service-index')) || index;
                const serviceData = chartData[serviceIndex];
                
                // Populate modal with service data
                document.getElementById('modal-service-name').textContent = serviceData.name || 'Service Details';
                document.getElementById('modal-service-type').textContent = serviceData.name || 'Unknown';
                document.getElementById('modal-property').textContent = serviceData.property_name || 'Not available';
                document.getElementById('modal-date').textContent = serviceData.date ? 
                    new Date(serviceData.date).toLocaleDateString() : 'Not available';
                document.getElementById('modal-hours').textContent = (serviceData.total_hours || 0) + ' hours';
                document.getElementById('modal-earnings').textContent = 'LKR ' + 
                    parseFloat(serviceData.totalEarnings).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                
                // Display service provider description
                const descriptionElement = document.getElementById('modal-description');
                if (descriptionElement) {
                    if (serviceData.service_provider_description) {
                        descriptionElement.textContent = serviceData.service_provider_description;
                    } else {
                        descriptionElement.textContent = 'No description provided for this service.';
                    }
                }
                
                // Set image
                const card = button.closest('.service-card');
                const cardImage = card.querySelector('.service-image img');
                const modalImage = document.getElementById('modal-image');
                
                if (cardImage && modalImage) {
                    modalImage.src = cardImage.src;
                } else {
                    modalImage.src = '<?= ROOT ?>/assets/images/service-placeholder.png';
                }
                
                // Show modal
                modal.style.display = 'block';
            });
        });
        
        // Close modal functionality
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        });
        
        // Initialize earnings summary on page load
        updateEarningsSummary(chartData);
    });
</script>

<?php require 'serviceproviderFooter.view.php' ?>
