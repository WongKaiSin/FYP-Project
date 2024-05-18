<!-- ============================================================== -->
<!-- left sidebar -->
<!-- ============================================================== -->
<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-view.php"><i class="fas fa-address-card"></i>Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_user.php"><i class="far fa-address-book"></i>User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="mdi mdi-comment-processing-outline"></i>Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php"><i class="mdi mdi-calendar-multiple"></i>Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order-list.php"><i class="mdi mdi-cart-outline"></i>Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product-view.php"><i class="mdi mdi-basket"></i>Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sales-report.php"><i class="fas fa-fw fa-chart-pie"></i>Sales Report</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- ============================================================== -->
<!-- end left sidebar -->
<!-- ============================================================== -->
<script>
    function addAdmin(){
    
    var Type = "<?php echo $_SESSION["adType"]; ?>";
    
    if (Type == 'SuperAdmin') {
            window.location.href = "admin-add.php";
        }
    else{
        alert('Only superadmins can add other admins.');
            return false; 
    }
    
        
}
</script>