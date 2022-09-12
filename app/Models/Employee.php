<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'jmeno', 'prijmeni', 'date', 'plat'];

    protected $casts = [
        'plat' => 'string',
    ];

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->jmeno;
    }

    public function getSurname()
    {
        return $this->prijmeni;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getSalary()
    {
        return $this->plat;
    }
}
