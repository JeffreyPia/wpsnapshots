<?php
/**
 * Download command
 *
 * @package  wpsnapshots
 */

namespace WPSnapshots\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use WPSnapshots\Connection;
use WPSnapshots\WordPressBridge;
use WPSnapshots\Config;
use WPSnapshots\Utils;
use WPSnapshots\Snapshot;
use WPSnapshots\Log;

/**
 * The downloads a snapshot to the .wpsnapshots directory but does not pull it into the current install.
 */
class Download extends Command {

	/**
	 * Setup up command
	 */
	protected function configure() {
		$this->setName( 'download' );
		$this->setDescription( 'Download a snapshot from the repository.' );
		$this->addArgument( 'snapshot-id', InputArgument::REQUIRED, 'Snapshot ID to download.' );
	}

	/**
	 * Executes the command
	 *
	 * @param  InputInterface  $input Command input
	 * @param  OutputInterface $output Command output
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		Log::instance()->setOutput( $output );

		$connection = Connection::instance()->connect();

		if ( Utils\is_error( $connection ) ) {
			Log::instance()->write( 'Could not connect to repository.', 0, 'error' );
			return;
		}

		$id = $input->getArgument( 'snapshot-id' );

		if ( empty( $path ) ) {
			$path = getcwd();
		}

		$snapshot = Snapshot::download( $id, $output );

		if ( is_a( $snapshot, '\WPSnapshots\Snapshot' ) ) {
			Log::instance()->write( 'Download finished!', 0, 'success' );
		}
	}
}