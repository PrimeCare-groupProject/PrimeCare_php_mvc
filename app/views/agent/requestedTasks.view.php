<div class="user_view-menu-bar">
    <h2>Requested Tasks</h2>
</div>
<div>
    <div class="date-filter-container">
      <div class="input-group-aligned width-100">
        <label for="year-sort" class="date-label">Year:</label>
        <select id="year-filter" class="date-input-field-small">
          <option value="all">All</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
          <!-- Add more years as needed -->
        </select>
      </div>
      <div class="input-group-aligned width-100">
        <label for="month-filter" class="date-label">Month:</label>
        <select id="month-filter" class="date-input-field-small">
          <option value="all">All</option>
          <option value="January">January</option>
          <option value="February">February</option>
          <option value="March">March</option>
          <option value="April">April</option>
          <option value="May">May</option>
          <option value="June">June</option>
          <option value="July">July</option>
          <option value="August">August</option>
          <option value="September">September</option>
          <option value="October">October</option>
          <option value="November">November</option>
          <option value="December">December</option>
        </select>
      </div>
      <div class="input-group-aligned width-100">
        <button id="sort-time-button" class="sort-time-button">Sort</button>
      </div>
    </div>

    <script>
      document.getElementById('sort-time-button').addEventListener('click', function() {
        const yearFilter = document.getElementById('year-filter').value;
        const monthFilter = document.getElementById('month-filter').value;
        
        let container = document.querySelector('.repair-cards-container');
        let cards = Array.from(container.children);
        
        // First, show all cards
        cards.forEach(card => card.style.display = 'block');
        
        // Filter cards
        cards.forEach(card => {
          const dateStr = card.getAttribute('data-date');
          if (!dateStr) return; // Skip if no date attribute
          
          const date = new Date(dateStr);
          let shouldShow = true;
          
          // Year filter
          if (yearFilter !== 'all') {
            if (date.getFullYear() !== parseInt(yearFilter)) {
              shouldShow = false;
            }
          }
          
          // Month filter
          if (monthFilter !== 'all' && shouldShow) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
            if (monthNames[date.getMonth()] !== monthFilter) {
              shouldShow = false;
            }
          }
          
          card.style.display = shouldShow ? 'block' : 'none';
        });
        
        // Sort visible cards
        const visibleCards = cards.filter(card => card.style.display !== 'none');
        visibleCards.sort((a, b) => {
          const dateA = new Date(a.getAttribute('data-date'));
          const dateB = new Date(b.getAttribute('data-date'));
          return dateB - dateA; // Sort in descending order (newest first)
        });
        
        // Re-append sorted cards
        visibleCards.forEach(card => container.appendChild(card));
      });
    </script>
    <div class="repair-cards-container">
        <?php require __DIR__ . '/repairCard.php' ?>
        <?php require __DIR__ . '/repairCard.php' ?>
        <?php require __DIR__ . '/repairCard.php' ?>
        <?php require __DIR__ . '/repairCard.php' ?>
        <?php require __DIR__ . '/repairCard.php' ?>
    </div>
</div>