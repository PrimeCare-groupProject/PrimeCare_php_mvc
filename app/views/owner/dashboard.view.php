<?php require_once 'ownerHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>dashboard</h2>
</div>

<div class="dashboard">
    <div class="top-section">
        <div class="card_pic my_property">
            <img src="<?= ROOT ?>/assets/images/my_properties.jpg" alt="">
            <h3 class="card_pic_h3">Rented Properties</h3>
        </div>
        <div class="card_pic add_property" onclick="location.href='<?= ROOT ?>/dashboard/propertyListing/addproperty'">
            <img src="<?= ROOT ?>/assets/images/my_property.jpg" alt="">
            <h3 class="card_pic_h3">Add Property</h3>
        </div>
        <div class="card_pic request_service">
            <img src="<?= ROOT ?>/assets/images/services.jpg" alt="">
            <h3 class="card_pic_h3">Request Service</h3>
        </div>
        <!-- <div class="card_pic total-expense">
            <img src="<?= ROOT ?>/assets/images/my_property.jpg" alt="">
            <h3 class="card_pic_h3">Add Property</h3>
        </div> -->
    </div>

    <div class="top-section">
        <div class="card total-property">
            <h3>Total Property</h3>
            <span><?= $propertyCount; ?></span>
        </div>
        <div class="card total-profit">
            <h3>Total Profit</h3>
            <span>LKR 30,000.00</span>
        </div>
        <div class="card total-income">
            <h3>Total Income</h3>
            <span>LKR 56,456.00</span>
        </div>
        <div class="card total-expense">
            <h3>Total Expense</h3>
            <span>LKR <?= number_format($totalServiceCost, 2); ?></span>
        </div>
    </div>

    <div class="bottom-section">
        <div class="payment-history">
            <h3>Payment History</h3>
            <table>
                <tr>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>September 2023</td>
                    <td>LKR 4568.00</td>
                    <td>Paid</td>
                </tr>
                <tr>
                    <td>October 2023</td>
                    <td>LKR 4568.00</td>
                    <td>Paid</td>
                </tr>
                <tr>
                    <td>November 2023</td>
                    <td>LKR 4568.00</td>
                    <td>Paid</td>
                </tr>
            </table>
            <a href="" class="orange-link">see more</a>
        </div>

        <div class="maintenance-status">
            <h3>Maintenance History</h3>
            <table>
                <?php
                if ($serviceLogs != null) {
                    $serviceLogs = array_slice($serviceLogs, -5);
                    foreach ($serviceLogs as $serviceLog) {
                        $statusClass = strtolower($serviceLog->status) . '-color';
                        echo "<tr>";
                        echo "<td>{$serviceLog->service_type}</td>";
                        echo "<td class='$statusClass'>{$serviceLog->status}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='2'>No maintenance requests</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <?php 
            if ($serviceLogs == 5) {
                echo "<hr>";
            }
            ?>
            <a href="<?= ROOT ?>/dashboard/maintenance" class="orange-link">see more</a>
        </div>

    </div>
    <div class="outstanding-payments">
        <h3>Outstanding Payments</h3>
        <div class="outstanding-flex-bar">
            <p>Outstanding payments for March</p>
            <div>
                <span>LKR 300.00</span>
                <button class="secondary-btn">Pay Outstanding</button>
            </div>
        </div>
    </div>

    <div class="RS__reviews-container">
        <div class="RS__stats-container">
            <div class="RS__stat-box">
                <div class="RS__total-number">10</div>
                <div class="RS__stat-label">Reviews on this year</div>
            </div>

            <div class="RS__stat-box">
                <div class="RS__total-number">4.0</div>
                <div class="RS__rating-stars">★★★★☆</div>
                <div class="RS__stat-label">Average rating on this year</div>
            </div>

            <div class="RS__stat-box">
                <div class="RS__rating-distribution">
                    <span>5</span>
                    <div class="RS__rating-bar">
                        <div class="RS__rating-bar-fill" style="width: 80%"></div>
                    </div>
                    <span class="RS__rating-count">2.0k</span>
                </div>
                <div class="RS__rating-distribution">
                    <span>4</span>
                    <div class="RS__rating-bar">
                        <div class="RS__rating-bar-fill" style="width: 60%"></div>
                    </div>
                    <span class="RS__rating-count">2.0k</span>
                </div>
                <div class="RS__rating-distribution">
                    <span>3</span>
                    <div class="RS__rating-bar">
                        <div class="RS__rating-bar-fill" style="width: 50%"></div>
                    </div>
                    <span class="RS__rating-count">2.0k</span>
                </div>
                <div class="RS__rating-distribution">
                    <span>2</span>
                    <div class="RS__rating-bar">
                        <div class="RS__rating-bar-fill" style="width: 40%"></div>
                    </div>
                    <span class="RS__rating-count">2.0k</span>
                </div>
                <div class="RS__rating-distribution">
                    <span>1</span>
                    <div class="RS__rating-bar">
                        <div class="RS__rating-bar-fill" style="width: 20%"></div>
                    </div>
                    <span class="RS__rating-count">2.0k</span>
                </div>
                <!-- Repeat for other ratings -->
            </div>
        </div>
    </div>
</div>
<?php require_once 'ownerFooter.view.php'; ?>