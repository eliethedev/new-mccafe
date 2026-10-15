-- CafeCat menu migration for existing databases
-- Run AFTER schema.sql on a MacCafe database to switch catalog to CafeCat.
-- WARNING: this replaces categories/products/variants. Back up first.
-- Existing users/orders are preserved; carts referencing old products are cleared.

USE cafecat_db;

SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM product_variants;
DELETE FROM cart;
DELETE FROM products;
DELETE FROM categories;
SET FOREIGN_KEY_CHECKS = 1;

-- CafeCat default categories (from revision menu boards)
INSERT INTO categories (name, description, sort_order) VALUES
('Coffee', 'Our best-loved coffee creations. Maliit 12oz / Malaki 16oz.', 1),
('Matcha Series', 'Ceremonial Grade Matcha. Maliit 12oz / Malaki 16oz.', 2),
('Non Coffee', 'Strawberry, blueberry, chocolate and choco berry lattes.', 3),
('Fruit Teas', 'Refreshing fruit-infused teas.', 4),
('Boneless Chicken', 'Crispy boneless fried chicken with rice. Choose your flavor!', 5),
('Rice Bowls', 'Savory rice bowls with corn and creamy sauce.', 6),
('Chicken Tenders', 'OG and flavored chick tenders. Choose your dip!', 7),
('Sandwiches', 'Crispy chicken sandwiches. Signature: Chick N Pro Max.', 8),
('Snacks', 'Fries, cheese sticks, salads and mixes.', 9),
('Add Ons & Extras', 'Extra shots, milk, creme, dips, rice and brew upgrades. Your coffee, your rules.', 10);


