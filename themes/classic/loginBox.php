<?php
    // location
    if(!$location) $location = Utility::currentPageURL();
?>
<div id="loginBox">
    <form action="<?php echo AUTHENTICATION_PATH; ?>login/?goto=<?php echo $location; ?>" id="loginForm" method="post">
        <h3>Login to Felix Online</h3>
        <table>
            <tr>
                <td><label for="user">IC Username: </label></td>
                <td><input type="text" name="username" id="user" placeholder="jb007"/></td>
            </tr>
            <tr>
                <td><label for="password">IC Password: </label></td>
                <td><input type="password" name="password" id="password" placeholder="•••••••••••"/></td>
            </tr>
            <tr>
                <td><label for="rememberButton">Remember Me: </label></td>
                <td><input type="checkbox" name="remember" id="rememberButton" value="rememberme" checked="checked" /></td>
            </tr>
            <tr>
                <td></td><td><input type="submit" value="Login (SSL)" name="login" id="submit"/></td>
            </tr>
        </table>
    </form>
</div>
