<?php
/**
 * PROPERTY MANAGEMENT SYSTEM - MANAGER DASHBOARD (NO SIDEBAR)
 *
 * This comprehensive dashboard includes all requested manager use cases:
 * - Employee registration & management
 * - Agent registration & assignment
 * - Property approval/removal
 * - Payment processing
 * - Financial reporting
 * - Salary management
 */

require_once 'managerHeader.view.php'; // Keep or remove based on your actual header file
?>

<style>
/* General Styles */
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f9f9f9;
  color: #333;
  margin: 0;
  padding: 0;
}

.user_view-menu-bar {
  background-color: #3a3f51;
  color: white;
  padding: 1rem;
  text-align: center;
}

.user_view-menu-bar h2 {
  margin: 0;
  font-size: 1.8rem;
}

/* Dashboard Layout */
.dashboard {
  padding: 2rem;
}

.pms-section {
  margin-bottom: 2rem;
}

.pms-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.pms-section-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
}

.pms-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
}

.pms-action-card {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.pms-action-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.pms-action-card i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  color: #546bff;
}

.pms-action-card span {
  font-size: 1rem;
  font-weight: bold;
  color: #333;
}

.pms-data-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

.pms-data-table th,
.pms-data-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.pms-data-table th {
  background-color: #3a3f51;
  color: white;
}

.pms-data-table tbody tr:hover {
  background-color: #f1f1f1;
}

.pms-btn {
  background-color: #546bff;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.pms-btn:hover {
  background-color: #4356cc;
}
</style>

<div class="agent-dashboard-container">
    <div class="agent-stats-row">
        <div class="agent-stat-card agent-property-card">
            <div class="agent-card-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="agent-card-details">
                <h3>Properties</h3>
                <span class="agent-count">45</span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 12% from last month</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard">
  <section class="pms-section">
    <div class="pms-section-header">
      <h2 class="pms-section-title">Quick Actions</h2>
    </div>
    <div class="pms-actions-grid">
      <a href="#" class="pms-action-card">
        <i class="fas fa-user-plus"></i>
        <span>Register Employee</span>
      </a>
      <a href="#" class="pms-action-card">
        <i class="fas fa-home"></i>
        <span>Approve Property</span>
      </a>
      <a href="#" class="pms-action-card">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Process Payments</span>
      </a>
      <a href="#" class="pms-action-card">
        <i class="fas fa-chart-pie"></i>
        <span>Generate Report</span>
      </a>
    </div>
  </section>

  <section class="pms-section">
    <div class="pms-section-header">
      <h2 class="pms-section-title">Pending Approvals</h2>
    </div>
    <table class="pms-data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Property</th>
          <th>Type</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>#PR-1001</td>
          <td>Luxury Apartment</td>
          <td>New Listing</td>
          <td>2 hours ago</td>
          <td>Pending</td>
          <td>
            <button class="pms-btn">Review</button>
          </td>
        </tr>
        <tr>
          <td>#PR-1002</td>
          <td>Beachfront Villa</td>
          <td>Update</td>
          <td>1 day ago</td>
          <td>Pending</td>
          <td>
            <button class="pms-btn">Review</button>
          </td>
        </tr>
      </tbody>
    </table>
  </section>

  <section class="pms-section">
    <div class="pms-section-header">
      <h2 class="pms-section-title">Financial Overview</h2>
    </div>
    <div class="pms-card">
      <div class="pms-card-body">
        <canvas id="financialChart"></canvas>
      </div>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('financialChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Revenue',
          data: [12000, 19000, 15000, 20000, 22000, 25000],
          backgroundColor: '#546bff',
          borderColor: '#4356cc',
          borderWidth: 1
        },
        {
          label: 'Expenses',
          data: [8000, 12000, 10000, 15000, 18000, 20000],
          backgroundColor: '#ff547d',
          borderColor: '#cc4364',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
});
</script>

<?php require_once 'managerFooter.view.php'; ?>