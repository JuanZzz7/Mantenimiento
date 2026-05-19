<?php

use Illuminate\Support\Facades\Schedule;

// Generador automático de órdenes preventivas — diariamente a las 6:00 AM
Schedule::command('maintenance:generate')->dailyAt('06:00');
