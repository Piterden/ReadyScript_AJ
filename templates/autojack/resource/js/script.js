$(document).ready(function () {

	$('#slider1').carousel({
		interval: 3000,
		pause: false
	});
	
	$('#slider2').carousel({
		interval: 3000,
		pause: false
	});

	$('#slider1').on('slide.bs.carousel', function (e) {
		//console.log(e.direction);
		if (e.direction == 'left') {
			$('#slider2').carousel('next');
		} else {
			$('#slider2').carousel('prev');
		}
	})


});