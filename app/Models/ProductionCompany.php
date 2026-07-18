<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCompany extends Model
{
    protected $table = 'production_companies';
    protected $primaryKey = 'production_company_id';
    public $timestamps = false;

    protected $fillable = ['name'];
}
