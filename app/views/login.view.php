<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/login.css">
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
    <div class="login-container">
        <div class="login-form">
            <a href="/php_mvc_backend/public/home">
                <img class="login-form__logo" src="<?= ROOT ?>/assets/images/logo.png" alt="Property Management Agency Logo">
            </a>
            <div class="login-form__welcome-text">
                <h4>Welcome to our</h4>
                <h2>Property Management Agency</h2>
                <h4>Please login to your account</h4>
            </div>

            <!-- Login Form -->
            <form action="<?= ROOT ?>/login" method="post" class="login-form__fields">
                <label for="email" class="login-form__label">Email</label>
                <input type="email" name="email" id="email" class="login-form__input" placeholder="Enter email" value="<?= old_value('email') ?>" required>

                <label for="password" class="login-form__label">Password</label>
                <input type="password" name="password" id="password" class="login-form__input" placeholder="Enter Password" required>

                <a class="login-form-link-forgot-password" href="<?= ROOT ?>/resetPassword">Forgot password?</a>
                <button type="submit" class="login-form__submit-button">Log In</button>
            </form>

            <!-- <div class="social-login">
                <h4>Or continue with</h4>
                <button type="button" class="social-login__button">
                    <img src="https://www.pngall.com/wp-content/uploads/13/Google-Logo.png" alt="Google Logo">
                    Google
                </button>
            </div> -->
            

            <div class="login-form__new-member">
                <h5>New member here? <a class="login-form__register-link" href="<?= ROOT ?>/signup">Register Now</a></h5>
            </div>

            <div class="errors" style="display: <?= !empty($user->errors) ? 'block' : 'none'; ?>">
                <p><?= $user->errors['auth'] ?? '' ?></p>
            </div>
            <div class="success" style="display: <?= isset($success) ? 'block' : 'none'; ?>">
                <p><?= $success ?></p>
            </div>

        </div>
    </div>
    <!-- Classic Spinner -->
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>

</body>

<script>
    function displayLoader() {
        document.querySelector('.loader-container').style.display = '';
  // document.querySelector('.loader-container').style.display = 'block';
    }
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', displayLoader);
    });
</script>
</html>
