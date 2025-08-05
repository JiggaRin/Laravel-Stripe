# Laravel Stripe Integration 💳

This repository demonstrates a simple yet powerful integration of Stripe payment processing within a Laravel application. It provides a clean foundation for handling secure payments, subscriptions, and billing workflows using Laravel's expressive syntax and Stripe's robust APIs.

---

## 🚀 Features

- Stripe Checkout integration
- Secure payment processing
- Laravel 10+ support
- Blade templates for UI
- RESTful routing and controllers
- Environment-based configuration

---

## 📦 Installation

Follow these steps to get the project up and running:

```bash
# Clone the repository
git clone https://github.com/JiggaRin/Laravel-Stripe.git
cd Laravel-Stripe

# Install PHP dependencies
composer install

# Install frontend assets
npm install && npm run dev

# Set up environment
cp .env.example .env
php artisan key:generate

# Add your Stripe keys to .env
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key

# Run database migrations
php artisan migrate

# Start the development server
php artisan serve
```

---

## 🧪 Testing Payments

Use Stripe's test card numbers to simulate transactions:

- **Card Number:** `4242 4242 4242 4242`
- **Expiry Date:** Any future date
- **CVC:** Any 3 digits
- **ZIP Code:** Any valid ZIP

You can find more test card numbers in [Stripe's documentation](https://stripe.com/docs/testing).

---

## 📁 Project Structure

```text
├── app/
│   └── Http/
│       └── Controllers/
│           └── StripeController.php
├── resources/
│   └── views/
│       └── checkout.blade.php
├── routes/
│   └── web.php
├── .env
├── composer.json
└── package.json
```

---

## 🛠️ Technologies Used

| Technology | Purpose               |
|------------|------------------------|
| Laravel    | Backend framework      |
| Stripe     | Payment processing     |
| Blade      | Templating engine      |
| Tailwind   | Styling (if applicable)|

---

## 🙌 Contributing

Feel free to fork the repo, open issues, or submit pull requests. Contributions are welcome!

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

Made with ❤️ using Laravel and Stripe.
