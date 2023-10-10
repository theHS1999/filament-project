<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PreInvoice extends Model
{
    protected $table = 'pre_invoices';


    protected $fillable = [
        'client_id',
        'number',
        'user_id',
        'title',
        'advertisement_id',
        'data',
        'last_edit_data',
        'currency_unit',
        'has_tax',
        'start_date',
        'end_date',
        'rows_total_price_with_tax',
        'rows_total_price_without_tax',
        'rows_total_tax_price',
        'details_total_price_with_tax',
        'details_total_price_without_tax',
        'details_total_tax_price',
        'total_price_with_tax',
        'total_price_without_tax',
        'total_tax_price',
        'description',
        'project_title',
        'project_budget',
        'project_size',
        'project_type',
        'project_start_date',
        'project_end_date',
    ];

    protected $casts = [
        'data' => 'array',
        'last_edit_data' => 'array'
    ];

    protected $appends = [
        'LastEdit'
    ];

    /**
     * below global scope for retrive user data only for self
     */
    protected static function booted()
    {
        if(auth()->check()) {
            static::addGlobalScope('ancient', function (Builder $builder) {
                if(auth()->user()->role != "admin") {
                    $builder->where('client_id', auth()->id())->orWhere('user_id', auth()->id());
                }
            });
        }
    }

    public function getLastEditAttribute()
    {
        $result = [];
        if(!empty($this->last_edit_data)) {
            foreach ($this->last_edit_data as $key => $value) {
                $formattedKey = str_replace('_', ' ', $key);
                $result[$formattedKey] = [
                    "old" => $value["old"],
                    "new" => $value["new"]
                ];
            } // end foreach
        } // end if

        return $result;
    }
}
