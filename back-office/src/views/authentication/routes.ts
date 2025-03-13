import RouterAuthentication from './RouterAuthentication.vue'
import RegistrationPage from './RegistrationPage.vue'
import LoginPage from './LoginPage.vue'
import ModeSelectPage from './ModeSelectPage.vue'

export default [
  {
    path: '/autenticacao',
    name: 'Authentication',
    component: RouterAuthentication,
    children: [
      { path: '/registar', name: 'Registration', component: RegistrationPage },
      { path: '/aceder', name: 'Login', component: LoginPage },
      {
        path: '/selecionar-modo',
        name: 'ModeSelect',
        component: ModeSelectPage,
        meta: { requiresAuth: true },
      },
    ],
  },
]
