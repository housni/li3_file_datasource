# The file data source for the Lithium framework

This plugin uses [SplFileObject](http://php.net/manual/en/class.splfileobject.php) to read/write to files.
It's meant to be a plugin that will treat any file on disk as a datasource.

Right now, only a CSV adapter has been implemented which means you can use the regular [Lithium](https://github.com/UnionOfRAD/lithium) finders to query a CSV with all the options a finder gives you.


## Installation

Clone the code to your library directory (or add it as a submodule):

	cd libraries
	git clone git@github.com:housni/li3_file_datasource.git

Include the library in in your `/app/config/bootstrap/libraries.php`

	Libraries::add('li3_file_datasource');

## Configuration

### For reading CSV files as a datasource
`app/config/bootstrap/connections.php`:

	<?php
		Connections::add('csv', [
			'type' => 'file',
			'adapter' => 'Csv',
		]);
	?>

Here are all the options that you can override if you'd like to:

	<?php
		Connections::add('csv', [
			'type' => 'file',
			'adapter' => 'Csv',
			'delimiter' => ',',
			'enclosure' => '"',
			'escape' => '\\',
			'path' => Libraries::get(true, 'resources') . '/file/csv',
			'extension' => 'csv',
			'options' => [
				'flags' => 
					SplFileObject::DROP_NEW_LINE |
					SplFileObject::READ_AHEAD |
					SplFileObject::SKIP_EMPTY |
					SplFileObject::READ_CSV,
				'mode' => 'a+'
			]
		]);
	?>

#### NOTES
* Please make sure the `path` is writable if `mode` is `a+`.
* If `options.mode` is `r` then make sure `path` is readable, at least.
* By default, the `path` for CSV's are `app/resources/file/csv`
* By default, a `Posts` model would cause the plugin to look for the CSV file `app/resources/file/csv/posts.csv`
* For more about `options.mode`, look at the `mode` parameter of [fopen()](http://www.php.net/manual/en/function.fopen.php#function.fopen).
* The `options.flags` are for [SPLFileObject](http://www.php.net/manual/en/class.splfileobject.php#splfileobject.constants).


## Usage
Since the file won't have its own schema definition, you must specify the schema in your model.
The plugin will then read the data and map the data with the schema you defined.
In the example below, the plugin will map the 2nd comma separated value (since the configuration, above, specifies values should be separated by commas) of the data it reads from file to the to `title` attribute of the model.

	<?php

	namespace app\models;

	class Posts extends \lithium\data\Model {

		/**
		 * Specify that you want this model to use the CSV connection we defined.
		 */
		protected $_meta = [ 
			'connection' => 'csv',
		];

		/**
		 * You must define the schema
		 */
		protected $_schema = [ 
			'id' => ['type' => 'id'],
			'title' => [
				'type'   => 'string',
				'length' => 255, 
				'null'   => false
			],
			'content' => [
				'type' => 'text',
				'null' => false
			],
		];
	}
	?>

Now, you'd use it like any other model, using finders.

	<?php

	namespace app\controllers;

	use app\models\Posts;

	class PostsController extends \lithium\action\Controller {

		/**
		 * The SQL equivalent would have looked like:
		 * SELECT `Posts`.`id`, `Posts`.`title`
		 *   FROM `posts` AS `Posts`
		 *  LIMIT 5
		 * OFFSET 5
		 */
		public function index() {
			$posts = Posts::find('all', [
				'fields' => [
					'id',
					'title'
				],
				'limit' => 5,
				'page'  => 2
			]);
			return compact('posts');
		}

		/**
		 * The SQL equivalent would have looked like:
		 *   SELECT `Posts`.`id`, `Posts`.`title`
		 *     FROM `posts` AS `Posts`
		 * ORDER BY `Posts`.`id` DESC
		 *    LIMIT 5
		 */
		public function latest() {
			$posts = Posts::find('all', [
				'fields' => [
					'id',
					'title'
				],
				'limit' => 5,
				'order' => ['id' => 'DESC']
			]);
			return compact('posts');
		}

		/**
		 * The SQL equivalent would have looked like:
		 * SELECT *
		 *   FROM `posts` AS `Posts`
		 *  WHERE `Posts`.`id` = '1'
		 *  LIMIT 1
		 */
		public function view() {
			$post = Posts::find($this->request->id);
			return compact('post');
		}
	}
	?>


app/views/posts/index.html.php

	<h1>All Posts</h1>
	<ul>
		<?php foreach ($posts as $post) : ?>
			<li>
				<?= $this->html->link($post->title, [
						'Posts::view',
						'id' => $post->id
					],
					['title' => $post->title]
				) ?>
			</li>
		<?php endforeach; ?>
	</ul>


app/views/posts/latest.html.php

	<h1>Latest Posts</h1>
	<ul>
		<?php foreach ($posts as $post) : ?>
			<li>
				<?= $this->html->link($post->title, [
						'Posts::view',
						'id' => $post->id
					],
					['title' => $post->title]
				) ?>
			</li>
		<?php endforeach; ?>
	</ul>


app/views/posts/view.html.php

	<h1>Viewing Post</h1>
	<article>
		<h2><?= $post->title ?></h2>
		<p><?= $post->content ?></p>
	</article>


## Sample CSV file `app/resources/file/csv/posts.csv`

	1,My 1st Title,"Lorem ipsum dolor sit amet, consectetur adipiscing elit."
	2,My 2nd Title,"Suspendisse in nulla semper, aliquet ligula ac, laoreet libero."
	3,My 3rd Title,"Nullam eu orci ac ligula dapibus convallis ac at diam."
	4,My 4th Title,"Morbi nec quam vitae purus iaculis varius sit amet at nibh."
	5,My 5th Title,"Sed lobortis tellus nec lacus ultrices gravida."
	6,My 6th Title,"Etiam fringilla magna eget neque auctor, nec euismod urna tristique."
	7,My 7th Title,"Fusce at arcu sit amet purus tincidunt vulputate."
	8,My 8th Title,"Vestibulum eget eros ultrices, consectetur leo a, egestas nisi."
	9,My 9th Title,"Etiam eu turpis eleifend, convallis urna ac, vulputate metus."
	10,My 10th Title,"Nullam vestibulum arcu eu mattis tempor."



## Credits
* [jails](https://github.com/jails), for helping me solve a problem on #li3 (irc://irc.freenode.net/#li3)



## TODO
* Add relationships.
* Add `with` option to finders.
* Complete unit tests.
* Add support for more formats.