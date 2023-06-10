<?php

namespace App\Repositories\Interface;

interface KshInterface
{
    public function getAll();
    public function getById($id);
    public function create($attributes);
    public function edit($attributes, $id);
}
