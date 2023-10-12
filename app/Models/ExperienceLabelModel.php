<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExperienceLabelModel extends Model
{
    use HasFactory;
    protected $table = "experience_label";
    protected $fillable = ['title'];
    /**
     * The experiences that belong to the ExperienceLabelModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function experience(): BelongsToMany
    {
        return $this->belongsToMany(ExperienceModel::class, 'experience_has_experience_label', 'experience_label_id', 'experience_id');
    }
}
