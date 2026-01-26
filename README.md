# Smart-Task

Smart-Task is a productivity app that automatically prioritizes your tasks based on urgency, impact, deadlines, and effort.  
The goal is simple: help users instantly answer **“What should I work on next?”** without overwhelm or decision fatigue.

Built with a Laravel API, a modern React frontend, and a Docker-based environment that mirrors real production setups.

---

## 🎥 Demo Video

A short walkthrough of the application, demonstrating task management, infinite scrolling, priority scoring, and AI-assisted task analysis:

[![Smart task demo video](https://img.youtube.com/vi/rL1ao_cVQik/maxresdefault.jpg)](https://www.youtube.com/watch?v=rL1ao_cVQik)

---

## 🚀 Features

- **Smart task scoring** based on multiple weighted factors
- **Dynamic priority ranking** that updates in real time
- **Task attributes:** urgency, impact, effort, deadline
- **AI-assisted task analysis** to help suggest task priorities
- **Infinite scrolling** for efficient task browsing
- **Clean REST API** built with Laravel
- **Modern React UI** designed for clarity and speed
- **Dockerized environment** (Laravel + MySQL + phpMyAdmin + Nginx)
- **Future-ready architecture:** authentication, reminders, extended AI insights

---

## 🧠 How Smart Prioritization Works

Every task gets a **Priority Score** calculated from:

- **Urgency** — how fast the task needs attention
- **Impact** — effect on the project or overall goals
- **Effort** — estimated work required
- **Deadline proximity** — how close the due date is

Tasks are then automatically sorted from **Highest Priority → Lowest Priority**, giving immediate clarity on what to tackle next.

---

## 🛠️ Tech Stack

### **Backend**

- Laravel 11 (API mode)
- PHP 8.2
- MySQL 8
- Composer
- Docker (multi-service)

### **Frontend**

- React (Vite)
- Inertia.js
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

### Services

- `app` — Laravel + PHP-FPM
- `nginx` — API server
- `db` — MySQL
- `pma` — phpMyAdmin for debugging

### Start the environment

```bash
docker-compose up -d --build
```
