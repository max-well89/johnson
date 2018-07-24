<?php

class DbHelper extends nomvcDbHelper
{
    public function getQuery($query_code)
    {
        return $this->queries[$query_code];

    }
}
