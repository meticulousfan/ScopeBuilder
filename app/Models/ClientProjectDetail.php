<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ClientProjectDetail extends Model
{
    public $timestamps = false;

	protected $fillable = [
        'project_id',
        'type',
        'identifier',
        'data',
    ];

}
