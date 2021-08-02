# Menu
* [Home](../README.md)

# Bookkeeping logic

## Assumptions
Your company is register in Poland - EU.

## Company
### Poland (Case 1.1)

* VAT 23%
* MOSS not applicable
* Example: net 10PLN, gross 12,30PLN

### European Union (Case 1.2)
* VAT NP - It is not subject to VAT
* MOSS not applicable
* Example: net 10, gross 10

### The World (Case 1.3)

* VAT NP - It is not subject to VAT
* MOSS not applicable
* Example: net 10, gross 10

### Additional
#### VIES
When company provides tax identifier then we have to check the VIES status. When company has active VIES then the tax identifier
has country's ISO code ex. PL6482791634, when VIES status is inactive then the tax identifier does not have ISO code.

## Physical Person
### Poland (Case 2.1)

* VAT 23%
* MOSS not applicable
* Example: net 10PLN, gross 12,30PLN

### European Union (Case 2.2)

* VAT rate from the country person is from
* MOSS is applicable
* Example: net 10, gross = net + VAT

### The World (Case 2.3)

* VAT NP - It is not subject to VAT
* MOSS not applicable
* Example: net 10, gross 10
