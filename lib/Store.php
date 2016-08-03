<?php

namespace App;

class Store
{
    const STORE_REQUIRED_FIELD = 'This is a required field.';
    const STORE_NAME_MALFORMED = 'Name contains deprecated characters. Allowed only printable characters.';
    const STORE_EMAIL_NOT_VALID = 'Please, enter valid Email';
    const STORE_EMAIL_WRONG_DOMAIN = "To add store you MUST use email on the store's domain";
    const STORE_URL_NOT_VALID = 'Please, enter valid URL (with http:// or https://)';
    const STORE_ALREADY_ADDED = "This store is already added somebody's account";
    const STORE_EXISTS = 'This store is already added to other account';
    const STORE_WAITING_VALIDATION = 'added';
    const STORE_WAITING_REMOVING = 'deleted';
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
     * @var string
     **/
    private $email;

    /**
     * Keywords
     *
     * @var string
     **/
    private $keywords;

    /**
     * Description
     *
     * @var string
     **/
    private $description;

    /**
     * array of App\StoreLogo related to store
     *
     * @var array
     **/
    private $logos;

    /**
     * Flag to show if any var was modified
     *
     * @var boolean
     **/
    private $isModified;

    /**
     * array of App\Coupon related to store
     *
     * @var array
     **/
    private $coupons;


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
        $name = $this->prepareName($name);

