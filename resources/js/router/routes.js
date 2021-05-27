// general Components
const Welcome = () => import('~/pages/welcome').then(m => m.default || m)
const Category = () => import('~/pages/category').then(m => m.default || m)
const CategorySelcted = () => import('~/pages/categorychild').then(m => m.default || m)
const Product = () => import('~/pages/product').then(m => m.default || m)
const About = () => import('~/pages/about').then(m => m.default || m)
const ProductJson = () => import('~/pages/product_json').then(m => m.default || m)
const Contact = () => import('~/pages/contact').then(m => m.default || m)
const NotFound = () => import('~/pages/errors/404').then(m => m.default || m)
const Profile = () => import('~/pages/profile').then(m => m.default || m)
const Liked = () => import('~/pages/liked').then(m => m.default || m)

// admin login Components

// Auth Components
const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const RegisterSocial = () => import('~/pages/auth/register_social').then(m => m.default || m)

// NeedAuth
const Dashboard = () => import('~/pages/adminstrimi/Dashboard').then(m => m.default || m)
// const ProductEdit = () => import('~/pages/product').then(m => m.default || m)
const Products = () => import('~/pages/adminstrimi/products').then(m => m.default || m)
const AddProduct = () => import('~/pages/adminstrimi/add-product').then(m => m.default || m)
const Orders = () => import('~/pages/adminstrimi/orders').then(m => m.default || m)
const History = () => import('~/pages/adminstrimi/history').then(m => m.default || m)
const WaitingOrders = () => import('~/pages/adminstrimi/waiting').then(m => m.default || m)
const Settings = () => import('~/pages/adminstrimi/settings').then(m => m.default || m)
const EditProfile = () => import('~/pages/adminstrimi/edit-profile').then(m => m.default || m)
const ChangePassword = () => import('~/pages/adminstrimi/change-password').then(m => m.default || m)
const ShoppingCart = () => import('~/pages/adminstrimi/shopping-cart').then(m => m.default || m)
const WishlistCart = () => import('~/pages/adminstrimi/wishlistCart').then(m => m.default || m)
const CheckoutShipping = () => import('~/pages/adminstrimi/checkout-shipping').then(m => m.default || m)
const CheckoutReview = () => import('~/pages/adminstrimi/checkout-review').then(m => m.default || m)
const Users = () => import('~/pages/adminstrimi/admin/users').then(m => m.default || m)
const Categories = () => import('~/pages/adminstrimi/admin/categories').then(m => m.default || m)
const EditCategory = () => import('~/pages/adminstrimi/admin/editCategory').then(m => m.default || m)
const Parameters = () => import('~/pages/adminstrimi/admin/parameters').then(m => m.default || m)
const shopsEdit = () => import('~/pages/adminstrimi/admin/shopsEdit').then(m => m.default || m)
const Search = () => import('~/pages/search').then(m => m.default || m)
const ProductManage = () => import('~/pages/adminstrimi/product_manage').then(m => m.default || m)
const OrdersListSalesman = () => import('~/pages/adminstrimi/OrdersListSalesman').then(m => m.default || m)
const manualAddProduct = () => import('~/pages/adminstrimi/manual-add-product').then(m => m.default || m)
const RaportSalesman = () => import('~/pages/adminstrimi/RaportSalesman').then(m => m.default || m)
const Shops = () => import('~/pages/see_all_shops').then(m => m.default || m)

// profile
const PasswordEmail = () => import('~/pages/auth/password/email').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const SettingsProfile = () => import('~/pages/settings/profile').then(m => m.default || m)
const SettingsPassword = () => import('~/pages/settings/password').then(m => m.default || m)

