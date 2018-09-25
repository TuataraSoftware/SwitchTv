<?php

/**
 * Loads class file by its namespace and name.
 */
spl_autoload_register( function( $classNameWithNamespace ) {

	$relativePath = str_replace( "\\", DIRECTORY_SEPARATOR, $classNameWithNamespace ) . '.php';
	$fullPath = __DIR__ . '/../../../../' . $relativePath;

	require_once( $fullPath );
} );
