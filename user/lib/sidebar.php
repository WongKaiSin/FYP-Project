<aside id="sidebar_account" class="sidebar">
    <h1>My Account</h1>
    <ul class='menu'>
        <li><a href="member_profile.php">Profile</a></li>
        <li><a href="member_password.php">Change Password</a></li>
    </ul>
</aside>

<aside id="sidebar_orders" class="sidebar">
    <h1>My Orders</h1>
    <ul class='menu'>
        <li><a href="member_bookinglist.php">Booking</a></li>
        <li><a href="<?php echo $SiteUrl ?>/my-confirmed-orders">Order History</a></li>
    </ul>
</aside>

<aside id="sidebar_logout" class="sidebar">
    <form action="forms/logout.php" method="post">
        <button type="submit" class="logout-btn" name="logout">Log out</button>
    </form>
</aside>