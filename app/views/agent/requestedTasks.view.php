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
        // Assuming you have repair tasks data from database
        $repairTasks = []; // Replace with actual data fetch
        
        if (empty($repairTasks)) {
            // Sample data for demonstration
            for ($i = 0; $i < 5; $i++) {
                $date = date('Y-m-d', strtotime("-$i months"));
                echo "<div class='repair-card-box' data-date='$date'>";
                require __DIR__ . '/../components/repairCard.php';
                echo "</div>";
            }
        } else {
            foreach ($repairTasks as $task) {
                echo "<div class='repair-card-box' data-date='{$task['date']}'>";
                require __DIR__ . '/../components/repairCard.php';
                echo "</div>";
            }
        }
        ?>
    </div>
</div>