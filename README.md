# Eloquent OOWP

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
