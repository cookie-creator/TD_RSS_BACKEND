<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Notification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StatefulNotification|null $stateful
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification retrieve()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @mixin \Eloquent
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [

    ];

    protected $casts = [
        'id' => 'string'
    ];

    protected function uuid(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['id']
        );
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => json_decode($attributes['data'])
        );
    }
}
