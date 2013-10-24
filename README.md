# XeroBundle

`XeroBundle` makes it easy to communicate with the Xero api using the Guzzle library

## How to get started
1. Add the following to your `composer.json` file
   ```json
       "require": {
           ...
           "blackoptic/xerobundle": "*"
           ...
       }

2. Run `php composer.phar update "blackoptic/xerobundle"`

3. Register the bundle in your `app/AppKernel.php`:

   ``` php
       <?php
       ...
       public function registerBundles()
       {
           $bundles = array(
               ...
               new BlackOptic\Bundle\XeroBundle\BlackOpticXeroBundle(),
               ...
           );
       ...
   ```

3. Register the bundle in your `app/AppKernel.php`:

   ``` yaml
    xero:
        consumer_key: <Your Consumer Key>
        consumer_secret: <Your Consumer Secret>
        private_key: <Path to you private key>
   ```
