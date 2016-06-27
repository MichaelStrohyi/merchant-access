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

    public function __construct($id = null)
    {
        $this->id = $id;

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
     * @return void
     * @author Michael Strohyi
     **/
    public function setName($name)
    {
        $this->name = $name;
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
     * @return void
     * @author Michael Strohyi
     **/
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return void
     * @author Michael Strohyi
     **/
    public function setUrl($url)
    {
        $this->url = $url;
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
     * Set Owner to $owner
     *
     * @param int $owner
     * @return void
     * @author Michael Strohyi
     **/
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Load data for store with given $id from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadStoreData()
    {
        $this->eraseStoreData();
        $id = $this->id;

        if (empty($id)) {
            return;
        }

        $query = "SELECT * FROM `stores` WHERE `id` = $id";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->setName($res_element['name']);
        $this->setUrl($res_element['url']);
        $this->setStatus($res_element['status']);
        $this->setOwner($res_element['owner']);

        return;
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
        $this->owner = null;
        $this->status = null;
        $this->url = null;
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
}
