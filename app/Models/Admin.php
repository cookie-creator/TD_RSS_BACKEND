<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    use HasFactory;

    public function impersonate($user) {
        // Example of using this model:
        // $admin = Admin::first();
        // $admin->impersonate($user);
    }
}
