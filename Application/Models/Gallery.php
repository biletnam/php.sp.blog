<?php

namespace Application\Models;

use Application\Model;
use Application\Db;
use Application\Core\MultiException;

class Gallery
    extends \Application\Model
{
    const TABLE = 'galleries';
    
    public $name;
    public $description;
    public $alias;
    public $is_active;
    
    public function getFirstImage()
    {
        $db = Db::instance();
        $sql = "SELECT * FROM images WHERE id_gallery = :id_gallery ORDER BY id";
        $images = $db->select($sql, [":id_gallery" => $this->id]);
        return $images[0];
    }
}