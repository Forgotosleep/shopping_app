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
                - Merchant
                    * Read Merchant Data -OK!
                    * Register User as Merchant -OK!
                - Payment
                    * Fetch Payment Method -OK!
                - Product
                    * Fetch Products All -OK!
                        - Add in Queries to sort and filter based on the query -OK!
                    * Fetch Products by Merchant -OK!
                    * Show/hide Product -OK!
                    * Create Product -OK!
                - TRX
                    * Create TRX 
                        - Use COMMIT/ROLLBACK!
                    * Read TRX by User
                    * Read TRX by Merchant
                    * Update TRX status
                    * Delete TRX (optional)
                - Cart (Single Cart per User)  // TODO Make multiple cart implementation
                    * Fetch Cart by User
                    * Fetch Cart Detail
                    * Add Product to Cart
                    * Remove Product from Cart
                    * Change Qty of Product in Cart
        - Test it out
    
    Useful commands:
        php artisan migrate:reset && php artisan migrate && php artisan db:seed
        composer dump-autoload && php artisan serve
 -->