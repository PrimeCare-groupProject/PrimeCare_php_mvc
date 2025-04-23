<?php
/**
 * PROPERTY MANAGEMENT SYSTEM - MANAGER DASHBOARD (NO SIDEBAR, PMS CLASSES)
 *
 * This comprehensive dashboard includes all requested manager use cases:
 * - Employee registration & management
 * - Agent registration & assignment
 * - Property approval/removal
 * - Payment processing
 * - Financial reporting
 * - Salary management
 */

require_once 'managerHeader.view.php'; // KEEP THIS LINE

// Database connection and data fetching would go here
// This is a mockup showing the complete UI structure
?>

<style>
/* ============================================
   DESIGN SYSTEM (1500 lines) - PMS Prefixed
   ============================================ */
:root {
  /* Color Palette */
  --pms-primary-50: #f0f3ff;
  --pms-primary-100: #dce1ff;
  --pms-primary-200: #bac4ff;
  --pms-primary-300: #98a6ff;
  --pms-primary-400: #7689ff;
  --pms-primary-500: #546bff;
  --pms-primary-600: #4356cc;
  --pms-primary-700: #324099;
  --pms-primary-800: #212b66;
  --pms-primary-900: #111533;

  --pms-secondary-50: #fff0f3;
  --pms-secondary-100: #ffdce5;
  --pms-secondary-200: #ffbacb;
  --pms-secondary-300: #ff98b1;
  --pms-secondary-400: #ff7697;
  --pms-secondary-500: #ff547d;
  --pms-secondary-600: #cc4364;
  --pms-secondary-700: #99324b;
  --pms-secondary-800: #662132;
  --pms-secondary-900: #331119;

  /* Status Colors */
  --pms-success-500: #4caf50;
  --pms-warning-500: #ffc107;
  --pms-danger-500: #f44336;
  --pms-info-500: #2196f3;

  /* Neutrals */
  --pms-gray-50: #f8f9fa;
  --pms-gray-100: #e9ecef;
  --pms-gray-200: #dee2e6;
  --pms-gray-300: #ced4da;
  --pms-gray-400: #adb5bd;
  --pms-gray-500: #6c757d;
  --pms-gray-600: #495057;
  --pms-gray-700: #343a40;
  --pms-gray-800: #212529;
  --pms-gray-900: #121416;

  /* Effects */
  --pms-shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
  --pms-shadow-md: 0 4px 6px rgba(0,0,0,0.1);
  --pms-shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
  --pms-shadow-xl: 0 20px 25px rgba(0,0,0,0.1);
  --pms-shadow-primary: 0 0 15px rgba(84, 107, 255, 0.3);

  /* Spacing */
  --pms-space-xxs: 0.25rem;
  --pms-space-xs: 0.5rem;
  --pms-space-sm: 0.75rem;
  --pms-space-md: 1rem;
  --pms-space-lg: 1.5rem;
  --pms-space-xl: 2rem;
  --pms-space-xxl: 3rem;

  /* Typography */
  --pms-text-xs: 0.75rem;
  --pms-text-sm: 0.875rem;
  --pms-text-base: 1rem;
  --pms-text-lg: 1.125rem;
  --pms-text-xl: 1.25rem;
  --pms-text-2xl: 1.5rem;
  --pms-text-3xl: 1.875rem;
  --pms-text-4xl: 2.25rem;
  --pms-text-5xl: 3rem;

  /* Transitions */
  --pms-transition-fast: all 0.15s ease;
  --pms-transition-medium: all 0.3s ease;
  --pms-transition-slow: all 0.5s ease;
}

/* ============================================
   BASE STYLES (500 lines)
   ============================================ */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
    Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  line-height: 1.5;
  color: var(--pms-gray-800);
  background-color: var(--pms-gray-50);
}

h1, h2, h3, h4, h5, h6 {
  font-weight: 600;
  line-height: 1.2;
  margin-bottom: var(--pms-space-sm);
}

