<?php

namespace App;
use DateTime;

class TicketMessage
{
    const MESSAGE_REQUIRED_FIELD = 'This is a required field.';
    const MESSAGE_AUTHOR_OPERATOR = 'operator';
    const MESSAGE_AUTHOR_CUSTOMER = 'you';

    /**
     * Identifier (from the db)
     *
     * @var int
     **/
    private $id;

    /**
     * ticket
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
     * @var DateTime
     **/
    private $timeCreated;
    
    /**
     * userId
     *
     * @var int
     **/
    private $userId;

    /**
     * Errors
     *
     * @var array
     **/
    private $errors;
 
    public function __construct($ticket, $id = null)
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
        $this->timeCreated = new DateTime($res_element['timeCreated']);
        $this->userId = $res_element['userId'];
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
     * Return message information gathered from the given $info.
     * Keep non-existing fields empty.
     *
     * @param  array $info
     * @return self
     * @author Michael Strohyi
     **/
    public function fetchInfo($info)
    {
        if (!empty($info['body'])) {
            $this->setBody($info['body']);
        }

        return $this;
    }

    /**
     * Validate message data.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        $this->validateBody();

        return $this;
    }

    /**
     * Return true if all fields has valid data, false otherwise.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * Check if current message body has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateBody()
    {
        unset($this->errors['body']);
        $body = $this->getBody();
        if (empty($body)) {
            $this->errors['body'] = self::MESSAGE_REQUIRED_FIELD;
            return;
        }
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

    /**
     * Save message's data into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {
        # return true if message vars were not modified
        if (!$this->isModified) {
            return true;
        }

        # flag to show if new id is generated by query
        $new_id = false;

        # get current time
        $date_time_obj = new DateTime();
        $current_time = $date_time_obj->format('Y-m-d H:i:s');

        # make query
        if ($this->exists()) {
            $message_data = ['body' => $this->prepareTextForDb($this->getBody())];
            $query = "UPDATE `ticket_messages` SET " . _QUpdate($message_data) . " WHERE `id` = " . $this->getId();
        } else {
            $message_data = ['ticketId' => $this->getTicket()->getId(),
                'body' => $this->prepareTextForDb($this->getBody()),
                'timeCreated' => $current_time,
                'userId' => $this->getUserId(),
                ];
            $query = "INSERT INTO `ticket_messages` " . _QInsert($message_data);
            $new_id = true;
        }

        # run query
        $res = _QExec($query);

        # return false if error happens trying run query
        if ($res === false) {
            return false;
        }

        # set new id if it exists
        if ($new_id) {
            $this->id = _QID();
        }

        # reset isModified flag
        $this->isModified = false;

        return true;
    }

    /**
     * Convert text from $text to form can be inserted in db
     *
     * @return string
     * @author Michael Strohyi
     **/
    private function prepareTextForDb($text)
    {
        return strip_tags($text);
    }

    /**
     * Return userId
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId to $userId
     *
     * @param string $label
     * @return self
     * @author Michael Strohyi
     **/
    public function setUserId($userId)
    {
        if ($this->userId != $userId) {
            $this->userId = $userId;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return field $name as a string in specific format if it exists and has DateTime type.
     * Otherwise return empty string
     *
     * @param string $name
     * @return string
     * @author Michael Strohyi
     **/
    public function getFormattedDate($name)
    {
        if (property_exists($this, $name) && $this->$name instanceof DateTime) {
            return $this->$name->format('m-d-Y H:i');
        }

        return '';
    }

    /**
     * Return author of message.
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getAuthor()
    {
        return  $this->getUserId() == -1 ? self::MESSAGE_AUTHOR_OPERATOR : self::MESSAGE_AUTHOR_CUSTOMER;
    }
}
