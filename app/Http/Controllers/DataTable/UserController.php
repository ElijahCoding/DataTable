<?php

namespace App\Http\Controllers\DataTable;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends DataTableController
{
    public function builder()
    {
      return User::query();
    }
}