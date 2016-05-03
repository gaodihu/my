
paypal.use( ["login"], function(login) {
  login.render ({
    "appid": "AQ4-okOokGPQAMmL_OewXUAVp6CzOx5c6Y_sM7zKwGHBjU8fhIdql4p4PI2u-5jpKTe8LkstNYoqS-yC",
    //"authend": "sandbox",
    "scopes": "openid profile email address phone  https://uri.paypal.com/services/paypalattributes https://uri.paypal.com/services/expresscheckout",
    "containerid": "paypalAuth",
    "locale": "en-us",
    "returnurl": "https://www.moresku.com/paypal_login.php"
  });
});