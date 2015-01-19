<?php

class UpdateTasksTableBis extends Ruckusing_Migration_Base
{

	// We add three columns called "Refused_At", "Accepted_At" and "Completed_At", so that we can display the History Log 
	// corresponding to a task

    public function up()
    {
    	$this->add_column('tasks', 'refused_at', 'datetime', ['default' => NULL]);
    	$this->add_column('tasks', 'accepted_at', 'datetime', ['default' => NULL]);
    	$this->add_column('tasks', 'completed_at', 'datetime', ['default' => NULL]);
    	

    }//up()

    public function down()
    {
    	$this->remove_column("tasks", 'refused_at');
    	$this->remove_column("tasks", 'accepted_at');
    	$this->remove_column("tasks", 'completed_at');

    }//down()
}
