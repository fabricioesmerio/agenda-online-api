<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'description',
        'color',
        'tenant_id'
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'status';

    protected static function booted()
    {
        static::creating(function ($status) {
            $status->id = (string) Str::uuid();
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}