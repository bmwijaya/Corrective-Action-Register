<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \DateTimeInterface;

class Corrective extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'correctives';

    protected $appends = [
        'evident',
    ];

    protected $dates = [
        'finding_date',
        'target_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'finding_date',
        'sources_id',
        'finding',
        'action',
        'tanggung_jawab_id',
        'target_date',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getFindingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFindingDateAttribute($value)
    {
        $this->attributes['finding_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function sources()
    {
        return $this->belongsTo(Source::class, 'sources_id');
    }

    public function tanggung_jawab()
    {
        return $this->belongsTo(User::class, 'tanggung_jawab_id');
    }

    public function getTargetDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTargetDateAttribute($value)
    {
        $this->attributes['target_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEvidentAttribute()
    {
        $file = $this->getMedia('evident')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
