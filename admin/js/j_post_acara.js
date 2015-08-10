jQuery(function($) {
	var val_holder;
	$("form input[name='btnsubmitacara']").click(function() { // triggred click
		val_holder 		= 0;
		
		var txtjdlacara 	= jQuery.trim($("form input[name='txtjdlacara']").val()); // email field
		var txtktpacara 	= jQuery.trim($("form input[name='txtktpacara']").val()); // email field
		
		if(txtjdlacara == "") {
			$("span.txtjdlacara_val").html("Judul tidak boleh kosong.");
		val_holder = 1;
		}
		if(txtktpacara == "") {
			$("span.txtktpacara_val").html("Kutip tidak boleh kosong.");
		val_holder = 1;
		}
		if(val_holder == 1) {
			return false;
		}
		

		val_holder = 0;

		$("span.validation").html("");

	var datastring = $('#formacara');			
	datastring.on('submit', function(e) {
    e.preventDefault();



      $.ajax({
      url: 'fungsi/f_post_acara.php',
      type: "POST",
      dataType: "text json",
      data: datastring.serialize(),
      beforeSend: function() {
      },

					success: function(e) {
						if(e == 1) {
							$("span.txtalertacara_val").html("Judul, Isi & Tags tidak boleh kosong!!");
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