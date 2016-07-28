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
     * Store
     *
     * @var App\Store
     **/
    private $store;
    
    /**
     * Label
     *
     * @var string
     **/
    private $label;
    
    /**
     * Code
     *
     * @var string
     **/
    private $code;
    
    /**
     * Link
     *
     * @var string
     **/
    private $link;
    
    /**
     * Image
     *
     * @var App\CouponImage
     **/
    private $image;
    
    /**
     * Start date
     *
     * @var string
     **/
    private $startDate;
    
    /**
     * Expire date
     *
     * @var string
     **/
    private $expireDate;
    
    /**
     * Position
     *
     * @var int
     **/
    private $position;
    
    /**
     * Parent id
     *
     * @var int
     **/
    private $parentId;

    /**
     * Flag to show if any var was modified
     *
     * @var boolean
     **/
    private $isModified;


    public function __construct($store , $id = null)
    {
        $this->store = $store;

        $this->loadCouponData($id);
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
     * Return store
     *
     * @return App\Store
     * @author Michael Strohyi
     **/
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Return label
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label to $label
     *
     * @param string $label
     * @return self
     * @author Michael Strohyi
     **/
    public function setLabel($label)
    {
        if ($this->label != $label) {
            $this->label = $label;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return code
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code to $code
     *
     * @param string $code
     * @return self
     * @author Michael Strohyi
     **/
    public function setCode($code)
    {
        if ($this->code != $code) {
            $this->code = $code;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return link
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link to $link
     *
     * @param string $link
     * @return self
     * @author Michael Strohyi
     **/
    public function setLink($link)
    {
        if ($this->link != $link) {
            $this->link = $link;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return image
     *
     * @return App\CouponImage
     * @author Michael Strohyi
     **/
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Return startDate
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startDate to $startDate
     *
     * @param string $startDate
     * @return self
     * @author Michael Strohyi
     **/
    public function setStartDate($startDate)
    {
        if ($this->startDate != $startDate) {
            $this->startDate = $startDate;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return expireDate
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getexpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set expireDate to $expireDate
     *
     * @param string $expireDate
     * @return self
     * @author Michael Strohyi
     **/
    public function setExpireDate($expireDate)
    {
        if ($this->expireDate != $expireDate) {
            $this->expireDate = $expireDate;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return position
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position to $position
     *
     * @param int $position
     * @return self
     * @author Michael Strohyi
     **/
    public function setPosition($position)
    {
        if ($this->position != $position) {
            $this->position = $position;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return parentId
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set position to $parentId
     *
     * @param int $parentId
     * @return self
     * @author Michael Strohyi
     **/
    public function setParentId($parentId)
    {
        if ($this->parentId != $parentId) {
            $this->parentId = $parentId;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Load data for coupon from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function loadCouponData($id)
    {
        $store_id = $this->getStore()->getId();

        if (empty($id) || empty($store_id)) {
            return;
        }

        $this->isModified = false;
        $query = "SELECT * FROM `coupons` WHERE `id` = $id AND `store_id` = $store_id";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->id = $id;
        $this->label = $res_element['label'];
        $this->code = $res_element['code'];
        $this->link = $res_element['link'];
        $this->image = new CouponImage($this, $res_element['image']);
        $this->startDate = $res_element['startDate'];
        $this->expireDate = $res_element['expireDate'];
        $this->position = $res_element['position'];
    }

    /**
     * Return true if coupon exists ($this is not empty)
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id =  $this->id;
        return !empty($id);
    }

}
