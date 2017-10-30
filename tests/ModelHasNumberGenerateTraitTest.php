<?php

namespace Inisiatif\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\SQLiteConnection;
use Inisiatif\Tests\Stubs\EloquentModelStub;
use Inisiatif\NumberGenerator\Models\Generator;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\ConnectionResolverInterface;
use Inisiatif\Tests\Stubs\EloquentModelThrowExceptionStub;
use Inisiatif\NumberGenerator\Exceptions\NumberGeneratorException;

class ModelHasNumberGenerateTraitTest extends TestCase
{
    public function testCreateModelWillBeGenerateNumber()
    {
        $created = EloquentModelStub::create(['name' => 'Nuradiyana']);
        $this->assertEquals(10, strlen($created->number_generated));
        $this->assertEquals(date('y'), substr($created->number_generated, 0, 2));
        $this->assertEquals(date('m'), substr($created->number_generated, 2, 2));
        $this->assertEquals(date('d'), substr($created->number_generated, 4, 2));
    }

    public function testCreateModelWillBeNotGenerateNumberAutomaticWhenFieldIsAssign()
    {
        $created = EloquentModelStub::create(['name' => 'Nuradiyana', 'number_generated' => '0001']);
        $this->assertEquals('0001', $created->number_generated);
    }

    public function testCreateModelWillBeThrowException()
    {
        $this->expectException(NumberGeneratorException::class);
        $created = EloquentModelThrowExceptionStub::create(['name' => 'Nuradiyana']);
    }

    public function testCreateModelWhenGeneratorTableIsExist()
    {
        $dt = new \DateTime();

        $generator = Generator::create([
            'code' => EloquentModelStub::class,
            'year' => $dt->format('y'), 
            'month' => $dt->format('m'), 
            'day' => $dt->format('d'),
            'sequence' => 1,
        ]);

        $created = EloquentModelStub::create(['name' => 'Nuradiyana']);
        $this->assertEquals(10, strlen($created->number_generated));
        $this->assertEquals(date('y'), substr($created->number_generated, 0, 2));
        $this->assertEquals(date('m'), substr($created->number_generated, 2, 2));
        $this->assertEquals(date('d'), substr($created->number_generated, 4, 2));
    }

    public function testCreateModel()
    {
        $dt = new \DateTime();

        $generator = Generator::create(['code' => EloquentModelStub::class,
            'year' => '90', 'month' => '01', 'day' => '01', 'sequence' => 6,
        ]);

        $created = EloquentModelStub::create(['name' => 'Nuradiyana']);
        $this->assertEquals(10, strlen($created->number_generated));
        
        $this->assertEquals(1, substr($created->number_generated, -1));
        $this->assertEquals(date('y'), substr($created->number_generated, 0, 2));
        $this->assertEquals(date('m'), substr($created->number_generated, 2, 2));
        $this->assertEquals(date('d'), substr($created->number_generated, 4, 2));

        $this->assertNotEquals(6, substr($created->number_generated, -1));
        $this->assertNotEquals($generator->year, substr($created->number_generated, 0, 2));
        $this->assertNotEquals($generator->month, substr($created->number_generated, 2, 2));
        $this->assertNotEquals($generator->day, substr($created->number_generated, 4, 2));
    }

    /**
     * Helpers Eloquent.
     */
    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }

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