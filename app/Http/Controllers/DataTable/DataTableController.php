<?php

namespace App\Http\Controllers\DataTable;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

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

      $this->builder = $builder;
    }

    public function index(Request $request)
    {
      return response()->json([
        'data' => [
          'table' => $this->builder->getModel()->getTable(),
          'displayable' => array_values($this->getDisplayableColumns()),
          'updatable' => array_values($this->getUpdatableColumns()),
          'records' => $this->getRecords($request)
        ]
      ]);
    }

    public function update($id, Request $request)
    {
      $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }

    public function getDisplayableColumns()
    {
      return array_diff(
        $this->getDatabaseColumnNames(),
        $this->builder->getModel()->getHidden()
      );
    }

    public function getUpdatableColumns()
    {
      // return array_diff(
      //   $this->getDatabaseColumnNames(),
      //   $this->getDisplayableColumns()
      // );

      return $this->getDisplayableColumns();
    }

    protected function getDatabaseColumnNames()
    {
      return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    protected function getRecords(Request $request)
    {
      $builder = $this->builder;

      if ($this->hasSearchQuery($request)) {
        $builder = $this->buildSearch($builder, $request);
      }

      try {
        return $this->builder->limit($request->limit)->orderBy('id', 'asc')->get($this->getDisplayableColumns());
      } catch (QueryException $e) {
        return [];
      }
    }

    protected function hasSearchQuery(Request $request)
    {
      return count(array_filter($request->only(['column', 'operator', 'value']))) === 3;
    }

    protected function buildSearch(Builder $builder, Request $request)
    {
      $queryParts = $this->resolveQueryParts($request->operator, $request->value);

      return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }

    protected function resolveQueryParts($operator, $value)
    {
      return array_get([
        'equals' => [
          'operator' => '=',
          'value' => $value
        ],
        'contains' => [
          'operator' => 'LIKE',
          'value' => "%{$value}%"
        ],
        'equals' => [
          'operator' => '=',
          'value' => $value
        ]
      ], $operator);
    }
}
