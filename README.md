Zend
====

creoLIFE Zend Framework libraries

Repository contains sort of usefull libraries for mostly Zend Framework 1.1.x.

1. Main_Api_Google_Geocode
  Google Maps REST API class. Helps resolve location (longitude and lattitude) for given address. 
  Contains sort of additional info including:
  - right street names
  - right region definition
  - location type definition
  Class provide localization functionality for better app fit.


2. Main_Validator_SecurePassword
  Zend-like validator for secure password
  (source - http://stackoverflow.com/questions/11923891/is-there-any-custom-validator-for-password-zend-framework)

  Usage:
  <code>
  $passwordOpts = array('requireAlpha' => true,
                      'requireNumeric' => true,
                      'minPasswordLength' => 8);

  $pwValidator = new My_Validator_SecurePassword($passwordOpts);

  $password = new Zend_Form_Element_Password('password', array(
    'validators' => array($pwValidator),
    'description' => $pwValidator->getRequirementString(),
    'label' => 'Password:',
    'required' => true,
  ));
  </code>