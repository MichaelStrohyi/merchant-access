<?php

namespace App;
use DateTime;

class Ticket
{
    const TICKET_REQUIRED_FIELD = 'This is a required field.';
    const TICKET_THEME_MALFORMED = 'Theme contains deprecated characters. Allowed only printable characters.';
    const TICKET_STATUS_ACTIVE = 'active';
    const TICKET_STATUS_CLOSED = 'closed';
    const LAST_AUTHOR_OPERATOR = 'operator';
    const LAST_AUTHOR_CUSTOMER = 'you';

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
     * @var DateTime
     **/
    private $timeCreated;

    /**
     * Time when ticket was updated last time
     *
     * @var DateTime
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

    /**
     * Errors
     *
     * @var array
     **/
    private $errors;


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
        $this->timeUpdated = new DateTime($res_element['timeUpdated']);
        $this->timeCreated = new DateTime($res_element['timeCreated']);
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

    /**
     * Gather ticket information from the given $info.
     * Keep non-existing fields empty.
     *
     * @param  array $info
     * @return self
     * @author Michael Strohyi
     **/
    public function fetchInfo($info)
    {
        if (!empty($info['theme'])) {
            $this->setTheme($info['theme']);
        }

        $this->timeCreated = new DateTime();

        if (!empty($info['messages'])) {
            $this->fetchMessages($info['messages']);
        }

        return $this;
    }

    /**
     * Validate ticket data.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        $this->validateTheme();
        $this->validateMessages();

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
     * Check if current theme has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateTheme()
    {
        unset($this->errors['theme']);
        $theme = $this->getTheme();

        if (empty($theme)) {
            $this->errors['theme'] = self::TICKET_REQUIRED_FIELD;
            return;
        }

        # check if a theme has only allowed characters (printable only)
        if (!ctype_print($theme)) {
            echo "<hr>theme: $theme<hr>";
            $this->errors['theme'] = self::TICKET_THEME_MALFORMED;
            return;
        }
    }

    /**
     * Check if current messages are valid
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateMessages()
    {
        unset($this->errors['messages']);
        $messages = $this->getMessages();
        if (empty($messages)) {
            return;
        }

        foreach ($messages as $key => $message) {
            $message->validate();
            if (!$message->isValid()) {
                $this->errors['messages'] = 'not valid';
            }
        }
    }

    /**
     * Return last message from messages array.
     * If messages array is empty return false
     *
     * @return mixed
     * @author Michael Strohyi
     **/
    public function getLastMessage()
    {
        return empty($this->messages) ? false : end($this->messages);
    }

    /**
     * Gather messages information from the given $info.
     * Keep non-existing fields empty.
     *
     * @param  array $info
     * @return self
     * @author Michael Strohyi
     **/
    public function fetchMessages($info)
    {
        if (!empty($info)) {
            foreach ($info as $key => $value) {
                if ($key == 'new') {
                    $message = new TicketMessage($this);
                    $message->fetchInfo($value);
                    $this->messages[] = $message;
                } else {
                    $this->getMessages()[$key]->fetchInfo($value);
                }
            }
        }

        return $this;
    }

    /**
     * Prepare given $theme to use in Ticket
     *
     * @param string $theme
     * @return string
     * @author Michael Strohyi
     **/
    private function prepareTheme($theme)
    {
        $theme = trim(preg_replace('/\s+/', ' ',  $theme));
        return $theme;
    }


    /**
     * Save ticket's data into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {
        # return true if ticket vars were not modified
        if (!$this->isModified) {
            return true;
        }

        # flag to show if new id is generated by query
        $new_id = false;

        #get current time
        $date_time_obj = new DateTime();
        $current_time = $date_time_obj->format('Y-m-d H:i:s');

        # make query
        if ($this->exists()) {
            $ticket_data = ['status' => $this->getStatus(),
                'timeUpdated' => $current_time,
                ];
            $query = "UPDATE `ticket` SET " . _QUpdate($ticket_data) . " WHERE `id` = " . $this->getId();
        } else {
            $ticket_data = ['merchantId' => $this->getCustomer()->getId(),
                'theme' => $this->getTheme(),
                'timeCreated' => $current_time,
                'timeUpdated' => $current_time,
                'status' => self::TICKET_STATUS_ACTIVE,
                ];
            $query = "INSERT INTO `tickets` " . _QInsert($ticket_data);
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

        # save all ticket's messages into db and return false if error occured
        if (!$this->saveMessages()) {
            return false;
        }

        # reset isModified flag
        $this->isModified = false;

        return true;
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
            return $this->$name->format('m-d-Y H:i:s');
        }

        return '';
    }

    /**
     * Save ticket's messages into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    private function saveMessages()
    {
        $no_error = true;

        foreach ($this->getMessages() as $key => $message) {
            if (!$message->save()) {
                $no_error = false;
            }
        }

        $this->getMessagesList();
        return $no_error;
    }

    /**
     * Return true if ticket has status 'active', otherwise return false
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isActive()
    {
        return $this->getStatus() == self::TICKET_STATUS_ACTIVE;
    }

     /**
     * Set 'closed' status for ticket and return true.
     * Return false if some error happens.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function closeTicket()
    {
        $query = "UPDATE `tickets` SET `status` = '".self::TICKET_STATUS_CLOSED."' WHERE `id` = " . $this->getId();

        return  _QExec($query) != false;
    }

    /**
     * Return author of the last message of current ticket.
     * Return false if thera are no messages in the ticket.
     *
     * @return mixed
     * @author Michael Strohyi
     **/
    public function getLastAuthor()
    {
        return  $this->getLastMessage()->getUserId() == -1 ? self::LAST_AUTHOR_OPERATOR : self::LAST_AUTHOR_CUSTOMER;
    }
}
