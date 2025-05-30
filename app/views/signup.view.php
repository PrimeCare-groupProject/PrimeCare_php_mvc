<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/signup.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/loader.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/flash_messages.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <title>PrimeCare</title>
</head>

<body>
    <?php
        if (isset($_SESSION['flash'])) {
            flash_message();
        }
    ?>
    <div class="signup-container">
        <div class="signup-form">
            <a href="/php_mvc_backend/public/home">
                <img class="signup-form__logo" src="<?= ROOT ?>/assets/images/logo.png" alt="Property Management Agency Logo">
            </a>
            <div class="signup-form__welcome-text">
                <h4>Welcome to our</h4>
                <h2>Property Management Agency</h2>
                <h4>Sign up to our platform</h4>
            </div>

            <!-- Sign-Up Form -->
            <form action="<?= ROOT ?>/signup" method="post" class="signup-form__fields">
                <label for="email" class="signup-form__label">Email Address</label>
                <input type="email" name="email" id="email" class="signup-form__input" placeholder="johndoe@gmail.com" value="<?= old_value('email') ?>" required>
                
                <div class="signup-form__name-fields">
                    <div class="signup-form__name-field">
                        <label for="phoneNo" class="signup-form__label">Contact Number</label>
                        <input type="text" name="contact" id="phoneNo" class="signup-form__input" placeholder="076XXXXXXX" value="<?= old_value('contact') ?>" required>
                    </div>
                    <div class="signup-form__name-field">
                        <label for="nic" class="signup-form__label">NIC</label>
                        <input type="text" name="nic" id="nic" class="signup-form__input" placeholder="0000000000" value="<?= old_value('nic') ?>" required>
                    </div>
                </div>
                <!-- First and Last Name Fields in Flex Layout -->
                <div class="signup-form__name-fields">
                    <div class="signup-form__name-field">
                        <label for="fname" class="signup-form__label">First Name</label>
                        <input type="text" name="fname" id="fname" class="signup-form__input" placeholder="john" value="<?= old_value('fname') ?>" required>
                    </div>
                    <div class="signup-form__name-field">
                        <label for="lname" class="signup-form__label">Last Name</label>
                        <input type="text" name="lname" id="lname" class="signup-form__input" placeholder="doe" value="<?= old_value('lname') ?>" required>
                    </div>
                </div>

                <!-- Password and Confirm Password Fields in Flex Layout -->
                <div class="signup-form__password-fields">
                    <div class="signup-form__password-field">
                        <label for="password" class="signup-form__label">Password</label>
                        <input type="password" name="password" id="password" class="signup-form__input" placeholder="*********" required>
                    </div>
                    <div class="signup-form__password-field">
                        <label for="confirmPassword" class="signup-form__label">Confirm Password</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="signup-form__input" placeholder="*********" required>
                    </div>
                </div>
                <button type="submit" class="signup-form__submit-button">Register</button>
            </form>

            <!-- <div class="social-login">
                <h4>Or continue with</h4>
                <button type="button" class="social-login__button">
                    <img src="https://www.pngall.com/wp-content/uploads/13/Google-Logo.png" alt="Google Logo">
                    Google
                </button>
            </div> -->

            <div class="signup-form__existing-account">
                <h5>Already have an account? <a class="signup-form__login-link" href="<?= ROOT ?>/login">Login</a></h5>
            </div>

            <!-- <div class="errors" style="display: <?= !empty($user->errors) ? 'block' : 'none'; ?>">
                <p>
                </p>
            </div> -->

        </div>
    </div>
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</body>
<script>
    function displayLoader() {
        document.querySelector('.loader-container').style.display = '';
        //onclick="displayLoader()"
    }
    
    document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', displayLoader);
    });

</script>
</html>
