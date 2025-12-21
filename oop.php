
<?php 
class Car {

    public $color ;
    public $speed  = 0;
    public $name ;
    public $model ;

    public function __construct($color , $speed , $name , $model){
         $this->color = $color  ;
         $this->speed = $speed  ;
         $this->name = $name  ;
         $this->model = $model  ;
    }
    public function accelirate($acceliration){
        $this->speed  +=  $acceliration ;
        return "Accelerating! Speed is now: " . $this->speed . " km/h";

    }

}

class Tayota extends Car{
    public $soso ;
}

$pigo = new Car('red' , 100 , "keosa" , 'tayota') ;
$j = new Tayota()