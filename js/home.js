/**
 * Created by TipsyCoder on 11/17/15.
 */

$(document).ready(function() {
    var isEditing = false;

    $(document).on('click','#editMe', function() {
        if(!isEditing) {
            $(this).css({'color': '#e74c3c'});
            goodSweet('EDITING ENABLED', 'Click on the field you want to edit.\nWhen finished click back the pencil icon.');
        } else {
            $(this).css({'color': '#2c3e50'});
            goodSweet('EDITING DISABLED', 'Great Editing Skills');
        }

        isEditing = !isEditing;
    });

    $(document).on('click','.emailEdit', function() {
        if(isEditing) {
            badSweet('ERROR', 'This is key element.\nContact Admin Support.');
        }
    });

    $(document).on('click','.fNameEdit', function() {
        if(isEditing) {
            showInputSweet('First Name', 'fName');
        }
    });

    $(document).on('click','.lNameEdit', function() {
        if(isEditing) {
            showInputSweet('Last Name', 'lName');
        }
    });

    $(document).on('click','.dobEdit', function() {
        if(isEditing) {
            showInputSweet('Date Of Birth', 'dob');
        }
    });

    $(document).on('click','.nationEdit', function() {
        if(isEditing) {
            showInputSweet('Nationality', 'nation');
        }
    });

    $(document).on('click','.dummyEdit', function() {
        if(isEditing) {
            infoSweet('INFORMATION', 'Cannot Edit At This Moment');
        }
    });
});

function showInputSweet(field, aField) {
    swal({
        title: "Change " + field,
        text: "What's the new " + field + "?",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Type " + field
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Text is needed");
            return false
        }

        $.ajax({url: "../core/userservice.php?opt=update&field=" + aField + "&value=" + inputValue, success: function(result){
            var json = JSON.parse(result);
            console.log(json);
            if(json.success) {
                goodSweet('SUCCESS', 'Changes Made.');
                window.location.reload();
            } else {
                badSweet('ERROR', json.errMsg);
            }
        }});
    });
}
