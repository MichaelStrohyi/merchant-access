<?php

namespace App;

class Customer
{
    const CUSTOMER_REQUIRED_FIELD = 'This is a required field.';
    const CUSTOMER_PASSWORD_CONFIRM_FAILED = 'Your password confirmation do not match your password.';
    const CUSTOMER_NAME_MALFORMED = 'Name contains deprecated characters. Allowed only alpha characters.';
    const CUSTOMER_EMAIL_NOT_VALID = 'Please, enter valid Email';
    const CUSTOMER_EMAIL_EXISTS = 'Customer with this email is already registered. Please visit login page to enter your account.';
    const CUSTOMER_WAITING_VALIDATION = 'added';
    const CUSTOMER_ACTIVE = 'active';

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
     * Email
     *
     * @var string
     **/
    private $email;

    /**
     * Password
     *
     * @var string
     **/
    private $password;

    /**
     * Confirm password (for registration only)
     *
     * @var string
     **/
    private $password_confirm;

    /**
     * Errors
     *
     * @var array
     **/
    private $errors;

    /**
     * Status
     *
     * @var string
     **/
    private $status;

    /**
     * Flag to show if any var was modified
     *
     * @var boolean
     **/
    private $isModified;


    public function __construct($id = null)
    {
        $this->id = $id;

        # load data from db if $id is not null
        $this->loadCustomerData();
    }

