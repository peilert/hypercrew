# Notatka techniczna - zadanie rekrutacyjne EduCraft

Ta część zajęła mi realnie około 3-4 godzin pracy.

Największym wyzwaniem był checkout WooCommerce, zwłaszcza warunkowe dodanie pola NIP tylko dla produktów B2B i poprawna walidacja po stronie serwera. Przy większej ilości czasu wydzieliłbym logikę biznesową checkoutu do osobnego pluginu i dołożył testy automatyczne.

Motyw wybrałem dlatego, że większość zakresu dotyczyła widoków, CPT i integracji z WooCommerce/ACF, więc to było najszybsze i najprostsze rozwiązanie. Filtrowanie zrobiłem w oparciu o fetch i admin-ajax.php, bo to lekki i wystarczający mechanizm dla tego typu zadania. Walidację NIP zaimplementowałem własnym kodem, bo algorytm jest prosty, a dzięki temu nie trzeba było dodawać zewnętrznej biblioteki.

Korzystałem z WordPressa, WooCommerce, ACF, PHP, JavaScript, SCSS, Local WP, npm/webpack oraz narzędzi AI pomocniczo do analizy rozwiązań i przyspieszenia pracy.

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
