<?php
/**
 * li3_file_datasource: the data source for files
 *
 * @copyright     Copyright 2013, Housni Yakoob (http://koobi.co)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_file_datasource\tests\cases\extensions\adapter\data\source;

use li3_file_datasource\extensions\adapter\data\source\File;
use li3_file_datasource\extensions\adapter\data\source\file\Csv;
use li3_file_datasource\tests\mocks\models\MockUsers;
use lithium\core\Libraries;

/**
 * @todo
 */
class FileTest extends \lithium\test\Unit {

	protected $_file = [];

	public function setUp() {
		$this->_file['csv'] = MockUsers::connection('csv');
		$this->_file['csv']->_config['path'] = Libraries::get('li3_file_datasource', 'path') . '/tests/mocks/csv';
	}

	public function tearDown() {}

	public function testForInvalidPath() {
		$path = $this->_file['csv']->_config['path'] = '/foo/bar/baz';

		$this->expectException(
			"DirectoryIterator::__construct($path): failed to open dir: No such file or directory"
		);
		$this->_file['csv']->connect();
	}

	/**
	 * @todo    see if $_isConnected is true
	 */
	public function testForValidPath() {
		$this->_file['csv']->_config['options']['mode'] = 'r';
		$this->_file['csv']->connect();
	}

	public function testWriteAccessOnReadOnlyDirectory() {
		$this->_file['csv']->_config['options']['mode'] = 'a+';
		$path = $this->_file['csv']->_config['path'] = '/usr';

		$this->expectException("The path `$path` is not writable");
		$this->_file['csv']->connect();
	}

	public function testForInvalidMode() {
		$mode = 'foo';
		$this->_file['csv']->_config['options']['mode'] = $mode;
		$this->expectException("The mode `$mode` is not yet supported");
		$this->_file['csv']->connect();
	}
}
?>