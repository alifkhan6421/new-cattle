<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cattle extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cattle';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'title',
                  'cattle_type_id',
                  'status',
                  'father_insemination',
                  'parent_id',
                  'purchase_source',
                  'purchase_amount',
                  'purchase_date',
                  'farm_entry_date',
                  'purchase_image',
                  'latest_image',
                  'middleman',
                  'species',
                  'date_of_birth',
                  'teeth',
                  'expected_sale_price',
                  'daily_expense',
                  'birth_type',
                  'comments',
                  'created_by'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the cattleType for this model.
     */
    public function cattleType()
    {
        return $this->belongsTo('App\Models\CattleType','cattle_type_id');
    }

    /**
     * Get the Cattle for this model.
     */
    public function Cattle()
    {
        return $this->belongsTo('App\Models\Cattle','parent_id');
    }

    /**
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }

    /**
     * Set the purchase_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setPurchaseDateAttribute($value)
    {
        $this->attributes['purchase_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the farm_entry_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setFarmEntryDateAttribute($value)
    {
        $this->attributes['farm_entry_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the date_of_birth.
     *
     * @param  string  $value
     * @return void
     */
    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get purchase_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getPurchaseDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get farm_entry_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getFarmEntryDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get date_of_birth in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getDateOfBirthAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

}
