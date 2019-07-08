<script>
    var btnUser = document.querySelector(".btn-user");

    btnUser.onclick = function() {
        var userActions = document.querySelector(".user-actions");
        if (userActions.classList.contains('d-flex')) {
            userActions.classList.remove('d-flex');
            userActions.style = "display: none;";
        } else {
            userActions.classList.add('d-flex');
            userActions.style = "display: flex;";
        }
    }
</script>