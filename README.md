# Smart-Task

Smart-Task is a productivity app that automatically prioritizes your tasks based on urgency, impact, deadlines, effort, and personal energy level.  
The goal is simple: help users instantly answer **“What should I work on next?”** without overwhelm or decision fatigue.

Built with a Laravel API, a modern React frontend, and a Docker-based environment that mirrors real production setups.

---

## 🚀 Features

- **Smart task scoring** based on multiple weighted factors  
- **Dynamic priority ranking** that updates in real time  
- **Task attributes:** urgency, impact, effort, deadline, energy level  
- **Clean REST API** built with Laravel  
- **Modern React UI** designed for clarity and speed  
- **Dockerized environment** (Laravel + MySQL + phpMyAdmin + Nginx)  
- **Future-ready architecture:** authentication, reminders, AI insights  

---

## 🧠 How Smart Prioritization Works

Every task gets a **Priority Score** calculated from:

- **Urgency** (how fast the task is required)  
- **Impact** (effect on the project or life)  
- **Deadline proximity**  
- **Effort level**  
- **User’s current energy/mental load**

You get a sorted list from **Highest Priority → Lowest Priority**, giving immediate clarity on what to tackle next.

---

## 🛠️ Tech Stack

### **Backend**
- Laravel 11 (API mode)
- MySQL 8
- PHP 8.2
- Composer
- Docker (multi-service)

### **Frontend**
- React (Vite)
- TailwindCSS
- Axios for API communication

### **Dev Tools**
- Docker Compose
- phpMyAdmin
- ESLint + Prettier (optional)
- Postman / Thunder Client (optional)

---

## 🐳 Docker Setup

This project runs fully in Docker.  
Services include:

- `app` (Laravel + PHP-FPM)
- `nginx` (serving the API)
- `db` (MySQL)
- `pma` (phpMyAdmin for debugging)

### Start everything:

```bash
docker-compose up -d --build
