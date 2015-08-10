jQuery(function($) {
	var val_holder;
	$("form input[name='btnsubmit']").click(function() { // triggred click
		val_holder 		= 0;
		
		var txtjdlabout 	= jQuery.trim($("form input[name='txtjdlabout']").val()); // email field
		
		if(txtjdlabout == "") {
			$("span.txtjdlabout_val").html("Judul tidak boleh kosong.");
		val_holder = 1;
		}
		if(val_holder == 1) {
			return false;
		}
		

		val_holder = 0;

		$("span.validation").html("");

	var datastring = $('#formabout');			
	datastring.on('submit', function(e) {
    e.preventDefault();



      $.ajax({
      url: 'fungsi/f_post_about.php',
      type: "POST",
      dataType: "text json",
      data: datastring.serialize(),
      beforeSend: function() {
      },

					success: function(e) {
						if(e == 1) {
							$("span.txtalertabout_val").html("Judul, Isi & Tags tidak boleh kosong!!");
						} else {
							if(e == 3) {
								$("form input[type='text']").val('');

								setTimeout(function(){
								document.location.reload();
								},1000);

								datastring.trigger('reset');
						}
						}
					}
	  });
		});
	});
});