-- CafeCat products (prices from revision menu boards; base = Maliit 12oz / single serve)
INSERT INTO products (category_id, name, description, price, preparation_time, image) VALUES
-- Coffee: Our best-loved coffee creations (Maliit 12oz base; Malaki 16oz via variants)
((SELECT id FROM categories WHERE name = 'Coffee'), 'Americano', 'Classic Americano. Maliit 12oz 75 / Malaki 16oz 85.', 75.00, 2, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Spanish Latte', 'Bestseller. Sweet Spanish latte with condensed milk. Maliit 80 / Malaki 90.', 80.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caramel Latte', 'Smooth caramel latte. Maliit 80 / Malaki 90.', 80.00, 3, 'caramel-macchiato.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Hazelnut', 'Nutty hazelnut latte. Maliit 80 / Malaki 90.', 80.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'White Mocha Latte', 'Bestseller. Creamy white mocha. Maliit 85 / Malaki 95.', 85.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Vanilla Creme', 'Silky vanilla creme latte. Maliit 85 / Malaki 95.', 85.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caramel Macchiato', 'Bestseller. Espresso with vanilla and caramel drizzle. Maliit 85 / Malaki 95.', 85.00, 3, 'caramel-macchiato.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caradamia Latte', 'Rich caradamia latte. Malaki 16oz only.', 100.00, 3, 'signature-blend.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Seasalt Spanish Latte', 'Spanish latte with seasalt creme. Malaki 16oz only.', 100.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Cacao Latte', 'Chocolatey cacao latte topped with cocoa. Malaki 16oz only.', 120.00, 3, 'cacao-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Spanish Oat Blend', 'Spanish latte made with oat milk. Malaki 16oz only.', 120.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'CafeCat Signature Blend', 'MUST TRY. House signature blend. Malaki 16oz only.', 120.00, 3, 'signature-blend.jpg'),
-- Matcha Series: Ceremonial Grade Matcha
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Matcha Latte', 'Bestseller. Ceremonial grade matcha latte. Maliit 90 / Malaki 100.', 90.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'White Mocha Matcha', 'Matcha with white mocha. Maliit 90 / Malaki 100.', 90.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Berry Matcha', 'Bestseller. Matcha with berry. Maliit 100 / Malaki 110.', 100.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Matcha Oat Blend', 'Matcha with oat milk. Malaki 16oz only.', 135.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Seasalt Matcha Latte', 'Matcha latte with seasalt creme. Malaki 16oz only.', 125.00, 3, NULL),
-- Non Coffee
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Strawberry Latte', 'Bestseller. Creamy strawberry milk.', 95.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Blueberry Latte', 'Creamy blueberry milk.', 95.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Chocolate Latte', 'Bestseller. Rich chocolate milk.', 100.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Choco Berry', 'Chocolate with berry.', 100.00, 3, NULL),
-- Fruit Teas
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Lychee', 'Bestseller. Refreshing lychee fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Mixed Berries', 'Mixed berries fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Blueberry', 'Blueberry fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Kiwi', 'Bestseller. Kiwi fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Passion Fruit', 'Passion fruit tea.', 85.00, 3, NULL),
-- Boneless Chicken: flavors CH Cheesy, Snowcheese, Salted Egg, Buffalo, Spicy Salted Egg, Original
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '2PCS with Rice', '2pcs boneless fried chicken with rice. Choose 1 flavor.', 110.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '3PCS with Rice', '3pcs boneless fried chicken with rice. Choose 1 flavor.', 150.00, 12, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '6PCS Boneless Fried Chicken (1 Flavor)', '6pcs boneless fried chicken, 1 flavor. Flavors: CH Cheesy, Snowcheese, Salted Egg, Buffalo, Spicy Salted Egg, Original.', 280.00, 15, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '12PCS Boneless Fried Chicken (2 Flavors)', '12pcs boneless fried chicken, 2 flavors.', 550.00, 20, 'chicken-platter.jpg'),
-- Rice Bowls
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Pork Bulgogi', 'Savory pork bulgogi rice bowl with corn and creamy sauce.', 150.00, 10, 'rice-bowls.jpg'),
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Ham & Bacon', 'Ham and bacon rice bowl with corn and creamy sauce.', 150.00, 10, 'rice-bowls.jpg'),
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Chicken Pepper Steak', 'Chicken pepper steak rice bowl with corn and creamy sauce.', 155.00, 10, 'rice-bowls.jpg'),
-- Chicken Tenders: dips Cheese, Garlic Mayo, CH Dip (Signature)
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'Salted Egg - 3PCS with 1 Dip', 'Flavored tenders: salted egg, 3pcs with 1 dip.', 190.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH Buffalo - 3PCS with 1 Dip', 'Flavored tenders: buffalo, 3pcs with 1 dip.', 195.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'Spicy Salted Egg - 3PCS with 1 Dip', 'Flavored tenders: spicy salted egg, 3pcs with 1 dip.', 190.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 1 - 3PCS Chicken Tenders with 1 Dip', 'OG chick tenders combo 1.', 175.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 2 - 3PCS Chicken Tenders, 1 Dip, Fries', 'OG chick tenders combo 2 with fries.', 190.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 3 - 3PCS Chicken Tenders with 3 Dips', 'OG chick tenders combo 3.', 215.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 4 - 5PCS Chicken Tenders with 3 Dips', 'OG chick tenders combo 4.', 300.00, 12, 'chicken-platter.jpg'),
-- Sandwiches
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Chick N Pro Max', 'Signature dish. Crispy chicken sandwich with creamy sauce.', 125.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Buffalo Pro Max', 'Buffalo crispy chicken sandwich.', 150.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Pancakey Sandwich', 'Sweet-savory pancakey sandwich.', 100.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Overload Sandwich', 'Loaded overload sandwich.', 125.00, 8, NULL),
-- Snacks
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Fries', 'Classic fries.', 85.00, 5, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Cheese Stick', 'Crispy cheese sticks.', 95.00, 5, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Cheesy Fries', 'Fries topped with melted cheese.', 100.00, 6, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Salad', 'Fresh chick salad.', 160.00, 6, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Chick N Fries', 'Crispy chicken with fries and creamy topping.', 165.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Mix', 'Fries with cheese sticks and dips.', 165.00, 8, NULL),
-- Add Ons & Extras
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Extra Coffee Shot', 'Add-on: extra coffee shot.', 20.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Oat Milk', 'Add-on: oat milk.', 35.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Seasalt Creme', 'Add-on: seasalt creme.', 25.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Nata', 'Add-on: nata.', 15.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Rice', 'Extra rice.', 20.00, 2, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Dip Sauce', 'Extra dip sauce. Dips: Cheese, Garlic Mayo, CH Dip (Signature).', 40.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Extra Flavor', 'Extra flavor for chicken.', 20.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), '2PCS Extra Tenders', 'Add 2pcs chicken tenders.', 120.00, 5, NULL);


-- CafeCat variants: Malaki 16oz upgrades (+10) and Espresso brew style (+30)
INSERT INTO product_variants (product_id, name, price_adjustment) VALUES
((SELECT id FROM products WHERE name = 'Americano'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Spanish Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Caramel Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Hazelnut'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'White Mocha Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Vanilla Creme'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Matcha Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'White Mocha Matcha'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Berry Matcha'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Americano'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Spanish Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Caramel Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Hazelnut'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'White Mocha Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Vanilla Creme'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'CafeCat Signature Blend'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Matcha Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Berry Matcha'), 'Espresso brew style', 30.00);


