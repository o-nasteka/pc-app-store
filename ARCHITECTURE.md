# User Activity Tracker - Architecture Documentation

## Огляд архітектури

Додаток побудований згідно з принципами Domain-Driven Design (DDD) та Clean Architecture. Архітектура складається з чотирьох основних шарів:

### 1. Domain Layer (Доменний шар)
Центр додатку, що містить бізнес-логіку та правила.

### 2. Application Layer (Шар застосування)
Координує роботу домену та реалізує use cases.

### 3. Infrastructure Layer (Інфраструктурний шар)
Реалізує технічні деталі (БД, файли, зовнішні сервіси).

### 4. Presentation Layer (Презентаційний шар)
Взаємодія з користувачем (Web, API).

## Детальний опис файлів

### Domain Layer

#### Entities (Сутності)
- **`src/Domain/Entity/User.php`**
    - Представляє користувача системи
    - Містить бізнес-логіку користувача (зміна паролю, оновлення останнього входу)
    - Агрегат з власною ідентичністю

- **`src/Domain/Entity/Activity.php`**
    - Представляє дію користувача в системі
    - Зберігає метадані про кожну активність
    - Незмінна після створення (Event Sourcing pattern)

#### Value Objects (Об'єкти-значення)
- **`src/Domain/ValueObject/AbstractId.php`**
    - Базовий клас для всіх ідентифікаторів
    - Використовує UUID для унікальності

- **`src/Domain/ValueObject/UserId.php`**
    - Ідентифікатор користувача
    - Гарантує типобезпеку

- **`src/Domain/ValueObject/ActivityId.php`**
    - Ідентифікатор активності

- **`src/Domain/ValueObject/Email.php`**
    - Email користувача з валідацією
    - Нормалізація (lowercase)
    - Незмінний після створення

- **`src/Domain/ValueObject/Password.php`**
    - Пароль з хешуванням
    - Верифікація паролів
    - Перевірка необхідності перехешування

- **`src/Domain/ValueObject/UserRole.php`**
    - Роль користувача (user/admin)
    - Enum-подібна поведінка

- **`src/Domain/ValueObject/ActivityType.php`**
    - Тип активності
    - Обмежений набір значень

#### Repository Interfaces
- **`src/Domain/Repository/UserRepositoryInterface.php`**
    - Інтерфейс для роботи з користувачами
    - Не залежить від конкретної реалізації БД

- **`src/Domain/Repository/ActivityRepositoryInterface.php`**
    - Інтерфейс для роботи з активностями
    - Включає методи для статистики та звітів

#### Exceptions
- **`src/Domain/Exception/AuthenticationException.php`**
    - Помилка автентифікації

- **`src/Domain/Exception/UserAlreadyExistsException.php`**
    - Користувач вже існує

### Application Layer

#### Use Cases - Auth
- **`src/Application/UseCase/Auth/LoginUseCase.php`**
    - Логіка входу користувача
    - Перевірка credentials
    - Логування активності

- **`src/Application/UseCase/Auth/RegisterUseCase.php`**
    - Реєстрація нового користувача
    - Перевірка унікальності email
    - Створення користувача з роллю "user"

- **`src/Application/UseCase/Auth/LogoutUseCase.php`**
    - Логування виходу з системи

#### Use Cases - Activity
- **`src/Application/UseCase/Activity/TrackPageViewUseCase.php`**
    - Відстеження переглядів сторінок

- **`src/Application/UseCase/Activity/TrackButtonClickUseCase.php`**
    - Відстеження кліків на кнопки

- **`src/Application/UseCase/Activity/GetStatisticsUseCase.php`**
    - Отримання статистики з фільтрами

- **`src/Application/UseCase/Activity/GetReportUseCase.php`**
    - Формування звітів для графіків та таблиць

