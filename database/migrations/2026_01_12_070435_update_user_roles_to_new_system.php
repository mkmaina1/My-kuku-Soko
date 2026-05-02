<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing users with old role 'farmer' to 'supplier'
        User::where('role', 'farmer')->update(['role' => 'supplier']);

        // Update existing users with old role 'client' to 'farmer'
        User::where('role', 'client')->update(['role' => 'farmer']);
    }

    public function down(): void
    {
        // Revert changes if needed
        User::where('role', 'supplier')->update(['role' => 'farmer']);
        User::where('role', 'farmer')->update(['role' => 'client']);
    }
};
