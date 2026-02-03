$(document).ready(function() {

	window.addEventListener('scroll', function() {
		if(window.scrollY >= 250) {
			$('.navigate').removeClass('active')
		} else {
			$('.navigate').addClass('active')
		}
	})

	$('.endtoend-card').mouseenter(function() {
		$(this).addClass('active')
	})
	$('.endtoend-card').mouseleave(function() {
		$(this).removeClass('active')
	})
	$('.charge-card').mouseenter(function() {
		$(this).addClass('active')
	})
	$('.charge-card').mouseleave(function() {
		$(this).removeClass('active')
	})



})
