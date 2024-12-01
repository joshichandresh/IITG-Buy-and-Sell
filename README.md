# IITG-Buy-and-Sell
The IITG Buy-and-Sell platform is a purpose-built online marketplace for the IITG campus community, encompassing 
students, faculty, and staff. This platform makes it easy to exchange second-hand items securely and conveniently 
within the campus. Developed using HTML and CSS for the frontend, PHP for backend operations, and MySQL for data 
management, the platform enhances the user experience with a streamlined interface, efficient product listings, 
advanced search functions, and secure user authentication. It aims to simplify and enrich the process of buying and 
selling pre-owned goods in a user-friendly environment.  
# Key Features: 
Admin Functionality:  Admin is responsible for maintaining platform integrity through user and product management  
• User Monitoring and Blocking: If any suspicious activity or discrepancy is detected, Admin can block the user 
to prevent further activity. 

• Product Removal:  Admin has the authority to delete any product associated with the detected discrepancies. 

• User Appeal and Unblocking: If a blocked user contacts Admin with a valid explanation, Admin can review 
the appeal and unblock the user if deemed appropriate.  

User Functionality: A user can log in as either a buyer or a seller.  

• Seller Workflow: Upon logging in, the seller completes a form to upload product details, including an image. 
After submission, they are redirected to my_items.php, where a table displays all their listed products (both 
sold and unsold). The table includes a "status" column and other essential details for efficient product 
management. 

• Buyer: Upon logging in, the buyer is redirected to a page showing all available products for purchase, 
excluding any products the buyer (since here a buyer can be itself a seller also, so whatever products buyer 
itself listed for sell will not be visible to him) has listed and also excluding the sold products. If interested, the 
buyer can proceed with the purchasing process for selected products
