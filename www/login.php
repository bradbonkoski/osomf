<?php
/**
 * TEMP/Reference of SSO, not overly secure!
 *
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 6/19/11
 * Time: 3:33 PM
 * To change this template use File | Settings | File Templates.
 */

// Set up our Default include path
$path = dirname(dirname(__FILE__));
define('PATH', $path);
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

//include the bootstrap, the entry point for all that is holy
require('lib/bootstrap.php');

use \osomf\models\UserModel;

if($_SERVER['SERVER_PORT'] != '443') {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit();
}

//print_r($_SERVER);

$userName = ''; //default
$err = '';

/**
 * Authentication Routine
 */
if (isset($_POST['frmSubmit'])) {
    //echo "<pre>".print_r($_POST, true)."</pre>";
    // User lookup
    if (strlen($_POST['loginUserName']) > 0 ) {
        $u = new UserModel(UserModel::RO);
        try {
            $u->fetchUserByUserName($_POST['loginUserName']);
            $p = $u->checkPassword($u->getUserId());

            if ($p == null && strlen($p) <= 0) {
                // if no password, set it
                $u->setPassword($_POST['loginPassword']);
            } else {
                // if password, compare
                if($p != $_POST['loginPassword']) {
                    $err =  "Bad Username/Password!";
                    $userName = $_POST['loginUserName'];
                }
            }
        } catch (Exception $e) {
            $err = $e->getMessage();
        }
    } else {
        $err = "Please provide user name/password";
    }

    if (strlen($err) <= 0 ) {
        //echo "Setting Cookies!<br/>";
        // set cookie with username and userid
        setcookie("userId", $u->getUserId(), time()+3600, '/');
        setcookie("username", $u->uname, time()+3600, '/');

        //redirect to the home page!
        header("Location: http://{$_SERVER['HTTP_HOST']}/osomf/user/view/1");
    }

}
/* End of Authentication */

    echo "<pre>".print_r($_COOKIE, true)."</pre>";

include 'www/views/header.phtml';
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript" src="/osomf/www/js/crypt.js"></script>
<link rel="stylesheet" href="/osomf/www/css/main.css" />

    <form id='loginForm' method="post">
<fieldset>
    <legend>Login Information </legend>
    <span class="error"><?php echo $err; ?></span>
    <dl>
        <dt><label>username</label></dt>
        <dd><input type="text" name="loginUserName" value="<?php echo $userName; ?>"/></dd>
    </dl>

    <dl>
        <dt><label>password</label></dt>
        <dd><input id="loginPassword" type="password" name="loginPassword"/></dd>
    </dl>

    <dl>
        <dd>
        <input type="submit" name="frmSubmit" value="login"/>
        </dd>
    </dl>

</fieldset>
</form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('fieldset').each(function () {
                var heading = $('legend', this).remove().text();
                $('<h4></h4>')
                .text(heading)
                .prependTo(this);
            });
            $('form').submit(function () {
                var clearPass = $('#loginPassword').val();
                $('#loginPassword').val(Sha1.hash(clearPass));
            });
        });
    </script>
