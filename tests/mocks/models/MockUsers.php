<?php
/**
 * li3_file_datasource: the data source for files
 *
 * @copyright     Copyright 2013, Housni Yakoob (http://koobi.co)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_file_datasource\tests\mocks\models;

/**
 * @todo
 */
class MockUsers extends \lithium\data\Model {

    protected $_meta = [ 
        'connection' => 'csv',
    ];

    protected $_schema = [ 
        'id'           => ['type' => 'id'],
        'username'   => [
            'type'       => 'string',
            'length'     => 25, 
            'null'       => false,
        ],
        'first_name'   => [
            'type'       => 'string',
            'length'     => 50,
            'null'       => false,
        ],
        'last_name'    => [
            'type'       => 'string',
            'length'     => 50,
            'null'       => false,
        ],
        'email'        => [
            'type'       => 'string',
            'length'     => 100,
            'null'       => false,
        ],
        'status'       => [
            'type'    => 'boolean',
            'default' => true,
            'null'    => false,
            'comment' => 'true = enabled; false = disabled;'
        ],
        'timezone'     => [
            'type'    => 'string',
            'null'    => false,
            'default' => 'America/New_York'
        ],
    ];

	public $validates = [];

}
?>