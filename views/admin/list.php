<div class="paypite-list">
	<h1 class="paypite-text-purple">Liste des produits</h1>

	<table class="table">
		<tr>
			<th>Nom du produit</th>
			<th>Prix</th>
			<th>Crypto<span class="dashicons dashicons-editor-help paypite-dashicon" title="<?php echo CRYPTO_HELP; ?>"></span></th>
			<th class="th-description">Description</th>
			<th class="th-callback-url">URL de callback<span class="dashicons dashicons-editor-help paypite-dashicon" title="<?php echo CALLBACK_URL_HELP; ?>"></span></th>
			<th>Shortcode</th>
			<th>Actions</th>
		</tr>
		
		<?php
			if(count($products) == 0)
			{
				?>
					<tr>
						<td colspan="7" class="paypite-text-center paypite-text-bold">Aucun produit à afficher pour le moment</td>
					</tr>
				<?php
			}
			else
			{
				foreach($products as $product)
				{
					?>
						<tr>
							<td class="paypite-text-center"><?php echo $product->name; ?></td>
							<td class="paypite-text-center"><?php echo $product->amount . " " . $product->currency; ?></td>
							<td class="paypite-text-center"><?php echo $product->crypto; ?></td>
							<td class="paypite-text-center"><?php echo $product->description; ?></td>
							<td class="paypite-text-center"><?php echo ($product->callback_url != "" ? $product->callback_url : "Par défaut"); ?></td>
							<td class="paypite-text-center"><input type="text" class="paypite-shortcode" value='[paypite id="<?php echo $product->id; ?>" show_details="yes"]' /></td>
							<td class="paypite-text-center">
								<a href="?page=wp-paypite-ecommerce&tab=update&id=<?php echo $product->id; ?>">Modifier</a>
								- 
								<a href="?page=wp-paypite-ecommerce&tab=delete&id=<?php echo $product->id; ?>">Supprimer</a>
							</td>
						</tr>
					<?php
				}
			}
		?>
	</table>
</div>