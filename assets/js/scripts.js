jQuery( document ).ready( function() {
	jQuery( 'body' ).on( 'click', '.hamburger.hamburger--animate', function( e ) {
		e.preventDefault();
		$this = jQuery( this );
		$this.toggleClass( 'hamburger--open' );
	});
});
