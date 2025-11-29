README

I hosted this project on local host on Apache and MySQL. I then tunneled the web through NGROK to test payments and other functionality
Payment system integrated is PayU in Poland, however the payent system in test environment (SAndbox) is not available on localhost. 


Admin credentials
username: admin
password: *********


PayU Test Paymnet Credentials

Card Type: Master
Card Name: Test
Card Number: 5123456789012346
Expiry Date: 05/26
CVV: 123
OTP : 123456

Blik code - 123456


ngrok- ngrok http --host-header=rewrite "localhost:80"
