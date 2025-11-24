#!/bin/bash

# BLI Website - Shared Hosting Deployment Script
# This script helps optimize and prepare your Laravel app for production

echo "🚀 BLI Website - Production Deployment Script"
echo "=============================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if we're in the project root
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: Please run this script from the Laravel project root directory${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Checking environment...${NC}"
if [ ! -f ".env" ]; then
    echo -e "${RED}Error: .env file not found!${NC}"
    echo "Please copy .env.example to .env and configure it first"
    exit 1
fi

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}APP_KEY not set. Generating...${NC}"
    php artisan key:generate
fi

echo -e "${GREEN}✓ Environment configured${NC}"
echo ""

echo -e "${YELLOW}Step 2: Installing/Updating dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

echo -e "${YELLOW}Step 3: Running database migrations...${NC}"
read -p "Do you want to run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan migrate --force
    echo -e "${GREEN}✓ Migrations completed${NC}"
else
    echo -e "${YELLOW}⊘ Migrations skipped${NC}"
fi
echo ""

echo -e "${YELLOW}Step 4: Clearing all caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

echo -e "${YELLOW}Step 5: Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo -e "${GREEN}✓ Application optimized${NC}"
echo ""

echo -e "${YELLOW}Step 6: Setting up storage link...${NC}"
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo -e "${GREEN}✓ Storage link created${NC}"
else
    echo -e "${YELLOW}⊘ Storage link already exists${NC}"
fi
echo ""

echo -e "${YELLOW}Step 7: Setting permissions...${NC}"
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"
echo ""

echo -e "${YELLOW}Step 8: Building frontend assets...${NC}"
read -p "Do you want to build frontend assets? (requires npm) (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    if command -v npm &> /dev/null; then
        npm run build
        echo -e "${GREEN}✓ Assets built${NC}"
    else
        echo -e "${RED}npm not found. Please build assets manually with: npm run build${NC}"
    fi
else
    echo -e "${YELLOW}⊘ Asset building skipped${NC}"
fi
echo ""

echo -e "${GREEN}=============================================="
echo "✓ Deployment completed successfully!"
echo "=============================================="
echo ""
echo "Next steps:"
echo "1. Make sure your .env file has correct production values"
echo "2. Set APP_DEBUG=false in .env"
echo "3. Configure your cron job for queue processing"
echo "4. Test your application at your domain"
echo ""
echo "For cron job, add this to cPanel:"
echo "* * * * * cd $(pwd) && php artisan queue:work --stop-when-empty"
echo ""
