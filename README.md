# Cosmic Surfer 3D - Backend Setup Guide

## Requisiti

- **Server Web**: Apache o Nginx con PHP 7.4+
- **Database**: MySQL 5.7+ o MariaDB 10.3+
- **PHP Extensions**: PDO, PDO_MySQL

### Opzioni Raccomandate per Sviluppo Locale
- **XAMPP** (Windows/Mac/Linux): https://www.apachefriends.org/
- **WAMP** (Windows): https://www.wampserver.com/
- **MAMP** (Mac): https://www.mamp.info/

---

## Installazione

### 1. Setup del Server Web

#### Con XAMPP:
1. Installa XAMPP
2. Copia la cartella `cosmic-surfer-db` in `C:\xampp\htdocs\`
3. Avvia Apache e MySQL dal pannello di controllo XAMPP

#### Con WAMP:
1. Installa WAMP
2. Copia la cartella `cosmic-surfer-db` in `C:\wamp64\www\`
3. Avvia WAMP

### 2. Configurazione Database

1. **Apri phpMyAdmin**:
   - XAMPP: http://localhost/phpmyadmin
   - WAMP: http://localhost/phpmyadmin

2. **Crea il database**:
   - Clicca su "SQL" nella barra superiore
   - Copia e incolla il contenuto di `database.sql`
   - Clicca "Esegui"

3. **Verifica**:
   - Dovresti vedere il database `cosmic_surfer` con la tabella `users`
   - La tabella dovrebbe avere un utente di test: `TestPlayer`

### 3. Configurazione PHP

1. Apri `config.php`
2. Modifica le credenziali se necessario:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'cosmic_surfer');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Aggiungi password se necessario
   ```

### 4. Test degli Endpoint

Puoi testare gli endpoint usando un tool come Postman o curl:

#### Test Registrazione:
```bash
curl -X POST http://localhost/cosmic-surfer-db/api/register.php \
  -H "Content-Type: application/json" \
  -d '{"username":"player1","password":"test123","email":"player1@test.com"}'
```

#### Test Login:
```bash
curl -X POST http://localhost/cosmic-surfer-db/api/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"TestPlayer","password":"test123"}'
```

#### Test Leaderboard:
```bash
curl http://localhost/cosmic-surfer-db/api/get-leaderboard.php
```

---

## Struttura File

```
cosmic-surfer-db/
├── config.php                 # Configurazione database
├── database.sql              # Schema database
├── README.md                 # Questa guida
├── api/
│   ├── register.php          # POST - Registrazione utente
│   ├── login.php             # POST - Login utente
│   ├── logout.php            # POST - Logout utente
│   ├── check-session.php     # GET - Verifica sessione
│   ├── update-score.php      # POST - Aggiorna punteggio
│   └── get-leaderboard.php   # GET - Classifica top 10
└── cosmic-surfer-3d.html     # File di gioco (da copiare qui)
```

---

## API Endpoints

### POST /api/register.php
Registra un nuovo utente.

**Request:**
```json
{
  "username": "player1",
  "password": "mypassword",
  "email": "optional@email.com"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Registration successful",
  "user": {
    "id": 1,
    "username": "player1",
    "record": 0
  }
}
```

### POST /api/login.php
Effettua il login.

**Request:**
```json
{
  "username": "player1",
  "password": "mypassword"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "username": "player1",
    "record": 1500
  }
}
```

### GET /api/check-session.php
Verifica se l'utente è loggato.

**Response (200):**
```json
{
  "success": true,
  "loggedIn": true,
  "user": {
    "id": 1,
    "username": "player1",
    "record": 1500
  }
}
```

### POST /api/update-score.php
Aggiorna il punteggio (solo se maggiore del record attuale).

**Request:**
```json
{
  "score": 2500
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "New record!",
  "record": 2500,
  "isNewRecord": true,
  "previousRecord": 1500
}
```

### GET /api/get-leaderboard.php
Ottiene la classifica dei top 10 giocatori.

**Response (200):**
```json
{
  "success": true,
  "leaderboard": [
    {"username": "player1", "record": 5000},
    {"username": "player2", "record": 4500},
    ...
  ]
}
```

### POST /api/logout.php
Effettua il logout.

**Response (200):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

## Sicurezza

### Note Importanti:
1. **Password MD5**: Implementato come richiesto, ma MD5 è deprecato. Per produzione, usa `password_hash()` con bcrypt.
2. **HTTPS**: In produzione, usa sempre HTTPS per proteggere le credenziali.
3. **SQL Injection**: Protetto tramite prepared statements PDO.
4. **XSS**: Sanitizza sempre l'output quando mostri dati utente.
5. **CSRF**: Non implementato. Considera l'aggiunta di token CSRF per produzione.

### Utente di Test:
- **Username**: TestPlayer
- **Password**: test123
- **Record**: 0

---

## Troubleshooting

### Errore "Database connection failed"
- Verifica che MySQL sia avviato
- Controlla le credenziali in `config.php`
- Verifica che il database `cosmic_surfer` esista

### Errore 404 sugli endpoint
- Verifica che il server web sia avviato
- Controlla il percorso: dovrebbe essere `http://localhost/cosmic-surfer-db/api/...`
- Verifica che i file PHP siano nella cartella corretta

### Session non funziona
- Verifica che `session.save_path` in `php.ini` sia configurato
- Controlla i permessi della cartella delle sessioni
- Assicurati che i cookie siano abilitati nel browser

---

## Prossimi Passi

1. Copia `cosmic-surfer-3d.html` nella cartella `cosmic-surfer-db`
2. Modifica il file HTML per integrare il sistema di login
3. Testa il flusso completo: register → login → play → save score
4. Verifica la classifica

---

## Supporto

Per problemi o domande, controlla:
- Log di Apache: `xampp/apache/logs/error.log`
- Log di PHP: Configurato in `php.ini`
- Console del browser per errori JavaScript
