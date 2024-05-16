<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'breeds';

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
                  'cattle_id',
                  'breeding_date',
                  'breeding_type',
                  'breeding_status',
                  'expected_birth_date',
                  'cost',
                  'ai_worker_id',
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
     * Get the cattle for this model.
     */
    public function cattle()
    {
        return $this->belongsTo('App\Models\Cattle','cattle_id');
    }

    /**
     * Get the aiWorker for this model.
     */
    public function aiWorker()
    {
        return $this->belongsTo('App\Models\User','ai_worker_id');
    }

    /**
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }

    /**
     * Set the breeding_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setBreedingDateAttribute($value)
    {
        $this->attributes['breeding_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Set the expected_birth_date.
     *
     * @param  string  $value
     * @return void
     */
    public function setExpectedBirthDateAttribute($value)
    {
        $this->attributes['expected_birth_date'] = !empty($value) ? date($this->getDateFormat(), strtotime($value)) : null;
    }

    /**
     * Get breeding_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getBreedingDateAttribute($value)
    {
        return date(config('constants.DISPLAY_DATE_FORMAT'), strtotime($value));
    }

    /**
     * Get expected_birth_date in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getExpectedBirthDateAttribute($value)
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
