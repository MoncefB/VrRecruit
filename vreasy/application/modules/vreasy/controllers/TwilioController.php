<?php

use Vreasy\Models\Task;
use Vreasy\Utils\Twilio;

class Vreasy_TwilioController extends Vreasy_Rest_Controller {



    public function preDispatch() {
    
    // For the moment, we only use the parent dispatch function

        parent::preDispatch();

    }


    public function updateAction()
    {

        // To make it simple, we consider that the task the author of the message is talking about is necessarily the
        // last one, though there can be more than one pending, even from the same author

        // Let's get this task, using Zend Framework methods (getRequest() and getParam()) and Twilio Doc (Author <=> matches
        // the 'From' entry)


        $req = $this->getRequest();

        $tasks = Task::where(['state' => Task::STATE_PENDING,
                              'assigned_phone' => $req->getParam('From')
                            ],
                            [  'orderBy' => ['created_at'],
                               'orderDirection' => ['desc'],
                               'limit' => 1 
                            ]);


        // Unfortunately, we get an array, so we still have to extract the single value (cf 'limit' => 1) it may contain

        if ($tasks)
        // An empty array is equivalent to a FALSE boolean    
            
            {

            $task = $tasks[0];

            // Debug : have we correctly selected a case / task ?
            // print("!!!!!!!!! DEBUG ");
            // print($task->assigned_name);
            // print("!!!!!!!!!");
            // Debug OK
            
            switch(Twilio::analyzeAnswer($req->getParam('Body'))) 
            {

                case Twilio::ANSWER_YES:
                    $task->state = Task::STATE_ACCEPTED;
                    // We add the following line to get the History Log updated (cf Task 2)
                    $task->accepted_at = gmdate(DATE_FORMAT);
                break;

                case Twilio::ANSWER_NO:
                    $task->state = Task::STATE_REFUSED;
                    // We add the following line to get the History Log updated (cf Task 2)
                    $task->refused_at = gmdate(DATE_FORMAT);
                break;

            }


            $task->updated_at = gmdate(DATE_FORMAT);
            $task->save();

            $this->view->response = $task;


            }   


        else    
            {
        
            $this->view->response = ['error' => true, 'message' => 'No phone number matching the task'];
        
        }


    }   

}

    

