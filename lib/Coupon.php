<?php

namespace App;

class Coupon
{
    const COUPON_REQUIRED_FIELD = 'This is a required field.';
    const COUPON_LABEL_MALFORMED = 'Label contains deprecated characters. Allowed only printable characters.';
    const COUPON_CODE_MALFORMED = 'Code contains deprecated characters. Allowed only printable characters.';
    const COUPON_DATE_MALFORMED = 'Date is incorrect, please use format mm-dd-yyyy.';
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

    /**
     * New image
     *
     * @var string
     **/
    private $newImage;

    /**
     * Activity
     *
     * @var int
     **/
    private $activity;

    /**
     * Temp id for new coupons
     *
     * @var string
     **/
    private $tempId;

    public function __construct($store , $id = null)
    {
        $this->store = $store;
        $this->image = new CouponImage($this);

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

        $startDate = $res_element['startDate'];
        if (!empty($startDate)) {
            $date_array = explode('-', $startDate);
            $startDate = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
        }

        $expireDate = $res_element['expireDate'];
        if (!empty($expireDate)) {
            $date_array = explode('-', $expireDate);
            $expireDate = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
        }

        $this->startDate = $startDate;
        $this->expireDate = $expireDate;

        $this->position = $res_element['position'];
        $this->activity = $res_element['activity'];
    }

    /**
     * Return true if coupon exists ($this is not empty)
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
        $this->setActivity($info['activity']);

        if (isset($info['removeImage'])) {
            $this->getImage()->markDeleted();
        }

        $id = $this->getId();
        if (empty($id)) {
            $id = $this->getTempId();
        }

        if (!empty($_FILES['newImage' . $id]['name']) && $_FILES['newImage' . $id]['error'] == 0) {
            $this->setNewImage($_FILES['newImage' . $id]);
        }

        return $this;
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
        $this->validateNewImage();

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
     * Check if current newImage has a valid form
     *
     * @return void
     * @author Michael Strohyi
     **/
    private function validateNewImage()
    {
        unset($this->errors['newImage']);
        $newImageFile = $this->getNewImage();

         # check if newImage is not empty
        if (empty($newImageFile)) {
            return;
        }

        $newImage = new CouponImage($this);

        # load new image from $file into newImage
        $newImage->gatherFileInfo($newImageFile);
        $newImage->validate();

        if (!$newImage->isValid()) {
            $this->errors['newImage'] = $newImage->getErrorString();
        }

    }

    /**
     * Return newImage
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getNewImage()
    {
        return $this->newImage;
    }

    /**
     * Set newImage to $newImage
     *
     * @param string $newImage
     * @return self
     * @author Michael Strohyi
     **/
    private function setNewImage($newImage)
    {
        if ($this->newImage != $newImage) {
            $this->newImage = $newImage;
            $this->isModified = true;
        }

        return $this;
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
     * Save coupon's data into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {
        # check if coupon was modified
        if (!$this->isModified && !$this->getImage()->getIsModified()) {
            return true;
        }

        $image = $this->getImage();
        $new_image = $this->getNewImage();

        # check if coupon's image was deleted from db if user chose to delete it and new image was not chosen
        if ($image->isDeleted() && empty($new_image) && !$image->delete()) {
            return false;
        }

        # if new image was chosen
        if (!empty($new_image)) {
            # load image from file and validate it
            $image->gatherFileInfo($new_image);
            $image->validate();

            # if image is not valid or can not be saved into db
            if (!$image->isValid() || !$image->save()) {
                return false;
            }
        }

        $startDate = $this->getStartDate();
        if (!empty($startDate)) {
            $date_array = explode('-', $startDate);
            $startDate = $date_array[2] . '-' . $date_array[0] . '-' . $date_array[1];
        }

        $expireDate = $this->getExpireDate();
        if (!empty($expireDate)) {
            $date_array = explode('-', $expireDate);
            $expireDate = $date_array[2] . '-' . $date_array[0] . '-' . $date_array[1];
        }

        $coupon_data = [
            'store_id' => $this->getStore()->getId(),
            'label' => $this->getLabel(),
            'code' => $this->getCode(),
            'link' => $this->getLink(),
            'image' => $this->getImage()->getId(),
            'startDate' => $startDate,
            'expireDate' => $expireDate,
            'position' => $this->getPosition(),
            'activity' => $this->getActivity(),
            ];

        # if image already exists in db update it, else insert it into db
        if ($this->exists()) {
            $query = "UPDATE `coupons` SET " . _QUpdate($coupon_data) . " WHERE `id` = " . $this->getId();
            $new_id = false;
        } else {
            $query = "INSERT INTO `coupons` " . _QInsert($coupon_data);
            $new_id = true;
        }

        $res = _QExec($query);

        if ($res === false) {
            return false;
        }

        if ($new_id) {
            $this->id = _QID();
            $this->tempId = null;
        }

        $this->isModified = false;

        return true;
    }

    /**
     * Return activity
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getActivity()
    {
        return $this->activity;
    }
    /**
     * Set activity to $activity
     *
     * @param int $activity
     * @return self
     * @author Michael Strohyi
     **/
    public function setActivity($activity)
    {
        if ($this->activity != $activity) {
            $this->activity = $activity;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Delete coupon from db and return true. If some error happened retrn false
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function delete()
    {
        $tempId = $this->getTempId();
        if (!empty($tempId)) {
            $this->tempId = null;
            return true;
        }

        if ($this->getImage()->exists() && !$this->getImage()->delete()) {
            return false;
        }

        $query = "DELETE FROM `coupons` WHERE `id` = " . $this->getId();

        if (_QExec($query) == false) {
            return false;
        }

        $this->id = null;
        return  true;
    }


    /**
     * Set tempId to $temp_id
     *
     * @param string $temp_id
     * @return self
     * @author Michael Strohyi
     **/
    public function setTempId($temp_id)
    {
        $this->tempId = $temp_id;
        return $this;
    }

    /**
     * Return tempId
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getTempId()
    {
        return $this->tempId;
    }

}
