<?php
function download_plugin( $path, $plugin ) {
	echo passthru( "wget {$plugin['repo']}" ) . "\n\n";
	echo passthru( "unzip {$path}.zip" ) . "\n\n";
	echo passthru( "mv {$path} ../" ) . "\n\n";

	return true;
}

function download_plugins() {
	// this path needs to be kept in sync with the path set in install-wp-tests.sh
	// trailing slash expected
	$plugins_dir = '/tmp/wordpress/wp-content/plugins/';
	// the plugins to download
	$dependencies = require dirname( __DIR__ ) . '/tests/dependencies-array.php';
	foreach ( $dependencies as $k => $dependency ) {
		if ( ! is_dir( $plugins_dir . $k ) ) {
			if ( download_plugin( $k, $dependency ) ) {
				echo "Downloaded $k\n";
			} else {
				echo "FAILED to download $k\n";
			}
		} else {
			echo "DIRECTORY EXISTS, skipped $plugins_dir$k\n";
		}
	}
}

download_plugins();
