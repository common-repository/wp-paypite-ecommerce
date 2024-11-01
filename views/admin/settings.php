<div class="paypite-settings">
	<h1 class="paypite-text-purple">Paramètres</h1>

	<form method="post" action="" class="paypite-form settings">
		<div class="paypite-mb-1">
			<label>Votre ID vendeur</label>
			<input type="text" name="vendor_id" maxlength="256" value="<?php echo $this->get_vendor_id(); ?>" required />
		</div>
		
		<div>
			<input type="hidden" name="update_settings" value="1" />
			<input type="submit" name="submit" value="Mettre à jour" />
		</div>
		
		<?php
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/action-performed-notice.php");
		?>
	</form>

	<p>
		<strong>
			Avant de pouvoir utiliser le plugin, vous devez vous procurez de votre identifiant vendeur et l'indiquer dans le formulaire ci-dessus.
			<br />
			Pour avoir cette information, veuillez écrire à <a href="mailto:contact@paypite.org">contact@paypite.org</a>
		</strong>
	</p>
</div>