    /**
     * Return id
     *
     * @return int
     * @author Mykola Martynov
     **/
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return name
     *
     * @return string
     * @author Mykola Martynov
     **/
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param  string  $name
     * @return self
     * @author Mykola Martynov
     **/
    public function setName($name)
    {
        # remove extra/trailing spaces
        $name = trim(preg_replace('#\s+#', ' ', $name));

        if ($this->name != $name) {
            $this->name = $name;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return true if current name has a valid form and not empty
     *
     * @return void
     * @author Mykola Martynov
     **/
    private function validateName()
    {
        unset($this->errors['name']);

        # check if name is not empty
        $name = $this->getName();
        if (empty($name)) {
            $this->errors['name'] = self::CUSTOMER_REQUIRED_FIELD;
            return;
        }

        # check if a name has only allowed characters (alpha only)
        if (!preg_match('#^[a-z]+(\s[a-z]+)?$#i', $name)) {
            $this->errors['name'] = self::CUSTOMER_NAME_MALFORMED;
            return;
        }
    }

    /**
     * Return email
     *
     * @return string
     * @author Mykola Martynov
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
     * @author Mykola Martynov
     **/
    public function setEmail($email)
    {
        # remove trailing spaces
        $email = $this->prepareEmail($email);

        if ($this->email != $email) {
            $this->email = $email;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Prepare given $email to use in Customer
     *
     * @param string $email
     * @return string
     * @author Michael Strohyi
     **/
    static private function prepareEmail($email)
    {
        return strtolower(trim($email));
    }

    /**
     * Return true if current email has a valid form and not empty
     *
     * @return void
     * @author Mykola Martynov
     **/
    private function validateEmail()
    {
        unset($this->errors['email']);
        $email = $this->getEmail();

        if (empty($email)) {
            $this->errors['email'] = self::CUSTOMER_REQUIRED_FIELD;
            return;
        }

        # check if an email has a valid form
        if (!isEmailValid($email)) {
            $this->errors['email'] = self::CUSTOMER_EMAIL_NOT_VALID;
            return;
        }

        # check if customer with given email already exists
        if ($this->isRegistered($email)) {
            $this->errors['email'] = self::CUSTOMER_EMAIL_EXISTS;
        }
    }

    /**
     * Return password
     *
     * @return string
     * @author Mykola Martynov
     **/
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Return confirm password
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getConfirmPassword()
    {
        return $this->password_confirm;
    }

    /**
     * Set password
     *
     * @param  string  $password
     * @return self
     * @author Mykola Martynov
     **/
    public function setPassword($password)
    {
        if (!empty($password)) {
            $password = $this->encryptPassword($password);

            if ($this->password != $password) {
                $this->password = $password;
                $this->isModified = true;
            }
        }

        return $this;
    }

    /**
     * Return encrypted password
     *
     * @param string $password
     * @return string
     * @author Michael Strohyi
     **/
    private function encryptPassword($password)
    {
        return md5($password);
    }

    /**
     * Set confirm password
     *
     * @param  string  $password
     * @return self
     * @author Mykola Martynov
     **/
    public function setConfirmPassword($password)
    {
        if (!empty($password)) {
            $password_confirm = $this->encryptPassword($password);
            
            if ($this->password_confirm != $password_confirm) {
                $this->password_confirm = $password_confirm;
                $this->isModified = true;
            }
        }
        
        return $this;
    }

    /**
     * Return true if current password not empty and equals to confirm password
     *
     * @return void
     * @author Mykola Martynov
     **/
    private function validatePassword()
    {
        unset($this->errors['password']);
        unset($this->errors['password_confirm']);
        $password = $this->getPassword();
        $password_confirm = $this->getConfirmPassword();

        if (empty($password)) {
            $this->errors['password'] = self::CUSTOMER_REQUIRED_FIELD;
        }

        if (empty($password_confirm)) {
            $this->errors['password_confirm'] = self::CUSTOMER_REQUIRED_FIELD;
            return;
        }

        if ($password != $password_confirm) {
            $this->errors['password_confirm'] = self::CUSTOMER_PASSWORD_CONFIRM_FAILED;
            return;
        }
    }

    /**
     * Return customer information gathered from the given $form_Data. 
     * Keep non-existing fields empty.
     *
     * @param  array  $info
     * @return self
     * @author Mykola Martynov
     **/
    public function fetchInfo($info)
    {
        if (!empty($info['name'])) {
            $this->setName($info['name']);
        }

        if (!empty($info['email'])) {
            $this->setEmail($info['email']);
        }
        
        if (!empty($info['password'])) {
            $this->setPassword($info['password']);
        }
        

        if (!empty($info['password_confirm'])) {
            $this->setConfirmPassword($info['password_confirm']);
        }
        
        return $this;
    }

    /**
     * Return true if all fields has valid data, false otherwise.
     *
     * @return boolean
     * @author Mykola Martynov
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
     * Save customer's registration data into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {
        if (!$this->isModified) {
            return true;
        }

        $merchant_account = [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'name' => $this->getName(),
            'status' => self::CUSTOMER_WAITING_VALIDATION,
            'reg_date' => date("Y-m-d H:i:s"),
        ];
        $query = "INSERT INTO `merchants` "._QInsert($merchant_account);
        $res = _QExec($query);

        if ($res === false) {
            $this->id = null;
            return false;
        }

        $this->id = _QID();
        $this->isModified = false;
        
        return true;
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
        $this->validatePassword();

        return $this;
    }

    /**
     * Check if customer with given $email is already registered in db
     *
     * @param string $email
     * @return boolean
     * @author Michael Strohyi
     **/
    private function isRegistered($email)
    {
        $customer = self::findByEmail($email);

        return $customer->exists() && $customer->getId() != $this->getId();
    }

    /**
     * Return true if customer with set $this->id is registered, otherwise return false
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id = $this->getId();

        return !empty($id);
    }

    /**
     * Return true if hash is valid for this $customer, otherwise return false
     *
     * @param string $hash
     * @return boolenan
     * @author Michael Strohyi
     **/
    public function isHashValid($hash)
    {
        return $hash == $this->getHash();
    }

    /**
     * Return true if customer's account is added into db, but not validated yet.
     * Otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isWaitingValidation()
    {
        return $this->getStatus() == self::CUSTOMER_WAITING_VALIDATION;
    }

    /**
     * Set 'active' status for customer's account and return true.
     * Return false if some error happens.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function activateAccount()
    {
        $query = "UPDATE `merchants` SET `status` = '".self::CUSTOMER_ACTIVE."' WHERE `id` = " . $this->getId();

        return  _QExec($query) != false;
    }

    /**
     * Return hash for validation link for this $customer
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getHash()
    {
        return md5($this->getEmail() . $this->getId());
    }

        /**
     * Return name
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param  string $status
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
     * Load data for customer from db 
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadCustomerData()
    {
        $id = $this->id;

        if (empty($id)) {
            return;
        }

        $this->isModified = false;

        $query = "SELECT * FROM `merchants` WHERE `id` = $id";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->name = $res_element['name'];
        $this->email = $res_element['email'];
        $this->status = $res_element['status'];
        $this->password = $res_element['password'];

        return;
    }

    /**
     * Set id for customer with given $email if it exists.
     * Otherwise set null.
     *
     * @param string $email
     * @return App\Customer
     * @author Michael Strohyi
     **/
    static public function findByEmail($email)
    {
        $emal = self::prepareEmail($email);
        $query = "SELECT `id` FROM `merchants` WHERE `email` = "._QData($email);
        _QExec($query, true);
        $res_assoc = _QAssoc();
        $customer_id = empty($res_assoc) ? null : current(current($res_assoc));

        return new self($customer_id);
    }

    /**
     * Return true if given $password matches customer's password in db.
     * Otherwise return false.
     *
     * @param string $pasword
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isPasswordMatch($password)
    {
        return $this->encryptPassword($password) == $this->getPassword();
    }

    /**
     * Return true if customer's status in db is CUSTOMER_ACTIVE, otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isActive()
    {
        return $this->getStatus() == self::CUSTOMER_ACTIVE;
    }

    /**
     * Return array of App\Store associated with current customer
     *
     * @return array
     * @author Michael Strohyi
     **/
    public function getStores()
    {
        $query = "SELECT `id` FROM `stores` WHERE `owner` = ".$this->getId();
        _QExec($query, true);
        $res_assoc = _QAssoc();

        $stores = [];

        foreach ($res_assoc as $key => $value) {
            $stores [] = new Store($this, $value['id']);
        }

        return $stores;
    }
}
