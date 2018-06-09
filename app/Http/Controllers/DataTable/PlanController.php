<?php

namespace App\Http\Controllers\DataTable;

use App\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends DataTableController
{
    public function builder()
    {
      return Plan::query();
    }
}