        if ($this->name != $name) {
            $this->name = $name;
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
     * @param string $url
     * @return self
     * @author Michael Strohyi
     **/
    public function setUrl($url)
    {
        $url = $this->prepareUrl($url);
        
        if ($this->url != $url) {
            $this->url = $url;
            $this->isModified = true;
        }

        return $this;
    }


    /**
     * Return Description
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Description to $description
     *
     * @param string $description
     * @return self
     * @author Michael Strohyi
     **/
    public function setDescription($description)
    {
        if ($this->description != $description) {
            $this->description = $description;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return Keywords
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set Keywords to $keywords
     *
     * @param string $keywords
     * @return self
     * @author Michael Strohyi
     **/
    public function setKeywords($keywords)
    {
        if ($this->keywords != $keywords) {
            $this->keywords = $keywords;
            $this->isModified = true;
        }


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
        $owner = $this->getCustomer()->getId();

        if (empty($id) || empty($owner)) {
            return;
        }

        $this->isModified = false;
        $query = "SELECT * FROM `stores` WHERE `id` = $id AND `owner` = $owner";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->id = $id;
        $this->name = $res_element['name']; 
        $this->url = $res_element['url'];
        $this->status = $res_element['status'];
        $this->email = $res_element['email'];
        $this->keywords = $res_element['keywords'];
        $this->description = $res_element['description'];
        $this->getLogosList();
        $this->getCouponsList();
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
     * Check if current name has a valid form and not empty
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

        # check if a name has only allowed characters (printable only)
        if (!ctype_print($name)) {
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

        if ($this->email != $email) {
            $this->email = $email;
            $this->isModified = true;
        }

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
     * Check if current email has a valid form and not empty
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
     * Check if current url has a valid form and not empty
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
        
        if (!empty($info['description'])) {
            $this->setDescription($info['description']);
        }

        if (!empty($info['keywords'])) {
            $this->setKeywords($info['keywords']);
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
        if ($this->getPrimaryLogo()->getIsModified() && !$this->getPrimaryLogo()->save()) {
            return false;
        }

        if (!$this->isModified) {
            return true;
        }

        $new_id = false;

        if ($this->exists()) {
            $store_data = [
                'keywords' => $this->prepareTextForDb($this->getKeywords()),
                'description' => $this->prepareTextForDb($this->getDescription()),
                ];
            $query = "UPDATE `stores` SET " . _QUpdate($store_data) . " WHERE `id` = " . $this->getId();
        } else {
            $store_data = ['name' => $this->getName(),
                'email' => $this->getEmail(),
                'url' => $this->getUrl(),
                'status' => self::STORE_WAITING_VALIDATION,
                'owner' => $this->getCustomer()->getId(),
                'reg_date' => date("Y-m-d H:i:s"),
                ];
            $query = "INSERT INTO `stores` " . _QInsert($store_data);
            $new_id = true;
        }

        $res = _QExec($query);

        if ($res === false) {
            return false;
        }

        if ($new_id) {
            $this->id = _QID();
        }

        $this->isModified = false;

        return true;
    }

    /**
     * Return true if email has the same domain with url
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    private function EmailMatchDomain()
    {
        $email_domain = parse_url("http://" . $this->getEmail(), PHP_URL_HOST);
        $url_domain = parse_url($this->getUrl(), PHP_URL_HOST);

        if (strpos($url_domain, 'www.') === 0) {
            $url_domain = substr($url_domain, 4);
        }

        return $email_domain == $url_domain;
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
        $url = trim($url);
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

     /**
     * Return true if store is added into db, but not validated yet.
     * Otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isWaitingValidation()
    {
        return $this->getStatus() == self::STORE_WAITING_VALIDATION;
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
     * Return true if hash is valid for this $customer, otherwise return false
     *
     * @param string $hash
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isHashValid($hash)
    {
        return $hash == $this->getHash();
    }

    /**
     * Return true if store is added into db, but not validated yet.
     * Otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isWaitingRemoving()
    {
        return $this->getStatus() == self::STORE_WAITING_REMOVING;
    }

    /**
     * Return true if store is successfully deleted from db.
     * Otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function deleteFromList()
    {
        $query = "DELETE FROM `stores` WHERE `id` = " . $this->getId();

        return  _QExec($query) != false;
    }

    /**
     * Set 'active' status for store and return true.
     * Return false if some error happens.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function activateStore()
    {
        $query = "UPDATE `stores` SET `status` = '".self::STORE_ACTIVE."' WHERE `id` = " . $this->getId();

        return  _QExec($query) != false;
    }

    /**
     * Set 'deleted' status for store and return true.
     * Return false if some error happens.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function deleteStore()
    {
        $query = "UPDATE `stores` SET `status` = '".self::STORE_WAITING_REMOVING."' WHERE `id` = " . $this->getId();

        return  _QExec($query) != false;
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
     * Return first logo from $logos array
     *
     * @return array
     * @author Michael Strohyi
     **/
    public function getPrimaryLogo()
    {
        $logos = $this->getLogos();
        return $logos[0];
    }

    /**
     * Return logos
     *
     * @return array
     * @author Michael Strohyi
     **/
    public function getLogos()
    {
        return $this->logos;
    }

    /**
     * Get list of logos for current store from db and save into var $logos array of StoreLogo objects.
     * If there is no logos for current store save into var $logos one new StoreLogo object.
     *
     * @return void
     * @author Michael Strohyi
     **/
    public function getLogosList()
    {
        $id = $this->getId();

        if (empty($id)) {
            $this->logos = null;
            return;
        }

        $query = "SELECT * FROM `store_logos` WHERE `store_id` = $id";
        _QExec($query);
        $res_assoc = _QAssoc();
        $logos_array = [];

        foreach ($res_assoc as $key => $value) {
            $logo = new StoreLogo($this, $value['logo_id']);

            if ($logo->exists()) {
                $logos_array [] = $logo;
            }
        }

        if (empty($logos_array)) {
            $logos_array [] = new StoreLogo($this);
        }

        $this->logos = $logos_array;
    }

    /**
     * Prepare given $name to use in Store
     *
     * @param string $name
     * @return string
     * @author Michael Strohyi
     **/
    private function prepareName($name)
    {
        $name = trim(preg_replace('/\s+/', ' ',  $name));
        return $name;
    }

    /**
     * Calls StoreLogo::delete() and after cleanes primary logo in $logos.
     * Retrun true if no errors happened, otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function deletePrimaryLogo()
    {
        if (!$this->getPrimaryLogo()->delete()) {
            return false;
        }

        $this->logos[0] = new StoreLogo($this);
        return true;
    }

    /**
     * Return coupons
     *
     * @return array
     * @author Michael Strohyi
     **/
    public function getCoupons()
    {
        return $this->coupons;
    }

    /**
     * Get list of coupons for current store from db and save array of App\Coupon objects into var $coupons.
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function getCouponsList()
    {
        $id = $this->getId();

        if (empty($id)) {
            $this->coupons = null;
            return;
        }

        $query = "SELECT * FROM `coupons` WHERE `store_id` = $id ORDER BY `position`";
        _QExec($query);
        $res_assoc = _QAssoc();
        $coupons_array = [];

        foreach ($res_assoc as $key => $value) {
            $coupon = new Coupon($this, $value['id']);

            if ($coupon->exists()) {
                $coupons_array [$value['id']] = $coupon;
            }
        }

        $this->coupons = $coupons_array;
    }

    /**
     * Save store's coupons into db.
     * Return true if no errors happened, otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function saveCoupons()
    {
        $no_error = true;

        foreach ($this->getCoupons() as $coupon_id => $value) {
            if (!$value->save()) {
                $no_error = false;
            }
        }

        return $no_error;
    }

    /**
     * Get array of removed coupon's ids, remove these coupons and return true.
     * If some error happened return false.
     *
     * @param array $removed_coupons
     * @return boolean
     * @author Michael Strohyi
     **/
    public function deleteRemovedCoupons($removed_coupons)
    {
        $coupons = $this->getCoupons();

        foreach ($removed_coupons as $key => $value) {
            # check if coupon was deleted from db
            if (!$coupons[$value]->delete()) {
                return false;
            }
            # remove deleted coupon from coupns list
            unset($coupons[$value]);
        }

        $this->coupons = $coupons;
        return true;
    }
}
