<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
    <!-- Brand Logo Light -->
    <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="assets/images/constructa.png" alt="logo" width="200">
        </span>
    </a>
    <!-- Brand Logo Dark -->
    <a href="index.html" class="logo logo-dark">
        <span class="logo-lg">
            <img src="assets/images/constructa.png" alt="logo" style="width: 200px;  height: 70px;">
            <!-- Ajusta los valores según necesites -->

        </span>
    </a>
    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user p-3 text-white">
            <a href="#" class="d-flex align-items-center text-reset">
                <div class="flex-shrink-0">
                    <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42"
                        class="rounded-circle shadow" width="50px">
                </div>
                <div class="flex-grow-1 ms-2">
                    <span class="fw-semibold fs-15 d-block">{{ Auth::user()->fullname }}</span>
                    <span class="fs-13">{{ Auth::user()->rol->name }}</span>
                </div>
            </a>
        </div>
        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title mt-1"> Principal</li>

            <li class="side-nav-item">
                <a href="" class="side-nav-link">
                    <i class="ri-dashboard-2-fill"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-title mt-1">Gestion</li>

            <li class="side-nav-item">
                <a href="{{ route('projects.index') }}" class="side-nav-link">
                    <i class="ri-stack-fill"></i>
                    <span> Proyectos</span>
                </a>
            </li>
            @can('view.users')
                <li class="side-nav-item">
                    <a href="{{ route('usuarios.index') }}" class="side-nav-link">
                        <i class="ri-user-fill"></i>
                        <span> Usuarios </span>
                    </a>
                </li>
            @endcan
            <li class="side-nav-title mt-1"> Comunicacion</li>

            <li class="side-nav-item">
                <a href="{{ route('chatprojects') }}" class="side-nav-link">
                    <i class="ri-chat-voice-fill"></i>
                    <span> Chat </span>
                </a>
            </li>

        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
