#!/bin/bash
# ============================================================
# Constructa — Script de Despliegue para Producción
# Ejecutar via cPanel Terminal después de subir archivos
# Uso: bash deploy.sh
# ============================================================

echo "🚀 Iniciando despliegue de Constructa..."

# Directorio de la aplicación (ajustar si es diferente)
APP_DIR=$(pwd)

echo "📁 Directorio: $APP_DIR"

# 1. Permisos correctos para storage y cache
echo "🔧 Configurando permisos..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 2. Limpiar caches anteriores
echo "🧹 Limpiando caches..."
php artisan config:clear 2>/dev/null
php artisan route:clear 2>/dev/null
php artisan view:clear 2>/dev/null
php artisan cache:clear 2>/dev/null

# 3. Truncar log existente (puede tener GB de debug logs)
echo "📋 Limpiando log antiguo..."
if [ -f "storage/logs/laravel.log" ]; then
    LOG_SIZE=$(du -sh storage/logs/laravel.log 2>/dev/null | cut -f1)
    echo "   Tamaño del log actual: $LOG_SIZE"
    > storage/logs/laravel.log
    echo "   ✅ Log truncado"
fi

# 4. Limpiar sesiones expiradas
echo "🗑️  Limpiando sesiones expiradas..."
SESSION_COUNT=$(ls storage/framework/sessions/ 2>/dev/null | wc -l)
echo "   Sesiones encontradas: $SESSION_COUNT"
find storage/framework/sessions/ -type f -mmin +120 -delete 2>/dev/null
SESSION_COUNT_AFTER=$(ls storage/framework/sessions/ 2>/dev/null | wc -l)
echo "   Sesiones después de limpieza: $SESSION_COUNT_AFTER"

# 5. Limpiar vistas compiladas obsoletas
echo "🗂️  Limpiando vistas compiladas..."
find storage/framework/views/ -type f -name "*.php" -mtime +7 -delete 2>/dev/null

# 6. Optimizar Laravel para producción
echo "⚡ Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Optimizar autoloader de Composer
echo "📦 Optimizando autoloader..."
composer dump-autoload --optimize --no-dev 2>/dev/null

# 8. Verificar estado
echo ""
echo "============================================================"
echo "📊 Estado Post-Despliegue"
echo "============================================================"
echo "Cache config: $(test -f bootstrap/cache/config.php && echo '✅ Activo' || echo '❌ Inactivo')"
echo "Cache rutas:  $(test -f bootstrap/cache/routes-v7.php && echo '✅ Activo' || echo '❌ Inactivo')"
echo "Sesiones:     $(ls storage/framework/sessions/ 2>/dev/null | wc -l) archivos"
echo "Logs:         $(du -sh storage/logs/ 2>/dev/null | cut -f1)"
echo "Disco:        $(du -sh storage/ 2>/dev/null | cut -f1) total en storage/"
echo ""

# 9. Verificar OPcache
echo "🔍 Verificando OPcache..."
php -r "echo 'OPcache: ' . (function_exists('opcache_get_status') ? 'Disponible' : 'No disponible') . PHP_EOL;" 2>/dev/null

echo ""
echo "✅ Despliegue completado."
echo ""
echo "⚠️  RECORDATORIO: Configurar Cron Job en cPanel:"
echo "   Cada minuto: cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"
echo ""
