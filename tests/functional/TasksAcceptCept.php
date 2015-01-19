<?php

// We use the same test model to simulate a Twilio input, and to see if the database is updated correctly

$I = new TestGuy($scenario);
$I->wantTo('Test the update to a Yes Twilio Message');
$I->haveTask(['assigned_name' => 'John Doe', 'assigned_phone' => '+55 555-555-555', 'state'=>'Pending']);
$I->sendPOST('/twilio/update?format=json', ['From' => '+55 555-555-555', 'Body' => "I will do it today, I'm okay with that !"]);

$I->seeInDatabase('tasks', ['assigned_name' => 'John Doe','assigned_phone' => '+55 555-555-555', 'state' => 'Accepted']);
