$(document).ready(function () {

    $(document).on('click', '#save-profile-btn', function (e) {
        e.preventDefault();

        var login      = $('#login-input').val();
        var firstName  = $('#firstname-input').val();
        var secondName = $('#secondname-input').val();
        var workplace  = $('#workplace-input').val();
        var bio        = $('#bio-input').val();

        $.ajax({
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            url: '/api/updateUser',
            method: 'POST',
            data: {
                'login':      login,
                'firstName':  firstName,
                'secondName': secondName,
                'workplace':  workplace,
                'bio':        bio
            },
            success: function (jqXHR, textStatus) {
                // console.log(jqXHR);
                var data = JSON.parse(jqXHR);
                $('#login-input').val(data.login);
                $('#firstname-input').val(data.firstName);
                $('#secondname-input').val(data.secondName);
                $('#workplace-input').val(data.workPlace);
                $('#bio-input').val(data.biography);
            }
        });
    });

    $(document).on('submit', '#save-profile-form', function (e) {
        e.preventDefault();
    });

});