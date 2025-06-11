#!/bin/bash

# Create project structure for User Activity Tracker

echo "Creating project structure..."

# Create main directories
mkdir -p project/{public/downloads,src,templates,assets,config,tests,var,docker}

# Create src subdirectories
mkdir -p project/src/{Domain,Application,Infrastructure,Presentation,Config}

# Domain layer
mkdir -p project/src/Domain/{Entity,ValueObject,Repository,Exception}

# Application layer
mkdir -p project/src/Application/{UseCase,DTO}
mkdir -p project/src/Application/UseCase/{Auth,Activity}
mkdir -p project/src/Application/DTO/{Auth,Activity}

# Infrastructure layer
mkdir -p project/src/Infrastructure/{Repository,Service,Persistence/Migrations}

# Presentation layer
mkdir -p project/src/Presentation/{Web,Api,Middleware}

# Templates
mkdir -p project/templates/{auth,pages,admin}

# Assets
mkdir -p project/assets/{js/components,css,images}

# Tests
mkdir -p project/tests/{Unit,Integration,Feature}
mkdir -p project/tests/Unit/{Domain,Application}
mkdir -p project/tests/Unit/Domain/{Entity,ValueObject}
mkdir -p project/tests/Unit/Application/UseCase
mkdir -p project/tests/Integration/Repository
mkdir -p project/tests/Feature/{Auth,Activity}

# Var
mkdir -p project/var/{cache,logs}

# Docker
mkdir -p project/docker/{php,nginx,mysql}

# Create empty placeholder files
touch project/public/index.php
touch project/public/downloads/.gitkeep

# Domain Entity files
touch project/src/Domain/Entity/{User.php,Activity.php}

# Domain ValueObject files
touch project/src/Domain/ValueObject/{AbstractId.php,UserId.php,ActivityId.php,Email.php,Password.php,UserRole.php,ActivityType.php}

# Domain Repository interfaces
touch project/src/Domain/Repository/{UserRepositoryInterface.php,ActivityRepositoryInterface.php}

# Domain Exceptions
touch project/src/Domain/Exception/{AuthenticationException.php,UserAlreadyExistsException.php}

# Application UseCase files
touch project/src/Application/UseCase/Auth/{LoginUseCase.php,RegisterUseCase.php,LogoutUseCase.php}
touch project/src/Application/UseCase/Activity/{TrackPageViewUseCase.php,TrackButtonClickUseCase.php,GetStatisticsUseCase.php,GetReportUseCase.php}

# Application DTO files
touch project/src/Application/DTO/Auth/{LoginRequest.php,LoginResponse.php,RegisterRequest.php,RegisterResponse.php,LogoutRequest.php}
touch project/src/Application/DTO/Activity/{TrackPageViewRequest.php,TrackButtonClickRequest.php,GetStatisticsRequest.php,GetStatisticsResponse.php,GetReportRequest.php,GetReportResponse.php}

# Infrastructure files
touch project/src/Infrastructure/Repository/{DoctrineUserRepository.php,DoctrineActivityRepository.php}
touch project/src/Infrastructure/Service/SessionService.php
touch project/src/Infrastructure/Persistence/Migrations/schema.sql

# Presentation Controllers
touch project/src/Presentation/Web/{HomeController.php,AuthController.php,PageController.php,StatisticsController.php,ReportsController.php}
touch project/src/Presentation/Api/{ActivityController.php,UserController.php}
touch project/src/Presentation/Middleware/AuthenticationMiddleware.php

# Config files
touch project/src/Config/{Routes.php,Container.php,Bootstrap.php}

# Template files
touch project/templates/{base.html.twig,home.html.twig}
touch project/templates/auth/{login.html.twig,register.html.twig}
touch project/templates/pages/{page_a.html.twig,page_b.html.twig}
touch project/templates/admin/{statistics.html.twig,reports.html.twig}

# Asset files
touch project/assets/js/{app.js,components/activity-tracker.js,components/report-chart.js}
touch project/assets/css/custom.css

# Config files
touch project/config/{services.yaml,routes.yaml,parameters.yaml}

# Root files
touch project/{.env.example,.gitignore,composer.json,phpunit.xml,phpstan.neon,README.md,docker-compose.yml}

# Docker files
touch project/docker/php/Dockerfile
touch project/docker/nginx/default.conf
touch project/docker/mysql/init.sql

# Make script executable
chmod +x project/public/index.php

echo "Project structure created successfully!"
echo "Total directories created: $(find project -type d | wc -l)"
echo "Total files created: $(find project -type f | wc -l)"

# Create tree view of the structure
echo -e "\nProject structure:"
tree project -L 4 2>/dev/null || find project -type d | sort
