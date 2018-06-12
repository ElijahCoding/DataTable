<?php

namespace App\Http\Controllers\DataTable;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends DataTableController
{
    protected $allowCreation = true;

    protected $allowDeletion = true;

    public function builder()
    {
      return User::query();
    }

    public function getCustomColumnNames()
    {
      return [
        'name' => 'Full name',
        'email' => 'Email address'
      ];
    }

    public function getDisplayableColumns()
    {
      return [
        'id', 'name', 'email', 'created_at'
      ];
    }

    public function getUpdatableColumns()
    {
      return [
        'name', 'email', 'created_at'
      ];
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . '|email',
            'created_at' => 'date'
        ]);

        $this->builder->find($id)->update($request->only($this->getUpdatableColumns()));
    }
}
