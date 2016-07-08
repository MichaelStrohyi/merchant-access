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

     /**
     * Store
     *
     * @var App\Store
     **/
    private $store;

    public function __construct($store, $logo_id = null)
    {
        $this->id = $logo_id;
        $this->store = $store;

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
    }
}