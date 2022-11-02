<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
//    protected $with = ['driver'];

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
}
