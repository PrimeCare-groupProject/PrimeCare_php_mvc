<?php require_once 'managerHeader.view.php'; ?>


<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Employee Management </h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Employee ID...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons"></button>
        </div>
    </div>
</div>
<div class="content_wrapper">

    <div class="financial-details-container">
        <table class="listing-table-for-customer-payments">
            <thead>
                <tr>
                    <th class="extra-space sortable first" id="date-header">
                        Created Date
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="sortable" id="earnings-header">
                        User Type
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th class="last">Image</th>
                    <th hidden>Reset code</th>
                </tr>
            </thead>
            <tbody>
                <?php 
        
                    if(isset($userlist) && count($userlist) > 0){
                        foreach ($userlist as $user) {
                            echo "<tr>";
                            echo "<td class='first'>" . ($user->created_date ?? "-") .$tot. "</td>";
                            echo "<td>{$user->pid}</td>";
                            echo "<td>{$user->fname} {$user->lname}</td>";
                            echo "<td>{$user->email}</td>";
                            echo "<td>{$user->user_lvl}</td>";
                            echo "<td class='last' ><img class='header-profile-picture' style='margin:0px' src=".get_img($user->image_url)."></td>";
                            echo "<td hidden>{$user->reset_code}</td>";
                            echo "</tr>";
                        }
                    }else{
                        echo "<tr>";
                        echo "<td class='first'> ---</td>";
                        echo "<td> ---</td>";
                        echo "<td> --- </td>";
                        echo "<td> --- </td>";
                        echo "<td> --- </td>";
                        echo "<td class='last' > --- </td>";
                        echo "<td hidden> --- </td>";
                        echo "</tr>";
                    }
                ?>
                <!-- Additional rows here -->
            </tbody>
        </table>
    </div>
    <?php # show($userlist); ?>

    <div class="pagination">
            <!-- Render the pagination links -->
            <?php echo $paginationLinks; ?>
    </div>
</div>


<script>
    let isDateAscending = true;
    let isEarningsAscending = true;

    document.getElementById('date-header').addEventListener('click', function() {
        sortTableByDate(isDateAscending);
        isDateAscending = !isDateAscending; // Toggle sorting order
    });

    document.getElementById('earnings-header').addEventListener('click', function() {
        sortTableByEarnings(isEarningsAscending);
        isEarningsAscending = !isEarningsAscending; // Toggle sorting order
    });

    function sortTableByDate(isAscending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));

        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(1)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(1)').textContent);
            return isAscending ? dateA - dateB : dateB - dateA;
        });

        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    function sortTableByEarnings(isAscending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));

        rows.sort((a, b) => {
            const earningsA = parseFloat(a.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            const earningsB = parseFloat(b.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            return isAscending ? earningsA - earningsB : earningsB - earningsA;
        });

        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }
    //hovering effect
    document.querySelectorAll('.listing-table-for-customer-payments tbody tr').forEach(row => {
        row.addEventListener('mouseover', function() {
            this.style.backgroundColor = "#f0f0f0"; // Light gray background on hover
        });

        row.addEventListener('mouseout', function() {
            this.style.backgroundColor = ""; // Reset background color when not hovering
        });
    });
</script>


<?php require_once 'managerFooter.view.php'; ?>