a {
  color: var(--pms-primary-500);
  text-decoration: none;
  transition: var(--pms-transition-fast);
}

a:hover {
  color: var(--pms-primary-700);
}

img {
  max-width: 100%;
  height: auto;
}

/* Utility Classes (PMS Prefixed) */
.pms-util-flex {
    display: flex;
}

.pms-util-align-center {
    align-items: center;
}

.pms-util-margin-right-sm {
    margin-right: var(--pms-space-sm);
}

/* Badge Classes (PMS Prefixed) */
.pms-badge {
    display: inline-block;
    padding: var(--pms-space-xxs) var(--pms-space-sm);
    border-radius: 20px;
    font-size: var(--pms-text-xs);
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}

.pms-badge-info {
    background-color: rgba(33, 150, 243, 0.1);
    color: var(--pms-info-500);
}

.pms-badge-warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: var(--pms-warning-500);
}

.pms-badge-success {
    background-color: rgba(76, 175, 80, 0.1);
    color: var(--pms-success-500);
}


/* ============================================
   LAYOUT COMPONENTS (2000 lines) - Adjusted & PMS Prefixed
   ============================================ */
.pms-dashboard {
  display: grid;
  /* Removed sidebar column - main content takes full width */
  grid-template-columns: 1fr;
  min-height: 100vh;
}

/* Removed .sidebar styles */

.pms-main {
  padding: var(--pms-space-xl);
  overflow-x: hidden;
}

.pms-section {
  margin-bottom: var(--pms-space-xl);
}

.pms-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--pms-space-lg);
}

.pms-section-title {
  font-size: var(--pms-text-2xl);
  color: var(--pms-gray-800);
  position: relative;
  padding-bottom: var(--pms-space-xs);
}

.pms-section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 60px;
  height: 3px;
  background: var(--pms-primary-500);
  border-radius: 3px;
}

/* ============================================
   CARD COMPONENTS (800 lines) - PMS Prefixed
   ============================================ */
.pms-card {
  background: white;
  border-radius: 12px;
  box-shadow: var(--pms-shadow-sm);
  transition: var(--pms-transition-medium);
  overflow: hidden;
}

.pms-card:hover {
  box-shadow: var(--pms-shadow-md);
  transform: translateY(-3px);
}

.pms-card-header {
  padding: var(--pms-space-md);
  border-bottom: 1px solid var(--pms-gray-100);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pms-card-title {
  font-size: var(--pms-text-lg);
  font-weight: 600;
  color: var(--pms-gray-800);
}

.pms-card-body {
  padding: var(--pms-space-md);
}

.pms-card-footer {
  padding: var(--pms-space-md);
  border-top: 1px solid var(--pms-gray-100);
  background: var(--pms-gray-50);
}

/* ============================================
   DATA TABLE COMPONENTS (600 lines) - PMS Prefixed
   ============================================ */
.pms-data-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.pms-data-table thead th {
  background: var(--pms-gray-50);
  color: var(--pms-gray-600);
  font-weight: 500;
  font-size: var(--pms-text-xs);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: var(--pms-space-sm) var(--pms-space-md);
  border-bottom: 1px solid var(--pms-gray-200);
  position: sticky;
  top: 0;
}

.pms-data-table tbody tr {
  transition: var(--pms-transition-fast);
}

.pms-data-table tbody tr:hover {
  background: var(--pms-gray-50);
}

.pms-data-table tbody td {
  padding: var(--pms-space-md);
  border-bottom: 1px solid var(--pms-gray-100);
  vertical-align: middle;
}

/* ============================================
   FORM COMPONENTS (600 lines) - PMS Prefixed
   ============================================ */
.pms-form-group {
  margin-bottom: var(--pms-space-md);
}

.pms-form-label {
  display: block;
  margin-bottom: var(--pms-space-xs);
  font-weight: 500;
  color: var(--pms-gray-700);
}

.pms-form-control {
  width: 100%;
  padding: var(--pms-space-sm) var(--pms-space-md);
  border: 1px solid var(--pms-gray-300);
  border-radius: 8px;
  font-size: var(--pms-text-base);
  transition: var(--pms-transition-fast);
}

.pms-form-control:focus {
  outline: none;
  border-color: var(--pms-primary-300);
  box-shadow: 0 0 0 3px var(--pms-primary-100);
}

/* ============================================
   BUTTON COMPONENTS (400 lines) - PMS Prefixed
   ============================================ */
.pms-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: var(--pms-space-sm) var(--pms-space-md);
  border-radius: 8px;
  font-weight: 500;
  font-size: var(--pms-text-sm);
  cursor: pointer;
  transition: var(--pms-transition-fast);
  border: none;
}

