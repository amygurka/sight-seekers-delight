<?php

/**
 * Copyright (C) 2014 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */
abstract class Ai1wm_Export_Abstract {

	protected $args    = array();

	protected $storage = null;

	public function __construct( array $args = array() ) {
		$this->args = $args;
	}

	/**
	 * Create empty archive and add package config
	 *
	 * @return void
	 */
	public function start() {
		// Set default progress
		Ai1wm_Status::set( array(
			'type'      => 'info',
			'message'   => __( 'Creating an empty archive...', AI1WM_PLUGIN_NAME )
		) );

		// Get package file
		$service = new Ai1wm_Service_Package( $this->args );
		$service->export();

		// Get archive file
		$archive = new Ai1wm_Compressor( $this->storage()->archive() );

		// Add package file
		$archive->add_file( $this->storage()->package(), AI1WM_PACKAGE_NAME );
		$archive->close();

		// Next method
		return array( 'method' => 'enumerate' );
	}

	/**
	 * Enumerate content files and directories
	 *
	 * @return void
	 */
	public function enumerate() {
		// Set progress
		Ai1wm_Status::set( array(
			'message' => __( 'Retrieving a list of all WordPress files...', AI1WM_PLUGIN_NAME )
		) );

		// Enable maintenance mode
		if ( $this->should_enable_maintenance() ) {
			Ai1wm_Maintenance::enable();
		}

		$filters = array( 'managewp', 'ai1wm-backups' );

		// Exclude media
		if ( $this->should_exclude_media() ) {
			$filters = array_merge( $filters, array( 'uploads' ) );
		}

		// Exclude themes
		if ( $this->should_exclude_themes() ) {
			$filters = array_merge( $filters, array( 'themes' ) );
		}

		// Exclude plugins
		if ( $this->should_exclude_plugins() ) {
			$filters = array_merge( $filters, array( 'plugins' ) );
		} else {
			$filters = array_merge( $filters, array(
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-dropbox-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-gdrive-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-s3-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-multisite-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-unlimited-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-pro-extension',
				'plugins' . DIRECTORY_SEPARATOR . 'all-in-one-wp-migration-ftp-extension',
			) );
		}

		// Create map file
		$filemap = fopen( $this->storage()->filemap(), 'a+' );

		// Total files
		$total = 0;
		$not_readable = 0;

		// Iterate over WP_CONTENT_DIR directory
		$iterator = new RecursiveIteratorIterator(
			new Ai1wm_Recursive_Exclude_Filter(
				new Ai1wm_Recursive_Directory_Iterator(
					WP_CONTENT_DIR
				),
				apply_filters( 'ai1wm_exclude_content_from_export', $filters )
			),
			RecursiveIteratorIterator::SELF_FIRST,
			RecursiveIteratorIterator::CATCH_GET_CHILD
		);

		foreach ( $iterator as $item ) {
			if ( $item->isFile() ) {
				$path_to_check = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
				$path_to_check = wp_normalize_path( $path_to_check );
				if ( false === is_readable( $path_to_check ) ) {
					$not_readable++;
				} else {
					// Write path line
					if ( fwrite( $filemap, $iterator->getSubPathName() . PHP_EOL ) ) {
						$total++;
					}

				}
			}
		}

		fclose( $filemap );

		$failure_percent = $not_readable / $total * 100;

		if ( $failure_percent > 50 ) {
			// Set default progress
			Ai1wm_Status::set( array(
				'type'      => 'error',
				'message'   => __(
					'We couldn\'t read more than 50% of your files. ' .
					'Ensure that your webserver can access your files and the try again. ' .
					'If you need assistance, email support@servmask.com',
					AI1WM_PLUGIN_NAME
				)
			) );

			return false;
		}

		// Next method
		return array( 'method' => 'content', 'total' => $total );
	}

	/**
	 * Add content files and directories
	 *
	 * @return void
	 */
	public function content() {
		// Total and processed files
		$total     = @(int) $this->args['total'];
		$processed = @(int) $this->args['processed'];

		// What percent of files have we processed?
		$progress  = @(int) ( ( $processed / $total ) * 100 );

		// Set progress
		Ai1wm_Status::set( array(
			'message' => sprintf( __( 'Archiving %d files...<br />%d%% complete', AI1WM_PLUGIN_NAME ), $total, $progress )
		) );

		// Get map file
		$filemap = fopen( $this->storage()->filemap(), 'r' );

		// Start time
		$start = microtime( true );

		// Flag to hold if all files have been processed
		$completed = true;

		// Set file map pointer at the current index
		if ( fseek( $filemap, $this->pointer() ) !== -1 ) {
			// Get archive
			$archive = new Ai1wm_Compressor( $this->storage()->archive() );

			while ( $path = trim( fgets( $filemap ) ) ) {
				// Add file to archive
				$archive->add_file( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path, $path );

				$processed++;

				// time elapsed
				$time = microtime( true ) - $start;

				if ( $time > 3 ) {
					// More than 3 seconds have passed, break and do another request
					$completed = false;
					break;
				}
			}

			$archive->close();
		}

		// Get new file map pointer
		$pointer = ftell( $filemap );

		fclose( $filemap );

		// Next method
		if ( $completed ) {
			return array( 'method' => 'database' );
		} else {
			return array( 'method' => 'content', 'pointer' => $pointer, 'total' => $total, 'processed' => $processed );
		}
	}

