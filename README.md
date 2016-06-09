# Open Development
## [JEO](http://github.com/oeco/jeo) Child theme

Wordpress theme based on [JEO](http://github.com/oeco/jeo). This theme adds custom UI elements and logic.

### Dependencies

#### Composer Dependencies

See composer.json file for Dependencies. In order to install them:

- run ```composer install``` after having installed http://getcomposer.org/

#### NPM Dependencies

This project uses Gulp (http://gulpjs.com/) for compressing/minfying CSS/JS files. In order to be able to do so, you need to:

- Install node.js https://nodejs.org/en/download/ which comes with NPM
- It is recommended to update npm <code>sudo npm install npm -g</code>
- Install dependencies with <code>npm install</code>

### odm-taxonomy

Integrates https://github.com/OpenDevelopmentMekong/odm-taxonomy as submodule.

# Testing

Tests are found on ckanext/odm_dataset/tests and can be run with ```phpunit tests```

# Continuous deployment

Everytime code is pushed to the repository, travis will run the tests available on **/tests**. In case the code has been pushed to **master** branch and tests pass, the **_ci/deploy.sh** script will be called for deploying code in CKAN's DEV instance. Analog to this, and when code from **master** branch has been **tagged as release**, travis will deploy to CKAN's PROD instance automatically.

For the automatic deployment, the scripts on **_ci/** are responsible of downloading the odm-automation repository, decrypting the **odm_tech_rsa.enc** private key file ( encrypted using Travis-ci encryption mechanism) and triggering deployment in either DEV or PROD environment.
