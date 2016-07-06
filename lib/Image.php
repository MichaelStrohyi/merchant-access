<?php

namespace App;

class Image
{
     /**
     * Identifier (from the db)
     *
     * @var int
     **/
    protected $id;

     /**
     * Image width
     *
     * @var int
     **/
    protected $width;
    
     /**
     * Image height
     *
     * @var int
     **/
    protected $height;
    
     /**
     * Image mime
     *
     * @var string
     **/
    protected $mime;
    
     /**
     * Image size in bytes
     *
     * @var int
     **/
    protected $size;
    
     /**
     * Image type
     *
     * @var string
     **/
    protected $type;
    
     /**
     * Image name
     *
     * @var string
     **/
    protected $name;
    
     /**
     * Image content
     *
     * @var string
     **/
    protected $content;

   
    public function __construct($id = null)
    {
        $this->id = $id;

        #load image with given id
        $this->loadImageData();
    }

    /**
     * undocumented function
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set width to $width
     *
     * @param $width
     * @return self
     * @author Michael Strohyi
     **/
    public function setWidth($width)
    {
        $this->width = $width;;

        return self;
    }

    /**
     * Return width
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height to $height
     *
     * @param int $Height
     * @return self
     * @author Michael Strohyi
     **/
    public function setHeight($height)
    {
        $this->height = $height;

        return self;
    }

    /**
     * Return height
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set mime to $mime
     *
     * @param string $mime
     * @return self
     * @author Michael Strohyi
     **/
    public function setMime($mime)
    {
        $this->mime = $mime;

        return self;
    }

    /**
     * Return mime
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set size to $size
     *
     * @param int $size
     * @return self
     * @author Michael Strohyi
     **/
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Return size
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set type to $type
     *
     * @param string $type
     * @return self
     * @author Michael Strohyi
     **/
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Return type
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getType()
    {
        return $this->type;
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
     * Set content to $content 
     *
     * @param string $content
     * @return self
     * @author Michael Strohyi
     **/
    public function setContent($content)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Return content
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getcontent()
    {
        return $this->content;
    }

    /**
     * Load data for image from db
     *
     * @return void
     * @author Michael Strohyi
     **/
    protected function loadImageData()
    {
        $id = $this->id;

        if (empty($id)) {
            return;
        }

        $query = "SELECT * FROM `images` WHERE `id` = $id";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->setWidth($res_element['width']);
        $this->setHeight($res_element['height']);
        $this->setMime($res_element['mime']);
        $this->setSize($res_element['size']);
        $this->setType($res_element['type']);
        $this->setName($res_element['name']);
        $this->setContent($res_element['content']);
    }

    /**
     * Return true if image exists ($this is not empty)
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id = $this->getId();
        return !empty($id);
    }
}
