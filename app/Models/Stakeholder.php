<?php

namespace App\Models;

use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stakeholder extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'site_id',
        'full_name',
        'father_name',
        'occupation',
        'designation',
        'cnic',
        'contact',
        'address',
        'parent_id',
        'relation',
    ];

    public $rules = [
        // 'site_id' => 'required|numeric',
        'full_name' => 'required|string|min:1|max:50',
        'father_name' => 'required|string|min:1|max:50',
        'occupation' => 'required|string|min:1|max:50',
        'designation' => 'required|string|min:1|max:50',
        'cnic' => 'required|string|min:1|max:15',
        'contact' => 'required|string|min:1|max:20',
        'address' => 'required|string',
        'parent_id' => 'nullable|numeric',
        'relation' => 'nullable|string|min:1|max:50',
        'attachment' => 'sometimes|min:2',
        'stakeholder_type' => 'required|in:C,V,D,L',
    ];

    public $ruleMessages = [
        'attachment.min' => 'Minimum 2 attachments are required.',
    ];

    protected $casts = [
        'site_id' => 'integer',
        'full_name' => 'string',
        'father_name' => 'string',
        'occupation' => 'string',
        'designation' => 'string',
        'cnic' => 'string',
        'contact' => 'string',
        'address' => 'string',
        'parent_id' => 'integer',
        'relation' => 'string',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function stakeholder_types()
    {
        return $this->hasMany(StakeholderType::class);
    }

    public function multiValues() {
        return $this->morphMany(MultiValue::class, 'multivalueable');
    }
}
