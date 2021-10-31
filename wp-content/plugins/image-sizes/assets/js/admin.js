jQuery(function($){
	$('.image-sizes-help-heading').click(function(e){
		var $this = $(this);
		var target = $this.data('target');
		$('.image-sizes-help-text:not('+target+')').slideUp();
		if($(target).is(':hidden')){
			$(target).slideDown();
		}
		else {
			$(target).slideUp();
		}
	});

    // enable/disable
	var chk_all = $(".check-all");
	var chk_def = $(".check-all-default");
	var chk_cst = $(".check-all-custom");

	chk_all.change(function () {
	    $('.check-all-default,.check-all-custom').prop('checked',this.checked).change();
	});

	chk_def.change(function () {
	    $('.check-default').prop('checked',this.checked);
	    $('.check-this').change();
	});

	chk_cst.change(function () {
	    $('.check-custom').prop('checked',this.checked);
	    $('.check-this').change();
	});

	// counter
	$('.check-this').change(function(e){
		var total = $('.check-this').length;
		var enabled = $('.check-this:not(:checked)').length;
		var disabled = $('.check-this:checked').length;

		$('#disabled-counter .counter').text(disabled);
		$('#enabled-counter .counter').text(enabled);
	}).change();

	var limit = $('#image-sizes_regenerate-thumbs-limit').val();

	$("#image-sizes_regenerate-thumbs-limit").bind('keyup mouseup', function () {
	    limit = $(this).val();            
	});

	// var limit 	= 50;
	var offset 	= 0;
	var thumbs_deleted 	= 0;
	var thumbs_created 	= 0;

	function regenerate( limit, offset, thumbs_deleted, thumbs_created ) {
		$.ajax({
				url: CXIS.ajaxurl,
				type: 'GET',
				data: { action : 'cxis-regen-thumbs', 
				'offset' : offset, 
				'limit' : limit, 
				'thumbs_deleteds' : thumbs_deleted, 
				'thumbs_createds' : thumbs_created, 
				'_nonce' : CXIS.nonce 
			},
			success: function(res) {

				if ( res.has_image ) {
					var progress = res.offset / res.total_images_count * 100;
					$('.image-sizes-progress-content').text(Math.ceil( progress ) + '%').css({'width': progress + '%'});

					regenerate( limit, res.offset, res.thumbs_deleted, res.thumbs_created );
				}
				else {
					$('#cxis-regen-thumbs').text(CXIS.regen).attr('disabled', false);
					$('#cxis-message').html(res.message).show();
					$('.image-sizes-progress-panel .image-sizes-progress-content').addClass('progress-full');
				}
				console.log(res);
			},
			error: function(err){
				$('#cxis-regen-thumbs').text(CXIS.regen).attr('disabled', false);
				console.log(err);
			}
		})
	}

	// cx-regen-thumbs
	$('#cxis-regen-thumbs').click(function(e){
		$('#cxis-regen-thumbs').text(CXIS.regening).attr('disabled', true);
		$('#cxis-message').html('').hide();
		$('.image-sizes-progress-panel').hide();

		regenerate( limit, offset, thumbs_deleted, thumbs_created );

		// $('#cxis-regen-wrap').append('<progress id="cxis-progress" value="0" max="100"></progress>');
		$('.cxis-regen-thumbs-panel').after('<div class="image-sizes-progress-panel"><div class="image-sizes-progress-content" style="width:0%"><span>0%</span></div></div></div>');
	});

	// dismiss
	$('.cxis-dismiss').click(function(e){
		var $this = $(this);
		$this.parent().slideToggle();
		$.ajax({
			url: CXIS.ajaxurl,
			data: { action : 'cxis-dismiss', meta_key : $this.data('meta_key'), '_nonce' : CXIS.nonce  },
			type: 'GET',
			success: function(res) {console.log(res);},
			error: function(err){console.log(err);}
		})
	})

	$('#cxis-regen-wrap span').click(function(e){
		alert($(this).attr('title'))
	})
})