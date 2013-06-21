<?php
/**
 * li3_file_datasource: the data source for files
 *
 * @copyright     Copyright 2013, Housni Yakoob (http://koobi.co)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_file_datasource\extensions\adapter\data\source\file;

use SplFileObject;
use lithium\core\Libraries;

class Csv extends \li3_file_datasource\extensions\adapter\data\source\File {

	/**
	 * @see    http://php.net/manual/en/class.splfileobject.php
	 * @see    http://www.php.net/manual/en/function.fopen.php
	 */
	public function __construct(array $config = []) {
		/**
		 * Due to PHP bugs 55807 and 61032, SplFileObject::READ_AHEAD was added
		 * in order to avoid the newline character.
		 *
		 * @see    https://bugs.php.net/bug.php?id=55807
		 * @see    https://bugs.php.net/bug.php?id=61032
		 */
		$defaults = [
			'delimiter' => ',',
			'enclosure' => '"',
			'escape'    => '\\',
			'path'      => Libraries::get(true, 'resources') . '/file/csv',
			'extension' => 'csv',
			'options'   => [
				'flags' => 
					SplFileObject::DROP_NEW_LINE |
					SplFileObject::READ_AHEAD |
					SplFileObject::SKIP_EMPTY |
					SplFileObject::READ_CSV
				,
				'mode' => 'a+'
			]
		];
		$config += $defaults;
		parent::__construct($config);
	}

	/**
	 * This method overloads the parent::read() in order to filter into it and
	 * apply the CSV specific parameters and flags for SPLFileObject.
	 * 
	 * @param object $query `lithium\data\model\Query` object
	 * @param array $options
	 * @return object Returns a lithium\data\collection\RecordSet object
	 */
	public function read($query, array $options = []) {
		static::applyFilter('read', function($self, $params, $chain) {
			$delimiter = $this->_config['delimiter'];
			$enclosure = $this->_config['enclosure'];
			$escape    = $this->_config['escape'];

			$self->file->setFlags($this->_config['options']['flags']);
			$self->file->setCsvControl($delimiter, $enclosure, $escape);
			return $chain->next($self, $params, $chain);
		});

		return parent::read($query, $options);
	}

}

?>