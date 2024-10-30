<?php

/**
 * BP_Authnet_Admin
 *
 * Admin class for BP Authorize.net
 */
class BP_Authnet_Admin {

	function init() {
		add_action( 'admin_menu', array( 'BP_Authnet_Admin', 'add_settings_page' ) );
		add_action( 'admin_head', array( 'BP_Authnet_Admin', 'admin_head' ) );
	}

	function admin_head() {

	}

	function add_settings_page() {
		if ( !bp_authnet_has_access() )
			return false;

	   add_options_page( __( 'Authorize.net Settings', 'bp-authnet' ), __( 'Authorize.net', 'bp-authnet' ), 'admin-options', 'bp-authnet-admin', array( 'BP_Authnet_Admin', 'page' ) );
	}

	function page() {
		global $bp;

		if ( isset( $_POST[ 'submit' ] ) ) {
			check_admin_referer( 'BP_Authnet_Admin' );

			bp_authnet_set_setting( 'enabled',				strip_tags( $_POST['enabled'] ) );
			bp_authnet_set_setting( 'customer_id',			strip_tags( $_POST['customer_id'] ) );
			bp_authnet_set_setting( 'login_id',				strip_tags( $_POST['login_id'] ) );
			bp_authnet_set_setting( 'transaction_key',		strip_tags( $_POST['transaction_key'] ) );
			bp_authnet_set_setting( 'integration_method',	strip_tags( $_POST['integration_method'] ) );

			update_site_option( 'bp_authnet_settings', $bp->authnet->settings );
?>

		<div class="updated"><p><strong><?php _e( 'Settings saved.', 'bp-authet' ); ?></strong></p></div>
<?php	} ?>

		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
		    <h2><?php _e( 'Authorize.net Settings', 'bp-authnet' ) ?></h2>
			<form name="options" method="post" action="">
				<table class="form-table">
					<tbody>
<?php if ( defined( 'BP_PAYMENTS_VERSION' ) ) : ?>
						<tr valign="top">
							<th scope="row"><label for="enabled"><?php _e( 'Payment Method Enabled', 'bp-authnet' ); ?></label></th>
							<td>
								<select id="units" name="enabled">
									<option value="1"<?php if ( true == bp_authnet_get_setting( 'enabled' ) ) echo ' selected="selected"'; ?>><?php _e( 'Enabled', 'bp-authnet' ); ?></option>
									<option value="0"<?php if ( false == bp_authnet_get_setting( 'enabled' ) ) echo ' selected="selected"'; ?>><?php _e( 'Disabled', 'bp-authnet' ); ?></option>
								</select>
							</td>
						</tr>
<?php endif; ?>
						<tr valign="top">
							<th scope="row"><label for="customer_id"><?php _e( "Customer ID", 'bp-authnet' ); ?></label></th>
							<td>
								<input type="text" class="regular-text" id="customer_id" name="customer_id" value="<?php echo bp_authnet_get_setting( 'customer_id' ); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="login_id"><?php _e( "Login ID", 'bp-authnet' ); ?></label></th>
							<td>
								<input type="text" class="regular-text" id="login_id" name="login_id" value="<?php echo bp_authnet_get_setting( 'login_id' ); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="transaction_key"><?php _e( "Transaction Key", 'bp-authnet' ); ?></label></th>
							<td>
								<input type="text" class="regular-text" id="transaction_key" name="transaction_key" value="<?php echo bp_authnet_get_setting( 'transaction_key' ); ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="integration_method"><?php _e( 'Integration Method', 'bp-authnet' ); ?></label></th>
							<td>
								<select id="units" name="integration_method">
									<option value="sim"<?php if ( 'sim' == bp_authnet_get_setting( 'integration_method' ) ) echo ' selected="selected"'; ?>><?php _e( 'Simple Integration Method', 'bp-authnet' ); ?></option>
									<option value="aim"<?php if ( 'aim' == bp_authnet_get_setting( 'integration_method' ) ) echo ' selected="selected"'; ?>><?php _e( 'Advanced Integration Method', 'bp-authnet' ); ?></option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<?php wp_nonce_field( 'BP_Authnet_Admin' ) ?>
					<input type="submit" name="submit" value="<?php esc_attr_e( 'Update Settings', 'bp-authnet' ) ?>" />
				</p>
			</form>
		</div>
<?php
	}
}
add_action( 'init', array( 'BP_Authnet_Admin', 'init' ) );

?>
