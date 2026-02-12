#  Weather Monitor – Laravel Időjárás Monitorozó Alkalmazás

<div align="center">

**Készítette:** Cipola Ákos  
**Technológia:** Laravel 12 | PHP 8.2 | MySQL  
**Projekt típusa:** Backend + Web + API + Automatizáció

</div>

---

##  Projekt Leírás

Ez az alkalmazás egy **Laravel alapú időjárás-monitorozó webalkalmazás**, amely:

-  Városokat tud kezelni **(CRUD)**
-  Az **Open-Meteo API** segítségével lekéri a koordinátákat
-  **Artisan parancs** segítségével automatikusan gyűjti a hőmérsékleti adatokat
-  **Dashboardon grafikonon** jeleníti meg az adatokat
-  **REST API végponton** keresztül JSON formátumban elérhetővé teszi az adatokat
-  **Laravel Scheduler** segítségével **óránként automatikusan** frissíti az adatokat

---

##  Használt Technológiák

| Technológia | Verzió |
|---|---|
| **PHP** | 8.2.12 |
| **Laravel** | 12.51.0 |
| **Composer** | 2.8.11 |
| **XAMPP** | 3.3.0 |
| **MySQL** | XAMPP MySQL |
| **Frontend** | Blade |
| **Grafikon** | Chart.js |
| **Külső API** | Open-Meteo (ingyenes) |

---

## Adatbázis Struktúra

### 1 **cities tábla**

| Oszlop | Típus | Leírás |
|---|---|---|
| `id` | bigint | Elsődleges kulcs |
| `name` | string | Város neve |
| `country` | string | Ország |
| `latitude` | decimal | Földrajzi szélesség |
| `longitude` | decimal | Földrajzi hosszúság |
| `timestamps` | - | created_at, updated_at |

### 2 **weather_measurements tábla**

| Oszlop | Típus | Leírás |
|---|---|---|
| `id` | bigint | Elsődleges kulcs |
| `city_id` | foreign key | Kapcsolat a cities táblához |
| `temperature` | decimal | Hőmérséklet |
| `measured_at` | timestamp | Mérési idő |
| `timestamps` | - | created_at, updated_at |

###  Kapcsolat:

-  Egy városhoz **több mérés** tartozhat **(1:N)**
-  Egy mérés **csak egy városhoz** tartozik

---

##  Modellek és Kapcsolatok

### **City Model**

```php
public function weatherMeasurements(): HasMany
{
    return $this->hasMany(WeatherMeasurement::class);
}
```

### **WeatherMeasurement Model**

```php
public function city():  BelongsTo
{
    return $this->belongsTo(City::class);
}
```

---

##  Webes Funkciók (Blade + Controller)

*A webes működés a routes/ `web.php` fájlban található.*

### **Funkciók:**

-  Város hozzáadása *(név + ország)*
-  Open-Meteo Geocoding API hívás **koordináták lekérésére**
-  Városok listázása
-  Város törlése
-  Dashboard oldal

---

##  Dashboard

A **dashboard:**

-  Táblázatban megjeleníti a **legfrissebb hőmérsékletet** városonként
-  Grafikonon **(Chart.js)** mutatja az **utolsó 10 mérési adatot**
-  Minden grafikon **dinamikusan** generálódik Blade segítségével

---

##  API Végpont

### **GET** `localhost:8000/api/weather/{city_id}`
Insomniában tesztelve

Visszaadja a **kiválasztott város összes mérési adatát** JSON formátumban.

#### **Példa válasz:**

```json
{
  "city_id": 3,
  "city_name": "Kijev",
  "city_country": "Ukrajna",
  "latitude": 43.97725,
  "longitude": 16.3571,
  "measurements_count": 5,
  "measurements": [
    {
      "temperature": 8.7,
      "measured_at": "2026-02-11T14:36:40.000000Z"
    }
  ]
}
```

#### **Hibakezelés:**

-  **404 válasz**, ha a város **nem található**

---

##  Automatizáció – Artisan Command

### **Parancs neve:**

```bash
app:weather-update
```

### **Működés:**

1. Lekéri az **összes várost** az adatbázisból
2. Koordináták alapján hívja az **Open-Meteo API**-t
3. Lékéri az **aktuális hőmérsékletet**
4. Elmenti az adatot a **weather_measurements** táblába

