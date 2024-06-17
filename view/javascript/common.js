$(document).ready(function() {
	/* Search */
	$('.button-search').bind('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';

		var search = $('input[name=\'search\']').attr('value');

		if (search) {
			url += '&search=' + encodeURIComponent(search);
		}

		location = url;
	});

	$('#header input[name=\'search\']').bind('keydown', function(e) {
		if (e.keyCode == 13) {
			url = $('base').attr('href') + 'index.php?route=product/search';

			var search = $('input[name=\'search\']').attr('value');

			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}

			location = url;
		}
	});

	/* Ajax Cart */
	$('#cart > .heading a').live('click', function() {
		$('#cart').addClass('active');

		$('#cart').load('index.php?route=module/cart #cart > *');

		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});

	/* Mega Menu */
	$('#menu ul > li > a + div').each(function(index, element) {
		// IE6 & IE7 Fixes
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
			var category = $(element).find('a');
			var columns = $(element).find('ul').length;

			$(element).css('width', (columns * 143) + 'px');
			$(element).find('ul').css('float', 'left');
		}

		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');

			$('#column-right + #content').css('margin-right', '195px');

			$('.box-category ul li a.active + ul').css('display', 'block');
		}

		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});

			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});
		}
	}

	$('.success img, .warning img, .attention img, .information img').live('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});
});

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
function isIMEIValid(imei){

    if (!/^[0-9]{15}$/.test(imei)) {return false;}

    var sum = 0, factor = 2, checkDigit, multipliedDigit;

    for (var i = 13, li = 0; i >= li; i--) {
      multipliedDigit = parseInt(imei.charAt(i), 10) * factor;
      sum += (multipliedDigit >= 10 ? ((multipliedDigit % 10) + 1) : multipliedDigit);
      (factor === 1 ? factor++ : factor--);
    }
    checkDigit = ((10 - (sum % 10)) % 10);

    return !(checkDigit !== parseInt(imei.charAt(14), 10))
}
function addToCartHeader(carrier_id, category_id, product_id, imei, email, language, callbackOk, callbackBad) {
		var duplicate_title, duplicate_content, duplicate_yes, duplicate_no;
        $('#errors').html('');
		if (language == 'es') {
			duplicate_title = "Se ha detectado una orden duplicada";
			duplicate_content = "Hemos detectado que este IMEI ya está en tu carrito de compras. Deseas agregarlo de nuevo?";
			duplicate_yes = "No, ir a mi carrito";
			duplicate_no = "Si, agregar a mi carrito";
			err_carrier = 'Debes seleccionar un operador!';
			err_brand = 'Debes seleccionar una marca!';
			err_product = 'Debes seleccionar un producto!';
			err_imei = 'Debes ingresar el IMEI de tu teléfono!';
			err_invalidimei = 'IMEI inválido.  Ingresa un IMEI válido!';
			err_imeilength = 'IMEI should be 15 digit long!';
			err_email = 'Debes ingresar un correo electrónico!';
			err_imeitype = 'El IMEI no debe contener letras, puntos o espacios!';
			err_invalidemail = 'Correo electrónico inválido!';
			msg_delayed = "Este servicio está de baja durante las fiestas de fin de año y se reactivará el 4 de enero. Si deseas, puedes colocar tu orden ahora. Sin embargo, toma en cuenta que tu orden no va a ser procesada hasta entonces.";
		} else {
			duplicate_title = "Duplicate order detected";
			duplicate_content = "We have detected that you already have this IMEI in your cart. Would you like to add it anyway?";
			duplicate_yes = "No, go to cart";
			duplicate_no = "Yes, add to cart";
			err_carrier = 'You must select a carrier!';
			err_brand = 'You must select a brand!';
			err_product = 'You must select a product!';
			err_imei = 'You must enter your telephone imei!';
			err_invalidimei = 'Invalid IMEI. Please enter valid IMEI!';
			err_imeilength = 'IMEI should be 15 digit long!';
			err_email = 'You must enter your email!';
			err_imeitype = 'IMEI can not contain letters, periods, or spaces!';
			err_invalidemail = 'Invalid email id!';
			msg_delayed = "This service is down for the holidays and will be back on January 4th. If you wish, you can place your order now; but be aware that it will not be processed until then.";

		}
        json1 = new Array();
        if(category_id == ''){
            json1[2] = err_brand;
            $('#errors').append('<span class="error">' + json1[2] + '</span>');
        }
        if(product_id == ''){
            json1[3] = err_product;
            $('#errors').append('<span class="error">' + json1[3] + '</span>');
        }
        if(imei == '' || imei == 'Enter IMEI Number - first 15 digits' ){
            json1[4] = err_imei;
            $('#errors').append('<span class="error">' + json1[4] + '</span>');
        }
		else if(!isIMEIValid(imei)){
			json1[4] = err_invalidimei;
            $('#errors').append('<span class="error">' + json1[4] + '</span>');
			$('#imei').parents('#inputdiv').find('span.wrong').removeClass('right');
		}
		else if(imei.length != 15){
			json1[4] = err_imeilength;
            $('#errors').append('<span class="error">' + json1[4] + '</span>');
		}
		if(email == '' || email == 'Email (to receive unlock code)' ){
            json1[5] = err_email;
            $('#errors').append('<span class="error">' + json1[5] + '</span>');
        }

        var reg = new RegExp('^[0-9]+$');
        if(! reg.test(imei) ){
            json1[4] = err_imeitype;
            $('#errors').append('<span class="error">' + json1[4] + '</span>');
        }

		var regemail = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        if(! regemail.test(email) ){
            json1[5] = err_invalidemail;
            $('#errors').append('<span class="error">' + json1[5] + '</span>');
        }

		function addToCartQuery(carrier_id, category_id, product_id, imei, email, force, callback_ok) {
			if (typeof(force) == "undefined") {
				force = false;
			}

			$.ajax({
				url: 'index.php?route=checkout/cart/update',
				type: 'post',
				data: 'carrier_id=' + carrier_id + '&category_id=' + category_id + '&product_id=' + product_id + '&imei=' + imei + '&email=' + email + (force ? "&force=true" : ""),
				dataType: 'json',
				success: callback_ok
			});

		}

        if(json1.length > 0){
	        if (typeof(callbackBad) == "function") {
		        callbackBad();
	        }
	        return false;
        }else{
	        if (typeof(callbackOk) == "function") {
		        callbackOk();
	        }

	        addToCartQuery(carrier_id, category_id, product_id, imei, email, false, function addToQueryCallback(json) {
		        $('.success, .warning, .attention, .information, .error').remove();
		        var buttonClicked = false;

		        $("#dialog-duplicate").prop("title", duplicate_title);
		        $("#dialog-duplicate-content").text(duplicate_content);

		        var buttons = {};
		        buttons[duplicate_yes] = function() {
			        if(json['success'] && json['redirect'] && !buttonClicked) {
				        buttonClicked = true;
				        window.location = json['redirect'];
			        }
		        };
		        buttons[duplicate_no] = function() {
			        if (!buttonClicked) {
				        buttonClicked = true;
				        addToCartQuery(carrier_id, category_id, product_id, imei, email, true, addToQueryCallback);
			        }
		        };

		        if(json['delayed']) {
			        alert(msg_delayed);
		        }

		        if(json['duplicate']) {
			        $("#dialog-duplicate").dialog({
				        resizable: false,
				        height: 200,
				        width: 540,
				        modal: true,
				        buttons: buttons,
				        close: function() {
					        window.location = json['redirect'];
				        }
			        }).dialog("open");
			        return;
		        }

		        if (json['error']) {
			        if (json['error']['warning']) {
				        $('#dialog-cdma-content').text(json['error']['warning']);
				        $('#dialog-cdma').dialog({
					        resizeable: false,
					        height: 200,
					        width: 540,
					        buttons: {
						        OK: function() {
							        $(this).dialog("close");
						        }
					        },
					        close: function() {
						        buttonClicked = false;
						        $('#loading').hide();
						        if(typeof callbackBad == "function") {
							        callbackBad();
						        }
					        }
				        }).dialog("open");
			        }
		        }

		        if (json['success']) {
			        if (json['redirect']) {
				        window.location = json['redirect'];
			        } else {
				        $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				        $('.success').fadeIn('slow');
				        $('#cart_total').html(json['total']);
				        $('html, body').animate({scrollTop: 0}, 'slow');
			        }
		        }
	        });
        }
}


function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#cart-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
}
function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
}

function addToCompare(product_id) {
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#compare-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
}