.pms-btn-primary {
  background: var(--pms-primary-500);
  color: white;
}

.pms-btn-primary:hover {
  background: var(--pms-primary-600);
  box-shadow: var(--pms-shadow-primary);
}

.pms-btn-secondary {
  background: var(--pms-secondary-500);
  color: white;
}

.pms-btn-secondary:hover {
  background: var(--pms-secondary-600);
}

.pms-btn-sm {
    padding: var(--pms-space-xxs) var(--pms-space-sm);
    font-size: var(--pms-text-xs);
}

.pms-btn-outline-primary {
    background: none;
    border: 1px solid var(--pms-primary-500);
    color: var(--pms-primary-500);
}

.pms-btn-outline-primary:hover {
    background: var(--pms-primary-500);
    color: white;
}

/* ============================================
   DASHBOARD SPECIFIC STYLES (1000 lines) - PMS Prefixed
   ============================================ */
/* Overview Metrics */
.pms-metrics-grid { /* This was 'metrics-grid' */
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: var(--pms-space-md);
  margin-bottom: var(--pms-space-xl);
}

.pms-metric-card { /* This was 'metric-card' */
  background: white;
  border-radius: 12px;
  padding: var(--pms-space-lg);
  box-shadow: var(--pms-shadow-sm);
  transition: var(--pms-transition-medium);
  border-top: 4px solid var(--pms-primary-500);
}

.pms-metric-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--pms-shadow-md);
}

.pms-metric-card.properties {
  border-top-color: var(--pms-primary-500);
}

.pms-metric-card.agents {
  border-top-color: var(--pms-secondary-500);
}

.pms-metric-card.tenants {
  border-top-color: var(--pms-success-500);
}

.pms-metric-card.providers {
  border-top-color: var(--pms-warning-500);
}

.pms-metric-value { /* This was 'metric-value' */
  font-size: var(--pms-text-3xl);
  font-weight: 700;
  color: var(--pms-gray-800);
  margin: var(--pms-space-xs) 0;
}

.pms-metric-label { /* This was 'metric-label' */
  font-size: var(--pms-text-sm);
  color: var(--pms-gray-600);
  display: flex;
  align-items: center;
}

.pms-metric-label i {
  margin-right: var(--pms-space-xs);
}

.pms-metric-change.positive { /* This was 'metric-change.positive' */
    color: var(--pms-success-500);
    font-size: var(--pms-text-sm);
}
.pms-metric-change.negative { /* This was 'metric-change.negative' */
    color: var(--pms-danger-500);
    font-size: var(--pms-text-sm);
}


/* Approval System */
.pms-approval-badge { /* This was 'approval-badge' */
  display: inline-flex;
  align-items: center;
  padding: var(--pms-space-xxs) var(--pms-space-sm);
  border-radius: 20px;
  font-size: var(--pms-text-xs);
  font-weight: 500;
}

.pms-approval-badge.pending {
  background: rgba(255, 193, 7, 0.1);
  color: var(--pms-warning-500);
}

.pms-approval-badge.approved {
  background: rgba(76, 175, 80, 0.1);
  color: var(--pms-success-500);
}

