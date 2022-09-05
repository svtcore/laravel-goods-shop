# Laravel Goods Shop Service

Old personal project which represent multilingual selling goods service based on Laravel Framework and on the PHP 8. Contains separate cabinets for users, managers and administrators.


![Screenshoot](https://github.com/svtcore/laravel-goods-shop/blob/main/screenshots/screen_user_1.png)

### Manager panel
![Screenshoot](https://github.com/svtcore/laravel-goods-shop/blob/main/screenshots/screen_manager_1.png)

#### Admin panel
![Screenshoot](https://github.com/svtcore/laravel-goods-shop/blob/main/screenshots/screen_admin_1.png)

## Installation
1. Clone repository
```
git clone https://github.com/svtcore/laravel-goods-shop.git
```
2. Install composer
```
composer install
```
3. Rename file **.env.example** to **.env**

4. Generate app key
```
php artisan key:generate
```
5. Open **.env** and fill database data
6. Clear cache
```
php artisan cache:clear
```
7. Make migration with seeding
```
php artisan migrate --seed
```
8. Make symlink for storage folder
```
php artisan storage:link
```

## Run
```
php artisan serve
```
## Default credentials
User

```
Auth route: /user/auth
Login: 1234567890
Password: userpassword
```

Manager

```
Auth route: /manager/auth
Login: 380223344555
Password: managerpassword
```

Administrator

```
Auth route: /admin/auth
Login: 380112233444
Password: adminpassword
```

## License
[MIT](https://github.com/svtcore/laravel-restful-api-food-delivery/blob/main/LICENSE)

