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
abstract class Ai1wm_Import_Abstract {

	protected $args    = array();

	protected $storage = null;

	public function __construct( array $args = array() ) {
		$this->args = $args;
	}

	/**
	 * Unpack archive
	 *
	 * @return void
	 */
	public function start() {
		// Set default progress
		Ai1wm_Status::set( array(
			'type'      => 'info',
			'message'   => __( 'Unpacking archive...', AI1WM_PLUGIN_NAME ),
		) );

		// Open the archive file for reading
		$archive = new Ai1wm_Extractor( $this->storage()->archive() );

		// Unpack package.json and database.sql files
		$archive->extract_by_files_array(
			$this->storage()->path(),
			array(
				AI1WM_PACKAGE_NAME,
				AI1WM_DATABASE_NAME,
			)
		);

		// Close the archive file
		$archive->close();

		// Validate the archive file
		if ( $this->validate() ) {

			// Parse the package file
			$service = new Ai1wm_Service_Package( $this->args );
			if ( $service->import() ) {
				// Next method
				return array( 'method' => 'confirm' );
			} else {
				throw new Ai1wm_Import_Exception( __( 'Invalid package.json file.', AI1WM_PLUGIN_NAME ) );
			}

		} else {
			throw new Ai1wm_Import_Exception(
				__( 'Invalid archive file. It should contain <strong>package.json</strong> file.', AI1WM_PLUGIN_NAME )
			);
		}
	}

	/**
	 * Confirm import
	 *
	 * @return void
	 */
	public function confirm() {
		// Obtain the size of the archive
		$size = @filesize( $this->storage()->archive() );

		if ( false === $size ) {
			throw new Ai1wm_Not_Accesible_Exception(
				sprintf(
					__(
						'Unable to get the file size of <strong>"%s"</strong>',
						AI1WM_PLUGIN_NAME
					),
					$this->storage()->archive()
				)
			);
		}

		$allowed_size = apply_filters( 'ai1wm_max_file_size', AI1WM_MAX_FILE_SIZE );

		// Let's check the size of the file to make sure it is less than the maximum allowed
		if ( ( $allowed_size > 0 ) && ( $size > $allowed_size ) ) {
			throw new Ai1wm_Import_Exception(
				sprintf(
					__(
						'The file that you are trying to import is over the maximum upload file size limit of %s.' .
						'<br />You can remove this restriction by purchasing our ' .
						'<a href="https://servmask.com/products/unlimited-extension" target="_blank">Unlimited Extension</a>.',
						AI1WM_PLUGIN_NAME
					),
					apply_filters( 'ai1wm_max_file_size', AI1WM_MAX_FILE_SIZE )
				)
			);
		}

		// Set progress
		Ai1wm_Status::set(
			array(
				'type'    => 'confirm',
				'message' => __(
					'The import process will overwrite your database, media, plugins, and themes. ' .
					'Please ensure that you have a backup of your data before proceeding to the next step.',
					AI1WM_PLUGIN_NAME
				),
			)
		);
	}

	/**
	 * Enumerate content files and directories
	 *
	 * @return void
	 */
	public function enumerate() {
		// Set progress
		Ai1wm_Status::set( array(
			'type'    => 'info',
			'message' => __( 'Retrieving a list of all WordPress files...', AI1WM_PLUGIN_NAME )
		) );

		// Open the archive file for reading
		$archive = new Ai1wm_Extractor( $this->storage()->archive() );

		// Unpack package.json and database.sql files
		$total = $archive->get_number_of_files();

		// Substract database.sql and package.json
		$total -= 2;

		// close the archive file
		$archive->close();

		// Next method
		return array( 'method' => 'truncate', 'total' => $total );
	}

	/**
	 * Truncate content files and directories
	 *
	 * @return void
	 */
	public function truncate() {
		// Enable maintenance mode
		Ai1wm_Maintenance::enable();

		// Next method
		return array( 'method' => 'content', 'total' => $this->args['total'] );
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
		$progress  = @(int) ( ( $processed / $total ) * 100 ) or $progress = 4;

		// Set progress
		Ai1wm_Status::set( array(
			'type'    => 'info',
			'message' => sprintf( __( 'Restoring %d files...<br />%d%% complete', AI1WM_PLUGIN_NAME ), $total, $progress ),
		) );

		// Start time
		$start = microtime( true );

		// Flag to hold if all files have been processed
		$completed = true;

		// Open the archive file for reading
		$archive = new Ai1wm_Extractor( $this->storage()->archive() );

		// Set the file pointer to the one that we have saved
		$archive->set_file_pointer( null, $this->pointer() );

		while ( $archive->has_not_reached_eof() ) {
			// Extract a file from archive to wp_content_dir
			$archive->extract_one_file_to( WP_CONTENT_DIR, array(
				AI1WM_PACKAGE_NAME,
				AI1WM_DATABASE_NAME,
			) );

			// Increment processed files counter
			$processed++;

			// We are only extracting files for 5 seconds at a time
			$time = microtime( true ) - $start;
			if ( $time > 5 ) {
				// More than 5 seconds have passed, break and do another request
				$completed = false;
				break;
			}
		}

		// Set new file map pointer
		$pointer = $archive->get_file_pointer();

		// Close the archive file
		$archive->close();

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
		if ( ! is_file( $this->storage()->database() ) ) {
			// Next method
			return array( 'method' => 'finish' );
		}

		// Display progress
		Ai1wm_Status::set( array(
			'message' => __( 'Restoring database...', AI1WM_PLUGIN_NAME ),
		) );

		// Get database file
		$service  = new Ai1wm_Service_Database( $this->args );
		$service->import();

		// Next method
		return array( 'method' => 'finish' );
	}

