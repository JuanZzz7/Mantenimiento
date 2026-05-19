# Industrial CMMS — Sistema de Gestión de Mantenimiento

Sistema completo de gestión de mantenimiento industrial desarrollado con **Laravel 12**, **PHP 8.2+**, **MySQL** y **Bootstrap 5**. Diseñado para optimizar la confiabilidad de activos y la eficiencia de los equipos técnicos.

## 🚀 Características Principales

### 🛠️ Módulos de Operación
- **Gestión de Activos:** Registro detallado de equipos, ubicación, estado y código único.
- **Órdenes de Trabajo (OT):** Ciclo de vida completo (Pendiente, En Proceso, Completada, Cancelada) con asignación a técnicos.
- **Mantenimiento Preventivo:** Programación basada en frecuencia (semanal, mensual, etc.) con generación automática de OTs.
- **Gestión de Repuestos:** Control de stock, inventario y consumo asociado a órdenes de trabajo.

### 📊 Análisis y Reportes
- **Dashboard Dual:** 
  - **Admin:** Indicadores críticos, volumen de OTs, rendimiento de técnicos y estado de activos.
  - **Técnico:** Vista enfocada en tareas asignadas y productividad mensual.
- **Reportes PDF:** Exportación profesional de órdenes de trabajo filtrables por fecha y estado.

### 🛡️ Seguridad y PRO Features
- **Control de Roles:** Middlewares personalizados para `/admin` y `/tecnico`.
- **API REST:** Endpoints protegidos con **Laravel Sanctum** para integración móvil.
- **Notificaciones:** Alertas en tiempo real (Base de Datos + Email) para asignación de tareas y stock bajo.
- **Estética Premium:** Diseño con modo oscuro nativo, *glassmorphism* y animaciones fluidas.

## ⚙️ Instalación y Configuración

### 1. Requisitos
- PHP >= 8.2
- MySQL / MariaDB
- Composer & NPM

### 2. Pasos iniciales
```bash
# Clonar y entrar al proyecto
# Instalar dependencias
composer install
npm install && npm run build

# Configurar variables de entorno
cp .env.example .env
php artisan key:generate
```

### 3. Base de Datos & Datos Pro
```bash
# Ejecutar migraciones y seeders profesionales
php artisan migrate:fresh --seed
```
> [!IMPORTANT]
> **Credenciales de Acceso:**
> - **Admin:** `admin@cmms.com` / `password`
> - **Técnico:** `carlos@cmms.com` / `password`

### 4. Automatización del Mantenimiento
Para automatizar la generación de órdenes preventivas, ejecute o programe el siguiente comando:
```bash
php artisan cmms:generate-preventive
```

## 📈 Tecnologías Usadas
- **Laravel 12** (Framework)
- **Bootstrap 5 + Custom Premium CSS** (UI)
- **Chart.js 4** (Gráficas)
- **Dompdf** (Reportes)
- **Sanctum** (API Auth)

---
Desarrollado para la excelencia operativa industrial. 🏭
