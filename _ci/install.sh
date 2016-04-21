#!/usr/bin/env bash

set -e
echo "Installing dependencies"
sudo apt-get install -q -y python-software-properties
sudo apt-add-repository -y ppa:ansible/ansible
sudo apt-get update -q
sudo apt-get install -q -y ansible

echo "Downloading and unzipping odm_automation"
wget https://github.com/OpenDevelopmentMekong/odm-automation/archive/master.zip -O /tmp/odm_automation.zip
unzip /tmp/odm_automation.zip -d /tmp/

echo "decrypting private key and adding it key to ssh agent"
openssl aes-256-cbc -K $encrypted_d0e9e0897b60_key -iv $encrypted_d0e9e0897b60_iv -in odm_tech_rsa.enc -out ~/.ssh/id_rsa -d
chmod 600 ~/.ssh/id_rsa
eval `ssh-agent -s`
ssh-add ~/.ssh/id_rsa
