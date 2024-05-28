<style>
.icons-container {
  display: flex; /* Use flexbox for layout */
  align-items: center; /* Align items vertically */
}

.user-icon-link,
.cart-icon-link {
  padding: 5px; /* Adjust padding as needed */
}

.user-icon,
.cart-icon {
  width: 30px; /* Adjust size as needed */
  height: auto;
  border-radius: 50%; /* Make it circular */
  transition: transform 0.3s ease; /* Add a smooth transition effect */
}

.user-icon:hover,
.cart-icon:hover {
  transform: scale(1.1); /* Enlarge the icon on hover */
}

.cart-icon-link {
  margin-left: 10px; /* Adjust the space between icons */
}
</style>
<nav id="navbar" class="navbar">
<ul>
    <li><a href="index.php#hero">Home</a></li>
    <li><a href="index.php#about">About</a></li>
    <li><a href="index.php#menu">Recommendation</a></li>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="booking.php">Reservations</a></li>
    <li><a href="index.php#gallery">Gallery</a></li>
    <li><a href="index.php#contact">Contact</a></li>
</ul>
</nav><!-- .navbar -->

<div class="icons-container">
  <a class="cart-icon-link" href="#"><img class="cart-icon" src="assets/img/cart_icon.png" alt="Cart" title="Cart"></a>
  <a class="user-icon-link" href="member_profile.php"><img class="user-icon" src="assets/img/user_icon.png" alt="User" title="Setting"></a>
</div>
<i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
<i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>