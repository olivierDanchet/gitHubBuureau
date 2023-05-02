$(document).ready(function(){
	var defaultWidth = $('.responsive').width();
	var defaultWidth2 = $('.responsive2').width();
	if($(window).width() < 980){
		$('#navbar a').hide();
		$('.panier_head').show();
		$('.uncollapsed').hide();
		$('.bi-list').show();
		$('nav ul').css('justify-content', 'unset');
		$('.responsive').css('width', '100vw');
		$('.collapsed').show();
		$('.admin_navbar').hide();
		$('.compteur').hide();

		
		
	} else {
		$('#navbar a').show();
		$('.compteur').show();
		$('nav ul').css('justify-content', 'center');
		$('.collapsed').hide();
		$('.bi-list').hide()
		$('.uncollapsed').show();
		$('.admin_navbar').show();

		$('.responsive').css('width', defaultWidth + 'px');
		

	}


	// MORE MENU
	$('.bi-list').click(function(){
		$('.more_menu').slideToggle()

		
	});

	//MORE PROFIL
	$('.bi-person-circle').click(function(){
		$('.more_profil').toggle()

	});


	$(document).click(function(event) {
	if ($(event.target).hasClass('menu-update') || $(event.target).closest('.overlay_update').length) {
		return;
	}

	if ($('.overlay_update').is(':visible')) {
		$('.overlay_update').hide(200);
	}
	});

  });
  
  $(window).resize(function(){
	$('.more_profil').toggle(false);
	if($(window).width() < 980) {
	  $('#navbar a').hide();
	  $('.compteur').hide();
	  $('.panier_head').show();
	  $('.bi-list').show();
	  $('nav ul').css('justify-content', 'unset');
	  $('.bi-person-circle').css('justify-content','flex-end')
	  $('.collapsed').show();
	  $('.uncollapsed').hide();
	  $('.admin_navbar').hide();
	  $('.responsive').css('width', '100vw')
	  $('.responsive').css('width', '100vw')
	} else {
	  $('#navbar a').show();
	  $('.compteur').show();
	  $('nav ul').css('justify-content', 'center');
	  $('.more_menu').hide();
	  $('.collapsed').hide();
	  $('.uncollapsed').show();
	  $('.bi-list').hide();
	  $('.admin_navbar').show();
	  $('.responsive').css('width', defaultWidth + 'px');

	}
  });
  
