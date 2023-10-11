<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProjectRequest extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    protected $table = 'project_requests';

    const STATUS_PENDING = 0;
    const STATUS_ACCEPT = 1;
    const STATUS_ON_HOLD = 2;
    const STATUS_REJECT = 3;
    const STATUS_DELETE = 4;
    const STATUS_PREINVOICE = 5;
    const STATUS_ACCEPT_PREINVOICE = 6;
    protected $appends = [
        'StatusColor',
        'StatusName',
    ];

    protected $fillable = [
        'ad_id',
        'freelancer_id',
        'message_id',
        'status',
        'description',
        'has_signature'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('project_signature')
            ->singleFile();
    }

    public function project() {
        return $this->belongsTo(Advertisment::class, 'ad_id');
    }

    public function freelancer() {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function getStatusNameAttribute()
    {
        if($this->status == Self::STATUS_PENDING) {
            return '-- pending --';
        } elseif ($this->status == self::STATUS_ACCEPT) {
            return 'Accept';
        } elseif ($this->status == self::STATUS_ON_HOLD) {
            return 'On Hold';
        } elseif ($this->status == self::STATUS_REJECT) {
            return 'Reject';
        } elseif ($this->status == self::STATUS_DELETE) {
            return 'Delete';
        } elseif ($this->status == self::STATUS_PREINVOICE) {
            return 'Send Pre-Invoice';
        } elseif ($this->status == self::STATUS_ACCEPT_PREINVOICE) {
            return 'Accept Pre-Invoice';
        }
    }


    public function getStatusColorAttribute()
    {
        if($this->status == Self::STATUS_PENDING) {
            return 'warning';
        } elseif ($this->status == self::STATUS_ACCEPT) {
            return 'success';
        } elseif ($this->status == self::STATUS_ON_HOLD) {
            return 'primary';
        } elseif ($this->status == self::STATUS_REJECT) {
            return 'secondary';
        } elseif ($this->status == self::STATUS_DELETE) {
            return 'danger';
        } elseif ($this->status == self::STATUS_PREINVOICE) {
            return 'primary';
        } elseif ($this->status == self::STATUS_ACCEPT_PREINVOICE) {
            return 'primary';
        }
    }
}
