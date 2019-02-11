(function($) {

function checkPasswordStrength( $pass1, $strengthResult ) {

    var pass1 = $pass1.val();
    var blacklist = wp.passwordStrength.userInputBlacklist();
    var strength = wp.passwordStrength.meter( pass1, blacklist );

    $strengthResult.removeClass( 'short bad good strong' ).html( '' );
    if (pass1.length > 0) {
        switch ( strength ) {
            case 2:
                $strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
                break;
            case 3:
                $strengthResult.addClass( 'good' ).html( pwsL10n.good );
                break;
            case 4:
                $strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
                break;
            default:
                $strengthResult.addClass( 'short' ).html( pwsL10n.short );
        }
    }

    return strength;

}

jQuery( document ).ready( function( $ ) {
    $( 'body' ).on( 'keyup', 'input[name=pass1]', function( event ) {
        checkPasswordStrength( $( 'input[name=pass1]' ), $( '#hcup_pass_strength' ) );
    });
});

})(jQuery);