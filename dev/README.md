# Magento Plugin Development Environment

This directory contains tools and scripts for setting up a Magento development and testing environment.

## Docker Testing Instance

We use the [Magento Docker Testing Instance](https://github.com/Sovendus-GmbH/magento-docker-testing-instance) as a submodule to provide a consistent testing environment for the Magento plugin.

### Setup

The Docker testing instance has already been added as a Git submodule in the `dev/magento-docker-testing-instance` directory.

If you're cloning this repository for the first time, make sure to initialize and update the submodule:

```bash
yarn setup
```

### Usage

To start the Magento Docker testing environment, run:

```bash
yarn dev
```

This script will:

1. Build the SovendusApp module typescript files
2. Run the `start-test-docker.sh` script from the `dev` directory
3. Stop any running containers
4. Download Magento (community edition 2.4.7-p3 by default)
5. Set up Magento with the domain "magento.test"

After the script completes, you can access:

- Frontend: <https://magento.test/>
- Admin panel: <https://magento.test/admin/>
- Admin credentials are in `dev/magento-docker-testing-instance/env/magento.env`
- Two-Factor Authentication (2FA) has been permanently disabled for development convenience

### Available Commands

The Docker testing instance provides many useful commands. You can run them from the `dev/magento-docker-testing-instance` directory:

```bash
cd dev/magento-docker-testing-instance
./bin/magento                # Run Magento CLI commands
./bin/composer               # Run Composer commands
./bin/mysql                  # Access MySQL
./bin/bash                   # Get a bash shell in the container
```

For a full list of available commands, run:

```bash
cd dev/magento-docker-testing-instance
make help
```

### Stopping the Environment

To stop the Docker containers:

```bash
cd dev/magento-docker-testing-instance
./bin/stop
```

To remove all containers:

```bash
cd dev/magento-docker-testing-instance
./bin/remove
```
