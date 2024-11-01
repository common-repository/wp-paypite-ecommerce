<?php
	if(isset($_SESSION["action_performed_notice"]))
	{
		?>
			<div class="paypite-mt-1 alert alert-success">
				<strong>
					<span class="dashicons dashicons-yes"></span>
					<?php echo $_SESSION["action_performed_notice"]; ?>
				</strong>
				
				<?php
					if(isset($_GET["page"]) && $_GET["page"] == "wp-add-product-paypite-ecommerce")
					{
						?>
							<div>
								<a href="?page=wp-paypite-ecommerce"><strong>Revenir à la liste</strong></a>
							</div>
						<?php
					}
				?>
			</div>
		<?php
		
		unset($_SESSION["action_performed_notice"]);
	}