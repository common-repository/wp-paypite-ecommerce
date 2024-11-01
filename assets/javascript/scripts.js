jQuery(document).ready(function(){
	//Copy automatically shortcodes on admin's dashboard
	jQuery("input.paypite-shortcode").on("click", function(){
		jQuery(this).select();
		document.execCommand("copy");
	});
});