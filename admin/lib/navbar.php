<?php
$logo = $_SESSION["adLogo"];
$defaultImage = "../upload/admin/default-profile.png";
$profileImage = empty($logo) ? $defaultImage : "../upload/admin/" . $logo;

function timeElapsedString($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->days > 7) {
        // Custom message if the time difference is more than a week
        return 'Over a week ago';
    }

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<style>
    .navbar-brand{
        color:#646765;
        font-size: 22px;
    }
    .navbar-brand:hover{
        color:#646765;
    }
    .navbar-brand img{
        padding-right:10px;
        padding-left:5px;
    }
</style>

<!-- ============================================================== -->
<!-- navbar -->
<!-- ============================================================== -->
<div class="dashboard-header">
    <nav class="navbar navbar-expand-lg bg-white fixed-top">
        <a class="navbar-brand" href="index.php"><img src="assets/images/logo2.jpg" height="50px" weight="35px"> London Bagel Museum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-right-top">
                <li class="nav-item dropdown notification">
                    <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-bell"></i> <span class="indicator"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <li>
                            <div class="notification-title">Notification</div>
                            <div class="notification-list">
                                <div class="list-group">
                                    <?php
                                    mysqli_select_db($db_conn, "bagel");
                                    $productResult = $db_conn->query("SELECT *,
                                    IF(ProModifyDate = '0000-00-00 00:00:00', ProAddDate, ProModifyDate) AS DateToUse
                                    FROM product p WHERE ProStock <= 10 AND p.isUp = 1 ORDER BY DateToUse DESC");
                                    while ($productRow = $productResult->fetch_assoc()) {
                                        $ProID = $productRow["ProID"];
                                        $ProModifyDate = $productRow["ProModifyDate"];
                                        $ProAddDate = $productRow["ProAddDate"];
                                        $dateToUse = ($ProModifyDate === '0000-00-00 00:00:00') ? $ProAddDate : $ProModifyDate;
                                    ?>
                                    <a href="product-desc.php?ProID=<?php echo $ProID; ?>" class="list-group-item list-group-item-action active">
                                        <div class="notification-info">
                                            <div class="notification-list-user-img">
                                                <img src="../upload/product/<?php echo $productRow["ProUrl"]; ?>" alt="" class="user-avatar-md rounded-circle">
                                            </div>
                                            <div class="notification-list-user-block">
                                                <span class="notification-list-user-name" style="color:red;"><?php echo $productRow["ProName"]; ?></span> needs to be restocked.
                                                <div class="notification-date"><?php echo timeElapsedString($dateToUse); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="list-footer"><a href="product-view.php">View all products</a></div>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown nav-user">
                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo $profileImage; ?>" alt="" class="user-avatar-md rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                        <div class="nav-user-info">
                            <h5 class="mb-0 text-white nav-user-name mdi mdi-account-location"><?php echo $_SESSION["adName"]; ?></h5>
                            <span class="status"></span><span class="ml-2"><?php echo $_SESSION["adType"]; ?></span>
                        </div>
                        <a class="dropdown-item" href="admin-profile.php"><i class="fas fa-user mr-2"></i>Account</a>
                        <a class="dropdown-item" href="index.php?logout=yes"><i class="fas fa-power-off mr-2"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- ============================================================== -->
<!-- end navbar -->
<!-- ============================================================== -->
