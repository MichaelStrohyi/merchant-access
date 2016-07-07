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

    /**
     * Flag to show if any var was modified
     *
     * @var boolean
     **/
    private $isModified;

   
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
        $this->width = $width;
        $this->isModified = true;

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
        $this->isModified = true;

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
        $this->isModified = true;

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
        $this->isModified = true;
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
        $this->isModified = true;

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
        $this->isModified = true;

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
        $this->isModified = true;

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

        $this->isModified = false;

        $query = "SELECT * FROM `images` WHERE `id` = $id";
        _QExec($query);
        $res_element = _QElem();

        if (empty($res_element)) {
            $this->id = null;
            return;
        }

        $this->width = $res_element['width'];
        $this->height = $res_element['height'];
        $this->mime = $res_element['mime'];
        $this->size = $res_element['size'];
        $this->type = $res_element['type'];
        $this->name = $res_element['name'];
        $this->content = $res_element['content'];
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
