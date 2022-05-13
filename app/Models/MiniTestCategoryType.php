<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use URL;

class MiniTestCategoryType extends Model
{

    public $table = 'question_category_type';

    public $timestamps = false;

    protected $guarded = [];

    private $typeName = null;

    public function getParent()
    {
        return $this->hasOne(MiniTestCategoryType::class, 'id', 'parent_id');
    }


    public function getParentPath($id)
    {
        $type = MiniTestCategoryType::where('id', $id)->first();

        if ($type) 
        {
            if (isset($type->parent_id)) 
            {
                if (isset($this->typeName)) 
                {
                    $this->typeName = $type->name . " >> " . $this->typeName;

                } else {

                    //current path
                   
                }

                return $this->getParentNames($type->parent_id);

            } else {

                if (isset($this->typeName)) 
                {
                    return $type->name . " >> " . $this->typeName;

                    
                } else {

                    return $type->name;
                }

            }

        } else {

        }
    }


    public function getParentNames($id)
    {
        $type = MiniTestCategoryType::where('id', $id)->first();

        if ($type) 
        {
            if (isset($type->parent_id)) 
            {
                if (isset($this->typeName)) 
                {
                    $this->typeName = $type->name . " >> " . $this->typeName;

                } else {

                    $this->typeName = "<b>" . $type->name ."<B>";
                }

                return $this->getParentNames($type->parent_id);

            } else {

                if (isset($this->typeName)) 
                {
                    return $type->name . " >> " . $this->typeName;

                    
                } else {

                    return $type->name;
                }

            }

        } else {

        }
    }


    public function getCategoryTypeParentLinks($parentID = null) 
    {
        $items = MiniTestCategoryType::where('parent_id', $parentID)
                ->where('valid', true)
                ->orderBy('id', 'DESC')
                ->get();

        return $items;
    }


    public function getCategoryTypeSubLinks($parent, $level = null) 
    {    

        $items = MiniTestCategoryType::where('parent_id', $parent->id)
                ->where('valid', true)
                ->orderBy('id', 'DESC')
                ->get();

        foreach($items as $item) 
        {        

            echo "<div>";

            for ($i = 0; $i < $level; $i++) 
            {            
                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            }

            $url = URL::current() ."/category/". $item->id;

            
            echo "<span class='text-secondary' > >> </span>";

            echo "<a href='$url'>" . $item->name ."</a>";

            echo "</div>";

            $this->getCategoryTypeSubLinks($item, $level + 1);
        }
    }

    /*
    public function getParentNameHeirachy($id)
    {

        $type = MiniTestCategoryType::where('id', $id)->first();

        if ($type) {
            if (isset($type->parent_id)) {
                if (isset($this->name)) {
                    $this->name = $this->name . " >> " . $type->name;

                } else {

                    $this->name = $type->name;
                }

                return $this->getParentNameHeirachy($type->parent_id);

            } else {

                if (isset($this->name)) {
                    return $this->name . " >> " . $type->name;
                } else {

                    return $type->name;
                }

            }

        } else {

        }
    }
    */

}
