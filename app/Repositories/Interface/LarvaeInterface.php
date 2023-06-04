<?php

namespace App\Repositories\Interface;

interface LarvaeInterface
{
    public function getAll();
    public function getById($id);
    public function create($attributes);
    public function update($attributes, $id);

    public function deleteDetail($id);
    public function createDetail($attributes, $id);
}
