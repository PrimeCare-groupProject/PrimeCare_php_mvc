<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function index(){
        $this->showListing();
    }

    public function showListing(){
        if(isset($_POST) && !empty($_POST)){
            $propertiesFromView = new PropertySearchView;
            $propertiesids = $propertiesFromView->advancedSearch($_POST,  ['property_id']);
            
            $propertiesids = array_map(function($obj) {
                return $obj->property_id;
            }, $propertiesids);

            if(!empty($propertiesids)){
                $property = new PropertyConcat;
                $properties = $property->getByPropertyIds($propertiesids);
                $this->view('propertyListing' , ['properties' => $properties]);
                return;
            }
        }
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