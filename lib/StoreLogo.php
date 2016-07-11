<?php

namespace App;

class StoreLogo extends Image
{

    const LOGO_TYPE_LOGO = 'logo';
    const LOGO_MAX_WIDTH = '300';
    const LOGO_MAX_HEIGHT = '300';
    const LOGO_MAX_SIZE = '10240';
    const LOGO_MIME_PNG = 'image/png';
    const LOGO_MIME_JPG = 'image/jpg';
    const LOGO_MIME_JPEG = 'image/jpeg';
    const LOGO_MIME_GIF = 'image/gif';
    const LOGO_ACCEPT_FILTER = '.png,.jpg,.jpeg,.gif,image/png,image/jpg,image/jpeg,image/gif';
    const IMAGE_TYPE_LOGO = 'logo';

     /**
     * Store id
     *
     * @var int
     **/
    private $store;

    public function __construct($store_id, $logo_id = null)
    {
        $this->id = $logo_id;
        $this->store = $store_id;
        #load image with given id
        $this->loadImageData();
    }

    /**
     * Return string with accept filter for <input type="file">
     *
     * @return string
     * @author Michael Strohyi
     **/
    static public function getAcceptFilter()
    {
        return self::LOGO_ACCEPT_FILTER;
    }

    /**
     * Check if logo data match logo requirements.
     *
     * @return self
     * @author Michael Strohyi
     **/
    public function validate()
    {
        parent::validateWidth(self::LOGO_MAX_WIDTH);
        parent::validateHeight(self::LOGO_MAX_HEIGHT);
        parent::validateSize(self::LOGO_MAX_SIZE);
        parent::validateMime([self::LOGO_MIME_GIF,
            self::LOGO_MIME_JPEG,
            self::LOGO_MIME_JPG,
            self::LOGO_MIME_PNG,
            ]);        
        parent::validateContent();
    }

    /**
     * Calls parent::save() and after saves relation image_id-store_id into db 'store_images'.
     * Retrun true if no errors happened, otherwise return false.
     *
     * @return void
     * @author Michael Strohyi
     **/
    function save()
    {
        if (!$this->isModified) {
            return true;
        }

        $this->setType(self::IMAGE_TYPE_LOGO);
        $old_logo_id = $this->getId();

        if (!parent::save()) {
            return false;
        }

        $store_logo = [
            'store_id' => $this->store,
            'logo_id' => $this->getId(),
            ];

        $query = "INSERT INTO `store_logos`  "._QInsert($store_logo);
        $res = _QExec($query);

        if ($res === false) {
            return false;
        }

        if (!empty($old_logo_id)) {
            $query = "DELETE FROM `store_logos`  WHERE `id` = $old_logo_id"; 
            $res = _QExec($query);
        }
        
        return true;
    }

    /**
     * Calls parent::delete() and after deletes relation image_id-store_id from db 'store_images'.
     * Retrun true if no errors happened, otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        $query = "DELETE FROM `store_logos`  WHERE `store_id` = " . $this->getStore() . " AND `logo_id` = " . $this->getId(); 
 
        return  _QExec($query) != false;

    }

    /**
     * Return store.
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getStore()
    {
        return $this->store;
    }
}