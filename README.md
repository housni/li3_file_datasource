# The file data source for the Lithium framework

## Installation

Checkout the code to your library directory:

	cd libraries
	git clone https://github.com/housni/li3_file_datasource.git

Include the library in in your `/app/config/bootstrap/libraries.php`

	Libraries::add('li3_file_datasource');

## Configuration

Basic configuration for CSV files:

	<?php
		Connections::add('csv', [
			'type'    => 'file',
			'adapter' => 'Csv',
		]);
	?>

There are all the possible options:

	<?php
		Connections::add('csv', [
			'type'    => 'file',
			'adapter' => 'Csv',
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
		]);
	?>

	For more about `mode`, look at the `mode` parameter of [fopen()](http://www.php.net/manual/en/function.fopen.php#function.fopen).
	The `flags` are for [SPLFileObject](http://www.php.net/manual/en/class.splfileobject.php#splfileobject.constants).


## Usage

	<?php

	namespace app\models;

	class Posts extends \lithium\data\Model {

		protected $_meta = [ 
			'connection' => 'csv',
		];

		protected $_schema = [ 
			'id'           => ['type' => 'id'],
			'title'   => [
				'type'       => 'string',
				'length'     => 255, 
				'null'       => false,
			],
			'content'   => [
				'type'       => 'text',
				'null'       => false,
			],
		];
	}
	?>

Now, you'd use it like any other model, using finders.


## Credits
* [jails](https://github.com/jails), for helping me solve a problem on [#li3](irc://irc.freenode.net/#li3)


## TODO
* Add all the finder options.
* Complete unit tests.
* Add support for more formats.