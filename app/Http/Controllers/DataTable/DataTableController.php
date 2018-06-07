<?php

namespace App\Http\Controllers\DataTable;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

abstract class DataTableController extends Controller
{
    protected $builder;

    abstract public function builder();

    public function __construct()
    {
      $builder = $this->builder();

      if (!$builder instanceof Builder) {
        throw new Exception('Entity builder not instance of Builder');
      }
    }

    public function index()
    {

    }
}
