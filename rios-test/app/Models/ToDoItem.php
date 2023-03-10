<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoItem extends Model
{
    use HasFactory;

    protected $fillable = [
      'item_text',
      'prev',
      'next',
      'complete',
      'parent'
    ];

    public function children()
    {
        return $this->hasMany(ToDoItem::class, 'parent');
    }
}