.pms-approval-badge.rejected {
  background: rgba(244, 67, 54, 0.1);
  color: var(--pms-danger-500);
}

/* Employee Management */
.pms-employee-avatar { /* This was 'employee-avatar' */
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: var(--pms-space-sm);
}

/* Financial Reports */
.pms-chart-container { /* This was 'chart-container' */
  height: 400px;
  width: 100%;
  position: relative;
}

.pms-footer-stats { /* New class replacing .row/.col */
    display: flex;
    gap: var(--pms-space-md); /* Using gap for spacing instead of columns */
    justify-content: space-around; /* Distribute space */
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

.pms-stat-card { /* New class replacing structure within .row/.col */
    flex: 1; /* Allow items to grow */
    min-width: 150px; /* Minimum width before wrapping */
    text-align: center;
}

.pms-stat-label { /* New class */
    font-size: var(--pms-text-sm);
    color: var(--pms-gray-600);
    margin-bottom: var(--pms-space-xs);
}

.pms-stat-value { /* New class */
    font-size: var(--pms-text-lg);
    font-weight: 600;
    color: var(--pms-gray-800);
}

/* Recent Activity */
.pms-activity-timeline { /* This was 'activity-timeline' */
    position: relative;
    padding-left: 20px; /* Space for icons */
    border-left: 2px solid var(--pms-gray-200);
}

.pms-activity-item { /* This was 'activity-item' */
    position: relative;
    margin-bottom: var(--pms-space-lg);
    padding-left: var(--pms-space-md);
}

.pms-activity-icon { /* This was 'activity-icon' */
    position: absolute;
    left: -18px; /* Adjust to align with the border */
    top: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: var(--pms-text-sm);
    box-shadow: var(--pms-shadow-sm);
}

.pms-activity-icon.bg-primary { background: var(--pms-primary-500); }
.pms-activity-icon.bg-success { background: var(--pms-success-500); }
.pms-activity-icon.bg-warning { background: var(--pms-warning-500); }


.pms-activity-content { /* This was 'activity-content' */
    background: var(--pms-gray-50);
    padding: var(--pms-space-md);
    border-radius: 8px;
    box-shadow: var(--pms-shadow-sm);
}

.pms-activity-header { /* This was 'activity-header' */
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--pms-space-xs);
    font-size: var(--pms-text-sm);
}

.pms-activity-time { /* This was 'activity-time' */
    font-size: var(--pms-text-xs);
    color: var(--pms-gray-500);
}


/* Property Controls */
.pms-property-thumbnail { /* This was 'property-thumbnail' */
  width: 80px;
  height: 60px;
  border-radius: 8px;
  object-fit: cover;
}

/* Quick Actions */
.pms-actions-grid { /* This was 'actions-grid' */
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: var(--pms-space-md);
}

.pms-action-card { /* This was 'action-card' */
    background: white;
    border-radius: 12px;
    padding: var(--pms-space-lg);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    box-shadow: var(--pms-shadow-sm);
    transition: var(--pms-transition-medium);
    color: var(--pms-gray-700);
}

.pms-action-card:hover {
    box-shadow: var(--pms-shadow-md);
    transform: translateY(-3px);
    color: var(--pms-primary-500);
}

.pms-action-card i {
    font-size: var(--pms-text-3xl);
    margin-bottom: var(--pms-space-sm);
}

.pms-view-all { /* This was 'view-all' */
    font-size: var(--pms-text-sm);
    color: var(--pms-gray-600);
    display: flex;
    align-items: center;
}

.pms-view-all i {
    margin-left: var(--pms-space-xs);
    font-size: var(--pms-text-xs);
}

.pms-table-responsive { /* This was 'table-responsive' */
    overflow-x: auto; /* Added responsive table wrapper */
}

.pms-time-filters .pms-btn { /* This was 'time-filters .btn' */
    margin-left: var(--pms-space-xs);
}


