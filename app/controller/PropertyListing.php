<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function showListing(){
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);

        $this->view('propertyListing' , ['properties' => $properties]);
    }

    public function showListingDetail($propertyID){
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyID])[0];
        
        $this->view('propertyUnit' , ['property' => $propertyUnit]);
    }
}