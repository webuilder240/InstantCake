<?php

namespace InstantCake\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

/**
 * built-in Server Shell
 *
 */
class InstantCakeShell extends Shell
{

	/**
	 * Default ServerHost
	 *
	 * @var string
	 */
	const DEFAULT_HOST = 'localhost';

	/**
	 * Default ListenPort
	 *
	 * @var int
	 */
	const DEFAULT_PORT = 8765;

	/**
	 * server host
	 *
	 * @var string
	 */
	protected $_host = null;

	/**
	 * listen port
	 *
	 * @var string
	 */
	protected $_port = null;

	/**
	 * document root
	 *
	 * @var string
	 */
	protected $_documentRoot = null;


	/**
	 * initFile root
	 *
	 * @var string
	 */
	protected $_iniFile = null;

	/**
	 * Override initialize of the Shell
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->_host = self::DEFAULT_HOST;
		$this->_port = self::DEFAULT_PORT;
		$this->_documentRoot = WWW_ROOT;
	}

	/**
	 * Starts up the Shell and displays the welcome message.
	 * Allows for checking and configuring prior to command or main execution
	 *
	 * Override this method if you want to remove the welcome information,
	 * or otherwise modify the pre-command flow.
	 *
	 * @return void
	 * @link http://book.cakephp.org/3.0/en/console-and-shells.html#hook-methods
	 */
	public function startup()
	{
		if (!empty($this->params['host'])) {
			$this->_host = $this->params['host'];
		}
		if (!empty($this->params['port'])) {
			$this->_port = $this->params['port'];
		}
		if (!empty($this->params['document_root'])) {
			$this->_documentRoot = $this->params['document_root'];
		}

		if (!empty($this->params['ini_file'])){
			$this->_iniFile = ROOT . DS . $this->params['ini_file'];
		}

		// For Windows
		if (substr($this->_documentRoot, -1, 1) === DS) {
			$this->_documentRoot = substr($this->_documentRoot, 0, strlen($this->_documentRoot) - 1);
		}
		if (preg_match("/^([a-z]:)[\\\]+(.+)$/i", $this->_documentRoot, $m)) {
			$this->_documentRoot = $m[1] . '\\' . $m[2];
		}

		parent::startup();
	}

	/**
	 * Displays a header for the shell
	 *
	 * @return void
	 */
	protected function _welcome()
	{
		$this->out();
		$this->out(sprintf('<info>Welcome to CakePHP %s Console</info>', 'v' . Configure::version()));
		$this->hr();
		$this->out(sprintf('App : %s', APP_DIR));
		$this->out(sprintf('Path: %s', APP));
		$this->out(sprintf('DocumentRoot: %s', $this->_documentRoot));
		if (!empty($this->_iniFile)){
			$this->out(sprintf('php.ini FilePath: %s', $this->_iniFile));
		}
		$this->hr();
	}

	/**
	 * Override main() to handle action
	 *
	 * @return void
	 */
	public function main()
	{

		if (!empty($this->_iniFile)){
			$command = sprintf(
				"php -S %s:%d -c %s -t %s %s ",
				$this->_host,
				$this->_port,
				escapeshellarg($this->_iniFile),
				escapeshellarg($this->_documentRoot),
				escapeshellarg($this->_documentRoot . '/index.php')
			);
		} else {
			$command = sprintf(
				"php -S %s:%d -t %s %s",
				$this->_host,
				$this->_port,
				escapeshellarg($this->_documentRoot),
				escapeshellarg($this->_documentRoot . '/index.php')
			);
		}

		$port = ':' . $this->_port;
		$this->out(sprintf('built-in server is running in http://%s%s/', $this->_host, $port));
		$this->out(sprintf('You can exit with <info>`CTRL-C`</info>'));
		$this->out($command);
		system($command);
	}

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser()
	{
		$parser = parent::getOptionParser();

		$parser->description([
			'PHP Built-in Server for CakePHP',
			'<warning>[WARN] Don\'t use this at the production environment</warning>',
		])->addOption('host', [
			'short' => 'H',
			'help' => 'ServerHost'
		])->addOption('port', [
			'short' => 'p',
			'help' => 'ListenPort'
		])->addOption('document_root', [
			'short' => 'd',
			'help' => 'DocumentRoot'
		])->addOption('ini_file',[
			'short' => 'c',
			'help' => 'php.ini'
		]);

		return $parser;
	}
}

