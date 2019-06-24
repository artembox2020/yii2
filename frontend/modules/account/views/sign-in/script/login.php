<script>
    var authLinks = document.querySelectorAll(".google-login, .fb-login"); // authorization links

    for (var i = 0; i < authLinks.length; ++i) {
        authLinks[i].onclick = function () { authLinkClick(); };
    }

    // authorization link click processing
    function authLinkClick()
    {
        var ajax = new XMLHttpRequest();
        ajax.addEventListener("load", function() {
            if(JSON.parse(ajax.responseText).result) {
                clearInterval(hInterval);
                location.reload();
            }
        });

        var hInterval = setInterval(
            function() {
                ajax.open("GET", "/account/sign-in/is-user-logged", true);
                ajax.send();
            },
            200
        );
    }
</script>