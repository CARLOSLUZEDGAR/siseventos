 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="/img/circulo_logo.png" class="brand-image">
        <span class="brand-text font-weight-light">SISCOE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-1 pb-1 mb-1 d-flex">
            <div class="info" style="max-width: 180px;">
                <router-link
                    id="nombre"
                    class="nav-link d-block"
                    to="/passwordChange"
                    style="white-space: normal; word-break: break-word;">
                </router-link>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              {{-- PERMISO SIDEBAR 1 --}}
                <!-- <li class="nav-header" style="padding-left: 1px;">DATOS</li> -->
                  {{-- PERMISO SIDEBAR 2 --}}
                @can('side-evento', Model::class)
                  <li class="nav-header" style="padding-left: 1px;">EVENTOS</li>
                  <li class="nav-item">{{-- CARNETIZACION --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-building"></i>
                      <p>
                        EVENTOS
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-calendario', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/DatosEvento'>
                          <i class="nav-icon fas fa-calendar"></i>
                          <p>Calendario</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                  </li>  
                @endcan
                  {{-- PERMISO SIDEBAR 2 --}}

                @can('side-registro', Model::class)
                  <li class="nav-header" style="padding-left: 1px;">REGISTRO</li>
                  <li class="nav-item">{{-- REGISTRO --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-building"></i>
                      <p>
                        REGISTRO
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-reg-clasificacion', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/RegClasificacion'>
                          <i class="nav-icon fas fa-calendar"></i>
                          <p>CLASIFICACIÓN</p>
                        </router-link>
                      </li>
                      @endcan
                      @can('view-reg-predio', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/RegPredio'>
                          <i class="nav-icon fas fa-calendar"></i>
                          <p>PREDIOS</p>
                        </router-link>
                      </li>
                      @endcan
                    </ul>
                  </li>  
                @endcan
                                                                                                                                                              
              {{-- PERMISO SIDEBAR 1 --}}
                @can('side-administracion', Model::class)
                <li class="nav-header" style="padding-left: 1px;">ADMINISTRACIÓN</li>
                <li class="nav-item">{{-- ACCESO DEL SISTEMA --}}
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                        ACCESO DEL SISTEMA
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                      @can('view-user', Model::class)
                       <li class="nav-item">
                        <router-link class="nav-link" to='/usuarios'>
                          <i class="nav-icon fas fa-users-cog"></i>
                          <p>Usuarios</p>
                        </router-link>
                      </li>
                      @endcan
                      @can('view-rol', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/roles'>
                          <i class="nav-icon fas fa-user-clock"></i>
                          <p>Roles</p>
                        </router-link>
                      </li>
                      @endcan
                      @can('view-permi', Model::class)
                      <li class="nav-item">
                        <router-link class="nav-link" to='/permisos'>
                          <i class="nav-icon fas fa-user-edit"></i>
                          <p>Permisos</p>
                        </router-link>
                      </li>
                      @endcan

                    </ul>
                </li>
                @endcan

                @can('view-ayuda', Model::class)
                <li class="nav-item">{{-- AYUDAS --}}
                    <router-link class="nav-link" to='/ayuda'>
                      <i class="nav-icon fas fa-info-circle"></i>
                      <p>AYUDAS</p>
                    </router-link>
                </li>
                @endcan

                @can('view-acerca', Model::class)
                <li class="nav-item">{{-- ACERCA DE --}}
                    <router-link class="nav-link" to='/acercade'>
                      <i class="nav-icon fas fa-boxes"></i>
                      <p>ACERCA DE</p>
                    </router-link>
                </li>
                @endcan

            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->

</aside>
