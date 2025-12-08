<!-- resources/views/header.blade.php -->
<header class="header">
    <div class="container">
        <div class="logo">
            <h2>
                @auth
                    @if (session('nama_unitkerja'))
                        {{ session('nama_admin') }} - (
                        {{ session('nama_unitkerja') }})
                    @else
                        {{ session('nama_admin') }}
                    @endif
                @endauth
            </h2>
        </div>

        @auth
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @endauth
    </div>
</header>

<!-- Add header spacer -->
<div class="header-spacer"></div>

<style>
    /* Header Styling */
    .header {
        position: fixed;
        top: 0;
        left: 250px;
        /* Default for expanded sidebar */
        width: calc(100% - 250px);
        /* Default for expanded sidebar */
        background: linear-gradient(135deg, #007bff, #6610f2);
        padding: 15px 20px;
        color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        transition: all 0.3s ease;
        /* Smooth transition for dynamic adjustments */
    }

    /* Header Spacer */
    .header-spacer {
        height: 40px;
        /* Match header height */
        width: 100%;
    }

    /* Adjustments for collapsed sidebar */
    .sidebar-closed .header {
        left: 60px;
        /* Adjust for collapsed sidebar */
        width: calc(100% - 60px);
        /* Adjust for collapsed sidebar */
    }

    /* Main Content Adjustment */
    .main-content {
        margin-top: 26px;
        /* Add margin to prevent overlap with the fixed header */
    }

    /* Container */
    .header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Logo */
    .logo h2 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
    }

    .role {
        font-size: 16px;
        font-weight: bold;
        margin-right: 15px;
    }

    /* Logout Form */
    .logout-form {
        margin: 0;
    }

    /* Tombol Logout */
    .btn-logout {
        background: white;
        color: #007bff;
        border: none;
        padding: 8px 15px;
        font-size: 14px;
        border-radius: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-logout i {
        margin-right: 8px;
    }

    .btn-logout:hover {
        background: #f8f9fa;
        color: #0056b3;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .header .container {
            flex-direction: column;
            text-align: center;
        }

        .user-info {
            flex-direction: column;
            margin-top: 10px;
        }

        .role {
            margin-bottom: 5px;
        }

        .logout-form {
            margin-top: 10px;
        }

        .header {
            left: 60px;
            /* Adjusted for collapsed sidebar on smaller screens */
            width: calc(100% - 60px);
            /* Adjusted for collapsed sidebar */
        }

        .main-content {
            margin-top: 90px;
            /* Adjust margin for smaller screens */
        }

        .header-spacer {
            height: 120px;
            /* Increased height for mobile view */
        }
    }
</style>
