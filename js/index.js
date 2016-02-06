// Toggle Function
$('.toggle').click(function(){
  // Switches the Icon
  $(this).children('i').toggleClass('fa-pencil');
  var text = $('div.tooltip').text().toLowerCase();
  if(text === "sign me up") {
    $('div.tooltip').text("Log Me In");
  } else {
    $('div.tooltip').text("Sign me up");
  }
  // Switches the forms  
  $('.form').animate({
    height: "toggle",
    'padding-top': 'toggle',
    'padding-bottom': 'toggle',
    opacity: "toggle"
  }, "slow");
});

function nameSort(sort, by) {
    var url = window.location.href;
    url = url.substring(0, url.indexOf('?'));
    window.location.href = url + "?sort=" + sort + "&by=" + by;
}

function searchNameSort(sort, by, url) {
    window.location.href = url + "&sort=" + sort + "&by=" + by;
}
function changePassword(email) {
    swal({
        title: "Change Password",
        text: "What's the new password?",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Type Password"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Text is needed");
            return false
        }

        $.ajax({url: "../core/superservice.php?opt=changePassword&em=" + email + "&pass=" + inputValue, success: function(){
            swal("Nice!", "Password Changed", "success");
            window.location.reload();
        }});
    });
}


function closeAccount() {
    swal({
        title: "Are you sure?",
        text: "You will not be able to view your tree again!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, I am sure!",
        closeOnConfirm: false
        }, function(){
            $.ajax({url: "../core/data.php?opt=deleteMe", success: function(){
                swal("Deleted!", "Your tree is no more!", "success");
                window.location.href = "../../index.php";
            }});
        });
}

function removeUser(email, name) {
    swal({
            title: "Are you sure?",
            text: "You destroying " + name + "'s tree!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, I am sure!",
            closeOnConfirm: false },
        function(){
            $.ajax({url: "../core/superservice.php?opt=deleteUser&em=" + email, success: function(){
                swal("Deleted!", name + "'s tree is no more!", "success");
                window.location.reload();
            }});
        });
}

function removeRelation(pEmail, cEmail) {
    swal({
            title: "Are you sure?",
            text: "You destroying a link!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, I am sure!",
            closeOnConfirm: false },
        function(){
            $.ajax({url: "../core/superservice.php?opt=deleteRelation&pEm=" + pEmail + "&cEm=" + cEmail, success: function(){
                swal("Deleted!", "link is no more!", "success");
                window.location.reload();
            }});
        });
}

function goodSweet(title, msg) {
    swal(title, msg, "success");
}

function badSweet(title, msg) {
    swal(title, msg, "error");
}

function infoSweet(title, msg) {
    swal(title, msg, "info");
}

function timeSweet(title, msg, pTime) {
    swal({
        title: title,
        text: msg,
        timer: pTime,
        showConfirmButton: false });
}
