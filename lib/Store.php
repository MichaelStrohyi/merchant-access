<?php

namespace App;

class Store
{
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
     * @var int
     **/
    private $name;

    /**
     * Status
     *
     * @var int
     **/
    private $status;

    /**
     * Owner's id (from the db)
     *
     * @var int
     **/
    private $owner;

    /**
     * Url
     *
     * @var int
     **/
    private $url;

    /**
     * Customer
     *
     * @var App\Customer
     **/
    private $customer;


    public function __construct($customer = null, $id = null)
    {
        $this->id = $id;
        $this->customer = $customer;

        $this->loadStoreData();
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
        $this->url = $url;
        
        return $this;
    }

    /**
     * Return wner's id
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Load data for store from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadStoreData()
    {
        $id = $this->id;
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
        $name =  $this->name;
        return !empty($name);
    }
}
