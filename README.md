# Open Development
## [JEO](http://github.com/oeco/jeo) Child theme

Wordpress theme based on [JEO](http://github.com/oeco/jeo). This theme adds custom UI elements and logic.

### Dependencies

- **dataTables**: Under lib/dataTables for rendering tables on page-laws.php and page-profiles
- **leaflet**: For rendering maps
- **chosen**: jquery plugin for beautifying form UI components
- **moment**: js library for operation with dates

### odm-taxonomy

Integrates https://github.com/OpenDevelopmentMekong/odm-taxonomy as submodule. It is used for the automatic generation of the  navigation menu.

# Testing

Tests are found on ckanext/odm_dataset/tests and can be run with ```phpunit tests```

# Continuous deployment

Everytime code is pushed to the repository, travis will run the tests available on **/tests**. In case the code has been pushed to **master** branch and tests pass, the **_ci/deploy.sh** script will be called for deploying code in CKAN's DEV instance. Analog to this, and when code from **master** branch has been **tagged as release**, travis will deploy to CKAN's PROD instance automatically.

For the automatic deployment, the scripts on **_ci/** are responsible of downloading the odm-automation repository, decrypting the **odm_tech_rsa.enc** private key file ( encrypted using Travis-ci encryption mechanism) and triggering deployment in either DEV or PROD environment.
