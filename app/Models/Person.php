<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    
    public $title;

    public $initial;

    public $first_name;

    public $last_name;

    public function getFullName() {
        return join(" ", array_filter(array($this->title, $this->initial, $this->first_name, $this->last_name)));
    }
}
