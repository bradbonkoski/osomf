
var UserId = -1;
var UserName = '';

function checkForLogin()
{
    //var userId = parseInt(localStorage.getItem("osomf-userId"));
    //var userName = localStorage.getItem("osomf-username");
    if( !$.cookies.test() ) {
        alert("No Cookies supported?");
    }
    var userId = $.cookies.get('userId');
    var userName = $.cookies.get('username');
    if (userId == null || userName == null) {
        // do a redirect to the login page!
        //var cookieUserId = $.cookie.get('userId');
        //var cookieUserName = $.cookie.get('username');
        //if (cookieUserId == null || cookieUserName == null) {
            alert("You need to log in![1]");
            window.location = "/osomf/www/login.php?ref="+document.URL;
        //} else {
        //    localStorage.setItem('osomf-userId', cookieUserId);
        //    localStroage.setItem('osomf-username', cookieUserName);
        //    localStorage.setItem('osomf-expire', Date().getTime() + 12800);
        //}
    }

    //UserId = userId;
    //UserName = username;
//    var expired = Date().getTime();
//    if (parseInt(localStorage.getItem('osomf-expire')) < expired) {
//        //redirect to login here as well
//        alert("You need to log in![2]");
//        window.location = "/osomf/www/login.php";
//    }
}
