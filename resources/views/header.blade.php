<!-- resources/views/header.blade.php -->
<header class="header">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Mobile Menu Toggle -->
            <button class="btn btn-link text-white d-md-none p-0 me-3" id="mobileSidebarToggle" style="font-size: 1.5rem;">
                <i class="fa fa-bars"></i>
            </button>
            
            <div class="logo flex-grow-1">
                <h2 class="mb-0">
                    @auth
                        @if (session('nama_unitkerja'))
                            <span class="d-none d-md-inline">{{ session('nama_admin') }} - ({{ session('nama_unitkerja') }})</span>
                            <span class="d-md-none">{{ Str::limit(session('nama_admin'), 15) }}</span>
                        @else
                            <span class="d-none d-md-inline">{{ session('nama_admin') }}</span>
                            <span class="d-md-none">{{ Str::limit(session('nama_admin'), 15) }}</span>
                        @endif
                    @endauth
                </h2>
            </div>

            @auth
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa fa-sign-out-alt"></i> <span class="d-none d-sm-inline">Logout</span>
                    </button>
                </form>
            @endauth
        </div>
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
        width: calc(100% - 250px);
        background: linear-gradient(135deg, #007bff, #6610f2);
        padding: 12px 0;
        color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        transition: all 0.3s ease;
        min-height: 60px;
        display: flex;
        align-items: center;
    }

    /* Header Spacer */
    .header-spacer {
        height: 60px;
        width: 100%;
    }

    /* Adjustments for collapsed sidebar */
    .sidebar-closed .header {
        left: 60px;
        width: calc(100% - 60px);
    }

    /* Main Content Adjustment */
    .main-content {
        margin-top: 26px;
    }

    /* Logo */
    .logo h2 {
        margin: 0;
        font-size: clamp(14px, 4vw, 24px);
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    /* Mobile Sidebar Toggle */
    #mobileSidebarToggle {
        background: none;
        border: none;
        outline: none;
        box-shadow: none;
    }

    #mobileSidebarToggle:hover,
    #mobileSidebarToggle:focus {
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .header {
            left: 0 !important;
            width: 100% !important;
            padding: 10px 0;
        }

        .header-spacer {
            height: 60px;
        }

        .logo h2 {
            font-size: 16px;
        }

        .btn-logout {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-logout i {
            margin-right: 4px;
        }
    }

    @media (max-width: 576px) {
        .header {
            padding: 8px 0;
            min-height: 50px;
        }

        .header-spacer {
            height: 50px;
        }

        .logo h2 {
            font-size: 14px;
        }

        .btn-logout {
            padding: 5px 10px;
            font-size: 12px;
            min-width: auto;
        }
    }
</style>
