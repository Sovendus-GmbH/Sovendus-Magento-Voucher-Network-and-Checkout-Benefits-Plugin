#!/usr/bin/env bash
set -o errexit

# Define variables
DOMAIN="magento.test"
EDITION="community"
VERSION="2.4.7-p3"

# Check if bc is installed and install it if missing
if ! command -v bc &> /dev/null; then
    echo "Installing bc (basic calculator utility)..."
    if command -v pacman &> /dev/null; then
        # For Manjaro and other Arch-based distributions
        sudo pacman -Sy --noconfirm bc
    elif command -v apt-get &> /dev/null; then
        sudo apt-get update -qq && sudo apt-get install -y bc
    elif command -v yum &> /dev/null; then
        sudo yum install -y bc
    elif command -v apk &> /dev/null; then
        apk add --no-cache bc
    else
        echo "Warning: Could not automatically install bc. Please install it manually."
        echo "For Ubuntu/Debian: sudo apt-get install bc"
        echo "For CentOS/RHEL: sudo yum install bc"
        echo "For Manjaro/Arch: sudo pacman -S bc"
        echo "For Alpine: apk add bc"
        exit 1
    fi
fi

# Change to the magento-docker-testing-instance directory
cd "$(dirname "$0")/magento-docker-testing-instance"

# Stop any running containers
./bin/stop

# Check if we need to clean the environment for a fresh installMAGENTO_INSTALLED=false
if [ -d "src" ] && [ -f "src/bin/magento" ]; then
    MAGENTO_INSTALLED=true
    echo "Existing Magento installation detected."
else
    echo "No existing Magento installation found or installation is incomplete."
    echo "Cleaning environment for a fresh install..."
    
    # Remove src directory if it exists to ensure a clean install
    if [ -d "src" ]; then
        echo "Removing existing src directory..."
        sudo rm -rf src
    fi
    
    # Create empty src directory
    mkdir -p src
fi

# Start containers first
echo "Starting Docker containers..."
./bin/start --no-dev

# Only run download and setup if Magento isn't installed
if [ "$MAGENTO_INSTALLED" = false ]; then
    # Download Magento
    echo "Downloading and installing Magento ${VERSION}..."
    ./bin/download ${EDITION} ${VERSION}
    
    # Setup Magento
    echo "Setting up Magento..."
    ./bin/setup "${DOMAIN}"
else
    echo "Using existing Magento installation. Skipping download and setup."
    
    # Just ensure the Magento environment is properly configured
    echo "Ensuring Magento configuration is up to date..."
    if [ -f "src/bin/magento" ]; then
        ./bin/clinotty chmod +x bin/magento
        ./bin/clinotty bin/magento setup:upgrade
        ./bin/clinotty bin/magento cache:flush
    else
        echo "Warning: bin/magento script not found. Magento may not be properly installed."
    fi
fi

#!/usr/bin/env bash
set -o errexit

# Define variables
DOMAIN="magento.test"
EDITION="community"
VERSION="2.4.7-p3"

# Check if bc is installed and install it if missing
if ! command -v bc &> /dev/null; then
    echo "Installing bc (basic calculator utility)..."
    if command -v pacman &> /dev/null; then
        # For Manjaro and other Arch-based distributions
        sudo pacman -Sy --noconfirm bc
    elif command -v apt-get &> /dev/null; then
        sudo apt-get update -qq && sudo apt-get install -y bc
    elif command -v yum &> /dev/null; then
        sudo yum install -y bc
    elif command -v apk &> /dev/null; then
        apk add --no-cache bc
    else
        echo "Warning: Could not automatically install bc. Please install it manually."
        echo "For Ubuntu/Debian: sudo apt-get install bc"
        echo "For CentOS/RHEL: sudo yum install bc"
        echo "For Manjaro/Arch: sudo pacman -S bc"
        echo "For Alpine: apk add bc"
        exit 1
    fi
fi

# Permanently disable Two-Factor Authentication
# echo "Permanently disabling Two-Factor Authentication..."
./bin/magento config:set twofactorauth/general/force_providers '[]'


echo "Magento test environment is now running!"
echo "You can access the frontend at http://magento.test/"
echo "You can access the admin panel at http://magento.test/admin/"
echo "Admin credentials are in env/magento.env"
