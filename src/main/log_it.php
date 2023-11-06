<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "./Head.php";
    ?>
    <link rel="stylesheet" href="../../public/css/log_it.css" />
</head>

<body>
    <header>
        <?php
        include_once "./Header.php";
        ?>
    </header>
    <div class="ContainerDiv">
        <section id="BANNER_SECTION">
            <form class="SigningForm" id="SIGN_IN_FORM" method="POST">
                <label for="TXT_USER_NAME" id="LBL_LOG_IN_USER_NAME" class="LblLogInUserName LblUserName">School-ID</label>
                <input type="text" name="username" id="TXT_USER_NAME" class="TxtLogInUserName TxtUserName" placeholder="User School ID." />

                <label for="TXT_PASSWORD" id="LBL_LOG_IN_PASSWORD" class="LblLogInPassword LblPassword">Password</label>
                <input type="password" name="password" id="TXT_PASSWORD" class="TxtLogInPassword TxtPassword" placeholder="Password..." />

                <div class="ShowPasswordDiv">
                    <input type="checkbox" id="LBL_SHOW_PASSWORD" class="ChkboxLogInShowPassword ChkboxShowPassword" name="showPassword" />
                    <label for="LBL_SHOW_PASSWORD" id="LBL_LOG_IN_SHOW_PASSWORD" class="LblLogInShowPassword LblShowPassword">Show Password</label>
                </div>

                <div class="SignInSubmitDiv BtnSubmitDiv">
                    <button type="submit" class="BtnSignInSubmit BtnSubmit">Log-it</button>
                </div>
            </form>
        </section>
    </div>
    <footer>
        <?php
        include_once "./Footer.php"; //REM: Is it acceptable to call this at the footer tag?
        ?>
        <script src="../../public/js/log_it.js"></script>
    </footer>
</body>

</html>