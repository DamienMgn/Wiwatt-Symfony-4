<?php

namespace App\Entity;

class SearchFilter
{

    private $brand;

    private $model;

    private $seatNumber;

    private $maxSpeed;

    private $weight;

    private $power;

    private $autonomy;

    private $dayCost;

    private $type;


    /**
     * Get the value of brand
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand
     *
     * @return  self
     */ 
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of model
     */ 
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the value of model
     *
     * @return  self
     */ 
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the value of seatNumber
     */ 
    public function getSeatNumber()
    {
        return $this->seatNumber;
    }

    /**
     * Set the value of seatNumber
     *
     * @return  self
     */ 
    public function setSeatNumber($seatNumber)
    {
        $this->seatNumber = $seatNumber;

        return $this;
    }

    /**
     * Get the value of maxSpeed
     */ 
    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }

    /**
     * Set the value of maxSpeed
     *
     * @return  self
     */ 
    public function setMaxSpeed($maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;

        return $this;
    }

    /**
     * Get the value of weight
     */ 
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of weight
     *
     * @return  self
     */ 
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the value of power
     */ 
    public function getPower()
    {
        return $this->power;
    }

    /**
     * Set the value of power
     *
     * @return  self
     */ 
    public function setPower($power)
    {
        $this->power = $power;

        return $this;
    }

    /**
     * Get the value of autonomy
     */ 
    public function getAutonomy()
    {
        return $this->autonomy;
    }

    /**
     * Set the value of autonomy
     *
     * @return  self
     */ 
    public function setAutonomy($autonomy)
    {
        $this->autonomy = $autonomy;

        return $this;
    }

    /**
     * Get the value of dayCost
     */ 
    public function getDayCost()
    {
        return $this->dayCost;
    }

    /**
     * Set the value of dayCost
     *
     * @return  self
     */ 
    public function setDayCost($dayCost)
    {
        $this->dayCost = $dayCost;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