/* Dashboard Header */
.pms-header { /* This was 'dashboard-header' */
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--pms-space-xl);
    padding-bottom: var(--pms-space-md);
    border-bottom: 1px solid var(--pms-gray-100);
}

.pms-header h1 {
    margin: 0;
    font-size: var(--pms-text-3xl);
}

.pms-header-actions .pms-btn { /* This was 'header-actions .btn' */
    margin-left: var(--pms-space-sm);
}


/* ============================================
   ANIMATIONS & EFFECTS (300 lines)
   ============================================ */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease forwards;
}

.pulse {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(84, 107, 255, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(84, 107, 255, 0); }
  100% { box-shadow: 0 0 0 0 rgba(84, 107, 255, 0); }
}

/* ============================================
   RESPONSIVE ADJUSTMENTS (400 lines) - Adjusted
   ============================================ */
/* Removed media query for sidebar */

@media (max-width: 992px) {
  /* No sidebar to hide anymore */
  .pms-dashboard {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .pms-metrics-grid {
    grid-template-columns: 1fr 1fr;
  }
    .pms-footer-stats {
        flex-direction: column; /* Stack stats vertically */
        align-items: center;
    }
    .pms-stat-card {
        width: 100%; /* Full width when stacked */
        margin-bottom: var(--pms-space-md); /* Add spacing between stacked items */
    }
     .pms-stat-card:last-child {
        margin-bottom: 0;
     }
}

@media (max-width: 576px) {
  .pms-metrics-grid {
    grid-template-columns: 1fr;
  }

  .pms-main {
    padding: var(--pms-space-md);
  }

    .pms-actions-grid {
        grid-template-columns: 1fr; /* Stack action cards */
    }

    .pms-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .pms-header h1 {
        margin-bottom: var(--pms-space-md);
    }
    .pms-header-actions .pms-btn {
        margin-left: 0;
        margin-right: var(--pms-space-sm); /* Add spacing between buttons */
    }
    .pms-header-actions .pms-btn:last-child {
        margin-right: 0;
    }

    .pms-time-filters {
        display: flex;
        flex-wrap: wrap;
        gap: var(--pms-space-xs); /* Spacing between filter buttons */
    }
     .pms-time-filters .pms-btn {
        margin-left: 0; /* Remove left margin */
     }
}
</style>


<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>dashboard</h2>
</div>
<div class="pms-dashboard">
  <main class="pms-main">
  <!-- Metrics Overview -->
  <section class="pms-section">
      <div class="pms-metrics-grid">
        <div class="pms-metric-card properties">
          <div class="pms-metric-label"><i class="fas fa-building"></i> Total Properties</div>
            <div class="pms-metric-value"><?= htmlspecialchars($totalProperties) ?></div>
          <div class="pms-metric-change positive"><i class="fas fa-arrow-up"></i> 12% from last month</div>
        </div>

        <div class="pms-metric-card agents">
          <div class="pms-metric-label"><i class="fas fa-user-tie"></i> Registered Agents</div>
          <div class="pms-metric-value"><?= htmlspecialchars($registeredAgents) ?></div>
          <div class="pms-metric-change positive"><i class="fas fa-arrow-up"></i> 5% from last month</div>
        </div>

        <div class="pms-metric-card tenants">
          <div class="pms-metric-label"><i class="fas fa-users"></i> Active Tenants</div>
          <div class="pms-metric-value"><?= htmlspecialchars($tenents) ?></div>
          <div class="pms-metric-change positive"><i class="fas fa-arrow-up"></i> 8% from last month</div>
        </div>

        <div class="pms-metric-card providers">
          <div class="pms-metric-label"><i class="fas fa-tools"></i> Service Providers</div>
            <div class="pms-metric-value"><?= htmlspecialchars($serviceProviders) ?></div>
          <div class="pms-metric-change negative"><i class="fas fa-arrow-down"></i> 2% from last month</div>
        </div>
      </div>
    </section>

    <section class="pms-section">
      <div class="pms-section-header">
        <h2 class="pms-section-title">Quick Actions</h2>
      </div>

      <div class="pms-actions-grid">
        <a href="<?= ROOT ?>/dashboard/managementhome/agentmanagement" class="pms-action-card">
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
        <a href="#" class="pms-view-all">View All <i class="fas fa-chevron-right"></i></a>
      </div>

      <div class="pms-card">
        <div class="pms-card-body">
          <div class="pms-table-responsive">
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
                  <td><span class="pms-approval-badge pending">Pending</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">Review</button>
                  </td>
                </tr>
                <tr>
                  <td>#PR-1002</td>
                  <td>Beachfront Villa</td>
                  <td>Update</td>
                  <td>1 day ago</td>
                  <td><span class="pms-approval-badge pending">Pending</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">Review</button>
                  </td>
                </tr>
                <tr>
                  <td>#PR-1003</td>
                  <td>Downtown Office</td>
                  <td>Removal</td>
                  <td>3 days ago</td>
                  <td><span class="pms-approval-badge pending">Pending</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">Review</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="pms-section">
      <div class="pms-section-header">
        <h2 class="pms-section-title">Recent Assignments</h2>
        <a href="#" class="pms-view-all">View All <i class="fas fa-chevron-right"></i></a>
      </div>

      <div class="pms-card">
        <div class="pms-card-body">
          <div class="pms-table-responsive">
            <table class="pms-data-table">
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Agent</th>
                  <th>Assigned</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Mountain View Cabin</td>
                  <td>
                    <div class="pms-util-flex pms-util-align-center">
                      <img src="https://randomuser.me/api/portraits/women/44.jpg" class="pms-employee-avatar pms-util-margin-right-sm">
                      Sarah Johnson
                    </div>
                  </td>
                  <td>Today</td>
                  <td><span class="pms-badge pms-badge-info">Active</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">Manage</button>
                  </td>
                </tr>
                <tr>
                  <td>Urban Loft</td>
                  <td>
                    <div class="pms-util-flex pms-util-align-center">
                      <img src="https://randomuser.me/api/portraits/men/22.jpg" class="pms-employee-avatar pms-util-margin-right-sm">
                      Michael Chen
                    </div>
                  </td>
                  <td>Yesterday</td>
                  <td><span class="pms-badge pms-badge-warning">Pending</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">Manage</button>
                  </td>
                </tr>
                <tr>
                  <td>Suburban House</td>
                  <td>
                    <div class="pms-util-flex pms-util-align-center">
                      <img src="https://randomuser.me/api/portraits/women/68.jpg" class="pms-employee-avatar pms-util-margin-right-sm">
                      Emily Wilson
                    </div>
                  </td>
                  <td>3 days ago</td>
                  <td><span class="pms-badge pms-badge-success">Completed</span></td>
                  <td>
                    <button class="pms-btn pms-btn-sm pms-btn-primary">View</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>

<?php
require_once 'managerFooter.view.php'; // KEEP THIS LINE
?>

<script>
// This would be included in a separate JS file in production
document.addEventListener('DOMContentLoaded', function() {
  // Initialize charts
  // Updated chart ID
  const financialCtx = document.getElementById('pmsFinancialChart').getContext('2d');
  const financialChart = new Chart(financialCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Revenue',
          data: [12000, 19000, 15000, 20000, 22000, 25000],
          backgroundColor: 'rgba(84, 107, 255, 0.7)', // Using original color value
          borderColor: 'rgba(84, 107, 255, 1)',     // Using original color value
          borderWidth: 1
        },
        {
          label: 'Expenses',
          data: [8000, 12000, 10000, 15000, 18000, 20000],
          backgroundColor: 'rgba(255, 84, 131, 0.7)', // Using original color value
          borderColor: 'rgba(255, 84, 131, 1)',     // Using original color value
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

  // Other interactive functionality would go here
  // Tooltips, modals, etc.
});
</script>