import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router ({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [

        // INICIO RUTAS

        {
            path: '/usuarios',
            name: 'Usuarios',
            component:  require('./components/Usuarios/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-user')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/DatosEvento',
            name: 'Datosevento',
            component: require('./components/DatosEvento.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-calendario')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },
        
        {
            path: '/RegistroPersonal',
            name: 'RegistroPersonal',
            component: require('./components/RegistroPersonal.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-insert-per')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RenovarPersonal/:personalid',
            name: 'RenovarPersonal',
            component: require('./components/RenovarPersonal.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-renew-per')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegClasificacion',
            name: 'RegClasificacion',
            component: require('./components/RegClasificacion.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-reg-clasificacion')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegPredio',
            name: 'RegPredio',
            component: require('./components/RegPredio.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-reg-predio')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegEvento',
            name: 'RegEvento',
            component: require('./components/RegEvento.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-reg-evento')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegTarifa',
            name: 'RegTarifa',
            component: require('./components/RegTarifa.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-reg-tarifa')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        // FIN RUTAS

        {
            path: '/notFound',
            name: 'Notfound',
            component: require('./components/Notfound.vue').default
        },
        
        {
            path: '/',
            name: 'Inicio',
            component: require('./components/Bienvenida.vue').default
        },

        {
            path: '/passwordChange',
            name: 'PasswordChange',
            component: require('./components/PasswordChange.vue').default
        },

        /**
         * Ruta para creacion de usuarios
         */
        
        {
            path: '/datosUser',
            name: 'DatosUser',
            component:  require('./components/Usuarios/DatosUser.vue').default
        },
        /**
         * Rutas para cracion de roles
         */
        {
            path: '/roles',
            name: 'Roles',
            component:  require('./components/Roles/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-rol')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/nuevoRol',
            name: 'NuevoRol',
            component:  require('./components/Roles/Create.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/editarRol/:id',
            name: 'EditarRol',
            component:  require('./components/Roles/Editar.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },
        /**
         * Rutas para los permisos
         */
        {
            path: '/permisos',
            name: 'Permisos',
            component:  require('./components/Permisos/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-permi')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/acercade',
            name: 'Acercade',
            component:  require('./components/AcercaDe.vue').default
        },

        {
            path: '/ayuda',
            name: 'Ayuda',
            component:  require('./components/Ayuda.vue').default
        },
    ]
})