<?php

namespace App;

class Ticket
{
    /**
     * Identifier (from the db)
     *
     * @var int
     **/
    private $id;

    /**
     * Theme
     *
     * @var string
     **/
    private $theme;

    /**
     * Status
     *
     * @var string
     **/
    private $status;

    /**
     * Time when ticket was creadet
     *
     * @var string
     **/
    private $timeCreated;

    /**
     * Time when ticket was updated last time
     *
     * @var string
     **/
    private $timeUpdated;

    /**
     * Flag to show if any var was modified
     *
     * @var boolean
     **/
    private $isModified;

    /**
     * array of App\Message related to ticket
     *
     * @var array
     **/
    private $messages;

    /**
     * Customer
     *
     * @var App\Customer
     **/
    private $customer;


    public function __construct($customer = null, $id = null)
    {
        $this->customer = $customer;

        $this->loadTicketData($id);
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
     * Return theme
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme to $theme
     *
     * @param string $theme
     * @return self
     * @author Michael Strohyi
     **/
    public function setTheme($theme)
    {
        $theme = $this->prepareTheme($theme);

        if ($this->theme != $theme) {
            $this->theme = $theme;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return status
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status to $status
     *
     * @param string $status
     * @return self
     * @author Michael Strohyi
     **/
    public function setStatus($status)
    {
        if ($this->status != $status) {
            $this->status = $status;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return timeCreated
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * Set timeCreated to $timeCreated
     *
     * @param string $timeCreated
     * @return self
     * @author Michael Strohyi
     **/
    public function setTimeCreated($timeCreated)
    {
        
        if ($this->timeCreated != $timeCreated) {
            $this->timeCreated = $timeCreated;
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
     * Return Customer
     *
     * @return App\Customer
     * @author Michael Strohyi
     **/
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Return messages
     *
     * @return array
     * @author Michael Strohyi
     **/
    public function getMessages()
    {
        return $this->messages;
    }


    /**
     * Load data for ticket from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadTicketData($id)
    {
        $merchantId = $this->getCustomer()->getId();

        if (empty($id) || empty($merchantId)) {
            return;
        }

        $this->isModified = false;
        $query = "SELECT * FROM `tickets` WHERE `id` = $id AND `merchantId` = $merchantId";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->id = $id;
        $this->theme = $res_element['theme']; 
        $this->status = $res_element['status'];
        $timeUpdated = $res_element['timeUpdated'];
        if (!empty($timeUpdated)) {
            $date_array = explode(' ', $timeUpdated);
            $time_string = $date_array[1];
            $date_array = explode('-', $date_array[0]);
            $timeUpdated = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0]  . ' ' . $time_string;
        }

        $this->timeUpdated = $timeUpdated;

        $timeCreated = $res_element['timeCreated'];
        if (!empty($timeCreated)) {
            $date_array = explode(' ', $timeCreated);
            $time_string = $date_array[1];
            $date_array = explode('-', $date_array[0]);
            $timeCreated = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0]  . ' ' . $time_string;
        }

        $this->timeCreated = $timeCreated;
        $this->getMessagesList();
    }

    /**
     * Return true if ticket exists ($this is not empty)
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id =  $this->id;
        return !empty($id);
    }


    /**
     * Get list of messages for current ticket from db and save array of App\TicketMessage objects into var $messages.
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function getMessagesList()
    {
        $id = $this->getId();

        if (empty($id)) {
            $this->messages = null;
            return;
        }

        $query = "SELECT * FROM `ticket_messages` WHERE `ticketId` = $id ORDER BY `timeCreated`";
        _QExec($query);
        $res_assoc = _QAssoc();
        $messages_array = [];

        foreach ($res_assoc as $key => $value) {
            $message = new TicketMessage($this, $value['id']);

            if ($message->exists()) {
                $messages_array [$value['id']] = $message;
            }
        }

        $this->messages = $messages_array;
    }

}
