# Hackathon Project
Created by team ***Hacktastic Cartel***.

## Short Project Description
The "Green Choices" project aims to reduce carbon footprints by promoting eco-friendly products and educating consumers on sustainable living.

## 📹 Team Demo
Team Nagarro Oryx presented this demo at the conclusion of the Nagarro Hackathon on Augsut 2nd, 2024:

[![Nagarro Hackathon: Demo Team #1: Nagarro Oryx](https://img.youtube.com/vi/rpkTO-2L4IM/0.jpg)](https://www.youtube.com/watch?v=rpkTO-2L4IM)

[View other team demo's on our YouTube Playlist](https://www.youtube.com/playlist?list=PLJooqCSo73SiCupw9Xtj8-6vUERAxpdk_)

## Installation

1. Create a project folder and clone the B2C Demo Shop and the Docker SDK:
```bash
mkdir spryker-b2c && cd spryker-b2c
git clone https://github.com/spryker-shop/b2c-demo-shop.git ./
git clone git@github.com:spryker/docker-sdk.git docker
```

2. Set up a desired environment:
  * [Set up a development environment](#set-up-a-development-environment)
  * [Set up a production-like environment](#set-up-a-production-like-environment)

#### Set up a development environment

    1. Bootstrap the docker setup:

    ```bash
    docker/sdk boot deploy.dev.yml
    ```

    2. If the command you've run in the previous step returned instructions, follow them.

    3. Build and start the instance:
    ```bash
    docker/sdk up

#### Create Attribute in backoffice co2 with text type.
    1. Configure this attribute value for the product via the back office.
    2. Create Product Labels in the back office and assign them to the product.
    3. Set up a free shipping method in the back office and add the shipping plugin we created.
    4. After an order is successfully placed and the order state is marked as closed, carbon credit will be automatically added to the customer's account in the profile section.

### For Certificate Generator:
    we need below setup:
    #### setup dom pdf
        composer require dompdf/dompdf
