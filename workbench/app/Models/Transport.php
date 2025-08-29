<?php

namespace App\Models;

use Dcodegroup\DCodeChat\Support\Traits\ChatableModel;
use Dcodegroup\DCodeChat\Support\Traits\LastModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use ChatableModel;
    use HasFactory;
    use LastModifiedBy;
}
