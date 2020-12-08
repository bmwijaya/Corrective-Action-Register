## Corrective Action Register

The Safety Corrective and Preventive Action Register application was developed using Laravel framework. This application purpose are:
1. Record a Safety Inspection recommendation
2. Record and monitor a Risk Management action
3. Record and monitor an incident investigation recommendation
4. Record and monitor an HSE audit finding
5. Record and monitor a Safety Observation / Behaviour Based Safety

## Requirements
1. Server: 
   - personal computer server with laragon
   - macbook server with laravel valet
   - Company Network, please kindly refer to [Laravel Installation Requirement](https://laravel.com/docs/8.x/installation) 
2. PHP version 7.4 
   
## Installation

1. Extract the archive and put it in the folder you want
2. Run cp .env.example .env file to copy example file to .env
3. Then edit your .env file with DB credentials and other settings.
4. Run composer install command
5. Run php artisan migrate --seed command.
   Notice: seed is important, because it will create the first admin user for you.
6. Run php artisan key:generate command.
7. Due we have file/photo fields, run php artisan storage:link command.
8. run php artisan serv  
And that's it, go to your domain and login:

Username:	admin@admin.com
Password:	password

### Premium HSE Application

- **[Nirbhaya](https://nirbhaya.id/)**

## Security Vulnerabilities

If you discover a security vulnerability within this application, please send a message to [Benny Margawijaya](https://www.linkedin.com/in/bennymargawijaya/). All security vulnerabilities will be promptly addressed.

## License

The Corrective Action Register Apps is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
