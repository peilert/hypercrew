# Notatka techniczna - zadanie rekrutacyjne EduCraft

## 1) Dlaczego w motywie (a nie plugin)
Rozwiazanie zostalo osadzone programistycznie w motywie `woocommerce-starter-theme`, bo zadanie dopuszcza oba podejscia. W tym wariancie latwiej od razu powiazac CPT z gotowymi template'ami (`single` i `archive`) bez dodatkowego mapowania widokow.

## 2) CPT + ACF
- Zarejestrowano CPT `case_study` i taksonomie `case_industry` (Branze).
- Grupa pol ACF jest trzymana w `acf-json`, dzieki czemu:
  - mozna ja synchronizowac przez standardowy mechanizm ACF,
  - konfiguracja pol jest wersjonowana w repozytorium.
- Pola obejmuja:
  - `klient`
  - `branza` (pole taksonomii)
  - `krotki opis`
  - `zdjecie glowne`
  - `link do strony klienta`
  - `galeria`

## 3) Single i Archive
- `single-case_study.php` wyswietla komplet danych case study.
- `archive-case_study.php` pokazuje liste case studies i filtr po branzy.
- Filtrowanie dziala bez przeladowania strony przez `fetch` + `admin-ajax.php`.

### Dlaczego taki AJAX
Wybrany zostal natywny mechanizm WordPress AJAX (`admin-ajax.php`) i `fetch`, bo:
- nie wymaga dodatkowych bibliotek,
- jest czytelny,
- dziala dobrze dla aktualnych przegladarek,
- jest szybki do utrzymania w kodzie zadaniowym.

## 4) WooCommerce - NIP w checkout
Logika biznesowa:
- jesli koszyk zawiera co najmniej jeden produkt z kategorii `B2B` (slug `b2b`), pole `NIP` pojawia sie w checkout i jest wymagane,
- obsluga poprawnie uwzglednia produkty wielokategorialne,
- walidacja serwerowa obejmuje:
  - format (10 cyfr, z normalizacja np. `PL` i separatorow),
  - sume kontrolna NIP,
- NIP zapisuje sie do meta zamowienia (`_billing_nip`) i jest widoczny w panelu admina przy danych rozliczeniowych.

### Dlaczego walidator wlasny (bez biblioteki)
Wybrany zostal wlasny walidator, bo algorytm NIP jest krotki i jednoznaczny, a tutaj priorytetem jest prostota wdrozenia i brak dodatkowych zaleznosci.


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
