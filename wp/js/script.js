   $=jQuery.noConflict();
    jQuery(function(){
    	$('.spot.bordeaux').mouseover(function(){
    		$('.ph').hide();
			$('.ph.bordeaux').slideDown('fast');
			$('.link').removeClass('current');
			$('.link.bordeaux').addClass('current');
		});
		$('.spot.burgundy').mouseover(function(){
			    		$('.ph').hide();
			$('.ph.burgundy').slideDown('fast');
			$('.link').removeClass('current');
			$('.link.burgundy').addClass('current');
		});
		$('.spot.champagne').mouseover(function(){
			    		$('.ph').hide();
			$('.ph.champagne').slideDown('fast');
			$('.link').removeClass('current');
			$('.link.champagne').addClass('current');
		});
		$('.spot.alsace').mouseover(function(){
			    		$('.ph').hide();
			$('.ph.alsace').slideDown('fast');
			$('.link').removeClass('current');
			$('.link.alsace').addClass('current');
		});
		$('.spot.southfrance').mouseover(function(){
			    		$('.ph').hide();
			$('.ph.southfrance').slideDown('fast');
			$('.link').removeClass('current');
			$('.link.southfrance').addClass('current');
		});
		var p1h=$('.p1').height();
		if (p1h>330) {
			$('.more').show();
			$('.p1').addClass('p2')
		};
		$('.more').click(function() {
 			 $('.p1').removeClass('p2');
 			 $('.p1').css("padding-bottom","40px");
 			 $('.more').hide();
		});
    });
