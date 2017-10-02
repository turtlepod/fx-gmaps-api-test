<?php
/**
 * HTML.
 */

/**
 * Google API tester.
 *
 * @since 1.0.0
 *
 * @param string $key API Key.
 * @param string $addr Address to lookup.
 * @return array|string Return string for Error.
 */
function fx_gmaps_key_test( $key, $addr = '' ) {
	if ( ! $key ) {
		return 'No API Key';
	}
	if ( ! $addr ) {
		return 'No Address';
	}
	$url = add_query_arg( $args = array(
		'address' => $addr,
		'key'     => $key,
	), 'https://maps.googleapis.com/maps/api/geocode/json' );

	$result = wp_remote_get(
		esc_url_raw( $url ),
		array(
			'timeout'     => 10,
			'redirection' => 1,
			'httpversion' => '1.1',
			'user-agent'  => 'WordPress/fxGmapsAPITest; ',
			'sslverify'   => false,
		)
	);
	if ( is_wp_error( $result ) ) {
		return 'Request Error';
	}
	$result = wp_remote_retrieve_body( $result );
	return json_decode( $result, true );
}

// Fields.
$option = get_option( 'fx_gmaps_api_test', array() );
$key = isset( $option['key'] ) ? $option['key'] : '';
$addr = isset( $option['addr'] ) ? $option['addr'] : '';
?>
<p><input type="text" class="regular-text" name="fx_gmaps_api_test[key]" value="<?php echo esc_attr( $key ); ?>" placeholder="Google Maps API Key"> API Key</p>
<p><input type="text" class="regular-text" name="fx_gmaps_api_test[addr]" value="<?php echo esc_attr( $addr ); ?>" placeholder="Address to test"> Address</p>

<?php if ( $key || $addr ) : ?>
<pre id="gmaps-results">
<?php var_dump( fx_gmaps_key_test( $key, $addr ) ); ?>
</pre>
<?php endif; ?>