#### DTOs (Data Transfer Objects)
- **Auth DTOs**
    - `LoginRequest/Response` - дані для входу
    - `RegisterRequest/Response` - дані для реєстрації
    - `LogoutRequest` - дані для виходу

- **Activity DTOs**
    - `TrackPageViewRequest` - дані перегляду сторінки
    - `TrackButtonClickRequest` - дані кліку
    - `GetStatisticsRequest/Response` - фільтри та результати статистики
    - `GetReportRequest/Response` - параметри та дані звітів

### Infrastructure Layer

#### Repository Implementations
- **`src/Infrastructure/Repository/DoctrineUserRepository.php`**
    - Реалізація UserRepository через Doctrine DBAL
    - SQL запити для роботи з користувачами

- **`src/Infrastructure/Repository/DoctrineActivityRepository.php`**
    - Реалізація ActivityRepository
    - Складні запити для статистики та звітів

#### Services
- **`src/Infrastructure/Service/SessionService.php`**
    - Робота з сесіями

#### Database
- **`src/Infrastructure/Persistence/Migrations/schema.sql`**
    - SQL схема бази даних

### Presentation Layer

#### Web Controllers
- **`src/Presentation/Web/HomeController.php`**
    - Головна сторінка

- **`src/Presentation/Web/AuthController.php`**
    - Сторінки login/register
    - Обробка форм автентифікації

- **`src/Presentation/Web/PageController.php`**
    - Page A та Page B
    - Відстеження переглядів

- **`src/Presentation/Web/StatisticsController.php`**
    - Сторінка статистики (тільки для admin)

- **`src/Presentation/Web/ReportsController.php`**
    - Сторінка звітів з графіками

#### API Controllers
- **`src/Presentation/Api/ActivityController.php`**
    - API endpoints для активностей
    - AJAX запити з frontend

- **`src/Presentation/Api/UserController.php`**
    - API для роботи з користувачами

#### Middleware
- **`src/Presentation/Middleware/AuthenticationMiddleware.php`**
    - Перевірка автентифікації

### Config Layer

- **`src/Config/Bootstrap.php`**
    - Ініціалізація додатку
    - Обробка запитів

- **`src/Config/Container.php`**
    - DI контейнер
    - Реєстрація сервісів

- **`src/Config/Routes.php`**
    - Визначення маршрутів

### Templates (Twig)

- **`templates/base.html.twig`**
    - Базовий шаблон з навігацією

- **`templates/home.html.twig`**
    - Головна сторінка

- **`templates/auth/`**
    - login.html.twig - форма входу
    - register.html.twig - форма реєстрації

- **`templates/pages/`**
    - page_a.html.twig - сторінка з кнопкою "Buy a cow"
    - page_b.html.twig - сторінка з кнопкою "Download"

- **`templates/admin/`**
    - statistics.html.twig - таблиця активностей з фільтрами
    - reports.html.twig - графіки та звіти

### Configuration Files

- **`composer.json`** - залежності PHP
- **`docker-compose.yml`** - конфігурація Docker
- **`.env`** - змінні середовища
- **`.gitignore`** - ігнорування файлів Git

## Потік даних

1. **Request** → `index.php` → `Bootstrap`
2. **Bootstrap** → `Routes` → `Controller`
3. **Controller** → `UseCase` (with DTO)
4. **UseCase** → `Repository` → `Domain Entity`
5. **UseCase** → `Response DTO` → `Controller`
6. **Controller** → `Twig Template` → **Response**

## Принципи та патерни

- **DDD**: Domain в центрі, інші шари залежать від нього
- **Clean Architecture**: Залежності спрямовані всередину
- **Repository Pattern**: Абстракція доступу до даних
- **Use Case Pattern**: Кожна дія - окремий клас
- **DTO Pattern**: Передача даних між шарами
- **Value Objects**: Незмінні об'єкти без ідентичності
- **Dependency Injection**: Впровадження залежностей
- **SOLID**: Дотримання всіх п'яти принципів
