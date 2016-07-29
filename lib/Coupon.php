<?php

namespace App;

class Coupon
{
    const COUPON_REQUIRED_FIELD = 'This is a required field.';
    const COUPON_LABEL_MALFORMED = 'Label contains deprecated characters. Allowed only printable characters.';
    const COUPON_CODE_MALFORMED = 'Code contains deprecated characters. Allowed only printable characters.';
    const COUPON_DATE_MALFORMED = 'Date is incorrect.';
    const COUPON_LINK_NOT_VALID = 'Please, enter valid link (with http:// or https://)';


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

    /**
     * Errors
     *
     * @var array
     **/
    private $errors;


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

    /**
     * Fill in coupon vars from the given $info. 
     * Keep non-existing fields empty.
     *
     * @param  array $info
     * @return self
     * @author Michael Strohyi
     **/
    public function fetchInfo($info)
    {
        $this->setLabel($info['label']);
        $this->setCode($info['code']);
        $this->setLink($info['link']);
        $this->setStartDate($info['startDate']);
        $this->setExpireDate($info['expireDate']);
        $this->setPosition($info['position']);

        if (!isset($info['removeImage'])) {
            $this->deleteImage();
        }

        if (!empty($info['newImage'])) {
            loadNewImage($info['newImage']);
        }
        
        return $this;
    }

    /**
     * Load new image
     *
     * @param array
     * @return void
     * @author Michael Strohyi
     **/
    private function loadNewImage($new_image)
    {
        // !!! stub
        return;
    }

    /**
     * Delete image
     *
     * @return void
     * @author Michael Strohyi
     **/
    function deleteImage()
    {
        /// !!! stub
        return;
    }

    /**
     * Validate user-submitted data.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        $this->validateLabel();
        $this->validateCode();
        $this->validateLink();
        $this->validateStartDate();
        $this->validateExpireDate();
        $this->validateImage();

        return $this;
    }

    /**
     * Check if current label has a valid form and not empty
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateLabel()
    {
        unset($this->errors['label']);
        $label = $this->getLabel();

        # check if label is not empty
        if (empty($label)) {
            $this->errors['label'] = self::COUPON_REQUIRED_FIELD;
            return;
        }

        # check if a label has only allowed characters (printable only)
        if (!ctype_print($label)) {
            $this->errors['label'] = self::COUPON_LABEL_MALFORMED;
        }
    }

    /**
     * Check if current code has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateCode()
    {
        unset($this->errors['code']);        
        $code = $this->getCode();

        # check if code is not empty
        if (empty($code)) {
            return;
        }

        # check if a code has only allowed characters (printable only)
        if (!ctype_print($code)) {
            $this->errors['code'] = self::COUPON_CODE_MALFORMED;
        }
    }

    /**
     * Check if current link has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateLink()
    {
        unset($this->errors['link']);
        $link = $this->getLink();
        
        # check if link is not empty
        if (empty($link)) {
            return;
        }

        # check if link has a valid form
        if (!isUrlValid($link)) {
            $this->errors['link'] = self::COUPON_LINK_NOT_VALID;
        }
    }

    /**
     * Check if current startDate has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateStartDate()
    {
        unset($this->errors['startDate']);
        $startDate = $this->getStartDate();
        
        # check if startDate is not empty
        if (empty($startDate)) {
            return;
        }

        # check if startDate has a valid form
        if (!isDateValid($startDate)) {
            $this->errors['startDate'] = self::COUPON_DATE_MALFORMED;
        }
    }

    /**
     * Check if current expireDate has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateExpireDate()
    {
        unset($this->errors['expireDate']);
        $expireDate = $this->getExpireDate();
        
        # check if expireDate is not empty
        if (empty($expireDate)) {
            return;
        }

        # check if expireDate has a valid form
        if (!isDateValid($expireDate)) {
            $this->errors['expireDate'] = self::COUPON_DATE_MALFORMED;
        }
    }

    /**
     * Check if current imagee has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateImage()
    {
        // !!! stub
    }
}
