# Development Guide for Sovendus Magento Plugin

This document provides instructions for setting up and working with the test server for the Sovendus Magento plugin.

## Prerequisites

Ensure you have the following installed on your system:

- Docker
- yarn

## Setting Up the Test Server

1. Clone the repository and navigate to the project directory.

2. Run the development setup script:

   ```bash
   yarn dev
   ```

   This script performs the following actions:
   - Creates Docker containers for Magento, MySQL, Redis, OpenSearch, and more
   - Configures necessary services for Magento development

3. Access the Magento instance in your browser at `https://magento.test`.

## Working with the Plugin

- The Sovendus module is mounted into the Magento container. Any changes made to the module files in the `SovendusApp` directory will be reflected in the running Magento instance.

- To test changes, refresh the Magento admin or frontend as needed.

## Stopping the Test Server

To stop and remove the containers, use Docker commands as needed or refer to the Docker documentation.

## Troubleshooting

### Common Issues

1. **Missing bc command**:
   - If you see an error like `bin/start: line 6: bc: command not found`, install bc using:

     ```bash
     sudo apt-get update && sudo apt-get install bc
     ```

2. **Docker Errors**:
   - Check the Docker logs for specific container issues:

     ```bash
     docker logs magento-docker-testing-instance-app-1
     docker logs magento-docker-testing-instance-db-1
     ```

## Notes

- The Magento Docker setup uses a custom image based on the markshust/docker-magento configuration.
- The configuration can be modified in the `dev/magento-docker-testing-instance/compose.yaml` file.

## Compatibility

The Sovendus module is designed to be compatible with recent Magento versions.
