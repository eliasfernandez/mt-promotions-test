# Promotions Test

## Requirements

- [Docker](https://www.docker.com/) or Docker Desktop  
- `git` installed on your system

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone git@github.com:eliasfernandez/mt-promotions-test.git
cd mt-promotions-test
```

### 2. Start Docker Containers

```bash
docker compose up -d
```

### 3. Install PHP Dependencies

```bash
docker compose exec app composer install
```

### 4. Setup the Database

You can use the `./console` shortcut instead of the full Symfony console command:

```bash
./console doctrine:database:create --if-not-exists
./console doctrine:schema:create
```

### 5. Seed the Database

#### Option 1: Basic Seed  
Populates 5 products, 3 categories, and 2 discounts.

```bash
./console doctrine:fixtures:load --group=basic --no-debug
```

#### Option 2: Large Dataset  
Populates 20,000 products for stress testing.

```bash
./console doctrine:fixtures:load --group=advanced --no-debug
```

---

## ✅ Running Tests

### Unit & Integration Tests

Run via full path:

```bash
docker compose exec app vendor/bin/phpunit
```

Or using the shortcut:

```bash
./phpunit
```

### Behat Feature Tests

These tests require the network. 

```bash
docker compose exec app vendor/bin/behat features/list.feature
```

Or using the shortcut:

```bash
./behat features/list.feature
```

---

## 💡 Design Decisions

### Pagination

Although the requirements only mention showing 5 products at a time, I implemented proper pagination using a `page` query parameter to allow navigating through larger datasets.

### Database Choice

I chose **PostgreSQL** for robustness and performance, especially to handle large datasets like 20,000 products. The schema is optimized with indexes and includes fixtures for realistic data loading.

### CQRS (Command Query Responsibility Segregation)

The system is read-only from the API's perspective, and commands are only used internally for seeding. I chose **not** to implement full CQRS for simplicity and maintainability, aligning with **KISS** and **YAGNI** principles. However, the domain is structured with DDD boundaries in mind.

### Currency Formatting

A basic `Price` Value Object is used to encapsulate price-related logic. It fulfills the functional needs without unnecessary complexity.
