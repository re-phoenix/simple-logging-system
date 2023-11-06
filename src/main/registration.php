<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once "./head.php";
    ?>
</head>
<body>
    <header>
        <?php
        include_once "./header.php";
        ?>
    </header>
    <div class="ContainerDiv">
        <section id="BANNER_SECTION">
            <form class="SigningForm" id="SIGN_UP_FORM" method="POST">
                <label for="TXT_USER_NAME" id="LBL_LOG_UP_USER_NAME" class="LblSignUpUserName LblUserName">School-ID</label>
                <input type="text" name="username" id="TXT_USER_NAME" class="TxtSignUpUserName TxtUserName" placeholder="School ID..." />

                <label for="TXT_PASSWORD" id="LBL_LOG_UP_PASSWORD" class="LblSignUpPassword LblPassword">Password</label>
                <input type="password" name="password" id="TXT_PASSWORD" class="TxtSignUpPassword TxtPassword" placeholder="Password..." />

                <label for="TXT_RE_PASSWORD" id="LBL_LOG_UP_RE_PASSWORD" class="LblSignUpRePassword LblPassword">Re-Password</label>
                <input type="password" name="password" id="TXT_RE_PASSWORD" class="TxtSignUpRePassword TxtPassword" placeholder="Re-Password..." />

                
                <label for="TXT_EMAIL" id="LBL_LOG_UP_EMAIL" class="LblSignUpEmail LblEmail">Email</label>
                <input type="text" name="email" id="TXT_EMAIL" class="TxtSignUpEmail TxtEmail" placeholder="Email..." />

                <div class="ShowPasswordDiv">
                    <input type="checkbox" id="LBL_SHOW_PASSWORD" class="ChkboxSignUpShowPassword ChkboxShowPassword" name="showPassword" />
                    <label for="LBL_SHOW_PASSWORD" id="LBL_LOG_UP_SHOW_PASSWORD" class="LblSignUpShowPassword LblShowPassword">Show Password</label>
                </div>

                <div class="SignUpSubmitDiv BtnSubmitDiv">
                    <button type="submit" class="BtnSignUpSubmit BtnSubmit">Register</button>
                </div>
            </form>
        </section>
    </div>
    <footer>
        <?php
        include_once "./footer.php";
        ?>
        <script src="../../public/js/registration.js"></script>
    </footer>
</body>
</html>