<?php

namespace App;

class StoreLogo extends Image
{

    const IMAGE_TYPE_LOGO = 'logo';
    const IMAGE_MAX_WIDTH = '300';
    const IMAGE_MAX_HEIGHT = '300';
    const IMAGE_MAX_SIZE = '10240';
    const IMAGE_MIME_PNG = 'image/png';
    const IMAGE_MIME_JPG = 'image/jpg';
    const IMAGE_MIME_JPEG = 'image/jpeg';
    const IMAGE_MIME_GIF = 'image/gif';
    const IMAGE_ACCEPT_FILTER = '.png,.jpg,.jpeg,.gif,image/png,image/jpg,image/jpeg,image/gif';

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
    function getAcceptFilter()
    {
        return self::IMAGE_ACCEPT_FILTER;
    }
}