<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends BaseModel
{
    use HasFactory;

    protected $table = "accounts";
    protected $fillable = ["balance" , "user_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
