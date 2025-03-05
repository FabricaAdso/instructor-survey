<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VerificationCode extends Model
{
    protected $fillable = ['apprentice_id', 'code', 'expires_at'];

    public function isValid()
    {
        return $this->expires_at && Carbon::parse($this->expires_at)->isFuture();
    }
}

