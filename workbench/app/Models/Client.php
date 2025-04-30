<?php

namespace App\Models;

use Dcodegroup\LaravelChat\Support\Traits\Chatable;
use Dcodegroup\LaravelChat\Support\Traits\LastModifiedBy;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Chatable;
    use LastModifiedBy;
}
