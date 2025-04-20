# Laravel 11 Project Setup Guide

## **Requirements**

-   **Laravel**: 11
-   **PHP**: 8.2+
-   **Database**: MySQL

---

## **Setup Instructions**

If you already have cloned and set up before, run the following commands:

```bash
cd <project-directory>
```

1. **Migrate the Database:**

    ```bash
    php artisan migrate:fresh
    ```

2. **Copy this WeTransfer for Images:**

   Use the folder `storage\app\public`. Download the images from the following link:

   [https://we.tl/t-pR6V33m3DE](https://we.tl/t-pR6V33m3DE)

---

### **Full Setup Instructions**

Follow these steps to set up the project:

1. **Clone the Repository**

   Clone the project repository to your local machine:

   ```bash
   git clone <repository-url>
   ```

   Navigate into the project directory:

   ```bash
   cd <project-directory>
   ```

2. **Install Dependencies**

   Install the required dependencies using Composer:

   ```bash
   composer install
   ```

3. **Configure the Environment**

   Set up the `.env` file:

   1. Copy the `.env.example` file to `.env`:

      ```bash
      cp .env.example .env
      ```

   2. Update the database settings in `.env`:

      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=<your-database-name>
      DB_USERNAME=<your-database-username>
      DB_PASSWORD=<your-database-password>
      ```

4. **Run the Setup Script**

   Execute the `setup.sh` script to automate the setup process:

   ```bash
   ./setup.sh
   ```

   The script will perform the following steps:
   - Install Composer dependencies.
   - Run database migrations.
   - Create a personal access client for Laravel Passport.
   - Seed the database.
   - Prompt you to enter the number of random orders to create (default is 550). If no input is provided within 15 seconds, it will use the default value.

5. **Serve the Project**

   Start the Laravel development server:

   ```bash
   php artisan serve
   ```

   Access the application at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

### **S3 Configuration**

To configure S3 for file storage, follow these steps:

1. **Set Up AWS Credentials**

   Update the `.env` file with your S3 credentials:

   ```env
   FILESYSTEM_DRIVER=s3
   AWS_ACCESS_KEY_ID=<your-access-key-id>
   AWS_SECRET_ACCESS_KEY=<your-secret-access-key>
   AWS_DEFAULT_REGION=<your-region> # e.g., us-east-1
   AWS_BUCKET=<your-bucket-name>
   AWS_URL=<your-s3-url> # Optional, if using a custom domain
   ```

2. **Install AWS SDK**

   Ensure the AWS SDK is installed via Composer:

   ```bash
   composer require league/flysystem-aws-s3-v3 "^3.0"
   ```

3. **Verify Configuration**

   Test the S3 configuration by uploading a file:

   ```php
   Storage::disk('s3')->put('example.txt', 'This is a test file.');
   ```

   Check your S3 bucket to confirm the file was uploaded successfully.

---

### **Additional Notes**

- Ensure you have the correct permissions for the `storage` and `bootstrap/cache` directories:

  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

- If you encounter any issues, ensure that all dependencies are installed and that your database configuration is correct.

---

## **License**

This project is open-source and licensed under the [MIT License](https://opensource.org/licenses/MIT).
