<?php

/**
 * bp_authnet_has_access()
 *
 * Make sure user can perform special tasks
 *
 * @return bool $can_do
 */
function bp_authnet_has_access() {

	if ( is_super_admin() )
		$has_access = true;
	else
		$has_access = false;

	return apply_filters( 'bp_authnet_has_access', $has_access );
}

/**
 * bp_authnet_setting()
 *
 * Outputs the requested setting
 *
 * @param string $setting
 */
function bp_authnet_setting( $setting ) {
	echo bp_authnet_get_setting( $setting );
}
	/**
	 * bp_authnet_get_setting()
	 *
	 * Get a global authnet setting
	 *
	 * @global array $bp
	 * @param string $setting Setting to get
	 * @return string
	 */
	function bp_authnet_get_setting( $setting ) {
		global $bp;

		return $bp->authnet->settings[$setting];
	}

/**
 * bp_authnet_set_setting()
 *
 * Sets a global authnet setting
 *
 * @global array $bp
 * @param string $setting Setting to set
 * @param string $value  Value to assign
 */
function bp_authnet_set_setting( $setting, $value ) {
	global $bp;

	$bp->authnet->settings[$setting] = $value;
}

/* Template tags */
function bp_authnet_new( $args = '' ) {
	global $bp;

	$defaults = array(
		// Default transaction info
			'login_id'				=> bp_authnet_get_setting( 'login_id' ),
			'transaction_key'		=> bp_authnet_get_setting( 'transaction_key' ),
			'amount'				=> $_POST['bp_authnet_amount'],
			'description'			=> $_POST['bp_authnet_description'],
			'url'					=> 'https://secure.authorize.net/gateway/transact.dll',
			'integration_method'	=> bp_authnet_get_setting( 'integration_method' ),

		// Advanced integration method params
			'version'				=> '3.1',
			'delim_data'			=> 'TRUE',
			'delim_char'			=> '|',
			'relay_response'		=> 'FALSE',

			// Default card info
			'type'					=> 'AUTH_CAPTURE',
			'method'				=> 'CC',

		// Simple integration method params
			'invoice'				=> date( 'YmdHis' ),
			'sequence'				=> rand( 1, 1000 ),
			'timestamp'				=> time(),
			'fingerprint'			=> '',
			'testmode'				=> 'false',

	);

	// Go Voltron
	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	if ( 'aim' == $integration_method )
		$bp->authnet->method = new BP_Authnet_AIM( $r );
	else
		$bp->authnet->method = new BP_Authnet_SIM( $r );
}

function bp_authnet_seal() { ?>
		<script type="text/javascript" language="javascript">
			var ANS_customer_id = "<?php bp_authnet_setting( 'customer_id' ); ?>";
		</script>
		<script type="text/javascript" language="javascript" src="https://verify.authorize.net/anetseal/seal.js" ></script>
<?php
}


function bp_authnet_login_id() {
	echo bp_authnet_get_login_id();
}
	function bp_authnet_get_login_id() {
		global $bp;
		return $bp->authnet->settings['login_id'];
	}

function bp_authnet_transaction_key() {
	echo bp_authnet_get_transaction_key();
}
	function bp_authnet_get_transaction_key() {
		global $bp;
		return $bp->authnet->settings['transaction_key'];
	}

function bp_authnet_amount() {
	echo bp_authnet_get_amount();
}
	function bp_authnet_get_amount() {
		global $bp;
		return apply_filters( 'bp_authnet_get_amount', $bp->authnet->method->amount );
	}

function bp_authnet_description() {
	echo bp_authnet_get_description();
}
	function bp_authnet_get_description() {
		global $bp;
		return apply_filters( 'bp_authnet_get_description', $bp->authnet->method->description );
	}

function bp_authnet_url() {
	echo bp_authnet_get_url();
}
	function bp_authnet_get_url() {
		global $bp;
		return apply_filters( 'bp_authnet_get_url', $bp->authnet->method->url );
	}

function bp_authnet_invoice() {
	echo bp_authnet_get_invoice();
}
	function bp_authnet_get_invoice() {
		global $bp;
		return apply_filters( 'bp_authnet_get_invoice', $bp->authnet->method->invoice );
	}

function bp_authnet_sequence() {
	echo bp_authnet_get_sequence();
}
	function bp_authnet_get_sequence() {
		global $bp;
		return apply_filters( 'bp_authnet_get_sequence', $bp->authnet->method->sequence );
	}

function bp_authnet_timestamp() {
	echo bp_authnet_get_timestamp();
}
	function bp_authnet_get_timestamp() {
		global $bp;
		return apply_filters( 'bp_authnet_get_timestamp', $bp->authnet->method->timestamp );
	}

function bp_authnet_fingerprint() {
	echo bp_authnet_get_fingerprint();
}
	function bp_authnet_get_fingerprint() {
		global $bp;
		return apply_filters( 'bp_authnet_get_fingerprint', $bp->authnet->method->fingerprint );
	}

function bp_authnet_testmode() {
	echo bp_authnet_get_testmode();
}
	function bp_authnet_get_testmode() {
		global $bp;
		return apply_filters( 'bp_authnet_get_testmode', $bp->authnet->method->testmode );
	}

/**
 * bp_authnet_response()
 *
 * Populate response into global and return it
 *
 * @return object
 */
function bp_authnet_response() {
	return bp_authnet_get_response();
}
	function bp_authnet_get_response() {
		global $bp;

		$bp->authnet->response = $bp->authnet->method->response();

		return apply_filters( 'bp_authnet_get_response', $bp->authnet->response );
	}

/**
 * bp_authnet_response_status()
 *
 *
 */
function bp_authnet_response_status() {
	echo bp_authnet_get_response_status();
}
	/**
	 * bp_authnet_get_response_status()()
	 *
	 * Return status response from authorize.net
	 *
	 * @global array $bp
	 * @return string
	 */
	function bp_authnet_get_response_status() {
		global $bp;
		return apply_filters( 'bp_authnet_get_response_status', $bp->authnet->response[BP_AUTHNET_RESPONSE_STATUS] );
	}

/**
 * bp_authnet_response_message()
 *
 * Echo bp_authnet_get_response_message()
 */
function bp_authnet_response_message() {
	echo bp_authnet_get_response_message();
}
	/**
	 * bp_authnet_get_response_message()()
	 *
	 * Return response message from authorize.net
	 *
	 * @global array $bp
	 * @return string
	 */
	function bp_authnet_get_response_message() {
		global $bp;
		return apply_filters( 'bp_authnet_get_response_message', $bp->authnet->response[BP_AUTHNET_RESPONSE_MESSAGE] );
	}

/**
 * bp_authnet_response_total()
 *
 * Echo bp_authnet_get_response_total()
 */
function bp_authnet_response_total() {
	echo bp_authnet_get_response_total();
}
	/**
	 * bp_authnet_get_response_total()()
	 *
	 * Return response total from authorize.net
	 *
	 * @global array $bp
	 * @return string
	 */
	function bp_authnet_get_response_total() {
		global $bp;
		return apply_filters( 'bp_authnet_get_response_total', $bp->authnet->response[BP_AUTHNET_RESPONSE_TOTAL] );
	}

?>
