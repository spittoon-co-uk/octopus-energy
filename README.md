# octopus-energy
A short PHP script for retrieving the average tariff rate from Octopus for the past 12 months.

The index.php file is an endpoint into which you can POST a valid product_code and tariff_code, and the script will query the <a href="https://developer.octopus.energy/docs/api/#list-tariff-charges">Octopus API</a>, perform some quick calculations, and return the average tariff (Exc. VAT) for the past 12 months.
