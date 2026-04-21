## WooCommerce Starter Theme

Czysta baza startowa pod kolejne projekty WordPress + WooCommerce.

### Wymagania

- WordPress >= 6.x
- PHP >= 8.0
- Node.js >= 18
- npm

### Szybki start

1. Skonfiguruj lokalny adres projektu w `webpack.config.js` (`browserSync.proxy`).
2. Zainstaluj zależności:
   `npm install`
3. Uruchom tryb developerski:
   `npm run dev:watch`
4. Dla buildu produkcyjnego:
   `npm run prod`

### Skrypty

- `npm run dev` - pojedynczy build assets (dev)
- `npm run dev:watch` - watch mode (dev)
- `npm run prod` - pojedynczy build assets (prod)
- `npm run prod:watch` - watch mode (prod)
- `npm run eslint` - lint JS
- `npm run eslint:fix` - auto-fix JS
- `npm run stylelint` - lint SCSS/CSS
- `npm run stylelint:fix` - auto-fix SCSS/CSS
- `npm run translate` - generowanie pliku tłumaczeń `.pot`

### Struktura

- `_src/js/` - moduły JavaScript pisane jako ESM
- `_src/scss/` - źródła SCSS uporządkowane warstwami (`foundation`, `layout`, `components`, `pages`)
- `assets/` - build wynikowy i pliki ładowane przez motyw
- `eslint.config.js`, `stylelint.config.js` - współczesna konfiguracja lintów w katalogu głównym motywu
- `inc/` - konfiguracja i hooki motywu
- `woocommerce/` - override'y WooCommerce
- `page-templates/`, `template-parts/` - opcjonalne rozszerzenia szablonów

### Założenia startera

- neutralne template'y bazowe (`front-page`, `home`, `page`, `single`, `404`)
- podstawowe wsparcie WooCommerce aktywowane automatycznie
