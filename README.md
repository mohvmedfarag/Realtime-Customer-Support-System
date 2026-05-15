# Realtime Customer Support System

A real-time customer support chat system built with Laravel and Firebase Realtime Database.

## Features

- Real-time messaging using Firebase
- Customer & agent chat sessions
- Live typing indicators
- Agent assignment system
- Session management
- Chat rating & feedback system
- Real-time status updates
- Queue-based background processing
- Responsive support dashboard

## Tech Stack

- Laravel
- Firebase Realtime Database
- MySQL
- jQuery
- Bootstrap 5
- SweetAlert2

---

## Installation

### Clone the repository

```bash
git clone git@github.com:mohvmedfarag/Realtime-Customer-Support-System.git
```

### Go to project directory

```bash
cd Realtime-Customer-Support-System
```

### Install dependencies

```bash
composer install
```

### Copy environment file

```bash
cp .env.example .env
```

### Generate application key

```bash
php artisan key:generate
```

### Configure Environment Variables

Update your `.env` file:

```env
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

FIREBASE_DATABASE_URL=
```

### Run migrations

```bash
php artisan migrate
```

### Start the development server

```bash
php artisan serve
```

---

## Firebase Structure

```text
sessions/
    session_id/
        status
        agent_name
        typing/

chats/
    session_id/
        messages/
```

---

## Main Functionalities

### Customer Side

- Create chat sessions
- Send and receive messages instantly
- Real-time typing indicator
- Session history
- Rate support experience

### Agent Side

- Manage customer sessions
- Real-time communication
- Typing status broadcasting
- Close chat sessions

---

## Screenshots

---

## Future Improvements

- Voice messages
- File attachments
- Push notifications
- AI-powered responses
- Multi-agent support
- Analytics dashboard

---

## Author

Mohamed Farag
