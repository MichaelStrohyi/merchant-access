<?php

namespace App;

class Store
{
    const STORE_REQUIRED_FIELD = 'This is a required field.';
    const STORE_NAME_MALFORMED = 'Name contains deprecated characters. Allowed only alpha characters.';
    const STORE_EMAIL_NOT_VALID = 'Please, enter valid Email';
    const STORE_EMAIL_WRONG_DOMAIN = "To add store you MUST use email on the store's domain";
    const STORE_URL_NOT_VALID = 'Please, enter valid URL';
    const STORE_ALREADY_ADDED = "This store is already added somebody's account";
    const STORE_EXISTS = 'This store is already added to other account';
    const STORE_WAITING_VALIDATION = 'added';
    const STORE_ACTIVE = 'active';

    /**
     * Identifier (from the db)
     *
     * @var int
     **/
    private $id;

    /**
     * Name
     *
     * @var string
     **/
    private $name;

    /**
     * Status
     *
     * @var string
     **/
    private $status;

    /**
     * Url
     *
     * @var string
     **/
    private $url;

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


    /**
     * Email
     *
     * @var array
     **/
    private $email;



    public function __construct($customer = null, $id = null)
    {
        $this->customer = $customer;

        $this->loadStoreData($id);
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
     * Return name
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name to $name
     *
     * @param string $name
     * @return self
     * @author Michael Strohyi
     **/
    public function setName($name)
    {
        $this->name = $name;

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
        $this->status = $status;
        
        return $this;
    }

    /**
     * Return URL
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set URL to $url
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function setUrl($url)
    {
        $url = $this->prepareUrl($url);
        $this->url = $url;
        return $this;
    }

    /**
     * Load data for store from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadStoreData($id)
    {
        $owner = $this->customer->getId();

        if (empty($id) || empty($owner)) {
            return;
        }

        $query = "SELECT * FROM `stores` WHERE `id` = $id AND `owner` = $owner";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->id = $id;
        $this->setName($res_element['name']);
        $this->setUrl($res_element['url']);
        $this->setStatus($res_element['status']);

    }

    /**
     * Return true if store has status 'active', otherwise return false
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isActive()
    {
        return $this->getStatus() == self::STORE_ACTIVE;
    }

    /**
     * Return true if store exists ($this is not empty)
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
     * Return true if current name has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateName()
    {
        unset($this->errors['name']);

        # check if name is not empty
        $name = $this->getName();
        if (empty($name)) {
            $this->errors['name'] = self::STORE_REQUIRED_FIELD;
            return;
        }

        # check if a name has only allowed characters (alpha only)
        if (!preg_match('#^[a-z]+(\s[a-z]+)?$#i', $name)) {
            $this->errors['name'] = self::STORE_NAME_MALFORMED;
            return;
        }
    }

    /**
     * Return email
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param  string  $email
     * @return self
     * @author Michael Strohyi
     **/
    public function setEmail($email)
    {
        # remove trailing spaces
        $email = $this->prepareEmail($email);

        $this->email = $email;

        return $this;
    }

    /**
     * Prepare given $email to use in Store
     *
     * @param string $email
     * @return string
     * @author Michael Strohyi
     **/
    private function prepareEmail($email)
    {
        return strtolower(trim($email));
    }

    /**
     * Return true if current email has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateEmail()
    {
        unset($this->errors['email']);
        $email = $this->getEmail();

        if (empty($email)) {
            $this->errors['email'] = self::STORE_REQUIRED_FIELD;
            return;
        }

        # check if an email has a valid form
        if (!isEmailValid($email)) {
            $this->errors['email'] = self::STORE_EMAIL_NOT_VALID;
            return;
        }

        # check if an email has the same domain with url
        if (!$this->EmailMatchDomain($email)) {
            $this->errors['email'] = self::STORE_EMAIL_WRONG_DOMAIN;
            return;
        }
    }

    /**
     * Return true if current url has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateUrl()
    {
        unset($this->errors['url']);

        # check if url is not empty
        $url = $this->getUrl();
        if (empty($url)) {
            $this->errors['url'] = self::STORE_REQUIRED_FIELD;
            return;
        }

        # check if an url has a valid form
        if (!isUrlValid($url)) {
            $this->errors['url'] = self::STORE_URL_NOT_VALID;
            return;
        }

        # check if store with given url already exists
        if ($this->isUrlRegistered()) {
            $this->errors['url'] = self::STORE_ALREADY_ADDED;
        }
    }

    /**
     * Return store information gathered from the given $info. 
     * Keep non-existing fields empty.
     *
     * @param  array $info
     * @return self
     * @author Michael Strohyi
     **/
    public function fetchInfo($info)
    {
        if (!empty($info['name'])) {
            $this->setName($info['name']);
        }

        if (!empty($info['email'])) {
            $this->setEmail($info['email']);
        }
        
        if (!empty($info['url'])) {
            $this->setUrl($info['url']);
        }
                
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
     * Validate user-submitted data.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        $this->validateName();
        $this->validateEmail();
        $this->validateUrl();

        return $this;
    }

    /**
     * Save store's registration data into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {
        $new_store = [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'url' => $this->getUrl(),
            'status' => self::STORE_WAITING_VALIDATION,
            'reg_date' => date("Y-m-d H:i:s"),
            'owner' => $this->customer->getId(),
        ];
        $query = "INSERT INTO `stores` "._QInsert($new_store);
        $res = _QExec($query);

        if ($res === false) {
            $this->id = null;
            $this->eraseStoreData();
            return false;
        }
        $this->id = _QID();

        return true;
    }

    /**
     * Return true if email has the same domain with url
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function EmailMatchDomain()
    {
        $email_domain = substr($this->getEmail(), strpos($this->getEmail(), '@')+1);
        return strpos($this->getUrl(), $email_domain) == strlen($this->getUrl())-strlen($email_domain);
    }

    /**
     * Prepare given $url to use in Store
     *
     * @param string $url
     * @return string
     * @author Michael Strohyi
     **/
    private function prepareUrl($url)
    {
        $replace = ['http://', 'http:/', 'http//', 'http/','https://', 'https:/', 'https//', 'https/'];
        $url = str_replace($replace, '', strtolower(trim($url)));
        return $url;
    }

    /**
     * Return true if store is already added by any customer
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isUrlRegistered()
    {
        $query = "SELECT `id` FROM `stores` WHERE `url` = " . _QData($this->getUrl());
        _QExec($query, true);
        $res_assoc = _QAssoc();

        return !empty($res_assoc);
    }

    /**
     * Unset all vars for Store
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function eraseStoreData()
    {
        $this->name = null;
        $this->email = null;
        $this->url = null;
        $this->errors = null;
        $this->status = null;
    }

    /**
     * Return hash for validation link for this $store
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getHash()
    {
        return md5($this->getEmail() . $this->getId());
    }
}
