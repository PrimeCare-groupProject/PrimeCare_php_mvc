<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2> Tenants</h2>
</div>

<div class="financial-details-container">
    <!-- Filter Options -->
    <?php
    $tenants = [
        (object)[
            'tenant_name' => 'John Doe',
            'property_name' => 'Ocean View Apartment',
            'check_in' => '2023-01-15',
            'check_out' => '2023-02-15',
            'total_income' => 150000,
            'status' => 'Occupied',
            'profit_status' => 'Profit',
            'email' => 'john@example.com',
            'contact' => '0771234567',
            'property_id' => '1',
            'tenant_id' => '79'
        ],
        (object)[
            'tenant_name' => 'Jane Smith',
            'property_name' => 'Mountain Cabin',
            'check_in' => '2023-03-01',
            'check_out' => '2023-03-15',
            'total_income' => 200000,
            'status' => 'Occupied',
            'profit_status' => 'Profit',
            'email' => 'jane@example.com',
            'contact' => '0779876543',
            'property_id' => '2',
            'tenant_id' => '80'
        ]
    ];
    $tenants = [];
    if ($tenants != null) {
    ?>
        <div class="filter-container">
            <div>
                <label for="year-filter">Year:</label>
                <select id="year-filter">
                    <option value="all">All</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>

            <div>
                <label for="property-filter">Property:</label>
                <select id="property-filter">
                    <option value="all">All</option>
                    <?php
                    foreach ($properties as $property) {
                        echo "<option value='{$property->property_id}'>{$property->name}</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="tenant-filter">Tenant:</label>
                <input type="text" id="tenant-filter" placeholder="Enter tenant name">
            </div>
        </div>

        <!-- Table -->
        <table class="listing-table-for-customer-payments">
            <thead>
                <tr>
                    <th class='first'>Tenant Name</th>
                    <th style="min-width: 120px;">Property Name</th>
                    <th style="min-width: 60px;">Check In</th>
                    <th style="min-width: 60px;">Check Out</th>
                    <th style="min-width: 80px;">Total Income</th>
                    <th class='last' style="min-width: 40px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($tenants as $tenant) {
                    echo "<tr class='tenant-row' data-email='{$tenant->email}' data-contact='{$tenant->contact}' data-property-id='{$tenant->property_id}' tenant-id='{$tenant->tenant_id}' data-img='" . get_img($_SESSION['user']->image_url) . "'>";
                    echo "<td class='first'>" . $tenant->tenant_name . "</td>";
                    echo "<td data-property-id='{$tenant->property_id}' class='TL__property_name' >" . $tenant->property_name . "</td>";
                    echo "<td>" . $tenant->check_in . "</td>";
                    echo "<td>" . $tenant->check_out . "</td>";
                    echo "<td>" . $tenant->total_income . " LKR</td>";
                    echo "<td><span class='border-button-sm green'>Occupied</span></td>";
                    echo "</tr>";
                }

                ?>
            </tbody>
        </table>

    <?php
    } else {
        echo '<div class="AA__property-management-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">';
        echo '<p style="text-align: center;">No Tenants found</p>';
        echo '<img src="' . ROOT . '/assets/images/no.jpg" alt="No requests" style="width: 200px; height: auto; align-self: center; margin-top: 20px;">';
        echo '</div>';
    }
    ?>
</div>

<!-- Style for expanded row -->
<style>
    .tenant-details-row td {
        background-color: #f9f9f9;
        border-top: none;
        padding: 12px 20px;
    }

    .tenant-details-content {
        display: flex;
        gap: 40px;
        font-size: 14px;
        color: #333;
    }

    .tenant-details-content div {
        flex: 1;
    }
</style>

<!-- Scripts -->
<script>
    // Filtering functionality
    document.getElementById('year-filter').addEventListener('change', filterTable);
    document.getElementById('tenant-filter').addEventListener('keyup', filterTable);
    document.getElementById('property-filter').addEventListener('change', filterTable);

    function filterTable() {
        const yearValue = document.getElementById('year-filter').value;
        const tenantValue = document.getElementById('tenant-filter').value.toLowerCase();
        const propertyValue = document.getElementById('property-filter').value;

        const rows = document.querySelectorAll('.listing-table-for-customer-payments tbody tr');

        rows.forEach(row => {
            if (row.classList.contains('tenant-details-row')) return;

            const tenantName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const checkInDate = row.querySelector('td:nth-child(3)').textContent;
            const checkInYear = checkInDate.split('-')[0];
            const propertyId = row.querySelector('td:nth-child(2)').getAttribute('data-property-id');

            let isVisible = true;

            if (yearValue !== 'all' && checkInYear !== yearValue) isVisible = false;
            if (tenantValue && !tenantName.includes(tenantValue)) isVisible = false;
            if (propertyValue !== 'all' && propertyId !== propertyValue) isVisible = false;

            row.style.display = isVisible ? '' : 'none';

            const next = row.nextElementSibling;
            if (next && next.classList.contains('tenant-details-row')) {
                next.style.display = isVisible ? '' : 'none';
            }
        });
    }

    // Expandable row logic
    document.querySelectorAll('.tenant-row').forEach(row => {
        row.addEventListener('click', function() {
            // Remove any existing detail rows
            document.querySelectorAll('.tenant-details-row').forEach(r => r.remove());
            document.querySelectorAll('.tenant-row.expanded').forEach(r => r.classList.remove('expanded'));

            if (row.classList.contains('expanded')) {
                row.classList.remove('expanded');
                return;
            }

            row.classList.add('expanded');

            const email = row.getAttribute('data-email');
            const contact = row.getAttribute('data-contact');
            const imgSrc = row.getAttribute('data-img');

            const detailRow = document.createElement('tr');
            detailRow.classList.add('tenant-details-row');
            const colSpan = row.children.length;

            detailRow.innerHTML = `
            <td colspan="${colSpan}">
                <div style="display: flex; justify-content: space-around; align-items: center; gap: 30px; padding: 15px 25px; border-radius: 0px 0px 10px 10px;">
                    <div>
                        <img src="${imgSrc}" alt="Tenant Image" style="border-radius: 50%; width: 50px; height: 50px; object-fit: cover;">
                    </div>
                    <div class="input-field" style="display: flex; justify-content: space-between;"><span style="color: var(--dark-grey-color);">Tenant Name:</span> ${row.children[0].textContent}</div>
                    <div class="input-field" style="display: flex; justify-content: space-between;"><span style="color: var(--dark-grey-color);">Email:</span> ${email}</div>
                    <div class="input-field" style="display: flex; justify-content: space-between;"><span style="color: var(--dark-grey-color);">Contact:</span> ${contact}</div>
                </div>
            </td>
        `;

            row.parentNode.insertBefore(detailRow, row.nextSibling);
        });
    });

    // Hide details on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.tenant-row') && !e.target.closest('.tenant-details-row')) {
            document.querySelectorAll('.tenant-details-row').forEach(r => r.remove());
            document.querySelectorAll('.tenant-row.expanded').forEach(r => r.classList.remove('expanded'));
        }
    });
</script>

<?php require_once 'ownerFooter.view.php'; ?>