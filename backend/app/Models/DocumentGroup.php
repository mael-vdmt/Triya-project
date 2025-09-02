<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentGroup extends Model
{
    use HasFactory;

    protected $table = 'document_groups';

    protected $fillable = [
        'document_id',
        'group_id',
    ];

    /**
     * Le document associé
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Le groupe associé
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
