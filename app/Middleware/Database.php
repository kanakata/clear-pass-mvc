<?php

namespace App\Middleware;

use App\Models\DatabaseObject\DatabaseObject as ModelsDatabaseObject;

class Database extends ModelsDatabaseObject
{
    private $connectionObject = null;

    protected function connectionObjectResource()
    {
        if ($this->connectionObject == null) {
            $this->connectionObject = $this->databaseConnect();
        }
        return $this->connectionObject;
    }
}
