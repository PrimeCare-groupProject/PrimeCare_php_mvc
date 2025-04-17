<div>
    <?php
    $notificationModel = new NotificationModel();
    $notifications = $notificationModel->where(['user_id' => $_SESSION['user']->pid]);

    $unreadCount = 0;
    if ($notifications != null) {
        foreach ($notifications as $notification) {
            if ($notification->is_read == 0) {
                $unreadCount++;
            }
        }
    }
    $unreadClass = $unreadCount > 0 ? 'has-unread' : '';
    ?>
    <div class="btn-light <?= $unreadClass ?>" id="notification_button" onclick="showNotifications()"><i class="fa-regular fa-bell"></i></div>
    <div id="notification_show_area" class="notification_show_area">
        <div class="notification_header">
            <h3>Notifications</h3>
        </div>
        <div class="NSA__listing_notifications">
            <?php


            if ($notifications != null) {
                foreach ($notifications as $notification) {
                    $is_read = $notification->is_read == 1 ? "read" : "unread";
                    echo "<div class='NSA__item $is_read $notification->color'>";
                    echo "<div class='NSA__item_message'>";
                    echo "<h4 class='NSA__item_title'>". $notification->title ."</h4>";
                    echo "<p>$notification->message</p>";
                    echo "</div>";
                    echo "<div class='NSA__item_time'>";
                    echo "<p>" . covertTimeToReadableForm($notification->created_at) . "</p>";
                    if($notification->link != ''){
                        echo "<a href='". $notification->link ."'>";
                        echo "<i class='fa-solid fa-arrow-right'></i>";
                        echo "</a>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='NSA__item'>";
                echo "<div class='NSA__item_message'>";
                echo "<p>No notifications</p>";
                echo "</div>";
                echo "<div class='NSA__item_time'>";
                echo "<p></p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <!-- <div class="NSA__item">
                                <div class="NSA__item_message">
                                    <p>Message will shown here Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam aspernatur itaque optio enim molestias! Ullam, impedit doloribus obcaecati quibusdam dolores, quia architecto sapiente molestias commodi consequatur tenetur ad, unde amet.</p>
                                </div>
                                <div class="NSA__item_time">
                                    <p>time</p>
                                </div>
                            </div> -->
        </div>
    </div>
</div>