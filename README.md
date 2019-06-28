# Eloquent OOWP

Create Post Types that have their data synched with an Eloquent Model.

Intended features:

1. Creating a post creates an associated eloquent shadow, and vice-versa.
2. Updating a post updates its eloquent shadow, and vice-versa.
3. Deleting a post deletes its eloquent shadow, and vice versa.

## Why?

We like using the WordPress admin dashboard to define data types, manage content, and manage data. ACFs provide a very flexible and powerful way to do this. However, doing more complex or intense operations on WordPress data is less than ideal. If this library works, it should be possible to both manage data through the admin system, and easily use that data in complex ways.

## Installation

`composer require joaquimds/eloquoowp`

## Usage

In your WordPress Theme, initialise Eloquent with: 

    init_eloquoowp([
        'host'      => DB_HOST,
        'database'  => DB_NAME,
        'username'  => DB_USER,
        'password'  => DB_PASSWORD,
        'prefix'    => 'el_'
    ]);
    
For all Post Types that you wish to associate with an Eloquent Model, create a new class:

    class ExamplePostType extends EloquentPost {
        public static function getModel()
        {
            return \Outlandish\Website\Models\ExampleModel::class;
        }
    }
    
And an associated model class:
    
    class ExampleModel extends Base {
        protected $table = 'examples';

        public static function createTable()
        {
            Manager::schema()->create('examples', function ($table) {
                $table->integer('id')->unique(); // REQUIRED
                $table->string('example_field');
                ...
            });
        }

        // Called when an instance of the model is inserted into the db
        public function createWordpressPost()
        {
            $postId = wp_insert_post([
                'ID' => $this->id,
                'post_type' => ExamplePostType::postType(),
                ...
            ]);
            $post = WordpressPost::fetchById($postId);
            return $post;
        }

        // Called when an instance is updated
        public function updateWordpressPost(WordpressPost $post)
        {
            $post->setMetadata('example_field', $this->example_field);
        }

        // Called when the associated Wordpress post is saved
        public function updateFromPost(WordpressPost $post)
        {
            $this->example_field = $post->metadata('example_field');
            $this->save();
        }
    }
    
Create your database tables with `wp eloquent:create`.
