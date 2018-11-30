<?php

namespace Ziikr\Config;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ziikr:config:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the config repository';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createRepository();

        $this->info('Config table created successfully.');
    }

    public function createRepository()
    {
        Schema::create('user_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户标识');
            $table->string('name')->default('');
            $table->string('value', 255)->default('');
            $table->tinyInteger('group')->unsigned()->default(0)->comment('分组');

            $table->timestamps();

            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'group']);
        });

        Schema::create('system_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('value')->default('');
            $table->tinyInteger('value_type')->unsigned()->default(0)->comment('0 - default, 1 - int, 2 - string, 3 - datetime');
            $table->string('display_name', 45)->default('');
            $table->boolean('configurable')->unsigned()->default(0);
            $table->tinyInteger('group')->unsigned()->default(0)->comment('分组');

            $table->timestamps();

            $table->unique('name');
            $table->index('configurable');
            $table->index('group');
        });
    }
}
