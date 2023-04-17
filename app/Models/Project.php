<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $attributes)
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The path format for a project
     *
     * @return string
     */
    public function path(): string
    {
        return "/projects/{$this->id}";
    }
}
