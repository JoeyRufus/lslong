<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExperienceModel extends Model
{
    use HasFactory;
    protected $table = 'experience';
    protected $fillable = ['title', 'content'];
    /**
     * The roles that belong to the ExperienceModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function experienceLabel(): BelongsToMany
    {
        return $this->belongsToMany(ExperienceLabelModel::class, 'experience_has_experience_label', 'experience_id', 'experience_label_id');
    }

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