	/**
	 * Add database
	 *
	 * @return void
	 */
	public function database() {
		// Set exclude database
		if ( $this->should_exclude_database() ) {
			// Disable maintenance mode
			Ai1wm_Maintenance::disable();

			// Next method
			return array( 'method' => 'export' );
		}

		// Set progress
		Ai1wm_Status::set( array( 'message' => __( 'Exporting database...', AI1WM_PLUGIN_NAME ) ) );

		// Get databsae file
		$service  = new Ai1wm_Service_Database( $this->args );
		$service->export();

		// Get archive file
		$archive = new Ai1wm_Compressor( $this->storage()->archive() );

		// Add database to archive
		$archive->add_file( $this->storage()->database(), AI1WM_DATABASE_NAME );
		$archive->close();

		// Disable maintenance mode
		Ai1wm_Maintenance::disable();

		// Next method
		return array( 'method' => 'export' );
	}

	/**
	 * Clean storage directory
	 *
	 * @return void
	 */
	public function clean() {
		$this->storage()->clean();
	}

	/**
	 * Get file name
	 *
	 * @return string
	 */
	public function filename() {
		$url  = parse_url( home_url() );
		$name = array();

		// Add domain
		if ( isset( $url['host'] ) ) {
			$name[] = $url['host'];
		}

		// Add path
		if ( isset( $url['path'] ) && ( $path = trim( $url['path'], '/' ) ) ) {
			$name[] = sanitize_file_name( $path );
		}

		// Add year, month and day
		$name[] = date( 'Ymd' );

		// Add hours, minutes and seconds
		$name[] = date( 'His' );

		// Add unique identifier
		$name[] = rand( 100, 999 );

		return sprintf( '%s.wpress.bin', implode( '-', $name ) );
	}

	/**
	 * Get folder name
	 *
	 * @return string
	 */
	public function foldername() {
		$url  = parse_url( home_url() );
		$name = array();

		// Add domain
		if ( isset( $url['host'] ) ) {
			$name[] = $url['host'];
		}

		// Add path
		if ( isset( $url['path'] ) ) {
			$name[] = trim( $url['path'] , '/' );
		}

		return implode( '-', $name );
	}

	/**
	 * Get export package
	 *
	 * @return void
	 */
	abstract public function export();

	/*
	 * Get storage object
	 *
	 * @return Ai1wm_Storage
	 */
	protected function storage() {
		if ( $this->storage === null ) {
			if ( isset( $this->args['archive'] ) ) {
				$this->args['archive'] = basename( $this->args['archive'] );
			}

			$this->storage = new Ai1wm_Storage( $this->args );
		}

		return $this->storage;
	}

	/**
	 * Get filemap pointer
	 *
	 * @return integer
	 */
	protected function pointer( ) {
		if ( ! isset( $this->args['pointer'] ) ) {
			$this->args['pointer'] = 0;
		}

		return (int) $this->args['pointer'];
	}

	/**
	 * Should exclude database?
	 *
	 * @return boolean
	 */
	protected function should_exclude_database() {
		return isset( $this->args['options']['no-database'] );
	}

	/**
	 * Should exclude media?
	 *
	 * @return boolean
	 */
	protected function should_exclude_media() {
		return isset( $this->args['options']['no-media'] );
	}

	/**
	 * Should exclude themes?
	 *
	 * @return boolean
	 */
	protected function should_exclude_themes() {
		return isset( $this->args['options']['no-themes'] );
	}

	/**
	 * Should exclude plugins?
	 *
	 * @return boolean
	 */
	protected function should_exclude_plugins() {
		return isset( $this->args['options']['no-plugins'] );
	}

	/**
	 * Should enable maintenance?
	 *
	 * @return boolean
	 */
	protected function should_enable_maintenance() {
		return isset( $this->args['options']['maintenance-mode'] );
	}
}
