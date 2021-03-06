<?php

namespace MVanDuijker\TransactionalModelEvents\Tests;

use Illuminate\Database\Schema\Blueprint;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });

        /** Second database connection */
        $app['config']->set('database.connections.other', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => 'second',
        ]);

        $app['db']->connection('other')->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });
    }

    protected function transactionalEvents()
    {
        return [
            'afterCommit.created',
            'afterCommit.saved',
            'afterCommit.updated',
            'afterCommit.deleted',
            'afterRollback.created',
            'afterRollback.saved',
            'afterRollback.updated',
            'afterRollback.deleted',
        ];
    }
}