export default [
  { path: '/', name: 'ballina', component: Welcome, meta: [{ title: 'welcome' }] },
  { path: '/rreth-nesh', name: 'about', component: About, meta: [{ title: 'about' }] },
  { path: '/panel/product-json', name: 'product-json', component: ProductJson, meta: [{ title: 'product json' }] },
  { path: '/contact', name: 'contact', component: Contact, meta: [{ title: 'contact' }] },
  { path: '/category', name: 'category-all', component: Category, meta: [{ title: 'category' }] },
  { path: '/category/:name', name: 'category', component: CategorySelcted, props: true, meta: [{ title: 'category' }] },
  { path: '/profile/:name', name: 'profile', component: Profile, props: true, meta: [{ title: 'profile' }] },
  { path: '/product/:id', name: 'product', component: Product, props: true, meta: [{ title: 'product' }] },
  { path: '/search', name: 'search', component: Search, meta: [{ title: 'search' }], props: (route) => ({ query: route.query.q }) },

  // NeedAuth
  { path: '/home', name: 'home', component: Welcome, meta: [{ title: 'home' }] },
  { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: [{ title: 'dashboard' }] },
  { path: '/panel/products', name: 'all-products', component: Products, props: true, meta: [{ title: 'product' }] },
  { path: '/panel/orders', name: 'orders', component: Orders, props: true, meta: [{ title: 'orders' }] },
  { path: '/panel/history', name: 'history', component: History, props: true, meta: [{ title: 'history' }] },
  { path: '/panel/waiting', name: 'waiting', component: WaitingOrders, props: true, meta: [{ title: 'waiting' }] },
  { path: '/panel/settings', name: 'settings', component: Settings, props: true, meta: [{ title: 'settings' }] },
  { path: '/shopping-cart', name: 'shopping-cart', component: ShoppingCart, props: true, meta: [{ title: 'shopping-cart' }] },
  { path: '/wishlist-cart', name: 'wishlist-cart', component: WishlistCart, props: true, meta: [{ title: 'wishlist-cart' }] },
  { path: '/checkout-shipping', name: 'checkout-shipping', component: CheckoutShipping, props: true, meta: [{ title: 'checkout-shipping' }] },
  { path: '/checkout-review', name: 'checkout-review', component: CheckoutReview, props: true, meta: [{ title: 'checkout-shipping' }] },
  { path: '/panel/add-product', name: 'add-product', component: AddProduct, props: true, meta: [{ title: 'add-product' }] },
  { path: '/panel/edit-product/:id', name: 'edit-product', component: AddProduct, props: true, meta: [{ title: 'edit-product' }] },
  { path: '/profile', name: 'profile-all', component: Profile, props: true, meta: [{ title: 'profile' }] },
  { path: '/liked', name: 'liked', component: Liked, props: true, meta: [{ title: 'liked' }] },
  { path: '/panel/users', name: 'users', component: Users, props: true, meta: [{ title: 'users' }] },
  { path: '/panel/product-manage', name: 'product-manage', component: ProductManage, props: true, meta: [{ title: 'product_manage' }] },
  { path: '/panel/orderslistsalesman', name: 'orderslistsalesman', component: OrdersListSalesman, props: true, meta: [{ title: 'orders-list' }] },
  { path: '/panel/manual-add-product', name: 'manualAddProduct', component: manualAddProduct, props: true, meta: [{ title: 'manual-add-product' }] },
  { path: '/panel/raportsalesman', name: 'raportsalesman', component: RaportSalesman, props: true, meta: [{ title: 'raport' }] },
  { path: '/panel/categories', name: 'categories', component: Categories, props: true, meta: [{ title: 'categories' }] },
  { path: '/panel/categories/:id', name: 'categoryedit', component: EditCategory, props: true, meta: [{ title: 'category' }] },
  { path: '/panel/parameters', name: 'parameters', component: Parameters, props: true, meta: [{ title: 'Parameters' }] },
  { path: '/panel/shops-edit', name: 'shops-edit', component: shopsEdit, props: true, meta: [{ title: 'shopsEdit' }] },
  { path: '/panel/homepage-edit', name: 'homepage-edit', component: shopsEdit, props: true, meta: [{ title: 'shopsEdit' }] },
  { path: '/shops', name: 'all-shops', component: Shops, props: true, meta: [{ title: 'shop' }] },

  // profile
  { path: '/password/reset', name: 'password.request', component: PasswordEmail, meta: [{ title: 'reset_password' }] },
  { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset, meta: [{ title: 'reset_password' }] },
  { path: '/settings.profile', name: 'settings.profile', component: SettingsProfile, meta: [{ title: 'profile' }] },
  { path: '/settings.password', name: 'settings.password', component: SettingsPassword, meta: [{ title: 'password' }] },
  { path: '/panel/edit-profile', name: 'edit-profile', component: EditProfile, meta: [{ title: 'profile' }] },
  { path: '/panel/change-password', name: 'change-password', component: ChangePassword, meta: [{ title: 'change-password' }] },

  // Auth routes
  { path: '/login', name: 'login', component: Login, meta: [{ title: 'login' }] },
  { path: '/register', name: 'register', component: Register },
  { path: '/oauth/:provider/callback', name: 'register_social', component: RegisterSocial },

  { path: '*', component: NotFound }
]
