<?php

namespace App;

class CouponImage extends Image
{

    const IMAGE_MAX_WIDTH = '300';
    const IMAGE_MAX_HEIGHT = '300';
    const IMAGE_MAX_SIZE = '10240';
    const IMAGE_MIME_PNG = 'image/png';
    const IMAGE_MIME_JPG = 'image/jpg';
    const IMAGE_MIME_JPEG = 'image/jpeg';
    const IMAGE_MIME_GIF = 'image/gif';
    const IMAGE_ACCEPT_FILTER = '.png,.jpg,.jpeg,.gif,image/png,image/jpg,image/jpeg,image/gif';
    const IMAGE_TYPE_COUPON = 'coupon_image';

     /**
     * Coupon
     *
     * @var App\Coupon
     **/
    private $coupon;

    /**
     * Flag to show image must be deleted
     *
     * @var boolean
     **/
    private $imageDeleted;

    public function __construct($coupon, $image_id = null)
    {
        $this->id = $image_id;
        $this->coupon = $coupon;
        #load image with given id
        $this->loadImageData();
    }

    /**
     * Return string with accept filter for <input type="file">
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getAcceptFilter()
    {
        return self::IMAGE_ACCEPT_FILTER;
    }

    /**
     * Check if image data match image requirements.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        parent::validateWidth(self::IMAGE_MAX_WIDTH);
        parent::validateHeight(self::IMAGE_MAX_HEIGHT);
        parent::validateSize(self::IMAGE_MAX_SIZE);
        parent::validateMime([self::IMAGE_MIME_GIF,
            self::IMAGE_MIME_JPEG,
            self::IMAGE_MIME_JPG,
            self::IMAGE_MIME_PNG,
            ]);        
        parent::validateContent();
    }

    /**
     * Calls parent::save() if isModified is true.
     * Retrun true if no errors happened, otherwise return false.
     *
     * @return void
     * @author Michael Strohyi
     **/
    function save()
    {
        # check if image was modified
        if (!$this->isModified) {
            return true;
        }

        $this->setType(self::IMAGE_TYPE_COUPON);

        if (!parent::save()) {
            return false;
        }
        # return if image is already in db (not new image)
                
        return true;
    }

    /**
     * Return coupon.
     *
     * @return App\Coupon
     * @author Michael Strohyi
     **/
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set flag imageDeleted to true
     *
     * @return void
     * @author Michael Strohyi
     **/
    public function markDeleted()
    {
        $this->imageDeleted = true;
        $this->isModified = true;
    }

    /**
     * Return imageDeleted
     *
     * @return void
     * @author Michael Strohyi
     **/
    public function isDeleted()
    {
        return $this->imageDeleted;
    }
}