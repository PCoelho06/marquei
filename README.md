# Marquei

Marquei is a booking management application tailored for the Portuguese market, focusing on beauty salons, hairdressers, and barbershops. The app allows customers to book appointments seamlessly, while professionals manage their services and schedules efficiently.

## Features

- User registration and authentication (clients and professionals)
- Salon and service management for professionals
- Appointment booking system for clients
- Real-time availability management
- User-friendly mobile interface built with React Native

## Tech Stack

- **Frontend:** React Native (Expo)
- **Backend:** Symfony
- **Database:** MySQL
- **Hosting:** Hostinger

## Getting Started

### Prerequisites

- Node.js & npm installed
- PHP & Composer installed
- MySQL server running
- Expo CLI installed globally (`npm install -g expo-cli`)

### Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your-username/marquei.git
   cd marquei
   ```

2. **Setup the Backend:**

   ```bash
   cd backend
   composer install
   cp .env.example .env
   # Configure your MySQL credentials in the .env file or use the included docker-compose configuration
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console server:run
   ```

3. **Setup the Frontend:**
   ```bash
   cd ../frontend
   npm install
   expo start
   ```

## Database Structure

### Tables

- **users**: Manages clients and professionals.

  - `id`, `email`, `password`, `role`, `created_at`

- **shops**: Represents beauty salons.

  - `id`, `name`, `address`, `phone`, `description`, `owner_id`

- **services**: Services offered by shops.

  - `id`, `shop_id`, `name`, `price`, `duration`

- **bookings**: User reservations.

  - `id`, `user_id`, `service_id`, `date`, `status`

- **employees**: Shop employees.

  - `id`, `shop_id`, `name`, `specialization`

- **resources**: Shop resources (e.g., equipment).

  - `id`, `shop_id`, `name`, `type`

- **availability**: Availability of employees/resources.
  - `id`, `employee_id`, `resource_id`, `start_time`, `end_time`

## Contribution

Feel free to fork this repository, submit issues, or open pull requests.

## License

This project is licensed under the MIT License.

---

Made with ❤️ by Pierre at Lapinou.tech
