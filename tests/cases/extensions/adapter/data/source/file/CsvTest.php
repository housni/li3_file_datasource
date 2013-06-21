<?php
/**
 * li3_file_datasource: the data source for files
 *
 * @copyright     Copyright 2013, Housni Yakoob (http://koobi.co)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_file_datasource\tests\cases\extensions\adapter\data\source\file;

use li3_file_datasource\extensions\adapter\data\source\file\Csv;
use lithium\core\Libraries;

/**
 * @todo
 */
class CsvTest extends \lithium\test\Unit {

	protected $_configuration = [];

	public function setUp() {
		$csv = new Csv;
		$csv->_config['path'] = Libraries::get(true, 'resources') . '/tmp/tests';
		$this->_configuration = $csv->_config;
	}

	public function tearDown() {}
}
?>