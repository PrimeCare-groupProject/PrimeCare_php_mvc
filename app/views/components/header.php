<link rel="stylesheet" href="<?= ROOT ?>/assets/css/header.css"> <!-- Link to your CSS file -->
<header class="header">
    <nav class="header__navbar"> <!-- Change from "navbar" to "header__navbar" for BEM consistency -->
        <div class="header__menu__group">
            <img class="header__menu__icon" src="<?= ROOT ?>/assets/images/menu-line.png" alt="Menu Icon" id="menu-btn"> <!-- Updated to header__menu__icon -->
            <img class="header__logo" src="<?= ROOT ?>/assets/images/logo.png" alt="Company Logo"> <!-- Updated to header__logo -->
        </div>
        <ul class="header__menu" id="nav-links"> <!-- Updated to header__menu -->
            <li class="header__menu-item"><a class="header__link" href="#hero">Home</a></li>
            <li class="header__menu-item"><a class="header__link" href="#aboutus">About Us</a></li>
            <li class="header__menu-item"><a class="header__link" href="#service">Services</a></li>
            <li class="header__menu-item"><a class="header__link" href="#ourteam">Our Team</a></li>
            <!-- <li class="header__menu-item"><a class="header__link" href="#reviews">Reviews</a></li> -->
            <li class="header__menu-item"><a class="header__link" href="#contact">Contact</a></li>
        </ul>
        <button class="header__button" onClick="window.location.href = 'login'">
            Sign In | Log In
        </button>

    </nav>
    <script src="<?= ROOT ?>/assets/js/openNavbar.js"></script> <!-- Link to your JS file -->

</header>