### **Manuális futtatás:**

```bash
php artisan app:weather-update
```

---

##  Scheduler Beállítás

A **Laravel Scheduler** `óránként` futtatja a parancsot:

```php
Schedule::command('app:weather-update')->hourly();
```

**Aktiválás:**

```bash
php artisan schedule:work
```

*– konzolra beíráskor életbe lép, és automatikusan fut.*

---

##  Telepítési Útmutató

### **1 Repository klónozása:**

```bash
git clone <repo-url>
```

### **2 Függőségek telepítése:**

```bash
composer install

```

### **3 .env fájl beállítása**
-  **Az env.example -ben benne az adatbázis beállitásával kapcsolatos sorok** 

### **4. Kulcs generálása**
-  **php artisan key:generate**


### **5 Adatbázis migráció:**

```bash
php artisan migrate
```

### **6 Szerver indítása:**


```bash
php artisan serve
```

### **7 Link:**
Miután elindult a szerver, a linkre kattintva : 
 [http://127.0.0.1:8000].
 megjelenik a felületünk, az url be beírva a végpontot : http://127.0.0.1:8000/cities pedig megjelenik a kezelni kívánt felület az összes működő funkciójával együtt.

---

##  Sensitive Információk

-  **API kulcs nem szükséges** (Open-Meteo ingyenes)
-  **Env állomány gitignore-ba került, az env.example nem tartalmaz sensitive információt, csak az adatbázis kapcsolathoz szükséges beállításokat, ezért az example feltöltésre kerül.**
---

##  Teljes Projekt Struktúra

```
WeatherMonitor/
│
├── 📄 .editorconfig                  # Editor konfigurációs szabályok
├── 📄 .env                           # Szerzői környezeti változók (lokális)
├── 📄 .env.example                   # Minta .env fájl
├── 📄 .gitattributes                 # Git attribútumok
├── 📄 .gitignore                     # Git figyelmen kívül hagyandó fájlok
├── 📄 artisan                        # Laravel Artisan parancsok
├── 📄 composer.json                  # PHP függőségek konfigurációja
├── 📄 composer.lock                  # Zárolt verziók
├── 📄 package.json                   # Node.js függőségek
├── 📄 phpunit.xml                    # PHPUnit tesztkonfigurációja
├── 📄 vite.config.js                 # Vite bundler konfigurációja
│
├── 📁 app/                           # Alkalmazás fő mappája
│   ├── 📁 Models/                    # Eloquent modellek
│   │   ├── 📄 City.php               # Város model – városok reprezentálása, kapcsolatok
│   │   ├── 📄 User.php               # Felhasználó model
│   │   └── 📄 WeatherMeasurement.php # Hőmérséklet mérés model – mérési adatok tárolása
│   │
│   ├── 📁 Http/                      # HTTP kérések kezelése
│   │   ├── 📁 Controllers/
│   │   │   ├── 📄 CityController.php # Város CRUD operációk + Dashboard logika
│   │   │   ├── 📄 WeatherController.php # Web weather megjelenítések
│   │   │   ├── 📄 Controller.php     # Base Controller osztály
│   │   │   └── 📁 Api/
│   │   │       └── 📄 WeatherController.php # API endpoint /api/weather/{city_id}
│   │   └── 📁 Middleware/
│   │
│   ├── 📁 Console/                   # Parancssori parancsok
│   │   ├── 📄 Kernel.php             # Scheduler konfigurációja – óránkénti parancs futtatás
│   │   └── 📁 Commands/
│   │       └── 📄 WeatherUpdate.php  # Artisan parancs (app:weather-update) – Open-Meteo API adatlekérés
│   │
│   └── 📁 Providers/
│       └── 📄 AppServiceProvider.php # Alkalmazás service provider
│
├── 📁 bootstrap/                     # Bootstrap mappája
│   ├── 📄 app.php                    # Alkalmazás indítása – API route-ok regisztrálása
│   ├── 📁 cache/                     # Cache bootstrap
│   └── 📄 providers.php              # Provider bootstrap
│
├── 📁 config/                        # Konfigurációs fájlok
│   ├── 📄 app.php
│   ├── 📄 auth.php
│   ├── 📄 cache.php
│   ├── 📄 database.php
│   ├── 📄 filesystems.php
│   ├── 📄 logging.php
│   ├── 📄 mail.php
│   ├── 📄 queue.php
│   ├── 📄 services.php
│   └── 📄 session.php
│
├── 📁 database/                      # Adatbázis mappája
│   ├── 📁 migrations/                # Adatbázis migrációk
│   │   ├── 📄 0001_01_01_000000_create_users_table.php
│   │   ├── 📄 0001_01_01_000001_create_cache_table.php
│   │   ├── 📄 0001_01_01_000002_create_jobs_table.php
│   │   ├── 📄 2026_02_11_100000_create_cities_table.php # Városok tábla – név, ország, koordináták
│   │   └── 📄 2026_02_11_100001_create_weather_measurements_table.php # Mérés tábla
│   │
│   ├── 📁 factories/
│   │   └── 📄 UserFactory.php
│   │
│   ├── 📁 seeders/
│   │   └── 📄 DatabaseSeeder.php
│   │
│   └── 📄 database.sqlite            # SQLite adatbázis fájl
│
├── 📁 public/                        # Nyilványos web root
│   ├── 📄 index.php                  # Laravel belépési pontja
│   ├── 📄 .htaccess                  # Apache rewrite szabályok
│   ├── 📄 robots.txt                 # SEO robots.txt
│   └── 📄 favicon.ico                # Weboldal ikon
│
├── 📁 resources/                     # Frontend erőforrások
│   ├── 📁 css/
│   │   └── 📄 app.css                # Alkalmazás stílusok
│   │
│   ├── 📁 js/
│   │   ├── 📄 app.js                 # Alkalmazás JavaScript
│   │   └── 📄 bootstrap.js           # Bootstrap inicializálása
│   │
│   └── 📁 views/                     # Blade template-ek
│       ├── 📄 welcome.blade.php      # Üdvözlő oldal
│       └── 📁 cities/
│           ├── 📄 create.blade.php   # Város hozzáadása forma
│           ├── 📄 index.blade.php    # Városok lista + Dashboard gomb
│           └── 📄 dashboard.blade.php # Dashboard – táblázat + Chart.js grafikonok
│
├── 📁 routes/                        # Route-ok definiálása
│   ├── 📄 web.php                    # Webes route-ok – város CRUD + dashboard
│   ├── 📄 api.php                    # API route-ok – /api/weather/{city_id} endpoint
│   └── 📄 console.php                # Konzol route-ok
│
├── 📁 storage/                       # Tárolási mappák
│   ├── 📁 app/
│   │   ├── 📁 private/
│   │   └── 📁 public/
│   ├── 📁 framework/
│   │   ├── 📁 cache/
│   │   ├── 📁 sessions/
│   │   ├── 📁 testing/
│   │   └── 📁 views/
│   └── 📁 logs/                      # Alkalmazás naplók
│
├── 📁 tests/                         # Teszt mappája
│   ├── 📄 TestCase.php               # Base test eset
│   ├── 📁 Feature/
│   │   └── 📄 ExampleTest.php
│   └── 📁 Unit/
│       └── 📄 ExampleTest.php
│
├── 📁 vendor/                        # Composer függőségek
│   └── 📄 autoload.php               # PHP autoloader
│
└── 📁 .git/                          # Git repository metadata
```

---

### Főbb Fájlok Összefoglalása

| Fájl/Mappa | Cél |
|---|---|
| **app/Models/** | Adatmodellezés – City és WeatherMeasurement kapcsolata |
| **app/Http/Controllers/CityController.php** | Város CRUD operációk + Dashboard logika |
| **app/Http/Controllers/Api/WeatherController.php** | REST API endpoint – JSON válaszok |
| **app/Console/Kernel.php** | Scheduler – óránkénti parancs futtatás |
| **app/Console/Commands/WeatherUpdate.php** | Open-Meteo API adatlekérés parancs |
| **database/migrations/** | Adatbázis séma (cities, weather_measurements) |
| **resources/views/cities/** | Blade template-ek – Frontend megjelenítés |
| **routes/web.php** | Webes útvonalak |
| **routes/api.php** | API útvonalak |
| **bootstrap/app.php** | Laravel inicializálása – API route-ok regisztrálása |
