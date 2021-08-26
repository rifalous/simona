# Sistem Informasi Monitoring Keuangan (SIMONA)
## Instalasi
 - clone projek ini https://github.com/rifalous/simona.git
 - jalankan
    ```
    composer update
    ```
 - rename .env copy.example menjadi .env
 - buat database di phpmyadmin dengan nama 'db_simona' atau dengan nama database yang kamu inginkan
 - melakukan setup .env sesuaikan dengan nama database yg dibuat beserta username dan password
 - jalankan
    ``` 
    php artisan key:generate
    ```
 - jika sudah lakukan migrasi dengan cara 
    ```
    php artisan migrate
    ```
 - lalu melakukan seeding dengan cara 
    ``` 
    php artisan db:seed
    ```
 - selesai
 
 ### Default akun
 username : admin123 | password : admin123 <br>
 username : user123 | password : user123

Terimakasih!
