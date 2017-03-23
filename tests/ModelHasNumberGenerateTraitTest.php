<?php

namespace Inisiatif\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\ConnectionResolverInterface;

class ModelHasNumberGenerateTraitTest extends TestCase
{
    /**
     * Bootstrap Eloquent.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        Eloquent::setConnectionResolver(
            new DatabaseIntegrationTestConnectionResolver
        );

        Eloquent::setEventDispatcher(
            new Dispatcher
        );
    }

    /**
     * Tear down Eloquent.
     */
    public static function tearDownAfterClass()
    {
        Eloquent::unsetEventDispatcher();
        Eloquent::unsetConnectionResolver();
    }

    /**
     * Setup the database schema.
     *
     * @return void
     */
    public function setUp()
    {
        $this->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('number_generated')->unique();
            $table->string('name');
            $table->timestamps();
        });

        $this->schema()->create('generators', function ($table) {
            $table->string('code', 100);
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->integer('sequence');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Tear down the database schema.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->schema()->drop('users');
        $this->schema()->drop('generators');
    }
}

class DatabaseIntegrationTestConnectionResolver implements ConnectionResolverInterface
{
    protected $connection;

    public function connection($name = null)
    {
        if (isset($this->connection)) {
            return $this->connection;
        }
        return $this->connection = new SQLiteConnection(new PDO('sqlite::memory:'));
    }

    public function getDefaultConnection()
    {
        return 'default';
    }

    public function setDefaultConnection($name)
    {
        //
    }
}