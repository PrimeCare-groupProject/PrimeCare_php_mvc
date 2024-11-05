<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/signup.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <title>PrimeCare</title>
</head>

<body>
    <div class="signup-container">
        <div class="signup-form">
            <img class="signup-form__logo" src="<?= ROOT ?>/assets/images/logo.png" alt="Property Management Agency Logo">
            <div class="signup-form__welcome-text">
                <h4>Welcome to our</h4>
                <h2>Property Management Agency</h2>
                <h4>Sign up to our platform</h4>
            </div>

            <!-- Sign-Up Form -->
            <form action="<?= ROOT ?>/signup" method="post" class="signup-form__fields">
                <label for="email" class="signup-form__label">Email Address</label>
                <input type="email" name="email" id="email" class="signup-form__input" placeholder="johndoe@gmail.com" required>

                <label for="phoneNo" class="signup-form__label">Contact Number</label>
                <input type="text" name="contact" id="phoneNo" class="signup-form__input" placeholder="076XXXXXXX" required>

                <!-- First and Last Name Fields in Flex Layout -->
                <div class="signup-form__name-fields">
                    <div class="signup-form__name-field">
                        <label for="fname" class="signup-form__label">First Name</label>
                        <input type="text" name="fname" id="fname" class="signup-form__input" placeholder="john" required>
                    </div>
                    <div class="signup-form__name-field">
                        <label for="lname" class="signup-form__label">Last Name</label>
                        <input type="text" name="lname" id="lname" class="signup-form__input" placeholder="doe" required>
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

            <div class="social-login">
                <h4>Or continue with</h4>
                <button type="button" class="social-login__button">
                    <img src="https://www.pngall.com/wp-content/uploads/13/Google-Logo.png" alt="Google Logo">
                    Google
                </button>
            </div>

            <div class="signup-form__existing-account">
                <h5>Already have an account? <a class="signup-form__login-link" href="<?= ROOT ?>/login">Login</a></h5>
            </div>
        </div>
    </div>
</body>

</html>
