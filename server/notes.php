<!-- 
    TODO:
        - Create Model & Migration :DONE!
        - Create Seeder for said Model :DONE!
            - Two for User
            - One for Merchants
            - Five Products
            - Three Payment Methods
            - Three TRXs
            
        - Create Routes & Controller
                - User 
                    * Login -OK!
                    * Logout -OK!
                    * Register -OK!
                    * Fetch User Data -OK!
                - TRX
                    * Create TRX
                    * Read TRX by User
                    * Read TRX by Merchant
                    * Update TRX status
                    * Delete TRX (optional)
                - Merchant
                    * Read Merchant Data -OK!
                    * Register User as Merchant -OK!
                - Payment
                    * Fetch Payment Method -OK!
                - Product
                    * Fetch Products All 
                    * Fetch Products by Merchant
                    * Show/hide Product
        - Test it out
    
    Useful commands:
        php artisan migrate:reset && php artisan migrate && php artisan db:seed
        composer dump-autoload && php artisan serve
 -->