<?php
/**
 * Created by Libaro.
 * User: Bert
 * Date: 15/07/14
 * Time: 10:01
 */

class Presentation {

    var $id;
    var $description;
    var $name;
    var $filePath;
    var $userID;
    var $creationDate;
    var $status;
    var $shareStatus;

    function __construct($param)
    {
        if (is_int($param)) {
            // numerical ID was given
            $this->id = $param;
        }
        elseif (is_array($param)) {
            if(isset($param['description'])) $this->description = $param['description'];
            if(isset($param['name'])) $this->name = $param['name'];
            if(isset($param['filePath'])) $this->filePath = $param['filePath'];
            if(isset($param['userID'])) $this->userID = $param['userID'];
            if(isset($param['creationDate'])) $this->creationDate = $param['creationDate'];
            if(isset($param['status'])) $this->status = $param['status'];
            if(isset($param['shareStatus'])) $this->shareStatus = $param['shareStatus'];
        }
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }




}