jQuery(document).ready( function () {
	jQuery(document).on( 'change' ,'#payment_method_class_meezan_bank' , function() {
		// for blocked checkout use #radio-control-wc-payment-method-options-class_meezan_bank
		jQuery("input[type=radio]").each(function() {
			var value = jQuery(this).val();
			jQuery(this).prop('checked', false);
		});
		jQuery(this).prop('checked', true);
	});
});