<?php

namespace App;

class Coupon
{
    /**
     * Identifier (from the db)
     *
     * @var int
     **/
    private $id;

    /**
     * ticketId
     *
     * @var App\Ticket
     **/
    private $ticket;
    
    /**
     * Body
     *
     * @var string
     **/
    private $body;
        
    /**
     * timeCreated
     *
     * @var string
     **/
    private $timeCreated;

    /**
     * Errors
     *
     * @var array
     **/
    private $errors;
 
    public function __construct($ticket , $id = null)
    {
        $this->ticket = $ticket;
        $this->loadMessageData($id);
    }

    /**
     * Return id
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return ticket
     *
     * @return App\Ticket
     * @author Michael Strohyi
     **/
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Return body
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set body to $body
     *
     * @param string $label
     * @return self
     * @author Michael Strohyi
     **/
    public function setBody($body)
    {
        if ($this->body != $body) {
            $this->body = $body;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return timeUpdated
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getTimeUpdated()
    {
        return $this->timeUpdated;
    }

    /**
     * Set timeUpdated to $timeUpdated
     *
     * @param string $timeUpdated
     * @return self
     * @author Michael Strohyi
     **/
    public function setTimeUpdated($timeUpdated)
    {
        if ($this->timeUpdated != $timeUpdated) {
            $this->timeUpdated = $timeUpdated;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Load data for message from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadMessageData($id)
    {
        $ticketId = $this->getTicket()->getId();

        if (empty($id) || empty($ticketId)) {
            return;
        }

        $this->isModified = false;
        $query = "SELECT * FROM `ticket_messages` WHERE `id` = $id AND `ticketId` = $ticketId";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->id = $id;
        $this->body = $res_element['body'];
        $timeUpdated = $res_element['timeUpdated'];

        if (!empty($timeUpdated)) {
            $date_array = explode(' ', $timeUpdated);
            $time_string = $date_array[1];
            $date_array = explode('-', $date_array[0]);
            $timeUpdated = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0]  . ' ' . $time_string;
        }

        $this->timeUpdated = $timeUpdated;
    }

    /**
     * Return true if message exists ($this is not empty)
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id =  $this->getId();
        return !empty($id);
    }

    /**
     * Return true if field with $name has error
     *
     * @param string $name
     * @return boolean
     * @author Michael Strohyi
     **/
    public function hasError($name)
    {
        return isset($this->errors[$name]);
    }

    /**
     * Return error string for given field $name
     *
     * @param string $name
     * @return string
     * @author Michael Strohyi
     **/
    public function getErrorString($name)
    {
        return $this->hasError($name) ? $this->errors[$name] : '';
    }
}
