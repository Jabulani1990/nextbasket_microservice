B
# Microservices Application: Data Submission and Notifications

This project comprises two main microservices designed to demonstrate a basic data submission flow, event generation upon data persistence, and notification handling via a message broker. The microservices are containerized with Docker, facilitating deployment and scaling.

## Overview

- Data Submission Service: Accepts user data including `email`, `firstName`, and `lastName`, then stores this information in a MySQL database or logs it to a file, based on the configuration.
- Notifications Service: Listens for events from the Data Submission Service through RabbitMQ, processes the event, and saves the received data in a log file.

Both the Data Submission Service and the Notifications Service are deployed using Docker containers, with Nginx serving as the web server for each service. Nginx is configured to handle HTTP requests, directing them to the appropriate service endpoint. This setup ensures efficient request handling and provides a robust environment for the services to operate.

### Accessing the Services
To access the services, you'll direct your HTTP requests to the Nginx server's host address. If you're running the services locally, the addres is http://localhost, with port forwarding configured in the docker-compose.yml file to expose the services on specific ports. For example, if the Data Submission Service is configured to run on port 8000, you would access it at http://localhost:8000/api/user. Notification Service is configured to run on port 8001

### Technologies Used

- **Docker**: For containerizing the microservices.
- **MySQL**: Used by both services for data persistence.
- **RabbitMQ (CloudAMQP)**: Serves as the message bus between services.
- **Laravel**: Framework used for the development of both services.

## Getting Started

### Prerequisites

- Docker and Docker Compose
- An account on CloudAMQP or access to a RabbitMQ server

### Configuration

1. **CloudAMQP Setup**: Ensure you have your CloudAMQP URL, which will be used by both services to communicate. you can find the credential url in the environment variable ('fish-01.rmq.cloudamqp.com').

2. **Environment Variables**: Each service has a `.env` file. Update these files with the correct MySQL and RabbitMQ connection details.

Example for the Data Submission Service `.env`:

for user service
DB_CONNECTION=mysql
DB_HOST=user_db
DB_PORT=3306
DB_DATABASE=nextbasket
DB_USERNAME=root
DB_PASSWORD=password

RABBITMQ_HOST=<CLOUDAMQP_URL>

for notification service
DB_CONNECTION=mysql
DB_HOST=notification_db
DB_PORT=3306
DB_DATABASE=notification
DB_USERNAME=root
DB_PASSWORD=password



### Running the Services

To start both services, navigate to the root directory of each service and run:

```bash
docker-compose up --build
```

This command builds the Docker images (if not already built) and starts the services.

## Usage

### Data Submission Service

**Endpoint**: `POST api/user`

**Payload**:

```json
{
  "email": "user@example.com",
  "first_name": "John",
  "last_name": "Doe"
}
```

**Response**:

Success:

```json
{
    "message": "User created successfully",
    "user": {
        "email": "jazlucks1@gmail.com",
        "first_name": "Jackson",
        "last_name": "Jonathan",
        "updated_at": "2024-03-13T08:50:01.000000Z",
        "created_at": "2024-03-13T08:50:01.000000Z",
        "id": 21
    }
}
```

Error:

```json
{
    "error": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### Notifications Service

Certainly, here's the enhanced paragraph for the Notifications Service with added instructions about running the `php artisan queue:work` command:

---

### Notifications Service

The Notifications Service automatically processes messages from the Data Submission Service and logs the data. To ensure the service is ready to process incoming messages, you must start the Laravel queue worker. This can be done by running the `php artisan queue:work` command within the service's Docker container. This command listens for new messages on the queue and triggers the corresponding job to handle the message— in this case, logging the data.

To start the queue worker, execute the following command from the root directory of the Notifications Service:

```bash
docker-compose exec app php artisan queue:work
```

This command initiates the Laravel queue worker within the `app` service defined in your `docker-compose.yml`. Ensure that the queue worker is running before submitting data through the Data Submission Service to guarantee the proper reception and logging of data. You can check the service logs to confirm the reception and processing of the data.

--- 

## Development

### Running Tests

To run the unit and feature tests for each service, use the following command within the service directory:

```bash
docker-compose exec user_app ./vendor/bin/phpunit --filter it_creates_a_new_user_and_dispatches_notification_job
```






