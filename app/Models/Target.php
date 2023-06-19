<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    const UPDATED_AT = null;

    public const OPEN_STATUS = 'open';
    public const CLOSED_STATUS = 'closed';
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'user_id',
        'status',
        'priority',
        'title',
        'description',
        'completed_at'
    ];

    /**
     * @inheritdoc
    */
    public function allChildren()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('allChildren');;
    }
}
