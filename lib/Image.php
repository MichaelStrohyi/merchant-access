<?php

namespace App;

class Image
{
    const IMAGE_INVALID_WIDTH = 'Image width must be less ';
    const IMAGE_INVALID_HEIGHT = 'Image height must be less ';
    const IMAGE_INVALID_MIME = 'Image type must be ';
    const IMAGE_INVALID_SIZE = 'Image must have size less ';
    const IMAGE_LOAD_ERROR = 'Some error happens. Please try to load New logo again';

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
    protected $isModified;

    /**
     * Error
     *
     * @var string
     **/
    protected $error;

   
    public function __construct($id = null)
    {
        $this->id = $id;
        $this->isModified = false;

        #load image with given id
        $this->loadImageData();
    }

    /**
     * Return id.
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set width to $width.
     *
     * @param $width
     * @return self
     * @author Michael Strohyi
     **/
    protected function setWidth($width)
    {
        if ($this->width != $width) {
            $this->width = $width;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return width.
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
    protected function setHeight($height)
    {
        if ($this->height != $height) {
            $this->height = $height;
            $this->isModified = true;
        }

        return $this;
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
     * @param string $mime.
     * @return self
     * @author Michael Strohyi
     **/
    protected function setMime($mime)
    {
        if ($this->mime != $mime) {
            $this->mime = $mime;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return mime.
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set size to $size.
     *
     * @param int $size
     * @return self
     * @author Michael Strohyi
     **/
    protected function setSize($size)
    {
        if ($this->size != $size) {
            $this->size = $size;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return size.
     *
     * @return int
     * @author Michael Strohyi
     **/
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set type to $type.
     *
     * @param string $type
     * @return self
     * @author Michael Strohyi
     **/
    protected function setType($type)
    {
        if ($this->type != $type) {
            $this->type = $type;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return type.
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name to $name.
     *
     * @param string $name
     * @return self
     * @author Michael Strohyi
     **/
    protected function setName($name)
    {
        if ($this->name != $name) {
            $this->name = $name;
            $this->isModified = true;
        }

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
     * Set content to $content.
     *
     * @param string $content
     * @return self
     * @author Michael Strohyi
     **/
    protected function setContent($content)
    {
        if ($this->content != $content) {
            $this->content= $content;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Return content
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Load data for image from db.
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
        $this->error = '';

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
     * Return true if image exists ($this is not empty).
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function exists()
    {
        $id = $this->getId();
        return !empty($id);
    }

    /**
     * Save image into db and return true. If error happens return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function save()
    {

        if (!$this->isModified) {
            return true;
        }

        $image = [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'mime' => $this->getMime(),
            'size' => $this->getSize(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'content' => $this->getContent(),
            ];

        $query = "INSERT INTO `images`  "._QInsert($image);
        $res = _QExec($query);

        if ($res === false) {
            $this->id = null;
            return false;
        }

        $this->id = _QID();
        $this->isModified = false;
        
        return true;
    }

    /**
     * Get image info from input file.
     *
     * @param array $new_image
     * @return void
     * @author Michael Strohyi
     **/
    public function gatherFileInfo($new_image)
    {
        $image_info = getimagesize($new_image['tmp_name']);
        $this->setWidth($image_info[0]);
        $this->setHeight($image_info[1]);
        $this->setMime($image_info['mime']);
        $this->setSize($new_image['size']);
        $this->setName($new_image['name']);
        $this->setContent(file_get_contents($new_image['tmp_name']));
    }

    /**
     * Return true if property $error is not empty.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * Return error string.
     *
     * @return string
     * @author Michael Strohyi
     **/
    public function getErrorString()
    {
        return $this->hasError() ? $this->error: '';
    }

    /**
     * Check if current width is in limitation.
     *
     * @param int $max_width
     * @return void
     * @author Michael Strohyi
     **/
    public function validateWidth($max_width)
    {
        if ($this->width > $max_width) {
            $this->error = self::IMAGE_INVALID_WIDTH . $max_width . 'px';
        }
    }

    /**
     * Check if current height is in limitation.
     *
     * @param int $max_height
     * @return void
     * @author Michael Strohyi
     **/
    public function validateHeight($max_height)
    {
        if ($this->height > $max_height) {
            $this->error = self::IMAGE_INVALID_HEIGHT . $max_height . 'px';
        }
    }

    /**
     * Check if current size is in limitation.
     *
     * @param int $max_size
     * @return void
     * @author Michael Strohyi
     **/
    public function validateSize($max_size)
    {
        if ($this->size > $max_size) {
            $this->error = self::IMAGE_INVALID_SIZE . $max_size/1024 . 'KB';
        }
    }

    /**
     * Check if current size is in limitation.
     *
     * @param array $mime_limit
     * @return void
     * @author Michael Strohyi
     **/
    public function validateMime($mime_limit)
    {
        if (!in_array($this->mime, $mime_limit)) {
            $this->error = self::IMAGE_INVALID_MIME . implode(', ', str_replace('image/', '*.',$mime_limit));
        }
    }

    /**
     * Return true if logo is valid. Otherwise return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function isValid()
    {
        return empty($this->error);
    }

    /**
     * Check if content was successfully loaded.
     *
     * @return void
     * @author Michael Strohyi
     **/
    public function validateContent()
    {
        if (empty($this->content)) {
            $this->error = self::IMAGE_LOAD_ERROR;
        }
    }

    /**
     * Delete Image from db and return true. If some error happened return false.
     *
     * @return boolean
     * @author Michael Strohyi
     **/
    public function delete()
    {
        $query = "DELETE FROM `images`  WHERE `id` = " . $this->getId(); 
       
        return  _QExec($query) != false;
    }
}
