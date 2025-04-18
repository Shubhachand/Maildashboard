<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'folder_id',
        'from',
        'to',
        'cc',
        'bcc',
        'subject',
        'body',
        'is_read',
        'is_starred',
        'is_important',
        'received_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_important' => 'boolean',
        'received_at' => 'datetime',
    ];

    /**
     * Get the user that owns the email.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the folder that owns the email.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}