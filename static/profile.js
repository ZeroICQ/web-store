$(document).ready(function () {

    $(document).on('click', '#save-profile-btn', function (e) {
        e.preventDefault();

        var login      = $('#login-input').val();
        var firstName  = $('#firstname-input').val();
        var secondName = $('#secondname-input').val();
        var workplace  = $('#workplace-input').val();
        var bio        = $('#bio-input').val();
        
    });

    $(document).on('submit', '#save-profile-form', function (e) {
        e.preventDefault();
    });

});