	/**
	 * Finish import process
	 *
	 * @return void
	 */
	public function finish() {
		// Set progress
		Ai1wm_Status::set( array(
			'type'    => 'finish',
			'title'   => __( 'Your data has been imported successfuly!', AI1WM_PLUGIN_NAME ),
			'message' => sprintf(
				__(
					'You need to perform two more steps:<br />' .
					'<strong>1. You must save your permalinks structure twice. <a class="ai1wm-no-underline" href="%s" target="_blank">Permalinks Settings</a></strong> <small>(opens a new window)</small><br />' .
					'<strong>2. <a class="ai1wm-no-underline" href="https://wordpress.org/support/view/plugin-reviews/all-in-one-wp-migration?rate=5#postform" target="_blank">Review the plugin</a>.</strong> <small>(opens a new window)</small>',
					AI1WM_PLUGIN_NAME
				),
				admin_url( 'options-permalink.php#submit' )
			)
		) );

		// Disable maintenance mode
		Ai1wm_Maintenance::disable();

		// Next method
		return array( 'method' => 'clean' );
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
	 * Get import archive
	 *
	 * @return void
	 */
	abstract public function import();

	protected function ensure_file_can_be_created( $path ) {
		$path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path;
		$path = wp_normalize_path( $path );

		// does the file already exist?
		if ( file_exists( $path ) ) {
			// check if we can overwrite the file
			if ( false === is_writable( $path ) ) {
				throw new Ai1wm_Import_Exception(
					sprintf(
						__(
							'The plugin cannot write to %s. FTP to your server, and change ' .
							'file\'s permission to either 664 or 666, then try again.',
							AI1WM_PLUGIN_NAME
						),
						$path
					)
				);
			}
			return true;
		}

		// the file doesn't exist, first we need to check if the path to the
		// file exist
		$path_to_file = dirname( $path );
		if ( file_exists( $path_to_file ) ) {
			// check if we can create a file
			if ( false === is_writable( $path_to_file ) || false === is_executable( $path_to_file ) ){
				throw new Ai1wm_Import_Exception(
					sprintf(
						__(
							'The plugin cannot write to %s. FTP to your server, and change ' .
							'folder\'s permission to either 775 or 777, then try again.',
							AI1WM_PLUGIN_NAME
						),
						$path_to_file
					)
				);
			}
			return true;
		}

		$max_depth_level = 2048;

		// the path doesn't exist, find the parent that exists
		while ( $max_depth_level > 0 ) {
			if ( file_exists( $path_to_file ) ) {
				break;
			}
			$path_to_file = dirname( $path_to_file );
			$max_checks--;
		}

		// check if we can create a folder in the parent that exists
		if ( false === is_writable( $path_to_file ) || false === is_executable( $path_to_file ) ){
			throw new Ai1wm_Import_Exception(
				sprintf(
					__(
						'The plugin cannot write to %s. FTP to your server, and change ' .
						'folder\'s permission to either 775 or 777, then try again.',
						AI1WM_PLUGIN_NAME
					),
					$path_to_file
				)
			);
		}
		return true;
	}

	/**
	 * Validate archive and WP_CONTENT_DIR permissions
	 *
	 * @return boolean
	 */
	protected function validate() {
		if ( is_file( $this->storage()->package() ) ) {
			// go over all files in the archive and ensure we can write to the file
			// Open the archive file for reading
			$archive = new Ai1wm_Extractor( $this->storage()->archive() );

			// Set the file pointer to the one that we have saved
			$archive->set_file_pointer( null, $this->pointer() );

			while ( $archive->has_not_reached_eof() ) {
				// Extract a file from archive to wp_content_dir
				$meta = $archive->get_one_file_header( array(
					AI1WM_PACKAGE_NAME,
					AI1WM_DATABASE_NAME,
				) );

				if ( false !== $meta ) {
					$this->ensure_file_can_be_created( $meta['path'] );
				}
			}

			// Set new file map pointer
			$pointer = $archive->get_file_pointer();
			@fseek( $archive->get_handle(), 0, SEEK_SET );

			// Close the archive file
			$archive->close();

			return true;
		}

		return false;
	}

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
	protected function pointer( $pointer = null ) {
		if ( ! isset( $this->args['pointer'] ) ) {
			$this->args['pointer'] = 0;
		}

		return (int) $this->args['pointer'];
	}
}
