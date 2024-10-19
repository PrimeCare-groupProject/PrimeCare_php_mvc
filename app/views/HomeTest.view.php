<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/HomeTest.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>PrimeCare</title>
</head>

<body>

    <!-- Header Section -->
    <section class="header" id="home">
        <nav>
            <a href=""><img src="<?= ROOT ?>/assets/images/logo.png" /></a>
            <div class="nav-links" id="navlinks">
                <img src="<?= ROOT ?>/assets/images/close.png" class="close-icon" alt="" onclick="hideMenu()">
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="">Logs</a></li>
                    <li><a href="#contactus">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <button class="header__button" onClick="window.location.href = 'login'">
                    Sign In | Log In
                </button>
            </div>
            <img src="<?= ROOT ?>/assets/images/menu.png" class="menu-icon" onclick="showMenu()" />
        </nav>
        <div class="text-box">
            <h1>Welcome to PrimeCare</h1>
            <p>Manage your properties effortlessly , offering seamless Management,</br> Maintenance, and Rental services</br>All in one platform.</p>
            <!-- <a href="<?= ROOT ?>/login" class="hero-btn">Get Started</a> -->
            <button class="hero-btn" onclick="window.location.href='dashboard'">
                Get Started
            </button>
        </div>
    </section>

    <section class="home-property-listing">
        <div class="search-box">

        </div>
        <div class="property-listing">
            
        </div>
    </section>

    <!-- Features Section -->
    <section class="services" id="about">
        <h1>Our Services</h1>
        <p>PrimeCare offers a range of services designed to meet all your property needs.</p>

        <div class="row">
            <div class="service-col">
                <h3>Property Management</h3>
                <p>List your property for rent and manage bookings effortlessly.</p>
            </div>
            <div class="service-col">
                <h3>Property Care</h3>
                <p>From cleaning to maintenance, we offer services to keep your property in top condition, even while you’re away.</p>
            </div>
            <div class="service-col">
                <h3>Rental Services</h3>
                <p>Rent out your property to verified tenants and track inquiries with ease.</p>
            </div>
        </div>
    </section>


    <section class="features">
        <div class="feature-column">
            <h1>Key Features of PrimeCare</h1>
            <p>Explore how PrimeCare can transform your property management experience with our advanced features.</p>
            <button class="hero-btn" onclick="window.location.href='dashboard'">
                Learn More
            </button>
        </div>
        <div class="feature-column">
            <div class="feature-cards-container">
                <div class="feature-cards">
                    <img src="<?= ROOT ?>/assets/images/homeImages/home-building.png" alt="">
                    <h3>Comprehensive Property Management</h3>
                    <p>Manage all aspects of your properties with ease using our all-in-one platform.</p>
                </div>
                <div class="feature-cards">
                    <img src="<?= ROOT ?>/assets/images/homeImages/home-chat.png" alt="">
                    <h3>Tenant Communication</h3>
                    <p>Enhance tenents satisfaction with seamless communication tools.</p>
                </div>
                <div class="feature-cards">
                    <img src="<?= ROOT ?>/assets/images/homeImages/home-support.png" alt="">
                    <h3>Maintenance Tracking</h3>
                    <p>Keep track of maintenance requests and ensure resolutions.</p>
                </div>
                <div class="feature-cards">
                    <img src="<?= ROOT ?>/assets/images/homeImages/home-graph.png" alt="">
                    <h3>Analytics & Reporting</h3>
                    <p>Gain insights into your property operations with detailed reports.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="statistics">
        <div class="statistics-header">
            <h1>PrimeCare By The Numbers</h1>
            <p>Insights about PrimeCare's performance</p>
        </div>
        <div class="stat">
            <h2>500+</h2>
            <p>Properties Managed</p>
        </div>
        <div class="stat">
            <h2>4.8/5</h2>
            <p>Average Customer Ratings</p>
        </div>
        <div class="stat">
            <h2>150+</h2>
            <p>Service Providers</p>
        </div>
    </section>

    <section class="title-section">
        <h1>Our Team</h1>
        <p>PrimeCare offers a range of services designed to meet all your property needs.</p>
    </section>

    <section class="team" id="team">
        <div class="carousel">
            <div class="list">
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member1.jpg" alt="">
                    <div class="content">
                        <div class="author">Developer</div>
                        <div class="title">Nimna Pathum</div>
                        <div class="des">Computer Science Undergraduate at UCSC</div>
                        <div class="buttons">
                            <button>GitHub</button>
                            <button>LinkedIn</button>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member2.jpg" alt="">
                    <div class="content">
                        <div class="author">Developer</div>
                        <div class="title">Wendt Edmund</div>
                        <div class="des">Computer Science Undergraduate at UCSC</div>
                        <div class="buttons">
                            <button>GitHub</button>
                            <button>LinkedIn</button>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member3.jpg" alt="">
                    <div class="content">
                        <div class="author">Developer</div>
                        <div class="title">Janith Prabash</div>
                        <div class="des">Computer Science Undergraduate at UCSC</div>
                        <div class="buttons">
                            <button>GitHub</button>
                            <button>LinkedIn</button>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member4.jpg" alt="">
                    <div class="content">
                        <div class="author">Developer</div>
                        <div class="title">Bimsara Imash</div>
                        <div class="des">Computer Science Undergraduate at UCSC</div>
                        <div class="buttons">
                            <button>GitHub</button>
                            <button>LinkedIn</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="thumbnail">
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member1.jpg" alt="">
                    <div class="content">
                        <div class="title">Nimna Pathum</div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member2.jpg" alt="">
                    <div class="content">
                        <div class="title">Wendt Edmund</div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member3.jpg" alt="">
                    <div class="content">
                        <div class="title">Janith Prabash</div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= ROOT ?>/assets/images/homeImages/member4.jpg" alt="">
                    <div class="content">
                        <div class="title">Bimsara Imash</div>
                    </div>
                </div>
            </div>

            <div class="arrows">
                <button id="prev">&#10094;</button>
                <button id="next">&#10095;</button>
            </div>

            <div class="time">
            </div>
            <script src="<?= ROOT ?>/assets/js/home_slider.js"></script>
        </div>
    </section>

    <section class="contactUs" id="contactus">
        <div>
            <h1>Contact Us</h1>
        </div>
        <div class="contact-content">
            <div class="left-side">
                <div class="address">
                    <div class="partition with-border">
                        <img src="<?= ROOT ?>/assets/images/homeImages/gps.png" alt="">
                        <div class="topic">Address</div>
                    </div>
                    <div class="partition right-indent">
                        <div class="text-one">No 9 , Marine drive,</div>
                        <div class="text-one">Bambalapitiya.</div>
                    </div>
                </div>
                <div class="address">
                    <div class="partition with-border">
                        <img src="<?= ROOT ?>/assets/images/homeImages/email.png" alt="">
                        <div class="topic">Email</div>
                    </div>
                    <div class="partition right-indent">
                        <div class="text-one">primeCare@gmail.com</div>
                        <div class="text-one">primeCareService@gmail.com</div>
                    </div>
                </div>
                <div class="address">
                    <div class="partition with-border">
                        <img src="<?= ROOT ?>/assets/images/homeImages/phone-call.png" alt="">
                        <div class="topic">Mobile</div>
                    </div>
                    <div class="partition right-indent">
                        <div class="text-one">011-1234567</div>
                        <div class="text-one">011-9876543</div>
                    </div>
                </div>
            </div>
            <div class="right-side">
                <div class="topic-text">Send us a message</div>
                <form action="">
                    <div class="input-box">
                        <label for="name">Your Name:</label>
                        <input type="text" name="name" id="name" placeholder="Name">

                        <label for="email">Your Email:</label>
                        <input type="text" name="email" id="email" placeholder="Email">

                        <label for="message">Your Message:</label>
                        <textarea type="text" name="message" id="message" cols="20" rows="5" placeholder="Message"></textarea>
                    </div>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
        <img src="<?= ROOT ?>/assets/images/homeImages/contactus.jpg" alt="" class="contactus-image">
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h2>PrimeCare</h2>
                <p>Your one-stop solution for property management. Experience ease and efficiency with PrimeCare.</p>
            </div>
            <div class="footer-column">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contactus">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h2>Follow Us</h2>
                <div class="social-icons">
                    <a href="#"><img src="<?= ROOT ?>/assets/images/homeImages/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="<?= ROOT ?>/assets/images/homeImages/twitter.png" alt="Twitter"></a>
                    <a href="#"><img src="<?= ROOT ?>/assets/images/homeImages/linkedin.png" alt="LinkedIn"></a>
                    <a href="#"><img src="<?= ROOT ?>/assets/images/homeImages/instagram.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <img src="<?= ROOT ?>/assets/images/homeImages/Footer-pic.png" alt="Footer Decorative" class="footer-image">
        <div class="footer-bottom">
            <p>&copy; 2024 PrimeCare. All rights reserved.</p>
        </div>
    </footer>




    <script>
        var navlinks = document.getElementById("navlinks");

        function showMenu() {
            navlinks.style.right = "0";
        }

        function hideMenu() {
            navlinks.style.right = "-200px";
        }
    </script>
</body>

</html>