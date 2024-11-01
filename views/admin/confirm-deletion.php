<div class="paypite-confirm-deletion">
	<h1 class="paypite-text-purple">Voulez-vous vraiment supprimer ce produit ?</h1>

	<div>
		<?php
			//Sanitizing ID
			$id = sanitize_text_field($_GET["id"]);
			
			//Validating ID
			!is_numeric($id) ? die(FORM_VALIDATION_ERROR) : "";
			
			//Escaping ID
			$id = $this->escape_input($id, "numeric");
		?>
	
		<a href="?page=wp-paypite-ecommerce&tab=delete&id=<?php echo $id; ?>&proceed=1" class="paypite-text-red paypite-mr-1"><strong>Supprimer</strong></a>
		<a href="?page=wp-paypite-ecommerce"><strong>Annuler</strong></a>
	</div>
</div>
