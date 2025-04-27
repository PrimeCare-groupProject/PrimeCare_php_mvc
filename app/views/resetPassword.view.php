<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/login.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/flash_messages.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/loader.css">

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
                <img class="signup-form__logo" src="<?= ROOT ?>/assets/images/logo.png" alt="Property Management Agency Logo">
            </a>
            <div class="login-form__welcome-text">
                <h4><?php 
                    if(!isset($confirmed)){
                        echo "Please enter email to send <b>reset code</b>"; 
                    } elseif (!isset($code_verified)) {
                        echo "Please enter the reset code sent to your email";
                    } else {
                        echo "Please enter new password";
                    }
                ?></h4>
            </div>

            <!-- Login Form -->
            <?php 
                if(!isset($confirmed)){
                    echo '<form method="post" class="login-form__fields">
                        <input type="hidden" name="email_submission" value="1">
                        <label for="email" class="login-form__label">Email</label>
                        <input type="email" name="email" id="email" class="login-form__input" placeholder="johndoe@gmail.com" required>
                        
                        <button type="submit" class="login-form__submit-button" style="margin-top: 0.5rem;">Submit</button>
                    </form>';
                } elseif (!isset($code_verified)) {
                    echo '<form method="post" class="login-form__fields">
                        <input type="hidden" name="code_submission" value="1">
                        <label for="reset_code" class="login-form__label">Reset Code</label>
                        <input type="text" name="reset_code" id="reset_code" class="login-form__input" placeholder="Enter Reset Code" required>
                        
                        <button type="submit" class="login-form__submit-button" style="margin-top: 0.5rem;">Submit</button>
                    </form>';
                } else {
                    echo '<form method="post" class="login-form__fields">
                        <label for="password" class="login-form__label">Password</label>
                        <input type="password" name="password" id="password" class="login-form__input" placeholder="Enter Password" style="margin-bottom: 1rem;" required> 

                        <label for="confirm_password" class="login-form__label">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="login-form__input" placeholder="Confirm Password" style="margin-bottom: 1rem;" required> 

                        <button type="submit" class="login-form__submit-button" style="margin-top: 0.5rem;">Submit</button>
                    </form>';
                }
            ?>
              
            <div class="login-form__new-member">
                <h5>Remember password?<a class="login-form__register-link" href="<?= ROOT ?>/login">login</a></h5>
            </div>

            <div class="errors" style="display: <?= !empty($user->errors) ? 'block' : 'none'; ?>">
                <p><?= $user->errors['auth'] ?? '' ?></p>
            </div>

        </div>
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
<script src="<?= ROOT ?>/assets/js/loader.js"></script>

</html>
