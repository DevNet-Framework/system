<?php

namespace Application\Models;

class UserForm
{
    private string $Username;
    private string $Password;
    private bool $Remember = false;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
         $this->$name = $value;
    }

    public function isValide() : bool
    {
        if (isset($this->Username) && isset($this->Password))
        {
            return true;
        }

        return false;
    }
}