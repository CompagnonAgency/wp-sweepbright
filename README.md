# WP SweepBright PRO

<img src="https://compagnon.agency/wp-content/themes/compagnon/assets/img/sweepbright.png" width="150">

3rd party WordPress plugin for SweepBright.

## 1. Introduction

### 1.1. Requirements

- PHP 7.3+
- Dedicated, managed or fast WordPress hosting (ability to resize & crop large image files 10MB+)
- [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)
- Yearly license (€99,00 annually, per website).

### 1.2. Features

- Seamless synchronization between properties
- Integration with [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/) for easy data manipulation
- Ability to return JSON
- Built-in contact form synchronization with SweepBright
- Easy multi-language support

### 1.3. Table of Contents

1. [Changelog](1.-changelog)
2. [Installation](2.-installation)
3. [Dashboard](3.-dashboard)
4. [Settings](4.-settings)
5. [Publishing in SweepBright](5.-publishing-in-sweepbright)
6. [Retrieving data](6.-retrieving-data)
7. [Helpers](7.-helpers)
8. [Filtering properties](8.-filtering-properties)
9. [Multilanguage](9.-multilanguage)
10. [License](license)
11. [Support](support)
12. [Terms & Conditions](terms-&-conditions)

## 2. License

### 2.1. Why do I need a yearly license?

SweepBright uses a [webhook](https://website.sweepbright.com/docs/#header-1.-publish-a-property-to-the-custom-website) for publishing properties to your website. The webhook on your website should be responsible for retrieving and storing all of the property's information whenever a publication occurs in SweepBright.

However, if you have a lot of publications scheduled at the same time it could take up a lot of processing resources. 
Potentially leading to unpredictable or unreliable behavior.

We've solved this by creating our own webhook server which acts as a "man in the middle" between SweepBright and your website.

Whenever a publication is scheduled in SweepBright it will first connect to our dedicated webhook server where it will first schedule your publication. Once scheduled, it will publish the properties back to your website, one at a time. This makes publishing more reliable, so you don't have to stress about the synchronization.

### 2.2. What's included in my annual license?

- License for 1 website
- Dedicated webhook server
- Support via e-mail

### 2.3. What's the price / additional costs?

> €99,00 annually, per website.

Additional costs may occur depending on your usage of our webhook server.
For regular use or less than 15 publications per day on average, there are no additional costs.

If you're running a high traffic real estate website, please contact [sales](mailto:info@compagnon.agency) for our large business & enterprise solutions.

### 2.4. How to obtain a license?

In order to retrieve your license, please contact [sales](mailto:info@compagnon.agency).

### 2.5. Free trial

When purchasing a license you have a free trial of two weeks. Within this period you're able to cancel your license at any time without costs.

## 3. Warranty

Our product is provided “as is” without warranty of any kind, expressed or implied. We shall not be liable for any damages, including but not limited to, direct, indirect, special, incidental or consequential damages or losses that occur out of the use or inability to use our products.

## 4. Copyright

Copyright (C) Compagnon Agency - All Rights Reserved.
