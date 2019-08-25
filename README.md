# Woocommerce Gift Manager

### Please note that this project is under construction. It is working, but only the backend part is ready yet so you won't be able to do any changes from UI, only by DB magic. But I will write a UI for you, I promise :)

## Mission statement
This project has one purpose: helping woocommerce users giving downloadable gifts for downloadable products within a certain period of time. Basically you can configure the system to attach certain products (the gifts you want to give) for an other product, category or to all orders.

## Why is it good? Our motivation
We are running a woocommerce webshop where we sell e-books. We do campaign in a certain period of time and for the business, it is highly beneficial to give gifts to the customer. In the past, we simply attach the gift as files to the products which were the subject of the campaign.

### 1. Customer satisfaction. The timing game...
 It was fair, but a lot of issue happened because after the campaign expired, we removed the gift files attached the products. As a result of this, our campaign buyers couldn't download the gifts after the removal. We had to find a way to support our existing customers so they can download their gifts and we didn't want to make a new 'campaign' product for each campaign
 
### 2. Remove our pain 
The adding and removing gift files were a big p.i.t.a. for us and it took a lot of effort. We had to find a more professional way to set it up only once and let the system work.

### 3. Remove duplication
We wanted to send the customer a catalogue with our products attached to his/her order. In the past we attached a pdf file to each product we sell. Seemed fair, but if one bought 2 or 3 different e-books in one order, he/she got the catalogue multiple time because it was attached to all of the products. We had to find a way to have an order level attachment.

### 4. Display, or not display, that is the question
We measured that it is highly beneficial to display the picture of the product in the checkout page. We wanted to display the image of the gifts as well but we did not want to display the attached catalogue. We had to find a way to say explicitly if we want to display a certain gift or not.

## The flow
Long story shot: You choose some products (the gifts) uploaded to your woocommerce store and you attach it to a single product, to a product category or to all orders with a given start and end date. Your customer's order will be extended by these gifts if one order your target between the start and the end date.

### The flow - attaching gift(s) for a single product:
We identify products by post id. 
The current flow uses wp_options to map the products together. To do that insert a row with the following format:
option_name column: wcgm_product_<product id for which the gifts are given>
option_value column: <gift product id 1>_<d/h>_<start date in iso seconds>_<end date in iso seconds>;<gift product id 2>_<d/h>_<start date in iso seconds>_<end date in iso seconds>


E.g.: To extend product number 10 with product number 1690 and 1802 we insert a new row to the wp_options db with:
option_name column: wcgm_product_10
option_value column: 1690_d_1564876800_1565827199;1802_h_0_1565827199

After 14 August 2019 23:59:59, no gift is given for product nr 10. Please note that product number 1802 won't be visible on the checkout page because its value is set to be hidden ('h'). 
Also please note that product 1690 won't be attached as a gift (nor be visible as one) before Sunday, 4 August 2019 00:00:00.
If you want to have only one gift, you can leave the ';' delimiter.

### The flow - attaching gift(s) for a product category:
The very same as attaching for a single product. The only difference is you write:
option_name column: wcgm_category_<product category id for which the gifts are given>
  
Every product which is in the defined category will have the defined gift attached.

option_name column: wcgm_category_3
option_value column: 1690_d_1564876800_1565827199;1802_h_0_1565827199

### The flow - attaching gift(s) for all product:
The very same as attaching for a single product. The only difference is you write:
option_name column: wcgm_all
  
Every product will have the defined gift attached.

option_name column: wcgm_all
option_value column: 1690_d_1564876800_1565827199;1802_h_0_1565827199

## Further improvements
### 1. UI
I know that the usage if not that comfortable for now. It will be after having a UI, because taht way you will not have to insert anything to the db.
### 2. Automatic cleanup mechanism
I am planning tp write a scheduled job which remove/archive all the expired mappings 

## License
This project goes under apache license. Feel free to use, extend and modify it. Any suggestions or forks are appreciated.
