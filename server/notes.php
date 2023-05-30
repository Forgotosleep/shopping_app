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
                    * Fetch Cart by User -OK!
                    * Add Product to Cart -OK!
                    * Remove Product from Cart -OK!
                    * Change Qty of Product in Cart -OK!
        - Test it out

        - Create Job/Scheduler to clean out soft-deleted Cart entries to reduce DB load
        - Pagination. Add pagination, especially for large data such as Product and Merchant. Along with Transaction, but not to Cart.
        - Should change Cart's 'price' column into something else. It is ambigous, and it should describe it's actually Price * QUantity of the products in the Cart. For now, Price it is.
    
    Useful commands:
        php artisan migrate:reset && php artisan migrate && php artisan db:seed
        php artisan migrate:fresh --seed
        composer dump-autoload && php artisan cache:clear && php artisan serve
 -->