$(document).ready(function () {
    var called_page = $('#called_page').html();

    $('#' + called_page).addClass('active');

    //code for panel extansion
    $(document).on('click', '.panel div.clickable', function (e) {
        var $this = $(this); //Heading
        var $panel = $this.parent('.panel');
        var $panel_body = $panel.children('.panel-body');
        var $display = $panel_body.css('display');

        if ($display == 'block') {
            $panel_body.slideUp();
        } else if ($display == 'none') {
            $panel_body.slideDown();
        }
    });

    var $classy = '.panel.autocollapse';

    var $found = $($classy);
    $found.find('.panel-body').hide();
    $found.removeClass($classy);
    
    $('#register-button-link').on('click', function () {
        window.location='?page=register';
    });

});
