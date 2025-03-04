<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles; // <---------------------- and this one

class ProdutoPdf extends Model
{
    use SoftDeletes;
    use HasRoles; // <------ and this

    protected $fillable = ['produto_id', 'title', 'description', 'type', 'path_image'];

    public function regras()
    {
        return $this->belongsToMany('Backpack\PermissionManager\app\Models\Role', 'pdf_role')->withTimestamps();
    }
}
