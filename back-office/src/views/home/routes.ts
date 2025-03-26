import HomeView from './HomeView.vue'
import TermsView from './TermsView.vue'
import PrivacyView from './PrivacyView.vue'

export default [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/termos-de-servico', name: 'Terms', component: TermsView },
  { path: '/politica-de-privacidade', name: 'Privacy', component: PrivacyView },
]
