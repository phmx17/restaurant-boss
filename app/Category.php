<?php

namespace App;

use App\Menu;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  public function menus()
  {
    return $this->hasMany(Menu::class);
  }
}
