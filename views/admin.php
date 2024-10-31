<?php

defined( 'ABSPATH' ) or die( '-1' );
?>
<div class="wrap">
	<h2>SafeCode</h2>

	<form action="" method="post">

		<?php wp_nonce_field( 'safecode_update' ); ?>

		<textarea cols="70" name="custom-functions" id="custom-functions" dir="ltr" tabindex="1"><?php echo esc_html( get_option( 'safecode', "<?php \n\n" ) ) ?></textarea>
		<input type="hidden" name="scrollto" id="scrollto" value="<?php echo isset( $_REQUEST['scrollto'] ) ? (int) $_REQUEST['scrollto'] : 0; ?>" />

		<?php submit_button() ?>
	</form>
</div><!-- .wrap -->