# Plan: Green Dot

## Concept

Een website voor PlayStation-spelers die hun vriendenlijst willen uitbreiden. De focus ligt op ontdekken: wie speelt wat, wat heeft iemand veel gespeeld, en is diegene een leuke toevoeging aan je lijst. Samen spelen (LFG) kan ook, maar is bijzaak. De site is een doorgeefluik: je vindt hier mensen, het echte contact loopt via PSN. Een lichte social laag met clips en trofeeën maakt profielen levendiger zonder een tweede PSN te worden.

De naam verwijst naar het groene online-bolletje naast vrienden in je vriendenlijst: het gevoel van 's avonds inloggen en een lijst vol mensen zien die aan het gamen zijn.

## Uitgangspunten

- **Alleen PlayStation** (PS4 en PS5)
- **Handmatig invullen als basis**, zodat de site niet afhankelijk is van de onofficiële PSN API. PSN-requests alleen voor verificatie en optionele acties, altijd met cooldown
- **Minimumleeftijd 16** in verband met de AVG, geen berichtenfunctie, alleen PSN ID als contactgegeven
- **Geen vrije berichten of comments**, alleen vaste posttypes en reacties, zodat moderatie behapbaar blijft
- **Geen PlayStation-merkelementen** in naam, logo of design (geen logo's, merkkleuren of knopsymbolen)

## Techniek

- Laravel met MySQL, Livewire of Inertia voor de interactieve delen
- IGDB API (via Twitch-credentials) voor de gamedatabase, lokaal opgeslagen en doorzocht via Laravel Scout met Meilisearch, zie "Gamedatabase"
- Kleine Node-service met `psn-api` voor verificatie en trofeeën, of een PHP-alternatief als dat er is
- Clips als embed van YouTube, Twitch, Streamable of Medal, niet zelf gehost
- Screenshots (fase 3) op objectopslag zoals S3 of Cloudflare R2

**Datamodel globaal:**

- `users`, `profiles`
- `games` (`igdb_id` uniek, naam, slug, alternatieve namen, cover-ID, releasedatum, platforms)
- `profile_game` (pivot met `hours`, `is_favorite`, `wants_to_play_together`)
- `follows`, `posts`, `reactions`
- `friend_adds` (wie heeft wie toegevoegd en of het geaccepteerd is)
- `reports`
- Tabel of setting voor `last_synced_at` van de IGDB-sync

## Gamedatabase

**Basisvulling (eenmalig, vóór lancering)**

- Alle games voor PS4 (platform-ID 48) en PS5 (167) ophalen uit IGDB
- Alleen hoofdgames, remakes, remasters en uitgebreide edities, geen DLC en bundels (via het gametype-veld, actuele naam checken in de IGDB-docs)
- Eventueel filteren op een minimum aantal ratings om shovelware eruit te houden
- Alternatieve namen meenemen zodat "GTA 5" en "COD" gevonden worden
- Covers niet downloaden, tonen via de IGDB-afbeeldings-URL met het cover-ID

**Zoeken**

- Altijd eerst lokaal via Scout en Meilisearch, met typfouttolerantie
- Onder de resultaten een knop "Niet gevonden? Zoek verder" die IGDB doorzoekt
- De gekozen game wordt direct opgeslagen, zodat de database vanzelf gaten opvult

**Nachtelijke sync**

- Laravel scheduler, één crontab-regel voor `schedule:run`, commando `igdb:sync` dagelijks om 03:00
- Alleen games opvragen met `updated_at` na de laatste succesvolle sync, met dezelfde filters als de basisvulling
- Pagineren per 500 resultaten met een korte pauze tussen requests (limiet is 4 per seconde)
- Upserten op `igdb_id`, zodat nieuwe games worden toegevoegd en bestaande worden bijgewerkt
- `last_synced_at` pas opslaan na een volledig geslaagde run
- Twitch-token automatisch vernieuwen als het verlopen is
- Logging en een melding via mail of Discord-webhook bij fouten

## Design

- Eerste ontwerp via Claude Design (prompt apart bewaard)
- Donker thema als standaard, licht thema als variant
- Groen als accentkleur, spaarzaam gebruikt voor online-status, primaire knoppen en highlights
- Logo: woordmerk "Green Dot" met een subtiel gloeiend groen bolletje
- Mobile first, maar ook sterk op desktop

## Fase 1: MVP

**Gamedatabase**

- Basisvulling en nachtelijke sync draaien vóór lancering

**Account en profiel**

- Registratie met leeftijdscheck (16+)
- PSN ID, korte bio, taal en regio
- Status "ik accepteer alle verzoeken"
- Favoriete games (max. 5) en gespeelde games met optioneel aantal uren
- "Nu aan het spelen" als handmatig veld

**Ontdekken**

- Overzicht van profielen met filters op game, taal en regio, standaard gesorteerd op recent actief
- Random-knop met dezelfde filters, alleen profielen die verzoeken accepteren

**Veiligheid**

- Meldknop per profiel, admin-overzicht van meldingen
- Privacyverklaring

## Fase 2: Kwaliteit en profielen

**Betrouwbare lijst**

- "Toegevoegd"-knop met na een week de vraag of het verzoek geaccepteerd is. Profielen die structureel niet accepteren zakken weg
- Profielen die 60 dagen niet actief zijn verdwijnen uit het overzicht tot de gebruiker terugkomt

**PSN-verificatie**

Stroom:
1. Gebruiker vraagt verificatie aan; de site genereert een unieke code, bijv. `greendot-a3f9k2`
2. Gebruiker plakt de code in zijn PSN-bio ("Over mij") op de PSN-app of console
3. Gebruiker klikt "Controleer" op de site; de Node-service doet één read-call naar het publieke PSN-profiel en controleert of de code erin staat
4. Gelukt: badge toegekend, code mag uit de bio worden gehaald, badge blijft permanent

Fallback als de PSN-API tijdelijk niet beschikbaar is:
- De verificatieknop toont "API tijdelijk niet beschikbaar, probeer het later"
- Als alternatief kan de gebruiker een screenshot insturen voor handmatige review door een admin
- Omdat de badge eenmalig wordt toegekend en daarna lokaal is opgeslagen, heeft een API-storing achteraf geen effect op bestaande geverifieerde profielen

**Delen**

- Posttype "clip": link naar YouTube, Twitch, Streamable of Medal, getoond als embed
- Elke post gekoppeld aan een game uit de database, met een bijschrift van max. 200 tekens
- Twee of drie posts vastzetten bovenaan je profiel als visitekaartje
- Meldknop per post

## Fase 3: Social laag

- **Volgen** (eenrichtingsverkeer, geen verzoeken)
- **Automatische tijdlijn** van mensen die je volgt: nieuwe posts, "speelt nu", nieuwe favorieten, nieuwe profielen met een vergelijkbare smaak
- **Reacties** met een vaste set emoji, geen vrije tekst
- **Geverifieerde trofeeën**: geverifieerde gebruikers halen op het moment van delen hun trofeeënlijst op en kiezen er een uit
- **Screenshots** met eigen upload, gelabeld als niet geverifieerd waar relevant
- **Samen spelen**: vinkje per game "zoekt ook mensen om mee te spelen", met een filter in het overzicht
- **Optionele PSN-import** van gespeelde games, met cooldown
- Sitebrede statistieken zoals meest gespeelde games

## Beveiliging en privacy

**Encryptie in transit**

- Alle verkeer via HTTPS/TLS, inclusief calls naar IGDB en de PSN-service
- HSTS-header instellen zodat browsers altijd HTTPS dwingen

**Encryptie at rest**

- E-mailadres opslaan via Laravel's `encrypted` cast (AES-256-CBC via de `APP_KEY`)
- Geboortedatum alleen bewaren zolang nodig voor de leeftijdscheck; daarna vervangen door een boolean `age_verified` en de datum weggooien
- PSN OAuth-tokens (als die tijdelijk worden opgeslagen) eveneens encrypted

**Wachtwoorden**

- Argon2id via Laravel's `Hash` facade, nooit plain-text of snelle hashes zoals MD5/SHA-1

**Dataminimalisatie**

- Geen tracking-pixels of externe analytics zonder expliciete toestemming
- IP-adressen uitsluitend voor rate limiting en beveiligingslogs, niet gekoppeld aan gebruikersprofielen, automatisch verwijderd na 30 dagen
- "Nu aan het spelen" is altijd handmatig; de site slaat geen sessie- of activiteitsdata op zonder dat de gebruiker er zelf iets voor doet

**AVG-rechten**

- Inzagerecht: gebruiker kan zijn eigen data downloaden als JSON-export
- Verwijderrecht: accountverwijdering wist alle persoonsgebonden data; posts worden geanonimiseerd tot "verwijderd account"
- Toestemming voor de privacyverklaring vastleggen met timestamp bij registratie; geen vinkje vooraf aangevinkt

**API-sleutels en secrets**

- Alle secrets in `.env`, nooit in code of git (`.env` staat in `.gitignore`)
- Productie-secrets via omgevingsvariabelen of een secrets manager, niet in gedeelde chats of documenten
- Twitch/IGDB-credentials en PSN-tokens onmiddellijk roteren bij vermoeden van een lek

**Admin en audit**

- Elke admin-actie (melding afhandelen, account schorsen, gebruiker verwijderen) gelogd met actor, actie en timestamp
- Admin-accounts beveiligd met 2FA, geen gedeeld wachtwoord
- Admins zien alleen wat nodig is: geen plain-text e-mailadressen tenzij expliciet ontsleuteld in een gedocumenteerde flow

## Lancering

- Tien tot twintig goed gevulde profielen voordat je live gaat, zodat de random-knop en het overzicht niet leeg voelen
- Daarna actief werven in bestaande "add me"-draadjes op Reddit, na het checken van de regels over zelfpromotie per subreddit
- Social features pas aanzetten als er genoeg activiteit is, anders voelt de tijdlijn leeg en werkt het tegen je

## Open punten

- Domein (.gg, .io of .com) en checken of de naam niet al door een grote gaming-app wordt gebruikt
- Minimumleeftijd 16 of 18
- Livewire of Inertia
- Hoe je nieuwe gebruikers na de eerste Reddit-golf blijft binnenhalen
