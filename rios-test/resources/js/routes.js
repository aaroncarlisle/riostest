const Main = () => import('./components/Main.vue')

export const routes = [
  {
    name: 'main',
    path: '/',
    component: Main
  }
]
