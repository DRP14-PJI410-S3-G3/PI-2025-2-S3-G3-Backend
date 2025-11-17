<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CetesbVehicle extends Model
{
    use HasFactory;

    protected $connection = 'mysql_data';
    
    protected $table = 'cetesb_emissions_vehicles_osasco';
    
    public $timestamps = true;
    
    const UPDATED_AT = null;
}