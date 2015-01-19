<?php

class UpdateTasksTable extends Ruckusing_Migration_Base
{

	// We add a new column, called "State", to Tasks table by creating a new migration file
	// The default state will be "Pending"

    public function up()
    {
    	$this->add_column('tasks', 'state', 'string', ['default' => 'Pending']);

    }//up()

    public function down()
    {
    	$this->remove_column("tasks", 'state');

    }//down()
}
