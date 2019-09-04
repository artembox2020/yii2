<script>
    var coworker = document.querySelector("#editcoworker");
    var changeAvatar = coworker.querySelector(".change-btn");
    var changeAvatarInput = coworker.querySelector(".change-avatar-block input[type=file]");
    var modalWindow = coworker.querySelector("#modal-crop");
    var cropBtn = modalWindow.querySelector("button.crop");
    var cancelBtn = modalWindow.querySelector("button.btn-default");
    var editBtn = document.querySelector("*[data-target='#editcoworker']");
    var delBtns = document.querySelectorAll("<?= $deleteModalSelector ?>");
    var closeBtns = document.querySelectorAll("button.close");
    var editPens = document.querySelectorAll(".edit-pen");

    // change avatar label(button) click processing
    changeAvatar.onclick = function(e)
    {
        e.preventDefault();
        var changeAvatarInput = coworker.querySelector(
            ".change-avatar-block input[type=file]"
        );
        changeAvatarInput.click();
    }

    // change avatar input change
    changeAvatarInput.onchange = function()
    {
        var changeAvatarBlock = this.closest(".change-avatar-block");
        changeAvatarBlock.classList.remove('hidden');
    }

    // redraw modal edit window, close and open
    function redrawModal(redrawModalSelector)
    {
        var editBtn = document.querySelector(redrawModalSelector);

        editBtn.click();

        var hInt = setInterval(function() {
            var ariaHidden = coworker.getAttribute('aria-hidden');
            if (ariaHidden != null && ariaHidden != '') {
                setTimeout(function(){ editBtn.click(); }, 800);
                clearInterval(hInt);
            }
        }, 10);
    }

    // crop button click process
    cropBtn.onclick = function()
    {
        redrawModal("<?= $redrawModalSelector ?>");
    }

    // cancel button click process
    cancelBtn.onclick = function()
    {
        redrawModal("<?= $redrawModalSelector ?>");
    }

    // avatar deletion click process
    function deleteEmployee(delBtn)
    {
        var username = document.querySelector("#del-coworker .username");
        var usernameCell = delBtn.closest("tr");

        if (usernameCell) {
            usernameCell = usernameCell.querySelector('td.name');
        }

        if (typeof usernameCell == 'undefined' || usernameCell == null) {
            var usernameCell = document.querySelector(".net-manager-new .coworker-card .username");
        }
        username.innerHTML = usernameCell.innerHTML;

        var erasecancelBtn = document.querySelector("#del-coworker .erase-cancel-btn");
        erasecancelBtn.onclick = function() {
            delBtn.click();
        }

        var eraseBtn = document.querySelector("#del-coworker .erase-btn");
        if (eraseBtn) {
            eraseBtn.onclick = function() {
                location.href = "/net-manager/delete-employee?id="+delBtn.dataset.deleteId;
            }
        }
    }

    // set edition pen click function
    for (var i = 0; i < editPens.length; ++i) {
        var editPen = editPens[i];
        editPen.onclick = function() {
            employeeEditionFunc(this);
        }
    }

    // set deletion click function
    for (var i = 0; i < delBtns.length; ++i) {
        delBtns[i].onclick = function() {
            deleteEmployee(this);
        }
    }

    // edit employee function
    function employeeEditionFunc(editPen)
    {
        var form = coworker.closest('form');
        form.action = '/net-manager/edit-employee?id=' + editPen.dataset.id;

        ajax = new XMLHttpRequest();
        ajax.addEventListener("load", function() {
            editEmployeeLoader(ajax);
        });
        ajax.open("GET", "/net-manager/edit-employee-data?id=" + editPen.dataset.id, true);
        ajax.send();
    }

    // load employee by ajax 
    function editEmployeeLoader(ajax)
    {
        var data = JSON.parse(ajax.responseText);
        coworker.querySelector("input[name='UserForm[username]']").value= data.username;
        coworker.querySelector("input[name='UserProfile[position]']").value= data.position;
        coworker.querySelector("input[name='UserProfile[birthday]']").value= data.birthday;
        coworker.querySelector("input[name='UserForm[email]']").value= data.email;
        coworker.querySelector("input[name='UserProfile[firstname]']").value= data.firstname;
        coworker.querySelector("select[name='UserProfile[gender]']").value= data.gender;
        coworker.querySelector("input[name='UserProfile[lastname]']").value= data.lastname;
        coworker.querySelector("textarea[name='UserProfile[other]']").value= data.other;
        coworker.querySelector("input[name='UserForm[status]']").value= data.status;
        coworker.querySelector(".avatar-img img").src = data.storageUrl + '/avatars/' + data.avatar_path;
        var roles = coworker.querySelectorAll("input[name='UserForm[roles][]']");

        for (var i = 0; i < roles.length; ++i) {
            roles[i].removeAttribute('checked');
        }

        for (var role in data.roles) {
            for (var i = 0; i < roles.length; ++i) {
                if (roles[i].value == role) {
                    roles[i].setAttribute('checked', 'checked');
                }
            }
        }

        cropBtn.onclick = function()
        {
            redrawModal(data.redrawModalSelector);
        }

        cancelBtn.onclick = function()
        {
            redrawModal(data.redrawModalSelector);
        }
    }
</script>