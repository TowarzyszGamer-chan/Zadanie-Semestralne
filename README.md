Sprawozdanie z projektu z PTW - Temat: Muzyka - Artyści, Zespoły, Projekty
1. Cel projektu
Celem projektu było stworzenie prostej aplikacji webowej umożliwiającej:

przeglądanie zespołów, artystów i projektów muzycznych (albumów, EP, soundtracków),

dodawanie nowych rekordów (dla administratora),

wyświetlanie szczegółów każdego obiektu,

dodawanie recenzji do projektów,

oznaczanie obiektów jako „ulubione”,

zarządzanie własnymi recenzjami,

filtrowanie i wyszukiwanie danych.

Projekt realizuje podstawowy CRUD oraz mechanizmy relacyjne między tabelami.

2. Technologie użyte w projekcie
Backend
PHP 8 – logika aplikacji, walidacja, obsługa formularzy, sesje.

PDO (PHP Data Objects) – bezpieczna komunikacja z bazą danych, prepared statements.

GD Library – generowanie miniaturek zdjęć (artystów, zespołów, okładek).

Baza danych
MariaDB / MySQL – relacyjna baza danych.

Tabele:

uzytkownicy

zespoly

artysci

projekty

recenzje

ulubione

Frontend
HTML + CSS (dark mode) – interfejs użytkownika.

JavaScript (jQuery) – AJAX do:

toggle ulubionych,

usuwania recenzji bez przeładowania.

3. Opis działania aplikacji
3.1. Logowanie i sesje
Użytkownik loguje się za pomocą loginu i hasła.
Dane są przechowywane w tabeli uzytkownicy, hasła są hashowane.

Po zalogowaniu sesja przechowuje:

$_SESSION["id"] – ID użytkownika,

$_SESSION["is_admin"] – uprawnienia.

3.2. Zespoły i artyści
Każdy zespół i artysta ma:

zdjęcie,

podstawowe dane (kraj, gatunek, rok założenia / urodzenia),

opis.

Administrator może dodawać, edytować i usuwać rekordy.

3.3. Projekty (albumy, EP, soundtracki)
Projekt może być powiązany:

z artystą lub

z zespołem.

Wyświetlane są:

okładka,

typ projektu,

rok,

oceny z AOTY i RYM,

powiązania (link do artysty/zespołu),

inne projekty tego samego wykonawcy.

3.4. Recenzje
Użytkownik może dodać recenzję do projektu:

ocena 1–10,

treść,

data dodania.

Recenzje są widoczne na stronie projektu.
Administrator może usuwać recenzje.

Użytkownik ma stronę Moje recenzje, gdzie widzi wszystkie swoje wpisy.

3.5. Ulubione (AJAX)
Użytkownik może dodać do ulubionych:

artystę,

zespół,

projekt.

Działa to bez przeładowania strony dzięki AJAX:

kliknięcie wysyła żądanie do toggle_fav.php,

przycisk zmienia wygląd (serduszko białe/czerwone),

stan jest zapisywany w tabeli ulubione.

Strona Ulubione wyświetla wszystkie obiekty z miniaturkami i przyciskiem toggle.

3.6. Wyszukiwanie i filtrowanie
Aplikacja posiada:

wyszukiwarkę live search (AJAX),

filtrowanie projektów po typie, roku, wykonawcy.

4. Struktura bazy danych (skrót)
uzytkownicy
login, email, hasło, is_admin

zespoly / artysci
dane opisowe + zdjęcie

projekty
tytuł, typ, rok, okładka

FK: id_artysty, id_zespolu

recenzje
ocena, treść, data_dodania

FK: id_projektu, id_uzytkownika

ulubione
typ obiektu (projekt/artysta/zespol)

id_obiektu

id_uzytkownika

5. Bezpieczeństwo
Projekt wykorzystuje:

prepared statements (ochrona przed SQL Injection),

CSRF tokeny przy dodawaniu recenzji,

walidację danych po stronie PHP,

sesje do autoryzacji użytkowników.

6. Możliwe alternatywy i ulepszenia
Backend
Zamiast czystego PHP → Laravel / Symfony (routing, ORM, migracje).

Zamiast PDO → Eloquent ORM (łatwiejsza praca z relacjami).

Frontend
Zamiast jQuery → Fetch API lub Vue/React.

Zamiast własnego CSS → TailwindCSS / Bootstrap.

Baza danych
Zamiast MariaDB → PostgreSQL (lepsze typy danych, stabilność).

Zamiast ręcznych ALTER TABLE → migracje (np. Laravel migrations).

Upload obrazów
Zamiast GD → Imagick (lepsza jakość miniaturek).

Zamiast lokalnego uploadu → Cloudinary / S3.

Ulubione
Zamiast ENUM → osobne tabele (np. ulubione_projekty, ulubione_artysci).

Recenzje
Można dodać system ocen gwiazdkowych,

Można dodać komentarze pod recenzjami.

7. Podsumowanie
Projekt realizuje kompletny system katalogowania muzyki z obsługą:

zespołów,

artystów,

projektów,

recenzji,

ulubionych,

wyszukiwania,

panelu administratora.

Aplikacja jest w pełni funkcjonalna, wykorzystuje relacyjną bazę danych, AJAX, sesje i bezpieczne zapytania.
Kod jest modularny i łatwy do rozbudowy.
