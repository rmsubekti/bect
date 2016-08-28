var main =function($){
    $( "#open" ).click(function() {
  		$("body").toggleClass( "open" );
	});
    $( "#close" ).click(function() {
  		$("body").toggleClass( "open" );
	});
}(jQuery);
jQuery(document).ready(main);

