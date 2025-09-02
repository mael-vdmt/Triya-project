<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUser extends Model
{
    use HasFactory;

    protected $table = 'document_users';

    protected $fillable = [
        'document_id',
        'user_id',
    ];

    /**
     * Le document associé
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * L'utilisateur associé
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
