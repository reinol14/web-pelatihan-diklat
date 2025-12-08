<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\ref_pegawais;
use App\Policies\PegawaiPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ref_pegawais::class => PegawaiPolicy::class, // Mendaftarkan policy untuk model ref_pegawais
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}



