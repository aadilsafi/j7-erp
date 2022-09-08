<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
        'attachment',
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
        'relation' => 'required_with:parent_id',
        'attachment' => 'required|min:2',
    ];

    public $ruleMessages = [
        'attachment' => 'Minimum Two Attachment Required.',
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
        'attachment' => 'string',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

}
